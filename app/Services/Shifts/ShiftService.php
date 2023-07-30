<?php

namespace App\Services\Shifts;

use App\Models\Doctor;
use App\Models\DoctorShift;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class ShiftService implements ShiftServiceInterface
{
    public function getAllShifts()
    {
        $allShifts = Shift::all('id', 'start_time', 'end_time');
        return $allShifts;
    }

    public function getShiftInformationById($id)
    {
        return DoctorShift::with(["shift", "doctor:id,name,image,price", "doctor.doctor_information"])
            ->where('id', $id)
            ->first();
    }

    public function updateShiftStatus($id, $status)
    {
        return DoctorShift::where("id", "=", $id)->update([
            "status" => $status
        ]);
    }

    public function getShiftByBookingId($id)
    {
        $selectData = [
            "doctor_shift.*"
        ];
        return DoctorShift::select($selectData)->join("booking_information", function ($join) use ($id) {
            $join->on('booking_information.shift_id', '=', 'doctor_shift.id')
                ->where('booking_information.id', '=', $id);
            })
            ->first();
    }
}
