<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Traits\OrderDeliverTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Library\ShoppingBonus;
use App\Models\Commisions\CashDeliveryBonusRecord;
use App\Models\Commisions\ShoppingBonusDistribution;
use App\Models\Members\MemberAsset;
use App\Models\MerchantBusiness;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use WalletsHistoryTrait, OrderDeliverTrait;

    function OrderList($state, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $merchant_business_id = MerchantBusiness::where('merchant_id', $merchant['data']->id)->first()->id;
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

            $orderList = [];
            switch (strtolower($state)) {
                case 'all':
                    $orderList = collect($items)->groupBy('invoice');
                    break;
                case 'pending':
                    $orderList = collect($p_items)->groupBy('invoice');
                    break;
                case'complete':
                    $orderList = collect($d_items)->groupBy('invoice');
                    break;
                default:
                    break;
            }
            return response()->json(['status' => true, 'message' => 200, 'data' => $this->orderListFormat($orderList)]);
        }
        return response()->json($merchant);
    }

    protected function orderListFormat($orders)
    {
        $list = [];
        foreach ($orders as $key => $order) {
            $product = [];

            $orderStatus = true;
            foreach ($order as $item) {

                $product[] = [
                    'name' => $item->getProduct->name,
                    'variant' => ($item->getProductVariant->name) ?? null,
                    'product_status' => $item->getOrderStatus->name];
                if ($item->getOrderStatus->key === 'process')
                    $orderStatus = false;
            }

            $list[] = [
                'order_id' => $order->first()->getOrder->id,
                'invoice' => $key,
                'order_date' => $order->first()->getOrder->order_date,
                'buyer' => $order->first()->getOrder->getUser->name . ' ' . $order->first()->getOrder->getUser->surname,
                'product' => $product,
                'order_status' => $orderStatus ? 'Delivered' : 'Processing'
            ];
        }
        return $list;
    }

    function orderDetail(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'order_id' => 'required',
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);


            $order = Order::find($request->order_id);
            if (!$order)
                return response()->json(['status' => false, 'message' => 404, 'error' => 'Order not found']);
            if ($order->getUser->is_member === 0) {
                if (str_contains($order->payment_method, 'ecash_wallet'))
                    $method = __('message.E-cash Wallet');
                else
                    $method = __('message.Cash on Delivery');
            } else
                $method = __('message.E-cash/E-voucher Wallet');

            $orderItem = [];
            $total = 0;
            foreach ($order->getOrderItem as $item) {
                $merchant_id = $item->getProduct->getBusiness->getMerchant->id;
                if ($merchant['data']->id == $merchant_id) {
                    $item['net_price'] = number_format($item->quantity * $item->sell_price, 2, '.', '');
                    $orderItem[] = $item;
                    $total += $item['net_price'];
                }
            }

            $invoice = collect($orderItem)->first();
            $count = count($orderItem);
            $delivery = number_format($count * (env('DELIVERY_COST') ?? 0), 2, '.', '');
            $tax = number_format($total * (env('TAX_PERCENT') ?? 0) / 100, 2, '.', '');
            $net_total = number_format($total + ($total * (env('TAX_PERCENT') ?? 0) / 100) + ($count * (env('DELIVERY_COST') ?? 0)), 2, '.', '');

            $total = number_format($total, 2, '.', '');

            return response()->json(['status' => true, 'message' => 200, 'data' => [
                'order' => [
                    'order_id' => $order->id,
                    'invoice' => $invoice->invoice,
                    'order_date' => $order->order_date,
                    'deliver_date' => $order->deliver_date,
                    'contact_number' => $order->contact_number,
                    'email' => $order->email,
                    'address' => $order->address,
                    'city' => $order->city,
                    'country' => $order->getCountry->name,
                    'payment_method' => $method,
                    'buyer_name' => $order->getUser->name . ' ' . $order->getUser->surname,
                    'is_member' => $order->getUser->is_member,
//                    'tax' => $tax,
                    'delivery' => $delivery,
                    'net_total' => $net_total,
                    'total' => $total,
                ],
                'order_item' => $this->orderItemFormat($orderItem),

            ]]);
        }
        return response()->json($merchant);
    }

    protected function orderItemFormat($items)
    {
        $list = [];
        foreach ($items as $item) {
            $list[] = [
                'order_item_id' => $item->id,
                'name' => $item->getProduct->name,
                'variant' => $item->getProductVariant->name ?? null,
                'deliver_date' => $item->deliver_date,
                'marked_price' => $item->marked_price,
                'sell_price' => $item->sell_price,
                'discount' => $item->discount,
                'quantity' => $item->quantity,
                'net_price' => $item->net_price,
                'order_status' => $item->getOrderStatus->name,
                'image' => url('image/products/' . $item->getProduct->featured_image)
            ];
        }
        return $list;
    }


    function orderItemStatus(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'order_item_id' => 'required',
                'action' => 'required|in:dispatch,deliver',
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);

            $orderItem = OrderItem::find($request->order_item_id);
            if ($orderItem->order_status_id != 'deliver') {
                if ($request->action == 'deliver') {
                    $orderItem->update(['order_status_id' => $request->action, 'deliver_date' => Carbon::now()]);
                    $this->shoppingLogAfterDeliver($orderItem);
                    $this->bonusOnCashDelivery($orderItem);
                } else {
                    $orderItem->update(['order_status_id' => $request->action]);
                }


                $status = true;
                foreach ($orderItem->getOrder->getOrderItem as $item) {
                    if ($item->order_status_id != 'deliver') {
                        $status = false;
                    }
                }
                if ($status) {
                    $orderItem->getOrder->update(['order_status_id' => 'complete']);
                }

                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'order item status udpated']);

            } elseif ($orderItem->order_status_id == 'deliver') {
//                if ($request->action != 'deliver') {
//                $orderItem->update(['order_status_id' => $request->action, 'deliver_date' => null]);
//                $this->commisionAfterReturn($id);
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'order item already delivered']);
//                }
            }


        }
        return response()->json($merchant);
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

//    function itemShipping($id, Request $request)
//    {
//        $order = Order::find($id);
//        if (!$order)
//            return redirect()->back();
//        $stat = false;
//        foreach ($order->getOrderItem as $item) {
//            $merchant_id = $item->getProduct->getBusiness->getMerchant->id;
//            if ($stat == false) {
//                if ($this->_merchant_id == $merchant_id) {
//                    $stat = true;
//                    $shipp = ShippingOrderItem::where('invoice', $item->invoice)->first();
//
//                    if (!$shipp)
//                        ShippingOrderItem::create([
//                            'invoice' => $item->invoice,
//                            'tracking_id' => $request->tracking_id,
//                            'carrier' => $request->carrier,
//                            'weight' => $request->weight,
//                            'url' => $request->url,
//                            'notify' => $request->notify ? 1 : 0,
//                        ]);
//                    else
//                        $shipp->update([
//                            'tracking_id' => $request->tracking_id,
//                            'carrier' => $request->carrier,
//                            'weight' => $request->weight,
//                            'url' => $request->url,
//                            'notify' => $request->notify ? 1 : 0,
//                        ]);
//                }
//            }
//        }
//        return redirect()->back()->with('shipp', __('message.Updated!'));
//    }

}
