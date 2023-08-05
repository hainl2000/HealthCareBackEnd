<?php

namespace App\Services\Booking;

use App\Enums\PaginationParams;
use App\Enums\Status;
use App\Events\PushLatestPatientEvent;
use App\Models\BookingInformation;
use App\Services\Shifts\ShiftServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Services\Google\GoogleServiceInterface;

class BookingService implements BookingServiceInterface
{
    private $googleService;
    private $shiftService;

    public function __construct(
        GoogleServiceInterface $googleService,
        ShiftServiceInterface $shiftService
    )
    {
        $this->googleService = $googleService;
        $this->shiftService = $shiftService;
    }

    public function createBooking($data)
    {
        $shiftInfo = $this->shiftService->getShiftInformationById(Arr::get($data, "shift_id"));
        $data["video_link"] = $this->googleService->createMeeting(Carbon::parse($shiftInfo->date), Arr::get($data, "email"));
        $createData = [
            "shift_id" => Arr::get($data, "shift_id"),
            "name" => Arr::get($data, "name"),
            "email" => Arr::get($data, "email"),
            "phone_number" => Arr::get($data, "phone_number"),
            "booker_email" => Arr::get($data, "booker_email"),
            "gender" => Arr::get($data, "gender"),
            "address" => Arr::get($data, "address"),
            "symptom" => Arr::get($data, "symptom"),
            "video_link" => Arr::get($data, "video_link"),
            "anamnesis" => Arr::get($data, "anamnesis"),
            "prev_information" => Arr::get($data, "prev_information"),
            "image" => Arr::get($data, "prev_diagnose"),
            "status" => Config::get("constants.BOOKING_STATUS.WAITING_PAYMENT"),
            "created_by" => Arr::get($data, "created_by")
        ];
        try {
            return BookingInformation::create($createData);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getBookingInformationById($id, $attributes = ["*"], $isShortInformation = false)
    {
        $query = BookingInformation::select($attributes);
        if (!$isShortInformation) {
            $query = $query->with([
                'prescription' => function ($builder) {
                    $builder->select('id', 'booking_id', 'diagnose', 'additional_direction');
                },
                'prescription.prescriptionDrugs' => function ($builder) {
                    $builder->select(
                        'prescription_id',
                        'drug_id',
                        'other_drug_name',
                        'other_drug_unit',
                        'dosages',
                        'number_per_time',
                        'meals',
                        'note',
                        'times'
                    );
                },
                'prescription.prescriptionDrugs.drug' => function ($builder) {
                    $builder->select(
                        'id', 'name', 'unit'
                    );
                }
            ])->join('doctor_shift as ds', function ($join) {
                $join->on('ds.id', '=', 'booking_information.shift_id');
            })->join('shifts as sh', function ($join) {
                $join->on('sh.id', '=', 'ds.shift_id');
            })->join('doctors as do', function ($join) {
                $join->on('do.id', '=', 'ds.doctor_id');
            })->join('specializations as sp', function ($join) {
                $join->on('sp.id', '=', 'do.specialization_id');
            });
        }

        return $query->where("booking_information.id", "=", $id)->first();
    }

    public function getBookingPrice($bookingId)
    {
        return BookingInformation::select('do.price')
            ->join('doctor_shift as ds', function ($join) {
                $join->on('ds.id', '=', 'booking_information.shift_id');
            })->join('doctors as do', function ($join) {
                $join->on('do.id', '=', 'ds.doctor_id');
            })->where("booking_information.id", "=", $bookingId)->first();
    }

    public function getListBooking($attributes = ["*"], $data = null, $searchConditions = [])
    {
        $query = BookingInformation::select($attributes)
            ->join('doctor_shift as ds', function ($join) {
                $join->on('ds.id', '=', 'booking_information.shift_id');
            })
            ->join('shifts as sh', function ($join) {
                $join->on('sh.id', '=', 'ds.shift_id');
            })
            ->join('doctors as do', function ($join) {
                $join->on('do.id', '=', 'ds.doctor_id');
            })
            ->join('specializations as sp', function ($join) {
                $join->on('sp.id', '=', 'do.specialization_id');
            });
        if (isset($searchConditions['patient_name'])) {
            $query = $query->whereLike('booking_information.name', "%{$searchConditions['patient_name']}%");
        }
        if (!empty($searchConditions['statuses'])) {
            $query = $query->whereIn('booking_information.status', $searchConditions['statuses']);
        }
        if (!empty($searchConditions['specializations'])) {
            $query = $query->whereIn('sp.id', $searchConditions['specializations']);
        }
        if (!empty($searchConditions['doctor_name'])) {
            $query = $query->whereLike('do.name', "%{$searchConditions['doctor_name']}%");
        }
        if (!empty($searchConditions['selectedDoctors'])) {
            $query = $query->whereIn('do.id', $searchConditions['selectedDoctors']);
        }
        if (!empty($searchConditions['dateRange'])) {
            if (count($searchConditions['dateRange']) == 1) {
                $query = $query->whereDate('booking_information.created_at', '=', $searchConditions['dateRange'][0]);
            } else {
                $query = $query->whereBetween('booking_information.created_at', [$searchConditions['dateRange'][0], $searchConditions['dateRange'][1]]);
            }
        }
        if ($data) {
            if (isset($data["doctor"])) {
                $query->where("ds.doctor_id", "=", $data["doctor"]["id"])
                    ->whereNotIn("booking_information.status", [
                        Config::get("constants.BOOKING_STATUS.CANCEL"),
                        Config::get("constants.BOOKING_STATUS.WAITING_PAYMENT")
                    ]);
            } else if (isset($data["user"])) {
                $query->where("booking_information.created_by", "=", $data["user"]["id"]);
            }
        }
        $query->orderBy('booking_information.created_at', 'desc');
        if ($searchConditions['itemsPerPage'] == PaginationParams::GetAllItems) {
            $records = $query->get();
        } else {
            $records = $query->paginate($searchConditions['itemsPerPage']);
        }
        return $records;
    }

    public function updateBookingStatus($bookingId, $status)
    {
        return BookingInformation::where('id', $bookingId)->update([
            "status" => $status
        ]);
    }

    public function getSoonestBooking()
    {
        $loginDoctorId = Auth::guard('sanctum')->id();
        $selectAttributes = [
            'booking_information.id',
            'users.name',
            'ds.date',
        ];

        return BookingInformation::select($selectAttributes)
            ->join('doctor_shift as ds', function ($join) {
                $join->on('ds.id', '=', 'booking_information.shift_id');
            })
            ->join('shifts as sh', function ($join) {
                $join->on('sh.id', '=', 'ds.shift_id');
            })
            ->join('doctors as do', function ($join) {
                $join->on('do.id', '=', 'ds.doctor_id');
            })
            ->join('users', function ($join) {
                $join->on('users.id', '=', 'booking_information.created_by');
            })
            ->where([
                ["ds.doctor_id", "=", $loginDoctorId],
                ['ds.date', '>', now()],
                ['booking_information.status', '=', Config::get("constants.BOOKING_STATUS.NOT_START")]
            ])
            ->orderByRaw('ABS(TIMESTAMPDIFF(MINUTE, ds.date, NOW()))')
            ->first();
    }

    public function rateBooking($bookingId, $rating)
    {
        try {
            $selectAttributes = [
                'booking_information.id as id',
                'booking_information.doctor_finish as doctor_finish',
                'do.id as doctor_id',
            ];
            $booking = BookingInformation::select($selectAttributes)
            ->join('doctor_shift as ds', function ($join) {
                $join->on('ds.id', '=', 'booking_information.shift_id');
            })->join('doctors as do', function ($join) {
                $join->on('do.id', '=', 'ds.doctor_id');
            })->where([
                'booking_information.id' => $bookingId
            ])->first();

            if ($booking) {
                $booking->rating = Arr::get($rating, 'rating');
                $booking->comment = Arr::get($rating, 'comment');
                $booking->patient_finish = Status::ACTIVE;
                $booking->save();
            }
            return $booking;
        } catch (\Exception $e) {
            dd($e->getMessage());
            return false;
        }
    }

    public function updateFinishStatus($bookingId, $finishActor, $status)
    {
        $finishActor = $finishActor . "_finish";
        try {
            BookingInformation::where('id', $bookingId)->update([
                $finishActor => $status
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getBookingInformationByShiftId($shiftId)
    {
        return BookingInformation::where([
            'shift_id' => $shiftId
        ])->first();
    }

    public function updateBookingShift($bookingId, $changeShiftId)
    {
        return BookingInformation::where([
            'id' => $bookingId
        ])->update([
            'shift_id' => $changeShiftId
        ]);
    }

    public function pushLatestBookingForDoctor($doctorId)
    {
        event(new PushLatestPatientEvent($doctorId));
    }

    public function getExportBookingData($id)
    {
        $attributes = [
            'do.name as doctor_name',
            'do.sign as doctor_sign',
            'booking_information.id',
            'booking_information.name as patient_name',
            'booking_information.gender as patient_gender',
            'booking_information.address as patient_address',
        ];
        $data = BookingInformation::select($attributes)->with([
            'prescription' => function ($builder) {
                $builder->select('id', 'booking_id', 'diagnose', 'additional_direction');
            },
            'prescription.prescriptionDrugs' => function ($builder) {
                $builder->select(
                    'prescription_id',
                    'drug_id',
                    'other_drug_name',
                    'other_drug_unit',
                    'dosages',
                    'number_per_time',
                    'meals',
                    'note',
                    'times'
                );
            },
            'prescription.prescriptionDrugs.drug' => function ($builder) {
                $builder->select(
                    'id', 'name', 'unit'
                );
            }
        ])->join('doctor_shift as ds', function ($join) {
            $join->on('ds.id', '=', 'booking_information.shift_id');
        })->join('doctors as do', function ($join) {
            $join->on('do.id', '=', 'ds.doctor_id');
        })->where("booking_information.id", "=", $id)
        ->first();
        return $this->handleShowPrescriptionData($data->toArray());
    }

    private function handleShowPrescriptionData($prescriptionData)
    {
        $showPrescriptionData = [];
        $showPrescriptionData['doctorName'] = $prescriptionData['doctor_name'];
        $showPrescriptionData['doctorSign'] = $prescriptionData['doctor_sign'];
        $showPrescriptionData['patientName'] = $prescriptionData['patient_name'];
        $showPrescriptionData['patientGender'] = $prescriptionData['patient_gender'] == 1 ? "Nam" : "Nữ";
        $showPrescriptionData['patientAddress'] = $prescriptionData['patient_address'];
        $showPrescriptionData['diagnose'] = $prescriptionData['prescription']['diagnose'];
        $showPrescriptionData['additionalDirection'] = $prescriptionData['prescription']['additional_direction'];
        $drugsData = $prescriptionData['prescription']['prescription_drugs'];
        foreach ($drugsData as $drug) {
            $showData = [];
            $showData['drugName'] = isset($drug['drug']) ? $drug['drug']['name'] : $drug['other_drug_name'];
            $showData['drugUnit'] = isset($drug['drug']) ? $drug['drug']['unit'] : $drug['other_drug_unit'];
            $showData['dosages'] = $drug['dosages'];
            $showData['numberPerTime'] = $drug['number_per_time'];
            $showData['meals'] = $drug['meals'];
            $showData['note'] = $drug['note'];
            $showData['timesText'] = "";
            foreach ($drug['times'] as $time) {
                $showData['timesText'] .= Config::get('messages.time_in_text')[$time];
                $showData['timesText'] .= " ";
            }
            $drugInformation[] = $showData;
        }
        $showPrescriptionData['drugs'] = $drugInformation;
        return $showPrescriptionData;
    }
}
