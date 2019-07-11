<?php

namespace App\Http\Controllers\Api;

use App\Models\Members\MemberAsset;
use App\Models\MerchantBusiness;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{

    function orderList()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $json['orders'] = $this->getOrderData($json['data']->id);
            $json['history'] = $this->getHistoryData($json['data']->id);
            $json['data']['ecash_wallet'] = MemberAsset::where('member_id', $json['data']->id)->first()->ecash_wallet ?? 0;
        }
        return response()->json($json);
    }

    function orderDetail(Request $request)
    {
        if (empty($request->id)) return response()->json(['status' => false, 'message' => 400, 'error' => __('message.missing Id')]);

        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $order_user_id = Order::find($request->id)->user_id ?? 0;
            if ($json['data']->id !== $order_user_id) return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid order detail')]);
            if (($json['data'] = $this->getOrderDetail($request->id, $json['data']->id)) === false)
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid order detail')]);
        }
        return response()->json($json);
    }

    /****protected functions****/


    /**
     * @return array
     * User Login check
     */
    protected
    function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return ['status' => false, 'message' => 400, 'error' => __('message.Invalid Login name or Password'), 'redirect' => true];
            }
        } catch (TokenExpiredException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Token Expired'), 'redirect' => true];
        } catch (TokenInvalidException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Invalid Token'), 'redirect' => true];
        } catch (JWTException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Unauthorized User'), 'redirect' => true];
        }

        $headtoken = collect(JWTAuth::getToken())->first() ?? 'invalid';
        if ($user->jwt_token_handle !== $headtoken)
            return ['status' => false, 'message' => 401, 'error' => __('message.Invalid Token'), 'redirect' => true];


        // the token is valid and we have found the user via the sub claim
        return ['status' => true, 'message' => 200, 'data' => $user, 'message-detail' => __('message.success')];
    }


    protected function getOrderData($user_id)
    {
        $orders = Order::where('user_id', $user_id)->whereIn('order_status_id', ['order', 'confirm'])->orderBy('id', 'DESC')->get();
        $data = $this->orderFormat($orders);
        return ['count' => count($data), 'order' => $data];
    }

    protected function getHistoryData($user_id)
    {
        $orders = Order::where('user_id', $user_id)->whereIn('order_status_id', ['cancel', 'complete'])->orderBy('id', 'DESC')->get();
        $data = $this->orderFormat($orders);
        return ['count' => count($data), 'order' => $data];
    }

    protected function orderFormat($orders)
    {
        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->id,
                'payment_array' => $order->payment_array,
                'payment_method' => unserialize($order->payment_method),
                'total_price' => $order->total_price,
                'sub_total' => $order->sub_total,
                'tax' => $order->tax,
                'order_date' => $order->order_date,
                'order_status_id' => $order->getOrderStatus->name,
                'address' => $order->address,
                'city' => $order->city,
                'country' => $order->getCountry->name,
            ];
        }
        return $data;
    }


    protected function getOrderDetail($id, $user_id)
    {
        $order = Order::find($id);
        if ($order) {
            $json = $this->orderFormat([$order])[0];
            $json['order_items'] = $order->getOrderItem;
            foreach ($order->getOrderItem as $key => $item) {
                $prod = Product::find($item->product_id);
                $proName = $prod->name ?? null;
                $proImg = $prod->featured_image ?? null;
                $proVar = ProductVariant::find($item->product_variant_id)->name ?? null;
                $proStat = OrderStatus::where('key', $item->order_status_id)->first()->name ?? null;

                $proBusiness = MerchantBusiness::select('id', 'registration_number', 'name', 'slug', 'contact_number', 'address')->where('id', $prod->merchant_business_id)->first();
                //                $proVar = $item->getProductVariant->name ?? null;

                $json['order_items'][$key]['product_name'] = $proName;
                $json['order_items'][$key]['variant_name'] = $proVar;
                $json['order_items'][$key]['status_name'] = $proStat;
                $json['order_items'][$key]['image'] = url('/') . '/image/products/' . $proImg;
                $json['order_items'][$key]['business'] = $proBusiness;
                unset($json['order_items'][$key]['order_status_id']);
            }
            $json['bonus'] = $this->getOrderBonus($order, $user_id);
            return $json;
        }
        return false;
    }

    protected function getOrderBonus($order, $user_id)
    {
        $bonus = [
            'customer_bonus' => false,
            'auto' => false,
            'special' => false,
            'standard' => false,
        ];

        foreach ($order->getOrderItem as $item) {
            if ($item->getShoppingBonus !== null) {
                foreach (unserialize($item->getShoppingBonus->bonus_list) as $key => $type) {
                    $bonus[$key][] = $type;
                }
            }
        }
        $bonus = collect($bonus);
        if ($bonus['customer_bonus']) {
            $sum = $bonus['customer_bonus'][0] ?? false;
        } else {
            $sum = __('front.Your shopping bonus will be distributed after delivery');
        }
        if ($bonus['standard']) {
            $sum1 = 0;
            foreach ($bonus['standard'] as $standard_bonus) {
                foreach ($standard_bonus as $stan) {
                    if ($user_id === $stan['member_id'])
                        $sum1 += $stan['shop_point'];
                }
            }
        }

        if ($bonus['auto']) {
            $sum2 = 0;
            foreach ($bonus['auto'] as $auto_bonus) {
                foreach ($auto_bonus as $aut) {
                    if ($user_id === $aut['member_id'])
                        $sum2 += $aut['shop_point'];
                }
            }
        }

        if ($bonus['special']) {
            $sum3 = 0;
            foreach ($bonus['special'] as $special_bonus) {
                foreach ($special_bonus as $special) {
                    if ($user_id === $special['member_id'])
                        $sum3 += $special['shop_point'];
                }
            }
        }

        return [
            'customer_bonus' => $sum ?? false,
            'standard' => $sum1 ?? false,
            'auto' => $sum2 ?? false,
            'special' => $sum3 ?? false,
        ];
    }


    function orderListInvoice()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $orders = Order::where('user_id', $json['data']->id)->latest()->get();
            $all_invoice = [];
            $c_invoice = [];

            foreach ($orders as $order) {
                foreach ($order->getOrderItem as $item) {
                    $all_invoice[] = $item->invoice;
                    if ($item->order_status_id === 'deliver')
                        $c_invoice[] = $item->invoice;
                }
            }

            $p_invoice = array_diff($all_invoice, $c_invoice);

            $p_orders = OrderItem::whereIn('invoice', $p_invoice)->orderBy('id', 'DESC')->get()->groupby('invoice');
            $pending = $this->orderFormatInvoice($p_orders);

            $c_orders = OrderItem::whereIn('invoice', $c_invoice)->orderBy('id', 'DESC')->get()->groupby('invoice');
            $history = $this->orderFormatInvoice($c_orders);

            return ['status' => true, 'message' => 200, 'data' => [
                'pending' => $pending,
                'history' => $history,
                'ecash_wallet' => MemberAsset::where('member_id', $json['data']->id)->first()->ecash_wallet ?? 0,
            ], 'message-detail' => __('message.success')];
        }
        return response()->json($json);
    }

    function orderDetailInvoice(Request $request)
    {
        if (empty($request->id)) return response()->json(['status' => false, 'message' => 400, 'error' => __('message.missing Id')]);

        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $order = Order::find($request->id);
            if (!$order)
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid order detail')]);

            if ($json['data']->id !== $order->user_id)
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid order detail')]);

            unset($json['data']);
            $json['data']['order'] = $this->orderFormat([$order])[0];

//            $json['data']['bonus'] = $this->getOrderBonus($order, $user_id);

            $items = collect($order->getOrderItem)->groupBy('invoice');
            $invoice_items = [];
            foreach ($items as $inv => $invoice_item) {
                $collect_item = [];
                $proBusiness = MerchantBusiness::select('id', 'registration_number', 'name', 'slug', 'contact_number', 'address')->where('id', $invoice_item[0]->getProduct->merchant_business_id)->first();
                foreach ($invoice_item as $item) {
                    $proStat = OrderStatus::where('key', $item->order_status_id)->first()->name ?? null;
                    $collect_item [] = [
                        'product_name' => $item->getProduct->name ?? null,
                        'product_slug' => $item->getProduct->slug ?? null,
                        'variant_name' => $item->getProductVariant->name??null,
                        'status_name' => $proStat,
                        'image' => url('/') . '/image/products/' . ($item->getProduct->featured_image ?? null),
                        'quantity' => $item->quantity,
                        'deliver_date' => $item->deliver_date,
                        'marked_price' => $item->marked_price,
                        'sell_price' => $item->sell_price,
                        'discount' => $item->discount,
                    ];
                }

                $invoice_items[] = [
                    'invoice' => $inv,
                    'business' => $proBusiness,
                    'items' => $collect_item
                ];
            }
            $json['order_items'] = $invoice_items;

        }
        return response()->json($json);
    }

    protected function orderFormatInvoice($items)
    {
        $data = [];
        foreach ($items as $inv => $item) {
            $order = Order::find($item[0]->order_id);
            $merchant = $item[0]->getProduct->getBusiness;

            $amount = 0;
            foreach ($item as $invoiced)
                $amount += $invoiced->sell_price;

            $data [] = [
                'id' => $order->id,
                'invoice' => $inv,
                'order_date' => $order->order_date,
                'order_status_id' => 'Pending',
                'address' => $order->address,
                'city' => $order->city,
                'country' => $order->getCountry->name,
                'merchant' => $merchant,
                'net_amount' => $amount
            ];
        }
        return $data;
    }
}