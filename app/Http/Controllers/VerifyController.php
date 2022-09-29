<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class VerifyController extends ApiController
{
    public function verifyAccount($id,$hash)
    {
        $user_id = Crypt::decryptString($id);
        $user = User::where([
            ['id' , '=' , $user_id],
            ['email_verify_token', '=' , $hash]
        ])->first();
        if ($user) {
            $user->email_verified = Carbon::now();
            $user->save();
            $resp = $this->respondNoContent();
        } else {
            $respMessage =  "Verify Account Fail";
            $respCode = 401;
            $resp = $this->respondError($respMessage,$respCode);
        }
        return $resp;
    }
}
