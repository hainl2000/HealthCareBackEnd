<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends ApiController
{
    public function actionLogout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
            return $this->respondSuccessWithoutData();
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }
    }
}
