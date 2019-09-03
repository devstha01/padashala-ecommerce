<?php

namespace App\Http\Controllers\backend\Merchant;

use App\Http\Traits\OrderDeliverTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Library\ShoppingBonus;
use App\Models\Commisions\CashDeliveryBonusRecord;
use App\Models\Commisions\Shopping;
use App\Models\Commisions\ShoppingBonusDistribution;
use App\Models\Commisions\ShoppingLog;
use App\Models\Commisions\ShoppingMerchant;
use App\Models\Members\MemberAsset;
use App\Models\Merchant;
use App\Models\MerchantBusiness;
use App\Models\OrderItem;
use App\Models\ShippingOrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use WalletsHistoryTrait, OrderDeliverTrait;
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

    public function manageorder(Request $request)
    {
        session()->flash('order-tab', $request->tab ?? 'all');

        $merchant_business_id = MerchantBusiness::where('merchant_id', $this->_merchant_id)->first()->id;
        $orders = OrderItem::orderBy('id', 'DESC')->get();
        $items = [];
        $p_items = [];
        $d_items = [];
        foreach ($orders as $order) {
            if ($order->getProduct->merchant_business_id === $merchant_business_id) {
                $items[] = $order;
                if ($order->order_status_id == 'deliver')
                    $d_items[] = $order;
                else
                    $p_items[] = $order;
            }
        }
        $this->_data['orders'] = collect($items)->groupBy('invoice');
        $this->_data['p_orders'] = collect($p_items)->groupBy('invoice');
        $this->_data['d_orders'] = collect($d_items)->groupBy('invoice');

        return view('backend.merchant.order.view-order', $this->_data);
    }

    public
    function orderdetails($id)
    {
        $order = Order::find($id);
        $this->_data['merchant'] = Merchant::find($this->_merchant_id);
        if (!$order)
            return redirect()->back();
        if ($order->getUser->is_member === 0) {
            if (str_contains($order->payment_method, 'ecash_wallet'))
                $this->_data['method'] = __('message.Cash Wallet');
            else
                $this->_data['method'] = __('message.Cash on Delivery');
        } else
            $this->_data['method'] = __('message.Cash/Voucher Wallet');

        $orderItem = [];
        $total = 0;
        $tax = 0;
        foreach ($order->getOrderItem as $item) {
            $merchant_id = $item->getProduct->getBusiness->getMerchant->id;
            if ($this->_merchant_id == $merchant_id) {
                $item['net_price'] = number_format($item->quantity * $item->sell_price, 2, '.', '');
                $orderItem[] = $item;
                $total += $item['net_price'];
                $tax += $item->net_tax;
            }
        }

        $invoice = collect($orderItem)->first();
        if ($invoice) {
            $this->_data['shipping'] = ShippingOrderItem::where('invoice', $invoice->invoice)->first();
        }
        $this->_data['order'] = $order;
        $this->_data['orderItem'] = collect($orderItem);
        $count = count($orderItem);
        $this->_data['delivery'] = number_format($count * (env('DELIVERY_COST') ?? 0), 2, '.', '');
        $this->_data['tax'] = $tax;
//        number_format($total * (env('TAX_PERCENT') ?? 0) / 100, 2, '.', '');
        $this->_data['net_total'] = number_format($total + $tax , 2, '.', '');

        $this->_data['total'] = number_format($total, 2, '.', '');

        return view('backend.merchant.order.order-details', $this->_data);
    }

    function itemShipping($id, Request $request)
    {
        $order = Order::find($id);
        if (!$order)
            return redirect()->back();
        $stat = false;
        foreach ($order->getOrderItem as $item) {
            $merchant_id = $item->getProduct->getBusiness->getMerchant->id;
            if ($stat == false) {
                if ($this->_merchant_id == $merchant_id) {
                    $stat = true;
                    $shipp = ShippingOrderItem::where('invoice', $item->invoice)->first();

                    if (!$shipp)
                        ShippingOrderItem::create([
                            'invoice' => $item->invoice,
                            'tracking_id' => $request->tracking_id,
                            'carrier' => $request->carrier,
                            'weight' => $request->weight,
                            'url' => $request->url,
                            'notify' => $request->notify ? 1 : 0,
                        ]);
                    else
                        $shipp->update([
                            'tracking_id' => $request->tracking_id,
                            'carrier' => $request->carrier,
                            'weight' => $request->weight,
                            'url' => $request->url,
                            'notify' => $request->notify ? 1 : 0,
                        ]);
                }
            }
        }
        return redirect()->back()->with('shipp', __('message.Updated!'));
    }

    function itemStatusChange($id, Request $request)
    {
        $orderItem = OrderItem::find($id);
        $orderItem->update([
            'merchant_status' => $request->merchant_status,
        ]);
        return redirect()->back();
    }

    protected
    function bonusOnCashDelivery($orderItem)
    {
        $pays = unserialize($orderItem->getOrder->payment_method);
        $user_id = $orderItem->getOrder->user_id;

        $memberA = MemberAsset::where('member_id', $user_id)->first();
        $total = $orderItem->sell_price * $orderItem->quantity;
        foreach ($pays as $met => $pay) {
            if ($met == 'cash') {

                $customerRep = new ShoppingBonus();
                $customerBonus = $customerRep->customerBonus($orderItem, $user_id);
                $bonus_list = serialize($customerBonus);

                $shopLog = $orderItem->getShoppingLog;
                CashDeliveryBonusRecord::create([
                    'merchant_id' => $orderItem->getProduct->getBusiness->getMerchant->id,
                    'order_item_id' => $orderItem->id,
                    'total' => $shopLog->total,
                    'admin' => $shopLog->total - $shopLog->merchant,
                ]);

                ShoppingBonusDistribution::create([
                    'buyer_id' => $user_id,
                    'item_id' => $orderItem->id,
                    'bonus_list' => $bonus_list,
                ]);
                $memberA->update(['ecash_wallet' => ($customerBonus['customer_bonus']) + $memberA->ecash_wallet]);
                $this->createWalletReport($user_id, $customerBonus['customer_bonus'], 'Customer Bonus on Cash Delivery', 'ecash', 'IN');
            }
        }
    }
}
