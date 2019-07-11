<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\NotificationTrait;
use App\Http\Traits\OrderDeliverTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Library\ShoppingBonus;
use App\Models\Cart;
use App\Models\Commisions\Shopping;
use App\Models\Commisions\ShoppingBonusDistribution;
use App\Models\Commisions\ShoppingLog;
use App\Models\Commisions\ShoppingMerchant;
use App\Models\Country;
use App\Models\Members\MemberAsset;
use App\Models\MerchantAsset;
use App\Models\MerchantBusiness;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class CartController extends Controller
{
    use WalletsHistoryTrait, NotificationTrait, OrderDeliverTrait;

    function cartList()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $json['data'] = $this->getCart($json['data']->id, [true, false]);
            $featured_products = new ApiController();
            $json['featured_products'] = $featured_products->FeaturedProducts()->original ?? [];
        }
        return response()->json($json);
    }


    function getCheckout()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $user = User::find($json['data']->id);
            $json['data'] = $this->getCart($json['data']->id, [true]);
            $json['data']['address'] = $user->address;
            $json['data']['city'] = $user->city;
            $json['data']['country'] = $user->getCountry->name;
            $json['country'] = Country::select('name', 'id')->get();
        }
        return response()->json($json);
    }

    function postCheckout(Request $request)
    {
        $old_address = $request->old_address ?? false;
        if ($old_address != 'true') {
            $validated = Validator::make($request->all(), [
                'address' => 'required',
//            'city'
                'country_id' => 'required',
            ]);
            if ($validated->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $validated->errors()->first()]);
        }
        $validate_pay = Validator::make($request->all(), [
            'ecash_wallet' => 'required',
            'evoucher_wallet' => 'required',
        ]);
        if ($validate_pay->fails())
            return response()->json(['status' => false, 'message' => 422, 'error' => $validate_pay->errors()->first()]);

        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $response = $this->checkoutToOrder($request, $json['data'], 'member');
            if ($response['status'] == false)
                return response()->json($response);
            return response()->json($response);
        }
        return response()->json($json);
    }

    function postCheckoutCustomer(Request $request)
    {
        $old_address = $request->old_address ?? false;
        if ($old_address != 'true') {
            $validated = Validator::make($request->all(), [
                'address' => 'required',
//            'city'
                'country_id' => 'required',
            ]);

            if ($validated->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $validated->errors()->first()]);
        }

        $validate_pay = Validator::make($request->all(), [
            'payment_method' => 'required',
        ]);
        if ($validate_pay->fails())
            return response()->json(['status' => false, 'message' => 422, 'error' => $validate_pay->errors()->first()]);

        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $response = $this->checkoutToOrder($request, $json['data'], 'customer');
            if ($response['status'] == false)
                return response()->json($response);
            return response()->json($response);
        }
        return response()->json($json);
    }

