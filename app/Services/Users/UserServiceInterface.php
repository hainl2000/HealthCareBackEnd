<?php

namespace App\Services\Users;

interface UserServiceInterface
{
    public function register($registerData);
    public function login($loginData);
}
