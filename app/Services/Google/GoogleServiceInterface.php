<?php

namespace App\Services\Google;

interface GoogleServiceInterface
{
    public function createMeeting($startDateTime, $patientEmail);
}
