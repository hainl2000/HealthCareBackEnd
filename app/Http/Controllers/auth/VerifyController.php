<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

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
        } else {
            $respMessage =  "Verify Account Fail";
            $respCode = 401;
        }
        return Redirect::to(getenv("CLIENT_URL"));
    }
}
