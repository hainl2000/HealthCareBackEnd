<?php

namespace App\Services\Specializations;

use App\Models\Specialization;

class SpecializationService implements SpecializationInterface
{
    public function __construct()
    {

    }

    public function getListSpecializations($isIncludeDetail = null)
    {
        if ($isIncludeDetail) {
            $listSpecialization = Specialization::all('name','image','slug','description');
        } else {
            $listSpecialization = Specialization::all('name','image','slug');
        }
        return $listSpecialization;
    }
}
