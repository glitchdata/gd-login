<?php

use App\Http\Controllers\Api\LicenseValidationController;
use App\Http\Controllers\LicenseValidatorJsonController;
use Illuminate\Support\Facades\Route;

Route::post('/licenses/validate', LicenseValidationController::class)
	->name('api.licenses.validate');

Route::get('/license/validate/{key}', LicenseValidatorJsonController::class)
	->name('api.license.validate');
