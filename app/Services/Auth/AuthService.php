<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;

class AuthService implements AuthServiceInterface
{
    public function getLoggingInActor()
    {
        $loginningUser = Auth::guard('sanctum')->user();
        $actor = array();
        if ($loginningUser) {
            if ($loginningUser->token()->payload('role:doctor')) {
                $actor["doctor"] = $loginningUser;
            } else if ($loginningUser->token()->payload('role:user')) {
                $actor["user"] = $loginningUser;
            }
            return $actor;
        }
        return false;
    }
}
