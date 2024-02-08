<?php

namespace App\Helpers;

use App\Models\Notification as ModelsNotification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Illuminate\Support\Facades\App;
trait TopicNotificationHelper
{
    protected $notification;
    public function __construct()
    {
        $this->notification = Firebase::messaging();
    }

    public function notificationTopic($title_ar,$title_en,$body_ar,$body_en)
    {
        $topic = 'doctorApp';
        // $title = App::currentLocale() == 'ar' ? $title_ar : $title_en;
        // $body = App::currentLocale() == 'ar' ? $body_ar : $body_en;
        $noty = ModelsNotification::create([
            'title_ar'=> $title_ar,
            'title_en'=> $title_en,
            'body_ar'=>  $body_ar,
            'body_en'=>  $body_en
        ]);
        $title = App::currentLocale() == 'ar' ? $noty->title_ar :  $noty->title_en;
        $body = App::currentLocale() == 'ar' ?  $noty->body_ar :  $noty->body_en;
        $notification = Notification::fromArray([
            'title' => $title,
            'body' => $body,
        ]);
        $message = CloudMessage::withTarget('topic', $topic)->withNotification($notification);
        $this->notification->send($message);
    }
}
