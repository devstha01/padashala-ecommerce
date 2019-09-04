<?php

namespace App\Http\Controllers\backend\Merchant;

use App\Models\Category;
use App\Models\Color;
use App\Models\MerchantAsset;
use App\Models\MerchantBusiness;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $wallet = MerchantAsset::where('merchant_id', $this->_merchant_id)->first()->ecash_wallet ?? 0;
        $business = MerchantBusiness::where('merchant_id', $this->_merchant_id)->first();
        $active = count($business->getProducts->where('admin_flag', 1)->where('status', 1));
        $request = count($business->getProducts->where('admin_flag', 0));
        $featured = count($business->getProducts->where('admin_flag', 1)->where('status', 1)->where('is_featured', 1));
        $this->_data['cards'] = [
            ['name' => 'Wallet', 'count' => 'Rs. ' . $wallet, 'url' => '#'],
            ['name' => 'Active Products', 'count' => $active, 'url' => route('view-product-merchant')],
            ['name' => 'Product Requests', 'count' => $request, 'url' => route('view-product-request-merchant')],
            ['name' => 'Featured Products', 'count' => $featured, 'url' => route('view-product-merchant')],
        ];
        $this->_data['byCategory'] = [];
        $this->_data['byProducts'] = [];
        $this->_data['byDate'] = [];
        $all_categories = Category::where('status', 1)->get();
        foreach ($all_categories as $key => $category) {
            $products = $business->getProducts->where('category_id', $category->id)->pluck('id')->toArray() ?? [];
            $quantity = OrderItem::whereIn('product_id', $products)->where('order_status_id', 'deliver')->sum('quantity') ?? 0;
            $value = OrderItem::select(DB::raw('sum((quantity * sell_price)+net_tax) as net_value'))
                ->whereIn('product_id', $products)->where('order_status_id', 'deliver')->first();
            $this->_data['byCategory']['id'][] = $category->id;
            $this->_data['byCategory']['name'][] = $category->name;
            $this->_data['byCategory']['quantity'][] = $quantity;
            $this->_data['byCategory']['net_value'][] = $value->net_value ?? 0;
            $this->_data['byCategory']['mixed'][] = ['x'=>$quantity,'y'=>$value->net_value ?? 0];
            $this->_data['byCategory']['color'][] = Color::find(++$key + 1)->color_code ?? 'skyblue';
        }

        $all_products = $business->getProducts;
        foreach ($all_products as $key => $product) {
            $quantity = OrderItem::where('product_id', $product->id)->where('order_status_id', 'deliver')->sum('quantity') ?? 0;
            $value = OrderItem::select(DB::raw('sum((quantity * sell_price)+net_tax) as net_value'))
                ->where('product_id', $product->id)->where('order_status_id', 'deliver')->first();
            $this->_data['byProducts']['id'][] = $product->id;
            $this->_data['byProducts']['name'][] = $product->name;
            $this->_data['byProducts']['quantity'][] = $quantity;
            $this->_data['byProducts']['net_value'][] = $value->net_value ?? 0;
            $this->_data['byProducts']['mixed'][] = ['x'=>$quantity,'y'=>$value->net_value ?? 0];
            $this->_data['byProducts']['color'][] = Color::find(++$key + 1)->color_code ?? 'skyblue';
        }

        $start = Carbon::now()->subMonths(12)->format('Y-m');
        $productIds = $business->getProducts->pluck('id')->toArray() ?? [];
        for ($i = 0; $i < 12; $i++) {
            $start = Carbon::parse($start)->addMonths(1);
            $end = Carbon::parse($start)->addMonths(1);
            $items = OrderItem::whereIn('product_id', $productIds)
                ->where('order_status_id', 'deliver')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<', $end);
            $quantity = $items->sum('quantity') ?? 0;
            $value = $items->select(DB::raw('sum((quantity * sell_price)+net_tax) as net_value'))->first();
            $this->_data['byDate']['id'][] = $start->format('Y-m');
            $this->_data['byDate']['name'][] = $end->format('Y-m');
            $this->_data['byDate']['quantity'][] = $quantity;
            $this->_data['byDate']['net_value'][] = $value->net_value ?? 0;
            $this->_data['byDate']['mixed'][] = ['x'=>$quantity,'y'=>$value->net_value ?? 0];
            $this->_data['byDate']['color'][] = Color::find($i + 2)->color_code ?? 'skyblue';
        }
        $this->_data['chart_count'] = [
            'category' => count($this->_data['byCategory']['id']??[]),
            'products' => count($this->_data['byProducts']['id']??[]),
            'date' => count($this->_data['byDate']['id']??[]),
        ];
        return view($this->_path . '.dashboard', $this->_data);
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
