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
            'DIRECT_OBJECT' => 'BookingDetailAdminPage',
            'RECEIVE_ACTOR' => 'admin',
            'TITLE' => 'THÔNG BÁO CHUYỂN TIỀN',
            'DESCRIPTION' => 'Người dùng {name} đã thông báo chuyển tiền tới ca khám #{bookingId}'
        ],
        'BOOKING_CONFIRMATION_FOR_DOCTOR' => [
            'CHANNEL' => 'doctor-channel-{doctorId}',
            'ACTION' => 'notify-booking-confirmation',
            'DIRECT_OBJECT' => 'DoctorBookingInformation',
            'RECEIVE_ACTOR' => 'doctor',
            'TITLE' => 'BẠN CÓ CA KHÁM MỚI',
            'DESCRIPTION' => 'Bạn có ca khám mới #{bookingId}. Bấm vào để xem thông tin ca khám'
        ],
        'BOOKING_CONFIRMATION_FOR_USER' => [
            'CHANNEL' => 'user-channel-{userId}',
            'ACTION' => 'notify-booking-confirmation',
            'DIRECT_OBJECT' => 'UserBookingInformation',
            'RECEIVE_ACTOR' => 'user',
            'TITLE' => 'CA KHÁM ĐÃ ĐƯỢC XÁC NHẬN',
            'DESCRIPTION' => 'Ca khám #{bookingId} của bạn đã được xác nhận. Bấm vào để xem thông tin ca khám'
        ]
    ],

    'ACTOR' => [
        'ADMIN' => 'admin',
        'DOCTOR' => 'doctor',
        'PATIENT' => 'patient',
    ]
];
