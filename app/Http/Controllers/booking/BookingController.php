<?php

namespace App\Http\Controllers\booking;

use App\Events\PushLatestPatientEvent;
use App\Http\Controllers\ApiController;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Booking\BookingServiceInterface;
use App\Services\File\FileServiceInterface;
use App\Services\Shifts\ShiftServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BookingController extends ApiController
{
    const PATIENT_ACTOR = "patient";
    const DOCTOR_ACTOR = "patient";

    private $bookingService;
    private $fileService;
    private $shiftService;
    private $authService;

    public function __construct(
        BookingServiceInterface $bookingService,
        FileServiceInterface $fileService,
        ShiftServiceInterface $shiftService,
        AuthServiceInterface $authService
    )
    {
        $this->bookingService = $bookingService;
        $this->fileService = $fileService;
        $this->shiftService = $shiftService;
        $this->authService = $authService;
    }

    public function createBooking(Request $request)
    {
        $loginUserId = Auth::guard('sanctum')->id();
        $prevDiagnose = $request->file('prev_diagnose');
        $folderPath = Config::get("constants.UPLOAD_FOLDER.BOOKING_DIAGNOSE") . "/" . $loginUserId;
        $bookingData = $request->input();
        $bookingData["created_by"] = $loginUserId;
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

    public function getListBooking()
    {
        $loggingActor = $this->authService->getLoggingInActor();
        $attributes = [];
        if (isset($loggingActor["user"])) {
            $attributes = $this->getBookingAttributesForUser();
        } else if (isset($loggingActor["doctor"])) {
            $attributes = $this->getBookingAttributesForDoctor();
        } else if (isset($loggingActor["admin"])) {
            $attributes = $this->getBookingAttributesForAdmin();
        }
        $listBooking = $this->bookingService->getListBooking($attributes, $loggingActor);
        return $this->respondSuccess($listBooking);
    }

    private function getBookingAttributesForDoctor()
    {
        return [
            "booking_information.id",
            "booking_information.name as patient_name",
            "ds.date as date",
            "sh.end_time as end_time",
            "booking_information.status",
            "booking_information.created_at"
        ];
    }

    private function getBookingAttributesForUser()
    {
        return [
            "booking_information.id",
            "do.name as doctor_name",
            "sp.name as specialization_name",
            "ds.date as date",
            "sh.end_time as end_time",
            "booking_information.status",
            "booking_information.created_at"
        ];
    }

    private function getBookingAttributesForAdmin()
    {
        return [
            "booking_information.id",
            "booking_information.name as patient_name",
            "ds.date as date",
            "sh.end_time as end_time",
            "booking_information.status",
            "booking_information.created_at"
        ];
    }

    public function deleteBooking(Request $request)
    {
        $id = $request->input('id');
        try {
            $this->apiBeginTransaction();
            $this->bookingService->updateBookingStatus($id, Config::get('constants.BOOKING_STATUS.CANCEL'));
            $doctorShift = $this->shiftService->getShiftByBookingId($id);
            $this->shiftService->updateShiftStatus($doctorShift->id, Config::get('constants.SHIFT.NO_PATIENT_STATUS'));
            $this->apiCommit();
            return $this->respondSuccessWithoutData();
        } catch (\Exception $e) {
            $this->apiRollback();
            return $this->respondError();
        }
    }

    public function confirmBooking(Request $request)
    {
        $id = $request->input('id');
        try {
            $this->apiBeginTransaction();
            $this->bookingService->updateBookingStatus($id, Config::get('constants.BOOKING_STATUS.NOT_START'));
            $doctorShift = $this->shiftService->getShiftByBookingId($id);
            $this->shiftService->updateShiftStatus($doctorShift->id, Config::get('constants.SHIFT.HAVE_PATIENT_STATUS'));
            $this->apiCommit();

            event(new PushLatestPatientEvent(Auth::guard('sanctum')->id()));
            $this->respondNoContent();
        } catch (\Exception $e) {
            $this->apiRollback();
            dd($e->getMessage());
        }
    }

    public function rateBooking(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $rating = $request->input('rating');
        try {
            $this->apiBeginTransaction();
            $updatedBooking = $this->bookingService->rateBooking($bookingId, $rating);
            if (!$updatedBooking) {
                throw new \Exception("rate booking error");
            }

            if (!$this->bookingService->updateFinishStatus($bookingId, self::PATIENT_ACTOR)) {
                throw new \Exception("Update patient finish error");
            }
            if ($updatedBooking->doctor_finish) {
                if (!$this->bookingService->updateBookingStatus($bookingId, Config::get("constants.BOOKING_STATUS.END"))) {
                    throw new \Exception("Update status error");
                }
            }
            $this->apiCommit();
            $this->respondSuccessWithoutData(Config::get("constants.RES_MESSAGES.RATING_SUCCESSFULLY"));

        } catch (\Exception $e) {
            $this->apiRollback();
            $this->respondError($e->getMessage());
        }
    }

    public function getSoonestBooking()
    {
        $soonestBooking = $this->bookingService->getSoonestBooking();
        return $this->respondSuccess($soonestBooking);
    }

    public function createPrescription(Request $request)
    {
        //TODO
    }
}
