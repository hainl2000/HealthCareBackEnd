<?php

namespace App\Services\Notification;

use App\Enums\Status;
use App\Models\Notification;
use Illuminate\Support\Arr;

class NotificationService implements NotificationServiceInterface
{
    public function createNotification($notificationData)
    {
        return Notification::create([
            'direct_id' => Arr::get($notificationData, 'direct_id'),
            'direct_object' => Arr::get($notificationData, 'direct_object'),
            'receiver_id' => Arr::get($notificationData, 'receiver_id'),
            'receive_actor' => Arr::get($notificationData, 'receive_actor'),
            'title' => Arr::get($notificationData, 'title'),
            'description' => Arr::get($notificationData, 'description'),
            'is_seen' => Status::INACTIVE
        ]);
    }

    public function updateNotificationsStatus($listNotificationIds)
    {
        //TODO: update notification status to seen
    }

    public function getNotifications($receiverActor, $receiverId)
    {
        //TODO: get logging actor notifications
    }
}
