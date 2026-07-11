<?php
use Illuminate\Support\Facades\Route;

// read API from files
use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\API\ModelAjaxCRUDController;

Route::middleware('auth:sanctum')->group(function () {
	Route::controller(ModelAjaxCRUDController::class)->group(function () {

	});
});


