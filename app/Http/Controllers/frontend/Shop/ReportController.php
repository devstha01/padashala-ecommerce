<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Models\Category;
use App\Models\CustomerWalletTransfer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    private $_path = 'frontend.reports';
    private $_data = [];

    public function __construct()
    {
        $categories = Category::where('status', 1)->get() ?? [];
        $this->_data['all_categories'] = collect($categories);
        $this->_data['home_categories'] = collect($categories)->take(8);
        $data = [];
        foreach ($categories as $category) {
            $catStatus = false;
            if (count($category->getSubCategory->where('status', 1)) !== 0)
                $catStatus = true;
            if ($catStatus)
                $data[] = $category;
        }
        $this->_data['categories'] = collect($data);
    }

    function allReport($report_type = null)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        $this->_data['user'] = Auth::user();
        $this->_data['active_nav'] = '';
        switch ($report_type) {
            case 'purchase':
                $this->_data['active_nav'] = 'purchase';
                $orders = Order::where('user_id', Auth::id())->pluck('id')->toArray()??[];
                $this->_data['reports'] = OrderItem::whereIn('order_id', $orders)->where('order_status_id','deliver')->latest()->get();
                return view($this->_path . '.purchase', $this->_data)->with('title', __('message.Golden Gate'));
                break;
            case 'transfer':
                $this->_data['active_nav'] = 'transfer';
                $this->_data['reports'] = CustomerWalletTransfer::where('from_id', Auth::id())->orWhere('to_id', Auth::id())
                    ->where('status', 1)->where('flag', 1)->latest()->get();
                return view($this->_path . '.transfer', $this->_data)->with('title', __('message.Golden Gate'));
                break;
            default:
                $this->_data['active_nav'] = 'payment';
                $this->_data['reports'] = UserPayment::where('from_member_id', Auth::id())->latest()->get();
                return view($this->_path . '.payment', $this->_data)->with('title', __('message.Golden Gate'));
                break;
        }
    }
}
