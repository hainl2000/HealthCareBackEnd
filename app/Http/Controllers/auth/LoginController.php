<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
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

            if (!$user->email_verified) {
                throw new \Exception("Need to verify email", 403);
            }

            //login in 1 device at the same time
            $user->tokens()->delete();

            $userData = [];
            $userData['id'] = $user->id;
            $userData['name'] = $user->name;
            $userData['email'] = $user->email;
            $userData['birthday'] = $user->birthday;
            $userData['gender'] = $user->gender;
            $respData = [
                "message" => 'Login successfully',
                'token' => $user->createToken("API_TOKEN",['role:user'])->plainTextToken,
                'user' => $userData
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
