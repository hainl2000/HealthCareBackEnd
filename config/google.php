<?php

return [
    'application_name' => env('APP_NAME'),
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect_uri' => env('GOOGLE_REDIRECT'),
    'scopes' => [
        Google_Service_Calendar::CALENDAR,
        Google_Service_Calendar::CALENDAR_EVENTS
    ],
    'access_type' => 'offline',
    'approval_prompt' => 'force',
    'auth_code' => env('GOOGLE_AUTH_CODE'),
    'prompt' => 'select_account consent',
    'calendar_id' => env('GOOGLE_CALENDAR_ID')
];
