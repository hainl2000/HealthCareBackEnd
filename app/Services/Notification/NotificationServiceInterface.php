<?php

namespace App\Services\Notification;

interface NotificationServiceInterface
{
    public function createNotification($notificationData);
    public function updateNotificationsStatus($listNotificationIds);
    public function getNotifications($receiverActor, $receiverId);
}
