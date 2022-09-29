<?php

namespace App\Http\Controllers\auth;

use App\Exceptions\SendMailFailException;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use App\Jobs\SendVerifyAccountEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use function PHPUnit\Framework\throwException;

class SignupController extends ApiController
{
    public function register(SignupRequest $request)
    {
        try {
            $this->apiBeginTransaction();
            $token = time() . bin2hex(random_bytes(18));
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verify_token' => $token,
                'birthday' => Carbon::parse($request->birthday),
                'gender' => $request->gender
            ]);
            $data = array(
                'name' => $user->name,
                'verifyLink' => "http://127.0.0.1:8000/api/signup/verify/" . Crypt::encryptString($user->id) . "/" . $user->email_verify_token
            );
            $isSendEmailSuccess = $this->sendVerificationEmail($data,$user);
            if ($isSendEmailSuccess) {
                $this->apiCommit();
            } else {
                $this->apiRollback();
                throw new SendMailFailException("Send Email Fail",400);
            }
            $respData = [
                "message" => 'Create new user successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ];
            $resp = $this->respondCreated($respData);
        }  catch (SendMailFailException $e) {
            $resp = $this->respondError($e->getMessage(),$e->getCode());
        } catch (\Exception $e) {
            $resp = $this->respondError($e->getMessage(),$e->getCode());
        }
        return $resp;
    }

    public function sendVerificationEmail($data,$user)
    {
        try {
            SendVerifyAccountEmail::dispatch($data,$user)->delay(now()->addMinute(1));
            return true;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


}
