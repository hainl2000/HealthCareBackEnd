<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use App\Models\Doctor;
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

    public function doctorLogin(LoginRequest $request)
    {
        try {
            $email = $request->input("email");
            $password = $request->input("password");
            $credentials = ['email' => $email, 'password' => $password];
            if(!Auth::guard('doctor')->attempt($credentials)){
                throw new AuthorizationException();
            }
            $doctor = Doctor::with('specializations:id,name')->where('email','=', $email)->first();

            if (!$doctor || !Hash::check($password, $doctor->password)) {
                throw new AuthorizationException();
            }

            //login in 1 device at the same time
            $doctor->tokens()->delete();

            $doctorData = [];
            $doctorData['id'] = $doctor->id;
            $doctorData['name'] = $doctor->name;
            $doctorData['email'] = $doctor->email;
            $doctorData['image'] = $doctor->image;
            $doctorData['type'] = $doctor->type;
            $doctorData['gender'] = $doctor->gender;
            $doctorData['specializationId'] = $doctor->specialization_id;
            $doctorData['specializationName'] = $doctor->specializations->name;

            $respData = [
                "message" => 'Login successfully',
                'token' => $doctor->createToken("API_TOKEN", ['role:doctor'])->plainTextToken,
                'doctor' => $doctorData
            ];
            $resp = $this->respondSuccess($respData);
        } catch (AuthorizationException $e) {
            $resp = $this->respondFailedLogin();
        } catch (\Exception $e) {
            $resp = $this->respondError($e->getMessage(),$e->getCode());
        }
        return $resp;
    }

    public function adminLogin(LoginRequest $request)
    {
        try {
            $email = $request->input("email");
            $password = $request->input("password");
            $credentials = ['email' => $email, 'password' => $password];
            if(!Auth::guard('admins')->attempt($credentials)) {
                throw new AuthorizationException();
            }

            $admin = Admin::where('email','=', $email)->first();
            if (!$admin || !Hash::check($password, $admin->password)) {
                throw new AuthorizationException();
            }

            $adminData = [];
            $adminData['id'] = $admin->id;
            $adminData['name'] = $admin->name;
            $adminData['email'] = $admin->email;
            $adminData['image'] = $admin->image;

            $respData = [
                "message" => 'Login successfully',
                'token' => $admin->createToken("API_TOKEN",['role:admin'])->plainTextToken,
                'admin' => $adminData
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
