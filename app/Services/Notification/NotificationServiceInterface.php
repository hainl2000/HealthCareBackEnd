<?php

namespace App\Services\Notification;

interface NotificationServiceInterface
{
    public function getNotifications($receiveActor, $receiverId, $isCountOnly);
    public function notifyNewNotification($channel, $actor, $notificationData);
    public function createTransferringMoneyNotification($bookingId);
    public function createBookingConfirmationNotificationForDoctor($bookingId, $doctorId);
    public function createBookingConfirmationNotificationForUser($bookingId, $userId);
}
