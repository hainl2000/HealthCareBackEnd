<?php

namespace App\Services\Doctors;

use App\Models\Doctor;
use App\Models\Shift;
use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
            $shift = Shift::where('id', '=', $shiftId)->first('start_time');
            $date = Arr::get($choseData, "date");
            $dateTimeRegister = Carbon::create($date)->toDateString() . " " . Carbon::create($shift->start_time)->toTimeString();
            $dateTimeRegister = Carbon::create($dateTimeRegister);
            $doctor->shifts()->attach($shiftId, [
                "status" => self::NO_PATIENT_STATUS,
                "date" => $dateTimeRegister
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
        $startDate = Carbon::create($startDate)->format("Y-m-d H:i:s");
        $endDate = Carbon::create($endDate)->format("Y-m-d 23:59:59");
        $registeredShifts = $doctor->shifts()
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->get();
        return $registeredShifts;
    }

    public function getDoctorsBySpecialization($slug)
    {
        $selectData = [
            "doctors.id",
            "doctors.name",
            "doctors.image",
        ];
        $next30Mins = Carbon::now()->addMinutes(30)->toDateTimeLocalString();
        $next3Days = Carbon::now()->addDay(3)->endOfDay();
        $doctors = Doctor::with(["shifts" => function ($query) use ($next30Mins, $next3Days) {
            $query->where('date', '>=', $next30Mins);
            $query->where('date', '<=', $next3Days);
            $query->where('status', '=', self::NO_PATIENT_STATUS);
            $query->orderBy('date', 'asc');
            $query->orderBy('start_time', 'asc');
            }, "doctor_information"])
            ->whereHas('specializations', function (Builder $query) use ($slug) {
                $query->where('specializations.slug', '=', $slug);
            })
            ->get($selectData);
        foreach ($doctors as $doctor) {
            if (isset($doctor["doctor_information"])) {
                $doctor["doctor_information"]["short_introduction"] = htmlspecialchars_decode($doctor["doctor_information"]["short_introduction"]);
                $doctor["doctor_information"]["introduction"] = htmlspecialchars_decode($doctor["doctor_information"]["introduction"]);
            }
        }
        return $doctors;
    }


}

