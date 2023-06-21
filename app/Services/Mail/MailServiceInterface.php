<?php

namespace App\Services\Mail;

interface MailServiceInterface
{
    public function sendVerificationEmail($data, $email);
    public function sendSignupDoctorEmail($data, $email);
}
