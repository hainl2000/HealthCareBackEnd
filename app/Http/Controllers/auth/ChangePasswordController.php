<?php
namespace App\Http\Controllers\auth;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends ApiController {

    public function changePassword(Request $request)
    {
        if(!Auth::guard('sanctum')->check()) {
            return $this->respondError('Bạn chưa đăng nhập!');
        }

        $user = Auth::guard('sanctum')->user();
        // Validate request data
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->respondError('Mật khẩu hiện tại không đúng');
        }

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return $this->respondError('Mật khẩu hiện tại không đúng');
        }
        // Update the password
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        $user->tokens()->delete();
        $token = $user->createToken("API_TOKEN",['role:user'])->plainTextToken;
        return $this->respond(['new_token' => $token]);
    }

    public function doctorChangePassword(Request $request)
    {
        if(!Auth::guard('sanctum')->user()->tokenCan('role:doctor')) {
            return $this->respondError('Bạn chưa đăng nhập!');
        }

        $doctor = Auth::guard('sanctum')->user();
        // Validate request data
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return $this->respondError('Mật khẩu hiện tại không đúng');
        }

        if (!Hash::check($request->input('current_password'), $doctor->password)) {
            return $this->respondError('Mật khẩu hiện tại không đúng');
        }
        // Update the password
        $doctor->password = Hash::make($request->input('new_password'));
        $doctor->save();

        $doctor->tokens()->delete();
        $token = $doctor->createToken("API_TOKEN",['role:doctor'])->plainTextToken;
        return $this->respond(['new_token' => $token]);
    }
}
