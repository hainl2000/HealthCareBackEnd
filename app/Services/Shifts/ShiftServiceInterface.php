<?php

namespace App\Services\Shifts;

interface ShiftServiceInterface
{
    public function getAllShifts();
    public function getShiftInformationById($id);
    public function updateShiftStatus($id, $status);
}

