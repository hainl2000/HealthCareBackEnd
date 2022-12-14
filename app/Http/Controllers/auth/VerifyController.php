<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Carbon\Carbon;

class VerifyController extends ApiController
{
    public function verifyAccount($hash)
    {
        $user = User::where([
            ['email_verify_token', '=' , $hash]
        ])->first();
        if ($user) {
            $user->email_verified = Carbon::now();
            $user->save();
            $resp = $this->respondNoContent();
        } else {
            $respMessage =  "Verify Account Fail";
            $respCode = 401;
            $resp = $this->respondError($respMessage,$respCode);
        }
        return $resp;
    }
}
