<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\auth\VerifyController;
use App\Http\Controllers\doctor\DoctorController;
use App\Http\Controllers\specialization\SpecializationController;
use App\Http\Controllers\shift\ShiftController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('/user')->group(function() {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/signup', [SignupController::class, 'register']);
//    Route::get('/signup/verify/{id}/{hash}', [VerifyController::class,'verifyAccount'])->middleware('auth:sanctum','type.user');
    Route::get('/signup/verify/{hash}', [VerifyController::class,'verifyAccount']);
});

Route::prefix('/specialization')->group(function () {
    Route::get('/detail/{slug}', [SpecializationController::class, 'getSpecializationDetail']);
    Route::get('/list', [SpecializationController::class, 'getListSpecializations']);
    Route::get('/doctors/{slug}', [DoctorController::class, 'getDoctorsBySpecialization']);
});

Route::prefix('/shift')->group(function () {
    Route::get('/list', [ShiftController::class, 'getAllShifts']);
});

Route::prefix('/doctor')->group(function () {
    Route::post('/login', [LoginController::class, 'doctorLogin']);
    Route::get('/{id}', [DoctorController::class, 'getDoctorInformationById']);
    Route::middleware(['auth:sanctum', 'type.doctor'])->prefix("/shift")->group(function () {
        Route::post('/register', [DoctorController::class, 'registerShift']);
        Route::get('/list', [DoctorController::class, 'getRegisteredShifts']);
    });
});

Route::prefix('/admin')->group(function() {
    Route::post('/doctor/signup', [SignupController::class, 'signupDoctor']);
});


