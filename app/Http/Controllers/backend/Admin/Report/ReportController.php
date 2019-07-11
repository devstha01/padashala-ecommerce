<?php

namespace App\Http\Controllers\backend\Admin\Report;

use App\Http\Traits\GrantRetainTrait;
use App\Models\Commisions\MonthlyBonus;
use App\Models\Commisions\ShoppingWithdraw;
use App\Models\GrantRetain;
use App\Models\MerchantCashWithdraw;
use App\Models\MerchantGrantRetain;
use App\Models\MerchantWalletTransfer;
use App\Models\MerchantWalletTransferMerchant;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Members\MemberCashWithdraw;
use App\Models\Members\MemberWalletConvert;
use App\Models\Members\MemberWalletTransfer;
use App\Models\UserPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{

    private $_path = 'backend.admin.reports.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function memberCashWithdrawReport()
    {
        $this->_data['reports'] = MemberCashWithdraw::orderBy('id', 'DESC')->get();
        return view('backend.admin.reports.member-cash-withdraw-report', $this->_data);
    }

    public function memberWalletConvertReport()
    {
        $this->_data['reports'] = MemberWalletConvert::orderBy('id', 'DESC')->get();
        return view('backend.admin.reports.member-wallet-convert-report', $this->_data);
    }

    public function memberWalletTransferReport()
    {
        $this->_data['reports'] = MemberWalletTransfer::orderBy('id', 'DESC')->get();
        return view('backend.admin.reports.member-wallet-transfer-report', $this->_data);
    }

    public function merchantWalletTransferReport()
    {
        $this->_data['reports'] = MerchantWalletTransfer::orderBy('id', 'DESC')->get();
        $this->_data['merchant_reports'] = MerchantWalletTransferMerchant::orderBy('id', 'DESC')->get();
        return view('backend.admin.reports.merchant-wallet-transfer-report', $this->_data);
    }

    public function grantRetainReport()
    {
        $this->_data['reports'] = GrantRetain::orderBy('id', 'desc')->get();
        $this->_data['merchant_reports'] = MerchantGrantRetain::orderBy('id', 'desc')->get();
        return view('backend.admin.reports.grant-retain-wallet-report', $this->_data);
    }

    function merchantPaymentReport()
    {
        $this->_data['reports'] = UserPayment::orderBy('id', 'DESC')->get();
        return view('backend.admin.reports.merchant-payment-report', $this->_data);
    }

    function monthlyBonusReport(Request $request)
    {

        $this->_data['month'] = $request->month ?? null;

//        $this->_data['sd'] = $request->start_date ?? null;
//        $this->_data['ed'] = $request->end_date ?? null;
        if ($this->_data['month'] !== null) {
            $this->_data['sd'] = Carbon::parse($this->_data['month']);
            $this->_data['ed'] = Carbon::parse($this->_data['month'])->addMonth(1);
        } else {
            $this->_data['sd'] = Carbon::now();
            $this->_data['ed'] = Carbon::now()->addMonth(1);
        }

//        dd($this->_data['sd'] .'     '. $this->_data['ed']);

        $this->_data['reports'] = MonthlyBonus::where('status', 1);
        if ($this->_data['sd'] !== null)
            $this->_data['reports'] = $this->_data['reports']->where('created_at', '>=', Carbon::parse($this->_data['sd']));
        if ($this->_data['ed'] !== null)
            $this->_data['reports'] = $this->_data['reports']->where('created_at', '<', Carbon::parse($this->_data['ed']));
        $this->_data['reports'] = $this->_data['reports']->orderBy('id', 'DESC')->get();
        return view('backend.admin.reports.monthly-bonus-report', $this->_data);
    }

    function shopPointReport()
    {
        $this->_data['reports'] = ShoppingWithdraw::orderBy('id', 'DESC')->get();
        return view('backend.admin.reports.shop-point-report', $this->_data);

    }

    function memberCashWithdrawDetail($id)
    {
        $this->_data['report'] = MemberCashWithdraw::find($id);
        return view('backend.admin.reports.member-cash-withdraw-detail', $this->_data);
    }

    function memberCashWithdrawEdit($id, Request $request)
    {
        $report = MemberCashWithdraw::find($id);
        if ($report) {
            $report->update([
                'withdraw_date' => Carbon::parse($request->withdraw),
                'flag' => 1,
                'updated_by' => Auth::guard('admin')->id()
            ]);
            return redirect()->back()->with('success', __('dashboard.Withdraw date updated successfully'));
        }
        return redirect()->back()->with('success', __('dashboard.Something went wrong'));
    }

    public function merchantCashWithdrawReport()
    {
        $this->_data['reports'] = MerchantCashWithdraw::orderBy('id', 'DESC')->get();
        return view('backend.admin.reports.merchant-cash-withdraw-report', $this->_data);
    }

    function merchantCashWithdrawDetail($id)
    {
        $this->_data['report'] = MerchantCashWithdraw::find($id);
        return view('backend.admin.reports.merchant-cash-withdraw-detail', $this->_data);
    }

    function merchantCashWithdrawEdit($id, Request $request)
    {
        $report = MerchantCashWithdraw::find($id);
        if ($report) {
            $report->update([
                'withdraw_date' => Carbon::parse($request->withdraw),
                'flag' => 1,
                'updated_by' => Auth::guard('admin')->id()
            ]);
            return redirect()->back()->with('success', __('dashboard.Withdraw date updated successfully'));
        }
        return redirect()->back()->with('success', __('dashboard.Something went wrong'));
    }

    function purchaseReport()
    {
        $this->_data['reports'] = OrderItem::where('order_status_id', 'deliver')->latest()->get();
        return view('backend.admin.reports.purchase-report', $this->_data);
    }

}
