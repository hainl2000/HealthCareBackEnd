<?php

namespace App\Http\Controllers\auth;

use App\Exceptions\SendMailFailException;
use App\Http\Controllers\ApiController;
use App\Http\Requests\SignupDoctorRequest;
use App\Http\Requests\SignupRequest;
use App\Services\Doctors\DoctorServiceInterface;
use App\Services\Mail\MailServiceInterface;
use App\Services\Users\UserServiceInterface;
use Illuminate\Http\Request;

class SignupController extends ApiController
{
    private $userService;
    private $mailService;
    private $doctorService;

    public function __construct(
        UserServiceInterface $userService,
        MailServiceInterface $mailService,
        DoctorServiceInterface $doctorService
    )
    {
        $this->userService = $userService;
        $this->mailService = $mailService;
        $this->doctorService = $doctorService;
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
            $isSendEmailSuccess = $this->mailService->sendVerificationEmail($data, $user->email);
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

    public function signupDoctor(SignupDoctorRequest $request)
    {
        try {
            $this->apiBeginTransaction();
            $signupDoctorData = $request->all();
            $doctor = $this->doctorService->signup($signupDoctorData);
            if ($doctor) {
                $this->apiCommit();
            }
            $respData = [
                "message" => 'Create new doctor successfully',
            ];
            $resp = $this->respondCreated($respData);
        } catch (\Exception $e) {
            $this->apiRollback();
            $resp = $this->respondError($e->getMessage(),400);
        }
        return $resp;
    }

}
