<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\FirebaseNotificationService;

class Notifications extends BaseModel
{
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'product_id',
        'order_id',
        'image',
        'title',
        'description',
    ];

    protected $firebaseService;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->firebaseService = new FirebaseNotificationService();
    }

    public function send_test_notification(array $data): bool
    {
        $deviceToken = $data['notification_token'];
        $title = $data['notification_title'];
        $body = $data['notification_description'] ?? '';
        $image = $data['notification_image'] ?? '';

        $extraData = [
            'type'       => $data['type'] ?? '',
            'product_id' => $data['product_id'] ?? '',
            'order_id'   => $data['order_id'] ?? '',
        ];

        return $this->firebaseService->sendPushNotification($deviceToken, $title, $body, $image, $extraData);
    }
}
