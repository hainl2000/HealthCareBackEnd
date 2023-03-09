<?php

namespace App\Services\Booking;

use App\Models\BookingInformation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;

class BookingService implements BookingServiceInterface
{
    public function createBooking($data)
    {
        $createData = [
            "shift_id" => Arr::get($data, "shift_id"),
            "name" => Arr::get($data, "name"),
            "email" => Arr::get($data, "email"),
            "booker_email" => Arr::get($data, "booker_email"),
            "gender" => Arr::get($data, "gender"),
            "address" => Arr::get($data, "address"),
            "symptom" => Arr::get($data, "symptom"),
            "anamnesis" => Arr::get($data, "anamnesis"),
            "prev_information" => Arr::get($data, "prev_information"),
            "image" => Arr::get($data, "prev_diagnose"),
            "status" => Config::get("constants.BOOKING_STATUS.NOT_START")
        ];
        try {
            BookingInformation::create($createData);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getBookingInformationById($id, $attributes = ["*"])
    {
        return BookingInformation::select($attributes)->where("shift_id", "=", $id)->first();
    }
}
