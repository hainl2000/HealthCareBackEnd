<?php

namespace App\Services\Payment;

use App\Services\Booking\BookingServiceInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class VnpayService implements VnpayServiceInterface
{
    private $bookingService;

    public function __construct(BookingServiceInterface $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    private function setupVnPayConfig($orderId, $price)
    {
        $vnPayConfig = [];
        $vnPayConfig['vnp_Version'] = "2.1.0";
        $vnPayConfig['vnp_TmnCode'] = Config::get('vnpay.tmn_code');
        $vnPayConfig['vnp_ReturnUrl'] = Config::get('vnpay.return_url');
        $vnPayConfig['vnp_CreateDate'] = date("YmdHis");
        $vnPayConfig['vnp_TxnRef'] = rand(1,10000);
        $vnPayConfig['vnp_CurrCode'] = "VND";
        $vnPayConfig['vnp_OrderType'] = 'other';
        $vnPayConfig['vnp_OrderInfo'] = $orderId;
        $vnPayConfig['vnp_Amount'] = $price * 100;
        $vnPayConfig['vnp_Locale'] = 'vn';
        $vnPayConfig['vnp_IpAddr'] = '127.0.0.1';
        $vnPayConfig['vnp_Command'] = "pay";
        $vnPayConfig['vnp_ExpireDate'] = Carbon::now()->addMinutes(30)->format('YmdHis');
        return $vnPayConfig;
    }

    private function createPaymentUrl($config)
    {
        ksort($config);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($config as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        $url = Config::get('vnpay.url') . "?" . $query;
        $vnpSecureHash =   hash_hmac('sha512', $hashdata, Config::get('vnpay.hash_secret'));
        $url .= 'vnp_SecureHash=' . $vnpSecureHash;
        return $url;
    }
    public function createPayment($bookingId)
    {
        $booking = $this->bookingService->getBookingPrice($bookingId);
        $vnPayConfig = $this->setupVnPayConfig($bookingId, $booking->price);
        return $this->createPaymentUrl($vnPayConfig);
    }


}
