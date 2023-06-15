<?php
namespace App\Services\Doctors;

interface DoctorServiceInterface
{
    public function signup($signupDoctorData);
    public function registerShift($choseData);
    public function cancelShift($cancelShiftId);
    public function getRegisteredShifts($startDate, $endDate);
    public function getDoctorsBySpecialization($slug, $priorityId = null);
    public function getDoctorInformationById($id, $isIncludeShifts);
    public function getListDoctor($searchParams);
    public function insertDoctorInformation($doctorId, $doctorInfo);
    public function getDoctorInformationByBookingId($bookingId);

}
