<?php

namespace App\Http\Controllers\backend\Admin;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    private $_path = 'backend.admin.dashboard';

    public function dashboard()
    {
        return view($this->_path . '.dashboard')->with([
            'title' => __('message.Admin Panel')
        ]);
    }

    public function confirmTransactionPassword(Request $request)
    {
        $TransactionPassword = $request->transactionpassword;
        return response()->json(Hash::check($TransactionPassword, Auth::guard('admin')->user()->transaction_password, []));

    }

    function viewNotification()
    {
        $notices = Notification::where('notification_for', 'admin')
            ->latest()->simplePaginate(25);
        foreach ($notices as $notice)
            $notice->group_date = Carbon::parse($notice->created_at)->format('Y-m-d');
        $data['notices'] = $notices;
        return view('backend.notification.view', $data);
    }

    function seenNotification()
    {
        $notices = Notification::where('notification_for', 'admin')
            ->where('status', 'unseen')
            ->get();

        foreach ($notices as $notice)
            $notice->update(['status' => 'seen']);
    }

}
