<?php

namespace App\Services\Mail;

use App\Jobs\SendSignupDoctorEmail;
use App\Jobs\SendVerifyAccountEmail;
use Illuminate\Support\Facades\Log;

class MailService implements MailServiceInterface
{
    public function sendVerificationEmail($data, $email)
    {
        try {
            SendVerifyAccountEmail::dispatch($data, $email)->delay(now()->addMinute(1));
            return true;
        } catch (\Exception $e) {
            Log::warning($e->getMessage());
            return false;
        }
    }

    public function sendSignupDoctorEmail($data, $email)
    {
        try {
            SendSignupDoctorEmail::dispatch($data, $email)->delay(now()->addMinute(1));
            return true;
        } catch (\Exception $e) {
            dd($e->getMessage());
            Log::warning($e->getMessage());
            return false;
        }
    }
}
