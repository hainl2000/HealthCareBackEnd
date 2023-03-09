<?php

namespace App\Services\Booking;

interface BookingServiceInterface
{
    public function createBooking($data);
    public function getBookingInformationById($id, $attributes = ["*"]);
}
