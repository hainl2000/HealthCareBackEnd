<?php

namespace App\Http\Controllers\specialization;

use App\Http\Controllers\ApiController;
use App\Services\Specializations\SpecializationServiceInterface;
use Illuminate\Http\Request;

class SpecializationController extends ApiController
{
    private $specializationService;

    public function __construct(SpecializationServiceInterface $specializationService)
    {
        $this->specializationService = $specializationService;
    }

    public function getListSpecializations(Request $request)
    {
        $isIncludeDetail = $request->input('includeDetail');
        $listSpecializations = $this->specializationService->getListSpecializations($isIncludeDetail);
        return $this->respondSuccess($listSpecializations);
    }

    public function getSpecializationDetail($slug)
    {
        $specializationDetail = $this->specializationService->getSpecializationDetail($slug);
        return $this->respondSuccess($specializationDetail);
    }

    public function createNewSpecialization(Request $request)
    {
        $specializationData = $request->input('specializationInfo');
        $specialization = $this->specializationService->createSpecialization($specializationData);
        return $this->respondCreated([$specialization]);
    }
}
