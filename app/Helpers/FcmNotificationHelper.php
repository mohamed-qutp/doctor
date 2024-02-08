<?php

namespace App\Helpers;
use App\Models\Notification as ModelsNotification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
trait FcmNotificationHelper
{
    protected $notification;
    public function __construct()
    {
        $this->notification = Firebase::messaging();
    }
    public function fc()
    {
        $deviceToken = auth()->user()->fcm_token;
        $notification = Notification::fromArray([
            'title' => 'Doctor App',
            'body' => 'hello',
        ]);
        $message = CloudMessage::withTarget('token', $deviceToken)->withNotification($notification);
        $noty = ModelsNotification::create([
            'title_ar'=> 'Doctor App',
            'title_en'=> 'Doctor App',
            'body_ar'=>  'Doctor App',
            'body_en'=>  'Doctor App',
        ]);
        $noty->users()->sync(auth()->user()->id);

        $this->notification->send($message);
    }

}
