<?php

namespace App\Http\Controllers\shift;

use App\Http\Controllers\ApiController;
use App\Services\File\FileService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\Shifts\ShiftServiceInterface;
use Illuminate\Support\Facades\Config;

class ShiftController extends ApiController
{
    private $shiftService;
    private $fileService;

    public function __construct(ShiftServiceInterface $shiftService,
        FileService $fileService)
    {
        $this->shiftService = $shiftService;
        $this->fileService = $fileService;
    }

    public function getAllShifts(Request $request)
    {
        $allShifts = $this->shiftService->getAllShifts();
        return $this->respondSuccess($allShifts);
    }

    public function getShiftInformationById($id)
    {
        $shift = $this->shiftService->getShiftInformationById($id);
        if (isset($shift)) {
            $noPatientStatus = Config::get('constants.SHIFT.NO_PATIENT_STATUS');
            $next30Mins = Carbon::now()->addMinutes(30)->toDateTimeLocalString();
            $next3Days = Carbon::now()->addDay(3)->endOfDay();

            if (Carbon::create($shift->date)->toDateTimeLocalString() <= $next30Mins || $shift->date >= $next3Days) {
                $shift = [];
            } else if ($shift->status != $noPatientStatus) {
                $shift = [];
            }

            if (isset($shift->doctor->doctor_information)) {
                $shift->doctor->doctor_information->short_introduction = htmlspecialchars_decode($shift->doctor->doctor_information->short_introduction);
                $shift->doctor->doctor_information->introduction = htmlspecialchars_decode($shift->doctor->doctor_information->introduction);
            }
            $shift->doctor->image = $this->fileService->getFileUrl($shift->doctor->image);
        }

        return $this->respondSuccess($shift);
    }
}
