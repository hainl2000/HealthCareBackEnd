<?php

namespace App\Services\Specializations;

use App\Models\Specialization;

class SpecializationService implements SpecializationServiceInterface
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

    public function getSpecializationDetail($slug)
    {
        $specializationDetail = Specialization::select('id','name','description','image','slug')
                                    ->where('slug', $slug)
                                    ->first();
        $specializationDetail->description = htmlspecialchars_decode($specializationDetail->description);
        return $specializationDetail;
    }
}
