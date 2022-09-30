<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\VerifyController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::prefix('/user')->group(function() {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/signup', [SignupController::class, 'register']);
    Route::get('/signup/verify/{id}/{hash}', [VerifyController::class,'verifyAccount'])->middleware('auth:sanctum','type.doctor');
});


