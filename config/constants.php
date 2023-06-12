<?php

return [
    'SHIFT' => [
        'NO_PICK_STATUS' => 0,
        'NO_PATIENT_STATUS' => 1,
        'HAVE_PATIENT_STATUS' => 2,
        'CHOSEN_STATUS' => 3,
    ],

    'BOOKING_STATUS' => [
        'NOT_START' => 0,
        'END' => 1,
        'CANCEL' => 2,
        'WAITING_PAYMENT' => 3
    ],

    'UPLOAD_FOLDER' => [
        'BOOKING_DIAGNOSE' => "public/booking/PreviousDiagnose",
        'AVATAR' => "public/avatar/"
    ],

    "RES_MESSAGES" => [
        'RATING_SUCCESSFULLY' => "Đánh giá ca khám thành công",
    ],

    'NOTIFICATIONS' => [
        'MONEY_TRANSFER' => [
            'CHANNEL' => 'admin-channel',
            'ACTION' => 'notify-transferring-money',
            'DIRECT_OBJECT' => 'AdminBookingInformation',
            'RECEIVER_ID' => null,
            'RECEIVE_ACTOR' => 'admin',
            'TITLE' => 'THÔNG BÁO CHUYỂN TIỀN',
            'DESCRIPTION' => 'Người dùng {name} đã thông báo chuyển tiền tới ca khám #{bookingId}'
        ]
    ],

    'ACTOR' => [
        'ADMIN' => 'admin',
        'DOCTOR' => 'doctor',
        'PATIENT' => 'patient',
    ]
];
