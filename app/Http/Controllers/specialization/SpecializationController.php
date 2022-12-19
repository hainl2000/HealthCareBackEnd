<?php

namespace App\Http\Controllers\specialization;

use App\Http\Controllers\ApiController;
use App\Services\Specializations\SpecializationInterface;
use Illuminate\Http\Request;

class SpecializationController extends ApiController
{
    private $specializationService;

    public function __construct(SpecializationInterface $specializationService)
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
}
