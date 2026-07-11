<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

// for controller output
use Illuminate\Http\JsonResponse;
// use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Redirect;
// use Illuminate\Http\Response;
// use Illuminate\View\View;

// models
use App\Models\{
	YesNoOption,
	System\ActivityLog,
	System\JobBatch,
};

// load db facade
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

// load validation
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
// use {{ namespacedRequests }}

// load batch and queue
// use Illuminate\Bus\Batch;
// use Illuminate\Support\Facades\Bus;

// load email & notification
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;// more email

// load helper
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

// load Carbon library
use \Carbon\Carbon;
use \Carbon\CarbonPeriod;
use \Carbon\CarbonInterval;

use Session;
use Throwable;
use Exception;
use Log;

class ModelAjaxSupportController extends Controller
{
	public function getYesNoOptions(Request $request): JsonResponse
	{
		$yno = YesNoOption::when($request->search, function (Builder $query) use ($request) {
						$query->where('option', 'LIKE', '%' . $request->search . '%');
					})
					->get();
		return response()->json($yno);
	}






















	///////////////////////////////////////////////
	// activityLog
	public function getActivityLogs(Request $request): JsonResponse
	{
		$columns = [
			0 => 'id',
			1 => 'user',
			2 => 'event',
			3 => 'model_type',
			4 => 'model_id',
			5 => 'route_name',
			6 => 'method',
			7 => 'url',
			8 => 'ip_address',
			9 => 'user_agent',
			10 => 'is_critical',
			11 => 'created_at',
		];

		$query = ActivityLog::select([
			'id',
			'user',
			'event',
			'model_type',
			'model_id',
			'route_name',
			'method',
			'url',
			'ip_address',
			'user_agent',
			'is_critical',
			'created_at',
		]);

		if ($request->search_value) {
			$search = $request->search_value;

			$query->where(function ($q) use ($search) {
				$q->where('model_type', 'LIKE', "%{$search}%")
				->where('model_id', 'LIKE', "%{$search}%")
				->orWhere('ip_address', 'LIKE', "%{$search}%")
				->orWhere('model_id', 'LIKE', "%{$search}%")
				->orWhere('created_at', 'LIKE', "%{$search}%")
				->orWhere('route_name', 'LIKE', "%{$search}%")
				->orWhere('user', 'LIKE', "%{$search}%");
			});
		}

		$totalRecords = ActivityLog::count();
		$filteredRecords = (clone $query)->count();

		$orderColumn = $columns[$request->order[0]['column']] ?? 'created_at';
		$orderDir = $request->order[0]['dir'] ?? 'desc';

		$data = $query
		->orderBy($orderColumn, $orderDir)
		->skip($request->start)
		->take($request->length)
		->get();

		return response()->json([
			'draw' => intval($request->draw),
			'recordsTotal' => $totalRecords,
			'recordsFiltered' => $filteredRecords,
			'data' => $data,
		]);
	}
	///////////////////////////////////////////////
	//generator
	public function getJobBatchTable(Request $request): JsonResponse
	{
		$values = JobBatch::orderBy('created_at', 'DESC')
						->get()
						->map(function($job){
							return [
								'name' => $job->name,
								'pending' => ($job->pending_jobs == 0)?'No Pending':'Pending',
								'success' => ($job->pending_jobs == 0 && $job->failed_jobs == 0)?'Success':(($job->pending_jobs > 0 && $job->failed_jobs == 0)?'Not Yet Process':(($job->pending_jobs == 0 && $job->failed_jobs > 0)?'Process with Fail':(($job->pending_jobs > 0 && $job->failed_jobs > 0)?'Process with Fail':NULL))),
								'failed' => ($job->failed_jobs == 0)?'No Failed':'Failed',
								'totalJobs' => $job->total_jobs,
								'processedJobs' => ($job->total_jobs - $job->pending_jobs),
								'created_at' => ($job->created_at->format('j F Y g:i A')),
							];
						});
		return response()->json($values??[]);
	}

	public function getProgress(Request $request): JsonResponse
	{
		try {
			$batchId = $request->id ?? session('lastBatchId');
			$batch1 = Bus::findBatch($batchId);
			// return response()->json([
			// 	'processedJobs' => $batch1->processedJobs(),
			// 	'totalJobs' => $batch1->totalJobs,
			// 	'progress' => $batch1->progress()
			// ]);
			$batch2 = JobBatch::find($batchId);
				// If batch is missing (already deleted), assume finished
			if (!$batch2) {
				return response()->json([
																	'processedJobs' => 0,
																	'totalJobs' => 0,
																	'progress' => 100,
																	'percent' => 100
																]);
			}
			$total = $batch2->total_jobs;
			$pending = $batch2->pending_jobs;
			$processed = $total - $pending;
				// Avoid division by zero
			if ($total == 0) {
				return response()->json([
																	'processedJobs' => 0,
																	'totalJobs' => 0,
																	'progress' => 100,
																	'percent' => 100
																]);
			}
				// Force return 100 when finished
			if ($pending == 0) {
				return response()->json([
																	'processedJobs' => 0,
																	'totalJobs' => 0,
																	'progress' => 100,
																	'percent' => 100
																]);
			}
			// Calculate %
			$percent = number_format((($processed / $total) * 100), 2);
			return response()->json([
																'processedJobs' => $batch1->processedJobs(),
																'totalJobs' => $batch1->totalJobs,
																'progress' => $batch1->progress(),
																'percent' => $percent
															]);
		} catch (\Exception $e) {
			Log::error($e);
			return response()->json([
																'processedJobs' => 0,
																'totalJobs' => 0,
																'progress' => 100,
																'percent' => 100
															]);
		}
	}
}
