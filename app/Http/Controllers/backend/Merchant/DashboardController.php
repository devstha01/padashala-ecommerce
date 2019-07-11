<?php

namespace App\Http\Controllers\backend\Merchant;

use App\Models\MerchantAsset;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    private $_merchant_id = '';
    private $_path = 'backend.merchant.dashboard';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('merchant');
        $this->middleware(function ($request, $next) {
            $this->_merchant_id = Auth::guard('merchant')->user()->id;
            return $next($request);
        });
    }


    public function dashboard()
    {
        $this->_data['wallet'] = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
        return view($this->_path . '.dashboard', $this->_data)->with([
            'title' => __('message.Merchant Panel')
        ]);
    }

    function confirmTransactionPassword(Request $request)
    {
        $TransactionPassword = $request->transactionpassword;
        return response()->json(Hash::check($TransactionPassword, Auth::guard('merchant')->user()->transaction_password, []));
    }

    function success()
    {
        $wallet = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
        $success_title = session('success_title') ?? false;
        $success_brief = session('success_brief') ?? false;
        $success_detail = session('success_detail') ?? false;

        if (!$success_title) return redirect(route('merchant/dashboard'));
        return view($this->_path . '.success', compact('wallet', 'success_title', 'success_brief', 'success_detail'));
    }

    function viewNotification()
    {
        $notices = Notification::where('notification_for', 'merchant')
            ->where('member_id', $this->_merchant_id)->latest()
            ->simplePaginate(25);
        foreach ($notices as $notice)
            $notice->group_date = Carbon::parse($notice->created_at)->format('Y-m-d');
        $data['notices'] = $notices;
        return view('backend.notification.view', $data);
    }

    function seenNotification()
    {
        $notices = Notification::where('notification_for', 'merchant')->
        where('member_id', $this->_merchant_id)
            ->where('status', 'unseen')
            ->get();

        foreach ($notices as $notice)
            $notice->update(['status' => 'seen']);
    }
}
