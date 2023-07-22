<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function changePassword(Request $request)
    {
        if(!Auth::guard('sanctum')->check()) {
            return response()->json(['error' => 'Bạn chưa đăng nhập!']);
        }

        $user = Auth::guard('sanctum')->user();
        // Validate request data
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        // Check if the current password is correct
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['error' => 'Mật khẩu hiện tại không đúng']);
        }

        // Update the password
        $user->password = Hash::make($request->input('new_password'));
        $user->save();

        return response()->json(['message' => 'Thay đổi mật khẩu thành công']);
    }
}
