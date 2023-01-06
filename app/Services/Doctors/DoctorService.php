<?php
namespace App\Services\Doctors;

use App\Models\Doctor;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class DoctorService implements DoctorServiceInterface
{
    const NO_PATIENT_STATUS = 1;
    public function __construct()
    {

    }

    public function signup($signupDoctorData)
    {
        $doctor = Doctor::create([
            'name' => Arr::get($signupDoctorData, 'name'),
            'email' => Arr::get($signupDoctorData, 'email'),
            'password' => Hash::make(Arr::get($signupDoctorData, 'password')),
            'gender' => Arr::get($signupDoctorData, 'gender'),
            'type' => Arr::get($signupDoctorData, 'type'),
            'specialization_id' => Arr::get($signupDoctorData, 'specialization_id'),
            'created_by' => 1,
        ]);
        return $doctor;
    }

    public function login($loginData)
    {

    }

    public function registerShift($choseData)
    {
        try {
            $doctor = Doctor::find(2);
            $shiftId = Arr::get($choseData, "shiftId");
            $date = Arr::get($choseData, "date");
            $doctor->shifts()->attach($shiftId, [
                "status" => self::NO_PATIENT_STATUS,
                "date" => $date
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getRegisteredShifts($startDate, $endDate)
    {
        $doctorId = 2;
        $doctor = Doctor::find($doctorId);
        $registeredShifts = $doctor->shifts()
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->get();
        return $registeredShifts;
    }


}

