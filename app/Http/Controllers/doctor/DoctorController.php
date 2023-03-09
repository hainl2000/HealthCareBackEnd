<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\ApiController;
use App\Services\Doctors\DoctorServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class DoctorController extends ApiController
{
    private $doctorService;

    public function __construct(
        DoctorServiceInterface $doctorService,
    )
    {
        $this->doctorService = $doctorService;
    }

    public function registerShift(Request $request)
    {
        try {
            $this->apiBeginTransaction();
            $choseData = $request->input("registerData");
            $isRegisterShiftSuccess = $this->doctorService->registerShift($choseData);
            if ($isRegisterShiftSuccess) {
                $respData = [
                    "message" => 'Register shift successfully',
                ];
                $this->apiCommit();
                $resp = $this->respondCreated($respData);
            } else {
                throw new \Exception("Register shift fail");
            }
        } catch (\Exception $e) {
            $this->apiRollback();
            $resp = $this->respondError($e->getMessage(),400);
        }
        return $resp;
    }

    public function getRegisteredShifts(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $registeredShifts = $this->doctorService->getRegisteredShifts($startDate, $endDate);
        return $this->respondSuccess($registeredShifts);
    }

    public function getDoctorsBySpecialization($slug)
    {
        $doctors = $this->doctorService->getDoctorsBySpecialization($slug);
        return $this->respondSuccess($doctors);
    }

    public function getDoctorInformationById(Request $request, $id)
    {
        $isIncludeShifts = $request->input('includeShifts');
        $doctor = $this->doctorService->getDoctorInformationById($id, $isIncludeShifts);
        return $this->respondSuccess($doctor);
    }

}
