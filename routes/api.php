<?php
use Illuminate\Support\Facades\Route;

// read API from files
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\API\ModelAjaxSupportController;

Route::middleware(['auth', 'auth:sanctum'])->group(function () {
	Route::controller(ModelAjaxSupportController::class)->group(function () {
		Route::get('/getActivityLogs', 'getActivityLogs')->name('getActivityLogs');
		Route::get('/getYesNoOptions', 'getYesNoOptions')->name('getYesNoOptions');
	});
});

// Get all countries
Route::get('/countries', function () {
	$json = Storage::disk('public')->get('countries.json');
	$countries = json_decode($json, true);

	$formatted = collect($countries)->map(function ($country) {
		return [
			'id' => $country['id'],
            'text' => $country['name'], // Select2 expects "text"
          ];
        });

	return response()->json($formatted);
})->name('countries');

// Get states by country_id
Route::get('/states/{country_id}', function ($country_id) {
	$json = Storage::disk('public')->get('states.json');
	$states = json_decode($json, true);

	$filtered = collect($states)
	->where('country_id', (int) $country_id)
	->map(function ($state) {
		return [
			'id' => $state['id'],
			'text' => $state['name'],
		];
	})
	->values();

	return response()->json($filtered);
})->name('states');

