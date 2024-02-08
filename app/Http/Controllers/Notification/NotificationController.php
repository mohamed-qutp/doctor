<?php

namespace App\Http\Controllers\Notification;

use Illuminate\Http\Request;
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FcmNotificationHelper;

class NotificationController extends Controller
{
    use ApiResponseHelper;
    use FcmNotificationHelper;
    public function index (Request $request)
    {
        $per_page = (int) ($request->per_page ?? 10);
        $pageNumber = (int) ($request->current_page ?? 1);
        $user = Auth::user();
        $myNotifications = DB::table('notification_user')
            ->join('notifications', 'notifications.id', 'notification_user.notification_id')
            ->select('notification_user.notification_id as id','title_' . App::currentLocale() . ' as title' ,'body_'. App::currentLocale() . ' as Body')
            ->where('notification_user.user_id', $user->id)
            ->paginate($per_page, ['*'], 'page', $pageNumber);
        return $this->setCode(200)->setMessage('Successe')->setData($myNotifications->items())->send();
    }
    public function fctoken ()
    {
        $this->fc();
    }
}
