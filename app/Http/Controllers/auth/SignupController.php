<?php

namespace App\Http\Controllers\auth;

use App\Exceptions\SendMailFailException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\SignupRequest;
use App\Jobs\SendVerifyAccountEmail;
use App\Services\Users\UserServiceInterface;
use Illuminate\Http\Request;

class SignupController extends ApiController
{
    private $userService;
    public function __construct(
        UserServiceInterface $userService
    )
    {
        $this->userService = $userService;
    }

    public function register(SignupRequest $request)
    {
        try {
            $this->apiBeginTransaction();
            $registerData = $request->all();
            $user = $this->userService->register($registerData);

            $data = array(
                'name' => $user->name,
                'verifyLink' => env('API_ENDPOINT',"http://127.0.0.1:8000/api/") . "user/signup/verify/" . $user->email_verify_token
            );
            $isSendEmailSuccess = $this->sendVerificationEmail($data,$user);
            if ($isSendEmailSuccess) {
                $this->apiCommit();
            } else {
                throw new SendMailFailException("Send Email Fail",400);
            }
            $respData = [
                "message" => 'Create new user successfully',
            ];
            $resp = $this->respondCreated($respData);
        }  catch (SendMailFailException $e) {
            $this->apiRollback();
            $resp = $this->respondError($e->getMessage(),$e->getCode());
        } catch (\Exception $e) {
            $this->apiRollback();
            $resp = $this->respondError($e->getMessage(),400);
        }
        return $resp;
    }

    public function registerDoctor(Request $request)
    {
        try {
            $this->apiBeginTransaction();
            $registerData = $request->all();
        } catch (\Exception $e) {
            $this->apiRollback();
            $resp = $this->respondError($e->getMessage(),400);
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
