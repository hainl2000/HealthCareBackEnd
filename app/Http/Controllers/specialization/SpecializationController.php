<?php

namespace App\Http\Controllers\specialization;

use App\Enums\PaginationParams;
use App\Http\Controllers\ApiController;
use App\Services\File\FileServiceInterface;
use App\Services\Specializations\SpecializationServiceInterface;
use Illuminate\Http\Request;

class SpecializationController extends ApiController
{
    private $specializationService;
    private $fileService;

    public function __construct(SpecializationServiceInterface $specializationService,
                FileServiceInterface $fileService)
    {
        $this->specializationService = $specializationService;
        $this->fileService = $fileService;
    }

    public function getListSpecializations(Request $request)
    {
        $isIncludeDetail = $request->input('includeDetail');
        $paginationParams = [];
        if ($isIncludeDetail) {
            $paginationParams['itemsPerPage'] = $request->query('itemsPerPage', PaginationParams::RecordsPerPage);
            $paginationParams['name'] = $request->query('name');
            $paginationParams['type'] = $request->query('type');
        }
        $listSpecializations = $this->specializationService->getListSpecializations($paginationParams, $isIncludeDetail);
        foreach ($listSpecializations as $specialization) {
            $specialization['image'] = $this->fileService->getFileUrl($specialization['image']);
        }
        return $this->respondSuccess($listSpecializations);
    }

    public function getSpecializationDetail($slug)
    {
        $specializationDetail = $this->specializationService->getSpecializationDetail($slug);
        return $this->respondSuccess($specializationDetail);
    }

    public function createNewSpecialization(Request $request)
    {
        $specializationData = $request->input();
        $specializationData['image'] = $request->file('image');
        $specialization = $this->specializationService->createSpecialization($specializationData);
        return $this->respondCreated([$specialization]);
    }

    public function updateSpecialization(Request $request)
    {
        $specializationData = $request->input();
        $specializationData['image'] = $request->file('image');
        $specialization = $this->specializationService->updateSpecialization($specializationData);
        return $this->respondCreated([$specialization]);
    }
}
