<?php
namespace App\Services\Doctors;

interface DoctorServiceInterface
{
    public function register($registerData);
    public function login($loginData);
}
