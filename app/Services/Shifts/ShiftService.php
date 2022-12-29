<?php

namespace App\Services\Shifts;

use App\Models\Shift;

class ShiftService implements ShiftInterface
{
    public function getAllShifts()
    {
        $allShifts = Shift::all('id','start_time','end_time');
        return $allShifts;
    }
}
