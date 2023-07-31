<?php

namespace App\Services\Specializations;

interface SpecializationServiceInterface
{
    public function getListSpecializations($paginationParams, $isIncludeDetail = null);
    public function getSpecializationDetail($slug);
    public function createSpecialization($specializationData);
    public function updateSpecialization($specializationData);
}
