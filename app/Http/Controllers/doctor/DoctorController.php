<?php

namespace App\Http\Controllers\doctor;

use App\Http\Controllers\ApiController;
use App\Services\Doctors\DoctorServiceInterface;
use Illuminate\Http\Request;


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
        $registeredShifts = $this->doctorService->getRegisteredShifts();
        return $this->respondSuccess($registeredShifts);
    }
}
