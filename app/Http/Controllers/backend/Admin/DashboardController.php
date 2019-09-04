<?php

namespace App\Http\Controllers\backend\Admin;

use App\Models\Category;
use App\Models\Color;
use App\Models\Merchant;
use App\Models\MerchantBusiness;
use App\Models\Notification;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    private $_path = 'backend.admin.dashboard';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');
    }


    public function dashboard()
    {
        $merchant = Merchant::where('status', 1)->count();
//        dd($merchant);
        $active = Product::where('admin_flag', 1)->where('status', 1)->count();
        $request = Product::where('admin_flag', 0)->count();
        $featured = Product::where('admin_flag', 1)->where('status', 1)->where('is_featured', 1)->count();

        $this->_data['cards'] = [
            ['name' => 'Merchants', 'count' => $merchant, 'url' => route('merchant-list-admin')],
            ['name' => 'Active Products', 'count' => $active, 'url' => route('all-product-admin')],
            ['name' => 'Product Requests', 'count' => $request, 'url' => route('product-approval-admin')],
            ['name' => 'Featured Products', 'count' => $featured, 'url' => route('admin-merchant-featured-product-request')],
        ];
        $this->_data['byCategory'] = [];
        $this->_data['byMerchants'] = [];
        $this->_data['byDate'] = [];
        $all_categories = Category::where('status', 1)->get();
        foreach ($all_categories as $key => $category) {
            $products = Product::where('category_id', $category->id)->pluck('id')->toArray() ?? [];
            $quantity = OrderItem::whereIn('product_id', $products)->where('order_status_id', 'deliver')->sum('quantity') ?? 0;
            $value = OrderItem::select(DB::raw('sum((quantity * sell_price)+net_tax) as net_value'))
                ->whereIn('product_id', $products)->where('order_status_id', 'deliver')->first();
            $this->_data['byCategory']['id'][] = $category->id;
            $this->_data['byCategory']['name'][] = $category->name;
            $this->_data['byCategory']['quantity'][] = $quantity;
            $this->_data['byCategory']['net_value'][] = $value->net_value ?? 0;
            $this->_data['byCategory']['mixed'][] = ['x' => $quantity, 'y' => $value->net_value ?? 0];
            $this->_data['byCategory']['color'][] = Color::find(++$key + 1)->color_code ?? 'skyblue';
        }

        $all_merchants = Merchant::where('status', 1)->get();
        foreach ($all_merchants as $key => $merchant) {
            $productIds = $merchant->getBusiness->getProducts->pluck('id')->toArray() ?? [];
            $quantity = OrderItem::whereIn('product_id', $productIds)->where('order_status_id', 'deliver')->sum('quantity') ?? 0;
            $value = OrderItem::select(DB::raw('sum((quantity * sell_price)+net_tax) as net_value'))
                ->whereIn('product_id', $productIds)->where('order_status_id', 'deliver')->first();
            $this->_data['byMerchants']['id'][] = $merchant->getBusiness->id;
            $this->_data['byMerchants']['name'][] = $merchant->getBusiness->name;
            $this->_data['byMerchants']['quantity'][] = $quantity;
            $this->_data['byMerchants']['net_value'][] = $value->net_value ?? 0;
            $this->_data['byMerchants']['mixed'][] = ['x' => $quantity, 'y' => $value->net_value ?? 0];
            $this->_data['byMerchants']['color'][] = Color::find(++$key + 1)->color_code ?? 'skyblue';
        }

        $start = Carbon::now()->subMonths(12)->format('Y-m');
        for ($i = 0; $i < 12; $i++) {
            $start = Carbon::parse($start)->addMonths(1);
            $end = Carbon::parse($start)->addMonths(1);
            $items = OrderItem::where('order_status_id', 'deliver')
                ->where('created_at', '>=', $start)
                ->where('created_at', '<', $end);
            $quantity = $items->sum('quantity') ?? 0;
            $value = $items->select(DB::raw('sum((quantity * sell_price)+net_tax) as net_value'))->first();
            $this->_data['byDate']['id'][] = $start->format('Y-m');
            $this->_data['byDate']['name'][] = $end->format('Y-m');
            $this->_data['byDate']['quantity'][] = $quantity;
            $this->_data['byDate']['net_value'][] = $value->net_value ?? 0;
            $this->_data['byDate']['mixed'][] = ['x' => $quantity, 'y' => $value->net_value ?? 0];
            $this->_data['byDate']['color'][] = Color::find($i + 2)->color_code ?? 'skyblue';
        }
        $this->_data['chart_count'] = [
            'category' => count($this->_data['byCategory']['id']??[]),
            'products' => count($this->_data['byMerchants']['id']??[]),
            'date' => count($this->_data['byDate']['id']??[]),
        ];
        return view($this->_path . '.dashboard', $this->_data);
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
