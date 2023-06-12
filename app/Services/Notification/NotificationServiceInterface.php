<?php

namespace App\Services\Notification;

interface NotificationServiceInterface
{
    public function updateNotificationsStatus($listNotificationIds);
    public function getNotifications($receiverActor, $receiverId, $isCountOnly);
    public function notifyNewNotification($channel, $actor, $notificationData);
    public function createTransferringMoneyNotification($bookingId);
}
