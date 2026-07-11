<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

// for controller output
use Illuminate\Http\JsonResponse;

// models
// use App\Models\{
// };

// load db facade
// use Illuminate\Database\Eloquent\Builder;
// use Illuminate\Support\Facades\DB;

// load validation
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Validator;

// load batch and queue
// use Illuminate\Bus\Batch;
// use Illuminate\Support\Facades\Bus;

// load email & notification
// use Illuminate\Support\Facades\Mail;
// use Illuminate\Support\Facades\Notification;// more email

// load helper
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

// load Carbon library
use \Carbon\{
	Carbon, CarbonPeriod, CarbonInterval
};

use Session;
use Throwable;
use Exception;
use Log;

class ModelAjaxCRUDController extends Controller
{
}
