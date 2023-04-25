<?php

namespace App\Services\Booking;

interface BookingServiceInterface
{
    public function createBooking($data);
    public function getBookingInformationById($id, $attributes = ["*"], $isShortInformation = false);
    public function getListBooking($attributes = ["*"], $doctorId = null, $searchConditions = []);
    public function updateBookingStatus($id, $status);
}
