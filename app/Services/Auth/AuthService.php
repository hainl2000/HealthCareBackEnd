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
            if ($loginningUser->tokenCan('role:doctor')) {
                $actor["doctor"] = $loginningUser;
            } else if ($loginningUser->tokenCan('role:user')) {
                $actor["user"] = $loginningUser;
            } else if ($loginningUser->tokenCan('role:admin')) {
                $actor["admin"] = $loginningUser;
            }
            return $actor;
        }
        return false;
    }
}
