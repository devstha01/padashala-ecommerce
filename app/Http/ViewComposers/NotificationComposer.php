<?php

namespace App\Http\ViewComposers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\View\View;

class NotificationComposer
{
    public $notifications = [];
    /**
     *
     * @return void
     */
    public function __construct()
    {
        $type='member';


        $notificationsCount = Notification::where('notification_for','member')->where('status', 'unseen')->where('member_id', Auth::id())->count();
        $notifications = Notification::where('notification_for', $type)->where('member_id', Auth::id())->latest()->get();
        if(Auth::guard('admin')->check() && Request::is('admin/*')){
            $notificationsCount = Notification::where('notification_for','admin')->where('status', 'unseen')->count();
            $notifications = Notification::where('notification_for', 'admin')->latest()->get();
        }
        if(Auth::guard('merchant')->check() && Request::is('merchant/*')){
            $notificationsCount = Notification::where('notification_for','merchant')->where('status', 'unseen')
                ->where('member_id', Auth::guard('merchant')->id())->count();
            $notifications = Notification::where('notification_for', 'merchant')
                ->where('member_id', Auth::guard('merchant')->id())->latest()->get();
        }
        $this->notifications = [
            'count' => $notificationsCount,
            'notifications' =>$notifications
        ];
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('notifications', $this->notifications);
    }
}