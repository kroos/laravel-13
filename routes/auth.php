<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\PasswordController;

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\System\ActivityLogController;

use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {

	Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
		->middleware(['signed', 'throttle:6,1'])
		->name('verification.verify');
	Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])	->middleware('throttle:6,1')	->name('verification.send');
	Route::get('verify-email', EmailVerificationPromptController::class)
	->name('verification.notice');
	Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
	->name('password.confirm');
	Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
	Route::put('password', [PasswordController::class, 'update'])
	->name('password.update');
	Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

	Route::middleware('verified')->group(function () {
		Route::get('/dashboard', function(){
			return view('dashboard');
		})->name('dashboard');
	});

	Route::middleware('password.confirm')->group(function () {
		Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
		Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
		Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

		Route::controller(ActivityLogController::class)->group(function(){
			Route::prefix('/activity-logs')->name('activity-logs.')->group(function(){
				Route::get('/', 'index')->name('index');
				Route::get('/{log}', 'show')->name('show');
				Route::delete('{log}', 'destroy')->name('destroy');
			});
		});
//////////////////////////////////////////////////////////////////////////////
		// insert security page route here





	});
//////////////////////////////////////////////////////////////////////////////
	// insert your normal page route here





});

