<?php

namespace App\Services\Prescription;

interface PrescriptionServiceInterface
{
    public function createPrescription($shiftId, $prescriptionData);
}
