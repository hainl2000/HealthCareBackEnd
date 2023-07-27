<?php

namespace App\Services\Google;

use Carbon\Carbon;
use Google_Service_Calendar_Event;
use Google_Client;
use Google_Service_Calendar;
use Exception;

class GoogleService implements GoogleServiceInterface
{

    private function getClient()
    {
        try {
            $client = new Google_Client();
            $client->setApplicationName(config('google.application_name'));
            $client->setAuthConfig(storage_path('app/google-credentials.json'));
            $client->setAccessType(config('google.access_type'));
            $client->setScopes(config('google.scopes'));
            $client->setPrompt(config('google.prompt'));
            $client->setRedirectUri('http://127.0.0.1:8000/oauthcallback');

            // Load previously authorized token from a file, if it exists.
            // The file token.json stores the user's access and refresh tokens, and is
            // created automatically when the authorization flow completes for the first
            // time.
            $tokenPath = storage_path('app/google-token.json');
            if (file_exists($tokenPath)) {
                $accessToken = json_decode(file_get_contents($tokenPath), true);
                $client->setAccessToken($accessToken);
            }
            //get code and delete file google-token.json

//             If there is no previous token or it's expired.
            if ($client->isAccessTokenExpired()) {
                // Refresh the token if possible, else fetch a new one.
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                } else {
                    // Request authorization from the user.
                    $client->getAccessToken();
                    $client->getRefreshToken();
                    $authCode = config('google.auth_code');
                    // Exchange authorization code for an access token.
                    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                    $client->setAccessToken($accessToken);
                    // Check to see if there was an error.
                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                }
                // Save the token to a file.
                if (!file_exists(dirname($tokenPath))) {
                    mkdir(dirname($tokenPath), 0700, true);
                }
                file_put_contents($tokenPath, json_encode($client->getAccessToken()));
            }
        } catch (\Google\Exception $e) {
            dd($e->getMessage());
        }
        return $client;
    }

    public function createMeeting($startDateTime, $patientEmail)
    {
        try{
            $client = $this->getClient();
            $service = new Google_Service_Calendar($client);
            $calendarId = config('google.calendar_id');

            $event = new Google_Service_Calendar_Event([
                'summary' => config('google.application_name'),
                'description' => config('google.application_name'),
                'location' => 'Hanoi, VietNam',
                'start' => [
                    'dateTime' => $startDateTime->format('Y-m-d\TH:i:s'),
                    'timeZone' => config('app.timezone')
                ],
                'end' => [
                    'dateTime' => $startDateTime->addMinutes(40)->format('Y-m-d\TH:i:s'),
                    'timeZone' => config('app.timezone')
                ],
                'conferenceData' => [
                    'createRequest' => [
                        "conferenceSolutionKey" => [
                            "type" => "hangoutsMeet"
                        ],
                        'requestId' => uniqid()
                    ]
                ],
                "allowedConferenceSolutionTypes" => [
                    "hangoutsMeet"
                ],
                'attendees' => [ [
                    "email" => $patientEmail,
                    "displayName" => "Bệnh nhân"
                ]]
            ]);
            $meet = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);
            return $meet->getHangoutLink();
            // Redirect to the Google Meet URL
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

}
