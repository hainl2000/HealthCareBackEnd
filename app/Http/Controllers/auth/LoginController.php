<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends ApiController
{
    public function login(LoginRequest $request)
    {
        try {
            $email = $request->input("email");
            $password = $request->input("password");

            if(!Auth::attempt($request->only(['email', 'password']))){
                throw new AuthorizationException();
            }

            $user = User::where('email','=', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                throw new AuthorizationException();
            }

            //login in 1 device at the same time
            $user->tokens()->delete();

            $respData = [
                "message" => 'Login successfully',
                'token' => $user->createToken("API_TOKEN",['role:user'])->plainTextToken
            ];
            $resp = $this->respondSuccess($respData);
        } catch (AuthorizationException $e) {
            $resp = $this->respondFailedLogin();
        } catch (\Exception $e) {
            $resp = $this->respondError($e->getMessage(),$e->getCode());
        }
        return $resp;
    }
}
