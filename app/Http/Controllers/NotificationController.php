<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthServiceInterface;
use App\Services\Notification\NotificationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class NotificationController extends ApiController
{
    private $notifcationService;
    private $authService;
    public function __construct(
        NotificationServiceInterface $notifcationService,
        AuthServiceInterface $authService
    )
    {
        $this->notifcationService = $notifcationService;
        $this->authService = $authService;
    }

    public function notifyTransferringMoney(Request $request)
    {
        $bookingId = $request->input('booking_id');
        try {
            $this->apiBeginTransaction();
            $moneyTransferNotification = $this->notifcationService->createTransferringMoneyNotification($bookingId);
            $this->apiCommit();
            $channel = Config::get('constants.NOTIFICATIONS.MONEY_TRANSFER.CHANNEL');
            $action = Config::get('constants.NOTIFICATIONS.MONEY_TRANSFER.ACTION');
            $this->notifcationService->notifyNewNotification($channel, $action, $moneyTransferNotification);
            return $this->respondCreated();
        } catch (\Exception $e) {
            $this->apiRollback();
            return $this->respondError($e->getMessage());
        }
    }

    public function getNotifications(Request $request)
    {
        try {
            $loggingActor = $this->handleLoggingActor();
            if (!$loggingActor) {
                throw new \Exception();
            }
            $isCountOnly = $request->query('is_count_only');
            $notifications = $this->notifcationService->getNotifications($loggingActor, Auth::guard('sanctum')->id(), $isCountOnly);
            if (!isset($notifications)) {
                throw new \Exception();
            }
            return $this->respond($notifications);
        } catch (\Exception $e) {
            return $this->respondError($e->getMessage());
        }
    }

    public function markNotificationsSeen(Request $request)
    {

    }

    private function handleLoggingActor()
    {
        $loggingActor = $this->authService->getLoggingInActor();
        if (isset($loggingActor["admin"])) {
            $actor = Config::get('constants.ACTOR.ADMIN');
        } else if (isset($loggingActor["doctor"])) {
            $actor = Config::get('constants.ACTOR.DOCTOR');
        } else if (isset($loggingActor["user"])) {
            $actor = Config::get('constants.ACTOR.PATIENT');
        }
        return $actor;
    }


}
