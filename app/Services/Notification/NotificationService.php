<?php

namespace App\Services\Notification;

use App\Enums\Status;
use App\Events\NewNotification;
use App\Models\Notification;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class NotificationService implements NotificationServiceInterface
{
    private function createNotification($notificationData)
    {
        return Notification::create([
            'direct_id' => Arr::get($notificationData, 'direct_id'),
            'direct_object' => Arr::get($notificationData, 'direct_object'),
            'receiver_id' => Arr::get($notificationData, 'receiver_id'),
            'receive_actor' => Arr::get($notificationData, 'receive_actor'),
            'title' => Arr::get($notificationData, 'title'),
            'description' => Arr::get($notificationData, 'description'),
            'is_seen' => Status::ACTIVE
        ]);
    }

    public function getNotifications($receiveActor, $receiverId, $isCountOnly)
    {
        $query = Notification::where([
            'receive_actor' => $receiveActor
        ])->orderBy('created_at', 'DESC');
        if ($receiveActor != Config::get('constants.ACTOR.ADMIN')) {
            $query = $query->where([
                'receiver_id' => $receiverId
            ]);
        }
        if ($isCountOnly) {
            $records = $query->where([
                'is_seen' => Status::ACTIVE
            ])->count();
        } else {
            $records = $query->get();
        }
        return $records;
    }

    public function notifyNewNotification($channel, $actor, $notificationData)
    {
        event(new NewNotification($channel, $actor, $notificationData));
    }

    public function createBookingConfirmationNotificationForDoctor($bookingId, $doctorId)
    {
        $replaceData = [
            'bookingId' => $bookingId
        ];
        $description = replacePlaceholders(Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_DOCTOR.DESCRIPTION'), $replaceData);
        $bookingConfirmationNotificationData = [
            'direct_id' => $bookingId,
            'direct_object' => Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_DOCTOR.DIRECT_OBJECT'),
            'receiver_id' => $doctorId,
            'receive_actor' => Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_DOCTOR.RECEIVE_ACTOR'),
            'title' => Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_DOCTOR.TITLE'),
            'description' => $description,
            'is_seen' => Status::ACTIVE
        ];

        return $this->createNotification($bookingConfirmationNotificationData);
    }

    public function createBookingConfirmationNotificationForUser($bookingId, $userId)
    {
        $replaceData = [
            'bookingId' => $bookingId
        ];
        $description = replacePlaceholders(Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_USER.DESCRIPTION'), $replaceData);
        $bookingConfirmationNotificationData = [
            'direct_id' => $bookingId,
            'direct_object' => Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_USER.DIRECT_OBJECT'),
            'receiver_id' => $userId,
            'receive_actor' => Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_USER.RECEIVE_ACTOR'),
            'title' => Config::get('constants.NOTIFICATIONS.BOOKING_CONFIRMATION_FOR_USER.TITLE'),
            'description' => $description,
            'is_seen' => Status::ACTIVE
        ];

        return $this->createNotification($bookingConfirmationNotificationData);
    }

    public function createTransferringMoneyNotification($bookingId)
    {
        $replaceData = [
            'name' => Auth::guard('sanctum')->user()->name,
            'bookingId' => $bookingId
        ];
        $description = replacePlaceholders(Config::get('constants.NOTIFICATIONS.MONEY_TRANSFER.DESCRIPTION'), $replaceData);
        $transferringMoneyNotificationData = [
            'direct_id' => $bookingId,
            'direct_object' => Config::get('constants.NOTIFICATIONS.MONEY_TRANSFER.DIRECT_OBJECT'),
            'receiver_id' => null,
            'receive_actor' => Config::get('constants.NOTIFICATIONS.MONEY_TRANSFER.RECEIVE_ACTOR'),
            'title' => Config::get('constants.NOTIFICATIONS.MONEY_TRANSFER.TITLE'),
            'description' => $description,
            'is_seen' => Status::ACTIVE
        ];

        return $this->createNotification($transferringMoneyNotificationData);
    }

    public function markNotificationsSeen($receiveActor, $receiverId)
    {
        $query = Notification::where([
            'receive_actor' => $receiveActor
        ]);
        if ($receiveActor != Config::get('constants.ACTOR.ADMIN')) {
            $query = $query->where([
                'receiver_id' => $receiverId
            ]);
        }
        return $query->update([
            'is_seen' => Status::INACTIVE
        ]);
    }
}
