<?php

namespace App\Services\Mail;

interface MailServiceInterface
{
    public function sendVerificationEmail($data, $email);
}
