<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $firebase = (new Factory)
            ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'));

        $this->messaging = $firebase->createMessaging();
    }

    /**
     * Send push notification to a specific device
     *
     * @param string $deviceToken
     * @param string $title
     * @param string $body
     * @return bool
     */
    public function sendPushNotification($deviceToken, $title, $body, $image, $extraData)
    {
        try {
            // Create notification object
            $notification = Notification::create($title, $body, $image);
            
            // Create the message
            $message = CloudMessage::withTarget('token', $deviceToken)
                ->withNotification($notification)
                ->withData($extraData); // Optional data payload
            
            $this->messaging->send($message);
            return true; // Notification sent successfully
        } catch (\Exception $e) {
            // Log the error for debugging
            // \Log::error('Firebase notification error: ' . $e->getMessage());
            return false;
        }
    }
}