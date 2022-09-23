<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends ApiController
{
    public function register(SignupRequest $request)
    {
        try
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'birthday' => Carbon::parse($request->birthday),
                'gender' => $request->gender
            ]);
            $resp = [
                "code" => 201,
                "message" => 'Create new user successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ];
        }catch (\Exception $e)
        {
            $resp = [
                "code" => $e->getCode(),
                "message" => $e->getMessage()
            ];
        }
        return $this->respondCreated($resp);
    }

    public function getVerifyEmailToken()
    {

    }


}
