<?php

namespace App\Http\Controllers\doctor;

use App\Enums\PaginationParams;
use App\Http\Controllers\ApiController;
use App\Services\Doctors\DoctorServiceInterface;
use App\Services\File\FileServiceInterface;
use Illuminate\Http\Request;


class DoctorController extends ApiController
{
    private $doctorService;
    private $fileService;

    public function __construct(
        DoctorServiceInterface $doctorService,
        FileServiceInterface   $fileService
    )
    {
        $this->doctorService = $doctorService;
        $this->fileService = $fileService;
    }

    public function registerShift(Request $request)
    {
        try {
            $this->apiBeginTransaction();
            $chooseDatas = $request->input("registerData");
            if (empty($chooseDatas)) {
                $respData = [
                    "message" => 'Đăng ký ca khám thành công',
                ];
            } else {
                $isRegisterShiftSuccess = $this->doctorService->registerShift($chooseDatas);
                if ($isRegisterShiftSuccess) {
                    $respData = [
                        "message" => 'Register shift successfully',
                    ];
                } else {
                    throw new \Exception("Register shift fail");
                }
            }
            $this->apiCommit();
            $resp = $this->respondCreated($respData);
        } catch (\Exception $e) {
            $this->apiRollback();
            $resp = $this->respondError($e->getMessage(), 400);
        }
        return $resp;
    }

    public function cancelShift(Request $request)
    {
        try {
            $this->apiBeginTransaction();
            $choseData = $request->input("deletedShift");
            $isDeletedSuccessfully = $this->doctorService->cancelShift($choseData);
            if ($isDeletedSuccessfully) {
                $respData = [
                    "message" => 'Cancel shift successfully',
                ];
                $this->apiCommit();
                $resp = $this->respondSuccess($respData);
            } else {
                throw new \Exception("Cancel shift fail");
            }
        } catch (\Exception $e) {
            $this->apiRollback();
            $resp = $this->respondError($e->getMessage());
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

    public function getDoctorsBySpecialization(Request $request, $slug)
    {
        $priorityId = $request->input('priorityId');
        $doctors = $this->doctorService->getDoctorsBySpecialization($slug, $priorityId);
        return $this->respondSuccess($doctors);
    }

    public function getDoctorInformationById(Request $request, $id)
    {
        $isIncludeShifts = $request->input('includeShifts');
        $doctor = $this->doctorService->getDoctorInformationById($id, $isIncludeShifts);
        $doctor['image'] = $this->fileService->getFileUrl($doctor['image']);
        return $this->respondSuccess($doctor);
    }

    public function getListDoctor(Request $request)
    {
        $paginationParams = [];
        $paginationParams['itemsPerPage'] = $request->query('itemsPerPage', PaginationParams::RecordsPerPage);
        $paginationParams['name'] = $request->query('name');
        $paginationParams['type'] = $request->query('type');
        $paginationParams['specialization_id'] = $request->query('specialization_id');
        $doctors = $this->doctorService->getListDoctor($paginationParams);
        foreach ($doctors as $doctor) {
            $rating = $this->doctorService->getDoctorRating($doctor->id);
            $doctor['average_rating'] = $rating['average_rating'];
            $doctor['total_ratings'] = $rating['total_ratings'];
        }
        return $this->respondSuccess($doctors);
    }

    public function getDoctorFullInformationById(Request $request, $id)
    {
        $doctor = $this->doctorService->getDoctorFullInformationById($id);
        if (isset($doctor['image'])) {
            $doctor['image'] = $this->fileService->getFileUrl($doctor['image']);
        }
        if (isset($doctor['sign'])) {
            $doctor['sign'] = $this->fileService->getFileUrl($doctor['sign']);
        }
        return $this->respondSuccess($doctor);
    }

    public function getFeaturedDoctors()
    {
        $doctors = $this->doctorService->getFeaturedDoctor();
        foreach ($doctors as $doctor) {
            $doctor['image'] = $this->fileService->getFileUrl($doctor['image']);
        }
        return $this->respondSuccess($doctors);
    }

    public function updateDoctorInformation(Request $request)
    {
        $doctorId = $request->input('doctor_id');
        $this->apiBeginTransaction();
        $updateResult = $this->doctorService->updateDoctorInformation($doctorId, $request->except(['doctor_id']));
        if ($updateResult) {
            $this->apiCommit();
            return $this->respondSuccessWithoutData("Update successfully");
        } else {
            $this->apiRollback();
            return $this->respondError("Update fail");
        }
    }

}
