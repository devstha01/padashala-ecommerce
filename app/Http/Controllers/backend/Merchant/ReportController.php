<?php

namespace App\Http\Controllers\backend\Merchant;

use App\Models\MerchantBusiness;
use App\Models\MerchantCashWithdraw;
use App\Models\MerchantGrantRetain;
use App\Models\MerchantWalletTransfer;
use App\Models\MerchantWalletTransferMerchant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\UserPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private $_data = [];
    private $_merchant_id = '';

    public function __construct()
    {
        $this->middleware('merchant');
        $this->middleware(function ($request, $next) {
            $this->_merchant_id = Auth::guard('merchant')->user()->id;
            return $next($request);
        });
    }

    function paymentReport()
    {
        $this->_data['reports'] = UserPayment::where('to_merchant_id', $this->_merchant_id)->get();
        return view('backend.merchant.report.payment-report', $this->_data);

    }


    function orderReport(Request $request)
    {
        $business_id = MerchantBusiness::where('merchant_id', $this->_merchant_id)->first()->id ?? false;
        if (!$business_id)
            return redirect()->back();
        $products = Product::where('merchant_business_id', $business_id)->pluck('id')->toArray() ?? [];

        $this->_data['sd'] = $request->sd ?? Carbon::now()->subYear(1)->format('d-M-Y');
        $this->_data['ed'] = $request->ed ?? Carbon::now()->format('d-M-Y');

        $this->_data['reports'] = OrderItem::whereIn('product_id', $products)->orderBy('id', 'DESC');

        $this->_data['reports'] = $this->_data['reports']->where('created_at', '>=', Carbon::parse($this->_data['sd'])->toDateTimeString());
        $this->_data['reports'] = $this->_data['reports']->where('created_at', '<=', Carbon::parse($this->_data['ed'])->addDays(1)->toDateTimeString());
        $this->_data['reports'] = $this->_data['reports']->get();

        return view('backend.merchant.report.order-product-report', $this->_data);

    }

    function walletReport(Request $request)
    {
        $business_id = MerchantBusiness::where('merchant_id', $this->_merchant_id)->first()->id ?? false;
        if (!$business_id)
            return redirect()->back();
        $products = Product::where('merchant_business_id', $business_id)->pluck('id')->toArray() ?? [];

        $this->_data['sd'] = $request->sd ?? Carbon::now()->subYear(1)->format('d-M-Y');
        $this->_data['ed'] = $request->ed ?? Carbon::now()->format('d-M-Y');

        $this->_data['reports'] = OrderItem::whereIn('product_id', $products)->orderBy('id', 'DESC');

        $this->_data['reports'] = $this->_data['reports']->where('created_at', '>=', Carbon::parse($this->_data['sd'])->toDateTimeString());
        $this->_data['reports'] = $this->_data['reports']->where('created_at', '<=', Carbon::parse($this->_data['ed'])->addDays(1)->toDateTimeString());
        $this->_data['reports'] = $this->_data['reports']->get();


        return view('backend.merchant.report.order-wallet-report', $this->_data);
    }

    function walletTransferReport()
    {
        $this->_data['reports'] = MerchantWalletTransfer::where('from_merchant_id', $this->_merchant_id)->get();
        $this->_data['from_reports'] = MerchantWalletTransferMerchant::where('from_merchant_id', $this->_merchant_id)->get();
        $this->_data['to_reports'] = MerchantWalletTransferMerchant::where('to_merchant_id', $this->_merchant_id)->get();
        return view('backend.merchant.report.wallet-transfer-report', $this->_data);
    }

    public function cashWithdrawReport()
    {
        $this->_data['reports'] = MerchantCashWithdraw::where('merchant_id', $this->_merchant_id)->orderBy('id', 'DESC')->get();
        return view('backend.merchant.report.cash-withdraw-report', $this->_data)->with('title', __('message.Cash Withdrawal'));
    }

    public function grantRetainReport()
    {
        $this->_data['reports'] = MerchantGrantRetain::orderBy('id', 'desc')->get();
        return view('backend.merchant.report.grant-retain-wallet-report', $this->_data);
    }

    function purchaseReport()
    {
        $business =MerchantBusiness::where('merchant_id',$this->_merchant_id)->first();
        if (!$business)
            return redirect()->back();
        $products = Product::where('merchant_business_id', $business->id)->pluck('id')->toArray() ?? [];
        $this->_data['reports'] = OrderItem::whereIn('product_id', $products)->where('order_status_id','deliver')->latest()->get();
        return view('backend.merchant.report.purchase-report', $this->_data);
    }
}
