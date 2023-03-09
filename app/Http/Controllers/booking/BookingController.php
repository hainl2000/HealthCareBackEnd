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
            $isCreatedSuccessfully = $this->bookingService->createBooking($bookingData);
            if (!$isCreatedSuccessfully) {
                throw new \Exception();
            }
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
        if (isset($isShortInformation)) {
            $selectData = [
                "name",
                "created_at"
            ];
        } else {
            $selectData = [
                "*"
            ];
        }
        $bookingInformation = $this->bookingService->getBookingInformationById($id, $selectData);
        return $this->respondSuccess([
            "information" => $bookingInformation
        ]);
    }
}
