<?php

namespace App\Http\Controllers\backend\Member;

use App\Models\Commisions\ShoppingWithdraw;
use App\Models\DailyBonus;
use App\Models\GenerationBonus;
use App\Models\Members\MemberAsset;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserPayment;
use App\Models\WalletHistory;
use Illuminate\Support\Facades\Auth;
use App\Models\Members\MemberCashWithdraw;
use App\Models\Members\MemberWalletConvert;
use App\Models\Members\MemberWalletTransfer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    private $_path = 'backend.member.reports';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('member');
    }

    public function cashWithdrawReport()
    {
        $this->_data['reports'] = MemberCashWithdraw::where('member_id', Auth::id())->orderBy('id','DESC')->get();
        return view($this->_path . '.cash-withdraw-report', $this->_data)->with('title', __('message.Cash Withdrawal'));
    }

    public function walletConvertReport()
    {
        $this->_data['reports'] = MemberWalletConvert::where('member_id', Auth::id())->orderBy('id','DESC')->get();
        return view($this->_path . '.wallet-convert-report', $this->_data);
    }

    public function walletTransferReport()
    {
        $this->_data['plus_reports'] = MemberWalletTransfer::where('to_member_id', Auth::id())->where('flag', 1)->where('status', 1)->orderBy('id','DESC')->get();
        $this->_data['minus_reports'] = MemberWalletTransfer::where('from_member_id', Auth::id())->where('flag', 1)->where('status', 1)->orderBy('id','DESC')->get();
        return view($this->_path . '.wallet-transfer-report', $this->_data);
    }

    function paymentReport()
    {
        $this->_data['reports'] = UserPayment::where('from_member_id', Auth::id())->orderBy('id','DESC')->get();
        return view($this->_path . '.payment-report', $this->_data);
    }
    function GenerationBonusReport()
    {
        $this->_data['reports'] = GenerationBonus::where('member_id', Auth::id())->orderBy('id','desc')->with('getMember')->get();
        return view($this->_path . '.generation-bonus-report', $this->_data);
    }
    function DailyBonusReport()
    {
        $this->_data['reports'] = DailyBonus::where('member_id', Auth::id())->orderBy('id','desc')->get();
        return view($this->_path . '.daily-bonus-report', $this->_data);
    }

    public function ecashReport(){
        $this->_data['reports'] = WalletHistory::where('member_id', Auth::id())->where('transaction_type','ecash')->orderBy('id','desc')->get();
        return view($this->_path . '.ecash-wallet-report', $this->_data);
    }
    public function evoucherReport(){
        $this->_data['reports'] = WalletHistory::where('member_id', Auth::id())->where('transaction_type','evoucher')->orderBy('id','desc')->get();
        return view($this->_path . '.evoucher-wallet-report', $this->_data);
    }
    public function rpointReport(){
        $this->_data['reports'] = WalletHistory::where('member_id', Auth::id())->where('transaction_type','rpoint')->orderBy('id','desc')->get();
        return view($this->_path . '.rpoint-wallet-report', $this->_data);
    }
    public function chipReport(){
        $this->_data['reports'] = WalletHistory::where('member_id', Auth::id())->where('transaction_type','chip')->orderBy('id','desc')->get();
        return view($this->_path . '.chip-wallet-report', $this->_data);
    }






    function shopPointWithdrawReport(){
        $this->_data['reports'] = ShoppingWithdraw::where('member_id', Auth::id())->orderBy('id','desc')->get();
        return view($this->_path . '.shop-point-report', $this->_data);
    }

    function purchaseReport(){
        $orders = Order::where('user_id', Auth::id())->pluck('id')->toArray()??[];
        $this->_data['reports'] = OrderItem::whereIn('order_id', $orders)->where('order_status_id','deliver')->latest()->get();
        return view($this->_path . '.purchase-report', $this->_data);
    }
}
