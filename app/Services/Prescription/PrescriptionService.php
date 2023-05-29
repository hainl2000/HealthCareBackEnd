<?php

namespace App\Services\Prescription;

use App\Models\Prescription;

class PrescriptionService
{
    public function createPrescription($prescriptionData)
    {
        return Prescription::insert([$prescriptionData]);
    }
}
