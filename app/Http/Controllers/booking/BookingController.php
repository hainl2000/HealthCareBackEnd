<?php

namespace App\Http\Controllers\booking;

use App\Enums\PaginationParams;
use App\Enums\Status;
use App\Http\Controllers\ApiController;
use App\Services\Auth\AuthServiceInterface;
use App\Services\Booking\BookingService;
use App\Services\Booking\BookingServiceInterface;
use App\Services\File\FileServiceInterface;
use App\Services\Notification\NotificationServiceInterface;
use App\Services\Payment\VnpayServiceInterface;
use App\Services\Prescription\PrescriptionServiceInterface;
use App\Services\Shifts\ShiftServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class BookingController extends ApiController
{
    const PATIENT_ACTOR = "patient";
    const DOCTOR_ACTOR = "doctor";

    private $bookingService;
    private $fileService;
    private $shiftService;
    private $authService;
    private $prescriptionService;
    private $notificationService;
    private $vnPayService;


    public function __construct(
        BookingServiceInterface $bookingService,
        FileServiceInterface $fileService,
        ShiftServiceInterface $shiftService,
        AuthServiceInterface $authService,
        PrescriptionServiceInterface $prescriptionService,
        NotificationServiceInterface $notificationService,
        VnpayServiceInterface $vnPayService
    )
    {
        $this->bookingService = $bookingService;
        $this->fileService = $fileService;
        $this->shiftService = $shiftService;
        $this->authService = $authService;
        $this->prescriptionService = $prescriptionService;
        $this->notificationService = $notificationService;
        $this->vnPayService = $vnPayService;
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
        try {
            $this->apiBeginTransaction();
            $booking = $this->bookingService->createBooking($bookingData);
            $this->shiftService->updateShiftStatus($bookingData["shift_id"], Config::get("constants.SHIFT.CHOSEN_STATUS"));
            $this->apiCommit();
            return $this->respondCreated(['booking' => $booking]);
        } catch (\Exception $e) {
            $this->apiRollback();
            dd($e->getMessage());
        }
    }

    public function getBookingInformation(Request $request, $id)
    {
        $isShortInformation = $request->input('short', false);
        $isFromShirt = $request->input('from_shift', false);
        if ($isFromShirt) {
            $id = $this->bookingService->getBookingInformationByShiftId($id)->id;
        }
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
                "booking_information.rating as booking_rating",
                "booking_information.comment as booking_comment",
                "booking_information.patient_finish",
                "booking_information.doctor_finish",
                "booking_information.status",
                "ds.date as booking_start_date",
                "do.name as doctor_name",
                "do.id as doctor_id",
                "sp.name as doctor_specialization",
                "sp.slug"
            ];
        }
        $bookingInformation = $this->bookingService->getBookingInformationById($id, $selectData, $isShortInformation)->toArray();
        if ($prescription = Arr::get($bookingInformation, "prescription")) {
            $bookingInformation["prescription"] = $this->handlePrescriptionResource($prescription);
        }
        return $this->respondSuccess($bookingInformation);
    }

    private function handlePrescriptionResource($prescription)
    {
        $returnPrescriptionData = [];
        $returnPrescriptionData["id"] = $prescription["id"];
        $returnPrescriptionData["diagnose"] = $prescription["diagnose"];
        $returnPrescriptionData["additional_direction"] = $prescription["additional_direction"];
        $returnPrescriptionData["prescriptionDrugs"] = [];
        foreach ($prescription["prescription_drugs"] as $drug) {
            $drugData = [];
            $drugData["id"] = $drug["drug_id"];
            if ($drugData["id"]) {
                $drugData["name"] = $drug["drug"]["name"];
                $drugData["unit"] = $drug["drug"]["unit"];
            } else {
                $drugData["is_other"] = true;
                $drugData["name"] = $drug["other_drug_name"];
                $drugData["unit"] = $drug["other_drug_unit"];
            }
            $drugData["dosages"] = $drug["dosages"];
            $drugData["number_per_time"] = $drug["number_per_time"];
            $drugData["meals"] = strval($drug["meals"]);
            $drugData["note"] = $drug["note"];
            $drugData["times"] = $drug["times"];
            $returnPrescriptionData["prescriptionDrugs"][] = $drugData;
        }
        return $returnPrescriptionData;
    }

    public function getListBooking(Request $request)
    {
        $loggingActor = $this->authService->getLoggingInActor();
        $paginationParams = [];
        $paginationParams['itemsPerPage'] = $request->query('itemsPerPage', PaginationParams::RecordsPerPage);
        $paginationParams['patient_name'] = $request->query('patient_name');
        $paginationParams['statuses'] = $request->query('statuses');
        $paginationParams['dateRange'] = $request->query('dateRange');
        $paginationParams['doctor_name'] = $request->query('doctor_name');
        $paginationParams['specializations'] = $request->query('specializations');
        $attributes = [];
        if (isset($loggingActor["user"])) {
            $attributes = $this->getBookingAttributesForUser();
        } else if (isset($loggingActor["doctor"])) {
            $attributes = $this->getBookingAttributesForDoctor();
        } else if (isset($loggingActor["admin"])) {
            $attributes = $this->getBookingAttributesForAdmin();
        }
        $listBooking = $this->bookingService->getListBooking($attributes, $loggingActor, $paginationParams);
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
            "booking_information.phone_number as phone_number",
            "ds.date as date",
            "sh.end_time as end_time",
            "booking_information.status",
            "booking_information.created_at"
        ];
    }

    public function deleteBooking(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return $this->respondError();
        }
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
        $bookingId = $request->input('id');
        try {
            $this->apiBeginTransaction();
            $this->bookingService->updateBookingStatus($bookingId, Config::get('constants.BOOKING_STATUS.NOT_START'));
            $doctorShift = $this->shiftService->getShiftByBookingId($bookingId);
            $this->shiftService->updateShiftStatus($doctorShift->id, Config::get('constants.SHIFT.HAVE_PATIENT_STATUS'));
            $this->apiCommit();

            $this->bookingService->pushLatestBookingForDoctor($doctorShift->doctor_id);
            $bookingConfirmationNotificationForDoctor = $this->notificationService->createBookingConfirmationNotificationForDoctor($bookingId, $doctorShift->doctor_id);
            $bookingInformation = $this->bookingService->getBookingInformationById($bookingId, ['*'], true);
            $bookingConfirmationNotificationForUser = $this->notificationService->createBookingConfirmationNotificationForUser($bookingId, $bookingInformation->created_by);
        } catch (\Exception $e) {
            $this->apiRollback();
            return $this->respondError($e->getMessage());
        }

        $this->notifyBookingConfirmationForDoctor($doctorShift->doctor_id, $bookingConfirmationNotificationForDoctor);
        $this->notifyBookingConfirmationForUser($bookingInformation->created_by, $bookingConfirmationNotificationForUser);
        return $this->respondNoContent();
    }

    private function notifyBookingConfirmationForDoctor($doctorId, $notification)
    {
        $replaceData = [
            'doctorId' => $doctorId
        ];
        $channel = replacePlaceholders(Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_DOCTOR.CHANNEL'), $replaceData);
        $action = Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_DOCTOR.ACTION');
        $this->notificationService->notifyNewNotification($channel, $action, $notification);
    }

    private function notifyBookingConfirmationForUser($userId, $notification)
    {
        $replaceData = [
            'userId' => $userId
        ];
        $channel = replacePlaceholders(Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_USER.CHANNEL'), $replaceData);
        $action = Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_USER.ACTION');
        $this->notificationService->notifyNewNotification($channel, $action, $notification);
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

            if ($updatedBooking->doctor_finish) {
                if (!$this->bookingService->updateBookingStatus($updatedBooking->id, Config::get("constants.BOOKING_STATUS.END"))) {
                    throw new \Exception("Update status error");
                }
                $this->bookingService->pushLatestBookingForDoctor($updatedBooking->doctor_id);
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
        $prescriptionData = $request->input('prescription');
        $bookingId = $request->input('booking_id');

        try {
            $this->apiBeginTransaction();
            $isCreatedPrescription = $this->prescriptionService->createPrescription($bookingId, $prescriptionData);
            if (!$isCreatedPrescription) {
                throw new \Exception('tao moi loi');
            }
            $selectAttributes = [
              'booking_information.doctor_finish',
              'booking_information.patient_finish',
            ];
            $booking = $this->bookingService->getBookingInformationById($bookingId, $selectAttributes);
            if (!$this->bookingService->updateFinishStatus($bookingId, self::DOCTOR_ACTOR, Status::ACTIVE)) {
                throw new \Exception("Update actor finish error");
            }

            if ($booking->patient_finish) {
                if (!$this->bookingService->updateBookingStatus($bookingId, Config::get("constants.BOOKING_STATUS.END"))) {
                    throw new \Exception("Update status error");
                }
            }

            $this->apiCommit();
            return $this->respondCreated(["message" => "tao thanh cong"]);
        } catch (\Exception $e) {
            $this->apiRollback();
            return $this->respondError($e->getMessage());
        }
    }

    public function changeBooking(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $changeShiftId = $request->input('change_shift_id');

        try {
            $this->apiBeginTransaction();

            if (!$this->shiftService->updateShiftStatus($changeShiftId, Config::get('constants.SHIFT.HAVE_PATIENT_STATUS'))) {
                return new \Exception('update change shift status fail');
            }

            $selectBookingAttributes = [
                'shift_id'
            ];
            $booking = $this->bookingService->getBookingInformationById($bookingId, $selectBookingAttributes, true);
            if (!$this->shiftService->updateShiftStatus($booking->shift_id, Config::get('constants.SHIFT.NO_PATIENT_STATUS'))) {
                return new \Exception('update old shift status fail');
            }

            if (!$this->bookingService->updateBookingShift($bookingId, $changeShiftId)) {
                return new \Exception('update booking shift fail');
            }
            $this->bookingService->pushLatestBookingForDoctor($booking->doctor_id);

            $this->apiCommit();
            return $this->respondSuccessWithoutData("Cap nhat thanh cong");
        } catch (\Exception $e) {
            $this->apiRollback();
            return $this->respondError($e->getMessage());
        }
    }

    public function exportPrescriptionPdf(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $fileName = 'prescription-' . $bookingId;
        $path = replacePlaceholders(Config::get("constants.UPLOAD_FOLDER.PRESCRIPTION"), [
            'filename' => $fileName
        ]);
        try {
            $this->fileService->exportPrescriptionPdf($path, []);
            return $fileName;
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }
    }

    public function payment(Request $request)
    {
        $bookingId = $request->query('booking_id');
        $paymentUrl = $this->vnPayService->createPayment($bookingId);
        return $this->respondSuccess(['payment_url' => $paymentUrl]);
    }

    public function getPaymentInformation(Request $request)
    {
        $paymentResult = $request->query('vnp_TransactionStatus');
        if ($paymentResult == Config::get('constants.PAYMENT.SUCCESS')) {
            $bookingId = $request->query('vnp_OrderInfo');
            try {
                $this->apiBeginTransaction();
                $this->bookingService->updateBookingStatus($bookingId, Config::get('constants.BOOKING_STATUS.NOT_START'));
                $doctorShift = $this->shiftService->getShiftByBookingId($bookingId);
                $this->shiftService->updateShiftStatus($doctorShift->id, Config::get('constants.SHIFT.HAVE_PATIENT_STATUS'));
                $this->apiCommit();

                $this->bookingService->pushLatestBookingForDoctor($doctorShift->doctor_id);
                $bookingConfirmationNotificationForDoctor = $this->notificationService->createBookingConfirmationNotificationForDoctor($bookingId, $doctorShift->doctor_id);
                $bookingInformation = $this->bookingService->getBookingInformationById($bookingId, ['*'], true);
                $bookingConfirmationNotificationForUser = $this->notificationService->createBookingConfirmationNotificationForUser($bookingId, $bookingInformation->created_by);
            } catch (\Exception $e) {
                $this->apiRollback();
                return Redirect::to('http://localhost:8080/user/page/booking-history');
            }

            $this->notifyBookingConfirmationForDoctor($doctorShift->doctor_id, $bookingConfirmationNotificationForDoctor);
            $this->notifyBookingConfirmationForUser($bookingInformation->created_by, $bookingConfirmationNotificationForUser);
            return Redirect::to('http://localhost:8080/user/page/booking-history');
        }
    }
}
