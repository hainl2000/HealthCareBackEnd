<?php
namespace App\Services\Doctors;

interface DoctorServiceInterface
{
    public function signup($signupDoctorData);
    public function registerShift($choseData);
    public function getRegisteredShifts($startDate, $endDate);
    public function getDoctorsBySpecialization($slug);
    public function getDoctorInformationById($id, $isIncludeShifts);
    public function getListDoctor($searchParams);
    public function insertDoctorInformation($doctorId, $doctorInfo);
}
