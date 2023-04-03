<?php

namespace App\Http\Controllers\booking;

use App\Http\Controllers\ApiController;
use App\Services\Booking\BookingServiceInterface;
use App\Services\File\FileServiceInterface;
use App\Services\Shifts\ShiftServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BookingController extends ApiController
{
    private $bookingService;
    private $fileService;
    private $shiftService;

    public function __construct(
        BookingServiceInterface $bookingService,
        FileServiceInterface $fileService,
        ShiftServiceInterface $shiftService
    )
    {
        $this->bookingService = $bookingService;
        $this->fileService = $fileService;
        $this->shiftService = $shiftService;
    }

    public function createBooking(Request $request)
    {
        $loginUserId = Auth::guard('sanctum')->id();
        $prevDiagnose = $request->file('prev_diagnose');
        $folderPath = Config::get("constants.UPLOAD_FOLDER.BOOKING_DIAGNOSE") . "/" . $loginUserId;
        $bookingData = $request->input();
        if (isset($prevDiagnose)) {
            $bookingData["prev_diagnose"] = $this->fileService->uploadImage($folderPath, $prevDiagnose);
        }
        //TODO: integrate meeting service
        try {
            $this->apiBeginTransaction();
            $this->bookingService->createBooking($bookingData);
            $this->shiftService->updateShiftStatus($bookingData["shift_id"], Config::get("constants.SHIFT.HAVE_PATIENT_STATUS"));
            $this->apiCommit();
            return $this->respondCreated();
        } catch (\Exception $e) {
            $this->apiRollback();
            dd($e->getMessage());
        }
    }

    public function getBookingInformation(Request $request, $id)
    {
        $isShortInformation = $request->input('short', false);
        if ($isShortInformation) {
            $selectData = [
                "id",
                "name",
                "created_at"
            ];
        } else {
            $selectData = [
                "booking_information.id",
                "booking_information.name as patient_name",
                "booking_information.gender as patient_gender",
                "booking_information.address as patient_address",
                "booking_information.symptom as patient_sympton",
                "booking_information.anamnesis as patient_anamnesis",
                "booking_information.prev_information as patient_prev_information",
                "booking_information.image as patient_history_image",
                "booking_information.video_link as booking_video_link",
                "booking_information.created_at as booking_created_at",
                "ds.date as booking_start_date",
                "do.name as doctor_name",
                "sp.name as doctor_specialization"
            ];
        }
        $bookingInformation = $this->bookingService->getBookingInformationById($id, $selectData, $isShortInformation);
        return $this->respondSuccess($bookingInformation);
    }
}
