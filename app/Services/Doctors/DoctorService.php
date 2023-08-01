<?php

namespace App\Services\Doctors;

use App\Enums\PaginationParams;
use App\Models\Doctor;
use App\Models\DoctorInformation;
use App\Models\DoctorShift;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;

class DoctorService implements DoctorServiceInterface
{
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
            'image' => Arr::get($signupDoctorData, 'image'),
            'sign' => Arr::get($signupDoctorData, 'sign'),
            'type' => Arr::get($signupDoctorData, 'type'),
            'price' => Arr::get($signupDoctorData, 'price'),
            'specialization_id' => Arr::get($signupDoctorData, 'specialization_id'),
            'created_by' => Arr::get($signupDoctorData, 'created_by'),
        ]);
        return $doctor;
    }

    public function insertDoctorInformation($doctorId, $doctorInfo)
    {
        return DoctorInformation::create([
            'doctor_id' => $doctorId,
            'short_introduction' => Arr::get($doctorInfo, "shortIntro"),
            'introduction' => Arr::get($doctorInfo, "fullIntro"),
        ]);
    }

    public function registerShift($chooseDatas)
    {
        try {
            $loginDoctorId = Auth::guard('sanctum')->id();
            $doctor = Doctor::find($loginDoctorId);

            foreach ($chooseDatas as $chooseData) {
                $shiftId = Arr::get($chooseData, 'shiftId');
                $shift = Shift::find($shiftId, ['start_time']);
                $date = Arr::get($chooseData, 'date');
                $dateTimeRegister = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' ' . $shift->start_time);

                $insertData[] = [
                    'doctor_id' => $doctor->id,
                    'shift_id' => $shiftId,
                    'status' => Config::get('constants.SHIFT.NO_PATIENT_STATUS'),
                    'date' => $dateTimeRegister,
                ];
            }
            $doctor->shifts()->attach($insertData);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getRegisteredShifts($startDate, $endDate)
    {
        $loginDoctorId = Auth::guard('sanctum')->id();
        $doctor = Doctor::find($loginDoctorId);
        $startDate = Carbon::create($startDate)->format("Y-m-d H:i:s");
        $endDate = Carbon::create($endDate)->format("Y-m-d 23:59:59");
        $registeredShifts = $doctor->shifts()
            ->where('date', '>=', $startDate)
            ->where('date', '<=', $endDate)
            ->get();
        return $registeredShifts;
    }

    public function getDoctorsBySpecialization($slug, $priorityId = null)
    {
        $selectData = [
            "doctors.id",
            "doctors.name",
            "doctors.image",
            "doctors.price"
        ];
        $next30Mins = Carbon::now()->addMinutes(30)->toDateTimeLocalString();
        $next3Days = Carbon::now()->addDay(3)->endOfDay();
        $doctors = Doctor::with(["shifts" => function ($query) use ($next30Mins, $next3Days) {
            $query->where('date', '>=', $next30Mins);
            $query->where('date', '<=', $next3Days);
            $query->where('status', '=', Config::get("constants.SHIFT.NO_PATIENT_STATUS"));
            $query->orderBy('date', 'asc');
            $query->orderBy('start_time', 'asc');
            }, "doctor_information"])
            ->whereHas('specializations', function (Builder $query) use ($slug) {
                $query->where('specializations.slug', '=', $slug);
            });
        if ($priorityId) {
            $doctors->orderByRaw('(id = ?) DESC', [$priorityId]);
        }
        $doctors = $doctors->get($selectData);

        foreach ($doctors as $doctor) {
            if (isset($doctor["doctor_information"])) {
                $doctor["doctor_information"]["short_introduction"] = htmlspecialchars_decode($doctor["doctor_information"]["short_introduction"]);
                $doctor["doctor_information"]["introduction"] = htmlspecialchars_decode($doctor["doctor_information"]["introduction"]);
            }
        }
        return $doctors;
    }

    public function getDoctorInformationById($id, $isIncludeShifts)
    {
        $selectData = [
            "doctors.id",
            "doctors.name",
            "doctors.image",
            "doctors.price"
        ];

        $query = Doctor::select($selectData)->with("doctor_information");

        if ($isIncludeShifts) {
            $next30Mins = Carbon::now()->addMinutes(30)->toDateTimeLocalString();
            $next3Days = Carbon::now()->addDay(3)->endOfDay();
            $query = $query->with(["shifts" => function ($query) use ($next30Mins, $next3Days) {
                $query->where('date', '>=', $next30Mins);
                $query->where('date', '<=', $next3Days);
                $query->where('status', '=', Config::get("constants.SHIFT.NO_PATIENT_STATUS"));
                $query->orderBy('date', 'asc');
                $query->orderBy('start_time', 'asc');
            }]);
        }
        $doctor = $query->where('id', '=', $id)->first();

        if (isset($doctor["doctor_information"])) {
            $doctor["doctor_information"]["short_introduction"] = htmlspecialchars_decode($doctor["doctor_information"]["short_introduction"]);
            $doctor["doctor_information"]["introduction"] = htmlspecialchars_decode($doctor["doctor_information"]["introduction"]);
        }
        return $doctor;
    }

    public function getDoctorFullInformationById($id)
    {
        $selectData = [
            "doctors.*",
        ];

        return Doctor::select($selectData)->with("doctor_information")->where('id', '=', $id)->first();
    }

    public function getListDoctor($paginationParams)
    {
        $selectAttributes = [
            'doctors.id',
            'doctors.name',
            'doctors.type',
            'doctors.created_at',
            'doctors.created_by',
            'doctors.specialization_id',
            "doctors.email",
            "doctors.gender",
            "doctors.image",
            "doctors.sign",
        ];
        $query = Doctor::query()->select($selectAttributes)->with("specializations:id,name","admin:id,name", "doctor_information");
        if (!empty($paginationParams['name'])) {
            $query = $query->whereLike('doctors.name', "%{$paginationParams['name']}%");
        }
        if (isset($paginationParams['type'])) {
            $query = $query->where('doctors.type', "=", $paginationParams['type']);
        }
        if (isset($paginationParams['specialization_id'])) {
            $query = $query->whereIn('doctors.specialization_id', $paginationParams['specialization_id']);
        }

        $query = $query->whereNull('deleted_at');
        if ($paginationParams['itemsPerPage'] == PaginationParams::GetAllItems) {
            $records = $query->get();
        } else {
            $records = $query->paginate($paginationParams['itemsPerPage']);
        }
        return $records;
    }

    public function getDoctorInformationByBookingId($bookingId)
    {
        $selectData = [
            'doctors.*'
        ];
        return Doctor::select($selectData)
            ->join('doctor_shift as ds', function ($join) {
                $join->on('ds.doctor_id', '=', 'doctors.id');
            })
            ->join('booking_information as bi', function ($join) {
                $join->on('bi.shift_id', '=', 'ds.id');
            })
            ->where([
                'bi.id' => $bookingId
            ])
            ->first();
    }

    public function cancelShift($cancelShiftId)
    {
        return DoctorShift::where([
            'id' => $cancelShiftId
        ])->delete();
    }

    public function getFeaturedDoctor()
    {
        $selectAttributes = [
            'doctors.id',
            'doctors.name',
            'doctors.image',
            'doctors.specialization_id',
        ];
        $doctors = Doctor::select($selectAttributes)->with(['specializations:id,name'])
            ->orderBy('doctors.created_at')
            ->get();

        $result = [];
        foreach ($doctors as $doctor) {
            $specializationId = $doctor->specializations->id;

            if (!isset($result[$specializationId]) || count($result[$specializationId]) < 2) {
                $result[$specializationId][] = $doctor;
            }
        }

        return collect($result)->flatten();
    }
}

