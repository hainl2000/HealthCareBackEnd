<?php

namespace App\Services\Specializations;

interface SpecializationServiceInterface
{
    public function getListSpecializations($isIncludeDetail = null);
    public function getSpecializationDetail($slug);
    public function createSpecialization($specializationData);
}
