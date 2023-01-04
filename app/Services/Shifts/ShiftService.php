<?php

namespace App\Services\Shifts;

use App\Models\Shift;

class ShiftService implements ShiftServiceInterface
{
    public function getAllShifts()
    {
        $allShifts = Shift::all('id','start_time','end_time');
        return $allShifts;
    }
}
