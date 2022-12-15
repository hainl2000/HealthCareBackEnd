<?php

namespace App\Services\Specializations;

interface SpecializationInterface
{
    public function getListSpecializations($isIncludeDetail = null);
}
