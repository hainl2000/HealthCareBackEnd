<?php

use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\SignupController;
use App\Http\Controllers\auth\VerifyController;
use App\Http\Controllers\doctor\DoctorController;
use App\Http\Controllers\specialization\SpecializationController;
use App\Http\Controllers\shift\ShiftController;
use App\Http\Controllers\booking\BookingController;
use App\Http\Controllers\drug\DrugController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\auth\ChangePasswordController;
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
    Route::get('test', [LoginController::class, 'test']);
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/signup', [SignupController::class, 'register']);
//    Route::get('/signup/verify/{id}/{hash}', [VerifyController::class,'verifyAccount'])->middleware('auth:sanctum','type.user');
    Route::get('/signup/verify/{hash}', [VerifyController::class, 'verifyAccount']);
    Route::prefix('/booking')->group(function () {
        Route::post('/create', [BookingController::class, 'createBooking']);
        Route::post('/rate', [BookingController::class, 'rateBooking']);
        Route::post('/change', [BookingController::class, 'changeBooking']);
        Route::post('/notify', [NotificationController::class, 'notifyTransferringMoney']);
        Route::get('/payment', [BookingController::class, 'payment']);
    });
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword']);
});

Route::prefix('/specialization')->group(function () {
    Route::get('/detail/{slug}', [SpecializationController::class, 'getSpecializationDetail']);
    Route::get('/list', [SpecializationController::class, 'getListSpecializations']);
    Route::get('/doctors/{slug}', [DoctorController::class, 'getDoctorsBySpecialization']);
});

Route::prefix('/shift')->group(function () {
    Route::get('/list', [ShiftController::class, 'getAllShifts']);
    Route::get('/detail/{id}', [ShiftController::class, 'getShiftInformationById']);
});

Route::prefix('/booking')->group(function () {
    Route::get('/list', [BookingController::class, 'getListBooking']);
    Route::get('/{id}', [BookingController::class, 'getBookingInformation']);
});

Route::prefix('/doctor')->group(function () {
    Route::post('/login', [LoginController::class, 'doctorLogin']);
    Route::get('/{id}', [DoctorController::class, 'getDoctorInformationById']);
    Route::get('/drug/list', [DrugController::class, 'getListDrugs']);
    Route::post('/prescription/create', [BookingController::class, 'createPrescription']);
    Route::middleware(['auth:sanctum', 'type.doctor'])->group(function () {
        Route::prefix('shift')->group(function () {
            Route::post('/register', [DoctorController::class, 'registerShift']);
            Route::post('/cancel', [DoctorController::class, 'cancelShift']);
            Route::get('/list', [DoctorController::class, 'getRegisteredShifts']);
        });
        Route::prefix('booking')->group(function () {
            Route::get('/soonest', [BookingController::class, 'getSoonestBooking']);
        });
    });
    Route::post('/doctor-change-password', [ChangePasswordController::class, 'doctorChangePassword']);
});

Route::prefix('/admin')->group(function() {
    Route::post('/login', [LoginController::class, 'adminLogin']);
    Route::post('/booking/delete', [BookingController::class, 'deleteBooking']);
    Route::post('/booking/confirm', [BookingController::class, 'confirmBooking']);
    Route::post('/doctor/signup', [SignupController::class, 'signupDoctor']);
    Route::get('/doctor/list', [DoctorController::class, 'getListDoctor']);
    Route::get('/doctor/{id}', [DoctorController::class, 'getDoctorFullInformationById']);
    Route::get('/drug/list', [DrugController::class, 'getListDrugs']);
    Route::post('/drug/create', [DrugController::class, 'createDrug']);
    Route::post('/drug/update', [DrugController::class, 'updateDrug']);
    Route::prefix('/specialization')->group(function() {
        Route::post('/create', [SpecializationController::class, 'createNewSpecialization']);
        Route::post('/update', [SpecializationController::class, 'updateSpecialization']);
    });
});

Route::prefix('/notification')->group(function() {
    Route::get('/', [NotificationController::class, 'getNotifications']);
    Route::post('/markSeen', [NotificationController::class, 'markNotificationsSeen']);
});
Route::middleware(['auth:sanctum'])->group(function() {
    Route::post('/logout', [LogoutController::class, 'actionLogout']);
});

Route::get('/vnpay-return', [BookingController::class, 'getPaymentInformation']);

Route::post('/test', [BookingController::class, 'exportPrescriptionPdf']);
//Route::post('/create-meet', [\App\Services\Google\GoogleService::class, 'createMeeting']);


