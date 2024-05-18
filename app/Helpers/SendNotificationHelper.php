<?php

namespace App\Helpers;

use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendNotificationHelper
{
    public static function sendNotificationToUser($userId, $message)
    {
        $user = User::find($userId);
        $token = $user->fcm_token;

        $messaging = app('firebase.messaging');
        $notification = Notification::create($message);

        $message = CloudMessage::withTarget('token', $token)->withNotification($notification);

        $messaging->send($message);
    }
}
