<?php
namespace App\Http\Traits;
use App\Models\Notification;

trait NotificationTrait {
    public function createNotificaton($notification_for,$member_id=Null,$desc,$status='unseen') {
        $data = [
            'member_id' =>$member_id,
            'desc' => $desc,
            'notification_for' => $notification_for,
            'status' => $status
            ];
         Notification::create($data);

    }
}