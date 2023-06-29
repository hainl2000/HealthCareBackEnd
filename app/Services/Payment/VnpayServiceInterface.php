<?php

namespace App\Services\Payment;

interface VnpayServiceInterface
{
    public function createPayment($bookingId);
}