//    function cancelCheckout(Request $request)
//    {
//        if (empty($request->invoice_number)) return response()->json(['status' => false, 'message' => 400, 'error' => 'missing invoice number parameter'], 400);
//        $json = $this->getAuthenticatedUser();
//        if ($json['status']) {
//            if (($this->cancelCheckoutAfterOrder($request->invoice_number)) === false)
//                return response()->json(['status' => false, 'message' => 400, 'error' => 'cancelCheckoutAfterOrder error!'], 400);
//            $json['data'] = $request->invoice_number;
//        }
//        return response()->json($json);
//    }

    function removeCartItem(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $status = false;
            if (!empty($request->id)) {
                $id = intval($request->id);
                $status = Cart::find($id);
                if ($status) {
                    $status->delete();
                    return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Cart item removed successfully')]);
                } else {
                    return response()->json(['status' => false, 'message' => 403, 'error' => __('message.Invalid id')]);
                }
            }
            return response()->json(['status' => false, 'message' => 404, 'error' => __('message.Failed to remove cart item')]);
        }
        return response()->json($json);
    }

    function addCartItem(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            if (!empty($request->product_id)) {

                $variant_id = (empty($request->variant_id)) ? null : $request->variant_id;
                $cartCheck = Cart::where('user_id', $json['data']->id)->where('product_id', $request->product_id)->where('variant_id', $variant_id)->first() ?? false;

                if (!$cartCheck) {
                    if (Cart::create([
                        'user_id' => $json['data']->id,
                        'product_id' => $request->product_id,
                        'quantity' => $request->quantity ?? 1,
                        'variant_id' => $variant_id,
                    ])) {
                        $cartCount = Cart::where('user_id', $json['data']->id)->get() ?? [];
                        sleep(1);
                        return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Item added to cart'), 'data' => count($cartCount)]);
                    }
                } else {
                    $cartCheck->update(['quantity' => $request->quantity ?? 1]);
                    $cartCount = Cart::where('user_id', $json['data']->id)->get() ?? [];
                    sleep(1);
                    return response()->json(['status' => true, 'message' => 200,
                        'message-detail' => 'cart item quantity updated!', 'data' => count($cartCount)]);
                }
            }
            return response()->json(['status' => false, 'message' => 404, 'error' => __('message.Failed to add item in cart')]);
        }
        return response()->json($json);
    }


    function quantityCartItem(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            if (!empty($request->cart_id)) {
                $cartCheck = Cart::find($request->cart_id);
                if ($cartCheck) {
                    if ($request->type === 'down') {
                        if ($cartCheck->quantity === 1) {
//                            $cartCheck->delete();
                            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.cart item is at minimum')]);
                        }
                        $cartCheck->decrement('quantity', 1);

                    } else {
                        $cartCheck->increment('quantity', 1);
                    }
                    return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Quantity updated successfully')]);
                }
            }
            return response()->json(['status' => false, 'message' => 404, 'error' => __('message.Failed to update quantity')]);
        }
        return response()->json($json);
    }

    function clearCart()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $carts = Cart::where('user_id', $json['data']->id)->get();
            foreach ($carts as $cart) {
                $cart->delete();
            }
            return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Cart cleared successfully')]);
        }
        return response()->json($json);
    }

    /****protected functions****/

    /**
     * @param $id
     * @param $type
     * @return array
     * Get api with items having positive stocks
     */
    protected
    function getCart($id, $type)
    {
        $carts = Cart::where('user_id', $id)->get();
        foreach ($carts as $cart) {
            $status = true;
            if ($cart->variant_id !== null && !empty($cart->variant_id)) {
//                return $varQty = ProductVariant::find($cart->variant_id)->quantity;
                $prodVar = ProductVariant::find($cart->variant_id);
                if ($prodVar) {
                    if ($cart->quantity > $prodVar->quantity) {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
            } else {
                $prod = Product::find($cart->product_id);
                if ($prod) {
                    if ($cart->quantity > $prod->quantity) {
                        $status = false;
                    }
                } else {
                    $status = false;
                }
            }
            $cart->update(['status' => $status]);
        }

        $filtered = Cart::where('user_id', $id)->whereIn('status', $type)->get();
        $jsonCart = [];
        $sub_total = 0;
        foreach ($filtered as $key => $item) {
            $jsonCart[$key]['status'] = $item->status;
            $jsonCart[$key]['id'] = $item->id;
            $jsonCart[$key]['name'] = $item->getProduct->name;
            $jsonCart[$key]['slug'] = $item->getProduct->slug;
            $jsonCart[$key]['image'] = url('/') . '/image/products/' . $item->getProduct->featured_image;
            $jsonCart[$key]['quantity'] = $item->quantity;
            if ($item->variant_id !== null) {
                $proVar = ProductVariant::find($item->variant_id);
                $jsonCart[$key]['sell_price'] = $proVar->sell_price;
                $jsonCart[$key]['marked_price'] = $proVar->marked_price;
                $jsonCart[$key]['discount'] = $proVar->discount;
                $jsonCart[$key]['sub_total'] = $item->quantity * $proVar->sell_price;
                $jsonCart[$key]['variant'] = $proVar->name;
                if ($item->status === 1)
                    $sub_total += ($proVar->sell_price * $item->quantity);

            } else {
                $jsonCart[$key]['sell_price'] = $item->getProduct->sell_price;
                $jsonCart[$key]['marked_price'] = $item->getProduct->marked_price;
                $jsonCart[$key]['discount'] = $item->getProduct->discount;
                $jsonCart[$key]['sub_total'] = $item->quantity * $item->getProduct->sell_price;
                $jsonCart[$key]['variant'] = null;
                if ($item->status === 1)
                    $sub_total += ($item->getProduct->sell_price * $item->quantity);
            }
            $jsonCart[$key]['stock'] = $this->getSoldProduct($item->product_id, $item->variant_id);
            $jsonCart[$key]['business'] = MerchantBusiness::select('registration_number', 'name', 'slug', 'contact_number', 'address')->where('slug', $item->getProduct->getBusiness->slug)->first();
        }

        $tax_val = env('TAX_PERCENT') ?? 0;
        $tax = number_format($sub_total * ($tax_val / 100), 2, '.', '');

        $shipping = 0;
//        $shipping = number_format(count($jsonCart) * env('DELIVERY_COST') ?? 0, 2, '.', '');

        $tax_percent = $tax_val . '%';
        $total = $sub_total + $tax + $shipping;
        return ['count' => count($jsonCart), 'cart' => $jsonCart, 'sub_total' => $sub_total, 'tax' => $tax_percent, 'shipping' => $shipping, 'total' => $total];
    }

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
        return ['status' => true, 'message' => 200, 'data' => $user,];
    }

    /**
     * @param $product_id
     * @return array
     * Get api for sold and available
     */
    protected
    function getSoldProduct($product_id, $variant_id = null)
    {
        if ($variant_id === null) {
            $product = Product::find($product_id);
            $sold = 0;
            $available = $product->quantity;

            $orderItems = OrderItem::where('product_id', $product_id)->where('created_at', '>', Carbon::now()->subDays(30))->get();
            foreach ($orderItems as $orderItem) {
                if ($orderItem->getOrder->order_status_id !== 'cancel') {
                    $sold += $orderItem->quantity;
                }
            }

            return [
                'total' => (int)($sold + $available),
                'sold' => (int)$sold,
                'available' => intval($available)
            ];
        } else {
            $product = ProductVariant::find($variant_id);
            $sold = 0;
            $available = $product->quantity;
            $orderItems = OrderItem::where('product_variant_id', $variant_id)->where('created_at', '>', Carbon::now()->subDays(30))->get();
            foreach ($orderItems as $orderItem) {
                if ($orderItem->getOrder->order_status_id !== 'cancel') {
                    $sold += $orderItem->quantity;
                }
            }

            return [
                'total' => (int)($sold + $available),
                'sold' => (int)$sold,
                'available' => intval($available)
            ];
        }
    }


    protected
    function checkoutToOrder($request, $user, $type)
    {
        $old_address = $request->old_address ?? false;

        if ($old_address == 'true') {
            $country_id = $user->country_id;
            $city = $user->city;
            $address = $user->address;
        } else {
            $country_id = $request->country_id;
            $city = $request->city;
            $address = $request->address;
        }

        $carts = Cart::where('user_id', $user->id)->get();
        foreach ($carts as $cart) {
            $status = true;
            if ($cart->variant_id !== null && !empty($cart->variant_id)) {
                if ($cart->quantity > ProductVariant::find($cart->variant_id)->quantity) {
                    $status = false;
                }
            } else {
                if ($cart->quantity > Product::find($cart->product_id)->quantity) {
                    $status = false;
                }
            }
            $cart->update(['status' => $status]);
        }


        $getTotals = Cart::where('user_id', $user->id)->where('status', 1)->get();

        if (count($getTotals) === 0) return ['status' => false, 'message' => 400, 'error' => __('message.No items in cart')];
        $total = 0;
        foreach ($getTotals as $getTotal) {
            if ($getTotal->variant_id !== null)
                $total += $getTotal->getVariant->sell_price * $getTotal->quantity;
            else
                $total += $getTotal->getProduct->sell_price * $getTotal->quantity;
        }
        $count = count($getTotals);
        $tax = number_format(($total * ((env('TAX_PERCENT') ?? 0) / 100)), 2, '.', '');


        $floatTotal = $total + $tax;
        $delivery_price = number_format((env('DELIVERY_COST') ?? 0 * $count), 2, '.', '');

        $checkAsset = $floatTotal + $delivery_price;

        $asset = MemberAsset::where('member_id', $user->id)->first();

        $payment_method = [];
        if ($user->is_member === 1) {

            if (!$asset)
                return ['status' => false, 'message' => 400, 'error' => __('message.Asset not found')];

            if (($request->ecash_wallet + $request->evoucher_wallet) < $checkAsset)
                return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];

            if ($request->ecash_wallet > $asset->ecash_wallet)
                return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Ecash Wallet Amount')];

            if ($request->evoucher_wallet > $asset->evoucher_wallet)
                return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Evoucher Wallet Amount')];

            $payment_method = ['ecash_wallet' => $request->ecash_wallet, 'evoucher_wallet' => $request->evoucher_wallet];
        } else {
            $pay_method = strtolower($request->payment_method);
            switch ($pay_method) {
                case 'cash':
                    $payment_method = ['cash' => $checkAsset];
                    break;
                case'ecash_wallet':
                    if (!$asset)
                        return ['status' => false, 'message' => 400, 'error' => __('message.Asset not found')];
                    if ($checkAsset > $asset->ecash_wallet)
                        return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Ecash Wallet Amount')];
                    $payment_method = ['ecash_wallet' => $checkAsset];
                    break;
                default:
                    return ['status' => false, 'message' => 400, 'error' => __('message.Invalid payment method')];
            }
        }

        $order = Order::create([
            'user_id' => $user->id,
            'payment_method' => serialize($payment_method),
            'total_price' => $floatTotal,
            'order_date' => Carbon::now(),
//            'deliver_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'order_status_id' => 'confirm',
            'address' => $address,
            'city' => $city,
            'country_id' => $country_id,
//            'invoice_number' => $invoice,
            'delivery_price' => $delivery_price,
            'sub_total' => $total,
            'tax' => $tax,
        ]);


        if ($order) {


            foreach ($payment_method as $key => $value) {
                switch ($key) {
                    case 'cash':
                        break;
                    case'ecash_wallet':
                        $asset->update(['ecash_wallet' => $asset->ecash_wallet - $value]);
                        $this->createWalletReport($user->id, $value, 'Product Order on ' . $order->order_date, 'ecash', 'OUT');
                        break;

                    case'evoucher_wallet':
                        $asset->update(['evoucher_wallet' => $asset->evoucher_wallet - $value]);
                        $this->createWalletReport($user->id, $value, 'Product Order on ' . $order->order_date, 'evoucher', 'OUT');
                        break;
                }
            }

            //loop cartitems
            foreach ($getTotals as $sort) {
                $sort['merchant_id'] = $sort->getProduct->getBusiness->id;
                $sort['merchant_slug'] = $sort->getProduct->getBusiness->slug;
            }
            $getTotals = $getTotals->sortBy('merchant_id');

            $prev = 0;
            $invoice = 0;
            foreach ($getTotals as $getTotal) {
                if ($prev !== $getTotal->merchant_id) {
                    $prev = $getTotal->merchant_id;
                    $serial = substr(str_pad(OrderItem::orderBy('id', 'DESC')->first()->id ?? 0, 6, '0', STR_PAD_LEFT), -6);
                    $invoice = 'GGT' . Carbon::now()->format('myd') . '-' . strtoupper(substr($getTotal->merchant_slug, 0, 3)) . $serial;
//                    $invoice = strtoupper(substr($getTotal->merchant_slug, 0, 3)) . Carbon::now()->format('Ymd') . $serial;
                }
                $this->fillOrderItem($getTotal, $order->id, $user->id, $invoice);
                $getTotal->delete();
            }
            //stock manage in product & variant db
            $this->stockManage($order->id);


            //            Notifiaction
            $this->createNotificaton('admin', $user->id, 'New order placed');
            $merchants = [];
            foreach ($order->getOrderItem as $item) {
                $merchant_id = $item->getProduct->getBusiness->getMerchant->id;
                if (!in_array($merchant_id, $merchants)) {
                    $merchants[] = $merchant_id;
                    $this->createNotificaton('merchant', $merchant_id, 'New order placed');
                }
            }

            return ['status' => true, 'message' => 200, 'message-detail' => __('message.Checkout Success!'),
                'data' => $this->getOrderDetail($order->id), 'confirm_image' => url('frontend') . '/Like.png'];
        }
        return ['status' => true, 'message' => 400, 'error' => __('message.Checkout to order Error!')];
    }

    protected function getOrderDetail($id)
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
            return $json;
        }
        return false;
    }


    protected function orderFormat($orders)
    {
        $data = [];
        foreach ($orders as $order) {
            $data[] = [
                'id' => $order->id,
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

    /**
     * @param $order_item
     * @param $order_id
     * Fill orderItems in db from order ID
     */
    protected
    function fillOrderItem($order_item, $order_id, $user_id, $invoice)
    {
        if ($order_item->variant_id !== null) {
            $proVar = ProductVariant::find($order_item->variant_id);
        } else {
            $proVar = Product::find($order_item->product_id);
        }
        $recordItem = OrderItem::create([
            'product_id' => $order_item->product_id,
            'order_id' => $order_id,
            'product_variant_id' => $order_item->variant_id,
            'quantity' => $order_item->quantity,
            'marked_price' => $proVar->marked_price,
            'sell_price' => $proVar->sell_price,
            'discount' => $proVar->discount,
            'invoice' => $invoice,
        ]);

        $this->shoppingLog($recordItem, $user_id);
    }

    /**
     * @param $order_id
     * @param string $type
     * change quantity according to order made and cancelled
     */
    protected
    function stockManage($order_id, $type = 'down')
    {
        $items = Order::find($order_id)->getOrderItem;
        if ($type === 'down') {
            foreach ($items as $item) {
                if ($item->product_variant_id !== null) {
                    ProductVariant::find($item->product_variant_id)->decrement('quantity', $item->quantity);
                } else {
                    Product::find($item->product_id)->decrement('quantity', $item->quantity);
                }
            }
        } elseif ($type === 'up') {
            foreach ($items as $item) {
                if ($item->product_variant_id !== null) {
                    ProductVariant::find($item->product_variant_id)->increment('quantity', $item->quantity);
                } else {
                    Product::find($item->product_id)->increment('quantity', $item->quantity);
                }
            }
        }
    }

    protected
    function cancelCheckoutAfterOrder($invoice)
    {
        $order = Order::where('invoice_number', $invoice)->first();
        if ($order->update(['order_status_id' => 'cancel'])) {
            $this->stockManage($order->id, 'up');
            return true;
        }
        return false;
    }

    function test()
    {
        return response()->json([
            'asd' => Cart::all(),
        ]);
    }

    protected
    function shoppingLog($orderItem, $user_id)
    {
        //        $this->shoppingLogAfterDeliver($orderItem);
        return true;
    }
}
