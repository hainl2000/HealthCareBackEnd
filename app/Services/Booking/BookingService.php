<?php

namespace App\Services\Booking;

use App\Models\BookingInformation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use App\Services\Google\GoogleServiceInterface;

class BookingService implements BookingServiceInterface
{
    private $googleService;

    public function __construct(GoogleServiceInterface $googleServiceInterface)
    {
        $this->googleService = $googleServiceInterface;
    }

    public function createBooking($data)
    {
        $data["video_link"] = $this->googleService->createMeeting();
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
            "status" => Config::get("constants.BOOKING_STATUS.NOT_START"),
            "created_by" => Arr::get($data, "created_by")
        ];
        try {
            BookingInformation::create($createData);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getBookingInformationById($id, $attributes = ["*"], $isShortInformation = false)
    {
        $query = BookingInformation::select($attributes);
        if (!$isShortInformation) {
            $query = $query->join('doctor_shift as ds', function ($join) {
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
        }

        return $query->where("booking_information.shift_id", "=", $id)->first();
    }

    public function getListBooking($attributes = ["*"], $data = null)
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
            });
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
        return $query->get();
    }

    public function updateBookingStatus($id, $status)
    {
        return BookingInformation::where('id', $id)->update([
            "status" => $status
        ]);
    }
}
