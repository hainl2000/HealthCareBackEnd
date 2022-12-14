<?php

namespace App\Services\Users;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    public function __construct()
    {

    }

    public function register($registerData)
    {
        $token = time() . bin2hex(random_bytes(18));
        $user = User::create([
            'name' => Arr::get($registerData, "name"),
            'email' => Arr::get($registerData, "email"),
            'password' => Hash::make(Arr::get($registerData, "password")),
            'email_verify_token' => $token,
            'birthday' => Carbon::parse(Arr::get($registerData, "birthday")),
            'gender' => Arr::get($registerData, "gender")
        ]);
        return $user;
    }

    public function login($loginData)
    {

    }
}
