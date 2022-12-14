<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\auth\VerifyController;
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


