<?php

namespace App\Services\Booking;

interface BookingServiceInterface
{
    public function createBooking($data);
    public function getBookingInformationById($id, $attributes = ["*"], $isShortInformation = false);
    public function getListBooking($attributes = ["*"], $doctorId = null, $searchConditions = []);
    public function updateBookingStatus($id, $status);
    public function getSoonestBooking();
    public function rateBooking($bookingId, $rating);
    public function updateFinishStatus($bookingId, $finishActor, $status);
    public function getBookingInformationByShiftId($shiftId);

}
