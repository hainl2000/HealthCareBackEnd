<?php
namespace App\Services\Doctors;

interface DoctorServiceInterface
{
    public function signup($signupDoctorData);
    public function login($loginDoctorData);
    public function registerShift($choseData);
    public function getRegisteredShifts($startDate, $endDate);
}
