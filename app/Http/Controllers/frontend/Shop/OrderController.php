<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Http\Traits\NotificationTrait;
use App\Http\Traits\OrderDeliverTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Library\ShoppingBonus;
use App\Models\Category;
use App\Models\Country;
use App\Models\Members\MemberAsset;
use App\Models\MerchantAsset;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use function Sodium\add;

class OrderController extends Controller
{
    use  NotificationTrait, OrderDeliverTrait;
    private $_path = 'frontend.home';
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

    function address()
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        if (count(Cart::content()) === 0) return redirect()->to(route('cart-view'));
        $this->_data['user'] = Auth::user();
        $this->_data['countries'] = Country::all();

        $getTotals = Cart::content();

        $total = 0;
        $count = 0;
        foreach ($getTotals as $getTotal) {
            if ($getTotal->options->status) {
                $total += $getTotal->price * $getTotal->qty;
                $count++;
            }
        }
        $this->_data['count'] = $count;
        $this->_data['total'] = $total;

        return view($this->_path . '.order-address', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function postAddress(Request $request)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));

        if (!request()->ajax()) {
            return back();
        }
        $validator = Validator::make($request->all(), [
//            'transaction_pass' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        $old_sddress = $request->old_address ?? false;
        $old_contact = $request->old_contact ?? false;

        if ($old_sddress !== false) {
            $country_id = Auth::user()->country_id;
            $city = Auth::user()->city;
            $address = Auth::user()->address;
            session()->flash('old_address', true);
        } else {
            $country_id = $request->country_id;
            $city = $request->city;
            $address = $request->address;
            session()->flash('input_address', $address);
            session()->flash('input_city', $city);
            session()->flash('input_country_id', $country_id);
            session()->flash('old_address', false);
        }

        if ($old_contact !== false) {
            $email = Auth::user()->email;
            $contact_number = Auth::user()->contact_number;
        } else {
            $email = $request->email;
            $contact_number = $request->contact_number;
            session()->flash('input_contact_number', $contact_number);
            session()->flash('input_email', $email);
        }

        session()->flash('input_ecash_wallet', $request->ecash_wallet);
        session()->flash('input_evoucher_wallet', $request->evoucher_wallet);
        session()->flash('input_payment_method', $request->payment_method);

        if (!(!empty($address) || !empty($country_id))) {
            $validator->errors()->add('old_address',
                __('message.Address field is required'));
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }
        if (!(!empty($contact_number) || !empty($email))) {
            $validator->errors()->add('old_contact',
                __('message.Contact field is required'));
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));

        }
//        return redirect()->back()->with('address', __('message.Address field is required'));

        //        $serial = substr(str_pad(Order::orderBy('id', 'DESC')->first()->id ?? 0, 4, '0', STR_PAD_LEFT), -4);
//        $invoice = strtoupper(substr(Auth::user()->identification_type ?? 'cit', 0, 3) . Carbon::now()->format('Ymd') . $serial);

        $getTotals = Cart::content();

        $total = 0;
        $count = 0;
        $net_tax = 0;
        foreach ($getTotals as $getTotal) {
            if ($getTotal->options->status) {
                $total += $getTotal->price * $getTotal->qty;

                $product = Product::find($getTotal->id);
                $tax = ($product->tax + $product->vat + $product->excise) / 100;
                $net_tax += ($getTotal->qty * $getTotal->price) * $tax;

                $count++;
            }
        }
        if ($total === 0) {
            session()->flash('fail', __('message.Empty Cart or Item out of stock'));
            return response()->json(array(
                'status' => 'success',
                'url' => url('order/address')
            ));
        }

        $floatTotal = $total + $net_tax;
        $delivery_price = number_format(((env('DELIVERY_COST') ?? 0) * $count), 2, '.', '');

        $checkAsset = $floatTotal + $delivery_price;

        $asset = MemberAsset::where('member_id', Auth::user()->id)->first();

        if (Auth::user()->is_member === 1) {
            if (!$asset) {
                session()->flash('fail', __('message.Asset not found'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('order/address')
                ));
            }
//                return redirect()->back()->with('fail', __('message.Asset not found'));

            if (($request->ecash_wallet + $request->evoucher_wallet) < $checkAsset) {
                session()->flash('fail', __('message.Insufficient Amount'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('order/address')
                ));
            }
//                return redirect()->back()->with('fail', __('message.Insufficient Amount'));

            if ($request->ecash_wallet > $asset->ecash_wallet) {
                session()->flash('fail', __('message.Insufficient Ecash Wallet Amount'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('order/address')
                ));
            }
//                return redirect()->back()->with('fail', __('message.Insufficient Ecash Wallet Amount'));

            if ($request->evoucher_wallet > $asset->evoucher_wallet) {
                session()->flash('fail', __('message.Insufficient Evoucher Wallet Amount'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('order/address')
                ));
            }
//                return redirect()->back()->with('fail', __('message.Insufficient Evoucher Wallet Amount'));

            $payment_method = ['ecash_wallet' => $request->ecash_wallet, 'evoucher_wallet' => $request->evoucher_wallet];
        } else {
            switch ($request->payment_method) {
                case 'cash':
                    $payment_method = ['cash' => $checkAsset];
                    break;
                case'ecash_wallet':
                    if (!$asset) {
                        session()->flash('fail', __('message.Asset not found'));
                        return response()->json(array(
                            'status' => 'success',
                            'url' => url('order/address')
                        ));
                    }
//              return redirect()->back()->with('fail', __('message.Asset not found'));

                    if ($checkAsset > $asset->ecash_wallet) {
                        session()->flash('fail', __('message.Insufficient Ecash Wallet Amount'));
                        return response()->json(array(
                            'status' => 'success',
                            'url' => url('order/address')
                        ));
                    }

//                    return redirect()->back()->with('fail', __('message.Insufficient Ecash Wallet Amount'));
                    $payment_method = ['ecash_wallet' => $checkAsset];
                    break;
            }
        }


        $order = Order::create([
            'user_id' => Auth::user()->id,
            'payment_method' => serialize($payment_method),
            'total_price' => $floatTotal,
            'order_date' => Carbon::now(),
//            'deliver_date' => Carbon::now()->addDays(7)->format('Y-m-d'),
            'order_status_id' => 'confirm',
            'address' => $address,
            'city' => $city,
            'country_id' => $country_id,
            'email' => $email,
            'contact_number' => $contact_number,
//            'invoice_number' => $invoice,
            'delivery_price' => $delivery_price,
            'sub_total' => $total,
            'tax' => $net_tax,
        ]);
        if ($order) {

            foreach ($payment_method as $key => $value) {
                switch ($key) {
                    case 'cash':
                        break;
                    case'ecash_wallet':
                        $asset->update(['ecash_wallet' => ($asset->ecash_wallet - $value)]);
//                        $this->createWalletReport(Auth::id(), $value, 'Product Order on ' . $order->order_date, 'ecash', 'OUT');
                        break;

                    case'evoucher_wallet':
                        $asset->update(['evoucher_wallet' => ($asset->evoucher_wallet - $value)]);
//                        $this->createWalletReport(Auth::id(), $value, 'Product Order on ' . $order->order_date, 'evoucher', 'OUT');
                        break;
                }
            }

            //        Distrubute reward, bonus and commision on each item
            $this->fillOrderItem($order->id);
            $this->removeOrdered();
            $this->stockManage($order->id);
            $this->_data['orders'] = OrderItem::where('order_id', $order->id)->get();

//            Notifiaction
            $this->createNotificaton('admin', Auth::id(), 'New order placed');
            $merchants = [];
            foreach ($order->getOrderItem as $item) {
                $merchant_id = $item->getProduct->getBusiness->getMerchant->id;
                if (!in_array($merchant_id, $merchants)) {
                    $merchants[] = $merchant_id;
                    $this->createNotificaton('merchant', $merchant_id, 'New order placed');
                }
            }

            session()->flash('success', __('message.Order made successfully!'));
            return response()->json(array(
                'status' => 'success',
                'url' => url('order-detail/' . $order->id)
            ));
//         return redirect()->to(route('order-detail', $order->id));
        }
        session()->flash('fail', __('message.Something went wrong'));
        return response()->json(array(
            'status' => 'success',
            'url' => url('order/address')
        ));
//        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    protected function fillOrderItem($order_id)
    {
        $carts = \App\Models\Cart::where('user_id', Auth::id())->where('status', 1)->get();

        foreach ($carts as $sort) {
            $sort['merchant_id'] = $sort->getProduct->getBusiness->id;
            $sort['merchant_slug'] = $sort->getProduct->getBusiness->slug;
        }
        $carts = $carts->sortBy('merchant_id');

        $prev = 0;
        $invoice = 0;
        $asdasd = [];
        foreach ($carts as $item) {
            if ($item->variant_id !== null) {
                $marked_price = $item->getVariant->marked_price;
                $sell_price = $item->getVariant->sell_price;
                $discount = $item->getVariant->discount;
            } else {
                $marked_price = $item->getProduct->marked_price;
                $sell_price = $item->getProduct->sell_price;
                $discount = $item->getProduct->discount;
            }
            if ($prev != $item->merchant_id) {
                $prev = $item->merchant_id;
                $serial = substr(str_pad(OrderItem::orderBy('id', 'DESC')->first()->id ?? 0, 6, '0', STR_PAD_LEFT), -6);
                $invoice = strtoupper(substr(Config::get('app.name'), 0, 3)) . Carbon::now()->format('myd') . '-' . strtoupper(substr($item->merchant_slug, 0, 3)) . $serial;
            }
            $vat = ($item->getProduct->vat / 100) * ($sell_price * $item->quantity);
            $tax = ($item->getProduct->tax / 100) * ($sell_price * $item->quantity);
            $excise = ($item->getProduct->excise / 100) * ($sell_price * $item->quantity);

            $orderItem = OrderItem::create([
                'product_id' => $item->product_id,
                'order_id' => $order_id,
                'product_variant_id' => $item->variant_id,
                'quantity' => $item->quantity,
                'marked_price' => $marked_price,
                'sell_price' => $sell_price,
                'vat' => $vat,
                'tax' => $tax,
                'excise' => $excise,
                'net_tax' => ($vat + $tax + $excise),
                'discount' => $discount,
                'invoice' => $invoice,
                'category_share' => $item->getProduct->getCategory->share_percentage ?? 0,
                'product_share' => $item->getProduct->share_percentage ?? 0,
            ]);

            $this->shoppingLog($orderItem);
        }
    }

    protected function stockManage($order_id, $type = 'down')
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

    protected function removeOrdered()
    {
        $carts = Cart::content();
        foreach ($carts as $rowId => $item) {
            if ($item->options->status) {
                $del = \App\Models\Cart::where('user_id', Auth::user()->id)->where('product_id', $item->id)->where('variant_id', $item->options->variant_id)->first() ?? false;
                if ($del !== false)
                    $del->delete();
                Cart::remove($rowId);
            }
        }
    }

    function orderDetail($id)
    {
        if (Auth::user()) {
            $order = Order::find($id);
            if ($order->user_id !== Auth::user()->id) return redirect()->to('/');
            if ($order === false) return redirect()->to('/');

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
                $sum = 0;
                foreach ($bonus['customer_bonus'] as $customer_bonus) {
                    $sum = $customer_bonus;
                }
            }

            if ($bonus['standard']) {
                $sum1 = 0;
                foreach ($bonus['standard'] as $standard_bonus) {
                    foreach ($standard_bonus as $stan) {
                        if (Auth::id() === $stan['member_id'])
                            $sum1 += $stan['shop_point'];
                    }
                }
            }

            if ($bonus['auto']) {
                $sum2 = 0;
                foreach ($bonus['auto'] as $auto_bonus) {
                    foreach ($auto_bonus as $aut) {
                        if (Auth::id() === $aut['member_id'])
                            $sum2 += $aut['shop_point'];
                    }
                }
            }

            if ($bonus['special']) {
                $sum3 = 0;
                foreach ($bonus['special'] as $special_bonus) {
                    foreach ($special_bonus as $special) {
                        if (Auth::id() === $special['member_id'])
                            $sum3 += $special['shop_point'];
                    }
                }
            }
            $this->_data['bonus'] = collect([
                'customer_bonus' => $sum ?? false,
                'standard' => $sum1 ?? false,
                'auto' => $sum2 ?? false,
                'special' => $sum3 ?? false,
            ]);

            $this->_data['orders'] = $order;
            $items = collect($order->getOrderItem)->groupBy('invoice');
            $this->_data['invoice_items'] = [];
            foreach ($items as $inv => $item) {
                $this->_data['invoice_items'] [] = [
                    'invoice' => $inv,
                    'merchant' => $item[0]->getProduct->getBusiness,
                    'items' => $item
                ];
            }
            return view($this->_path . '.order-view', $this->_data)->with('title', __('message.Golden Gate'));
        }
        return redirect()->to('/');
    }

    function confirmOrder($id)
    {
        Order::find($id)->update(['order_status_id' => 'confirm']);
        return redirect()->to(route('order-detail', Order::find($id)->invoice_number));
    }

    function orderList()
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));

        $this->_data['c_orders'] = Order::where('order_status_id', 'complete')->where('user_id', Auth::user()->id)->latest()->get();
        $this->_data['p_orders'] = Order::where('order_status_id', '!=', 'complete')->where('user_id', Auth::user()->id)->latest()->get();
//        $all_invoice = [];
//        $c_invoice = [];
//
//        foreach ($orders as $order) {
//            foreach ($order->getOrderItem as $item) {
//                $all_invoice[] = $item->invoice;
//                if ($item->order_status_id === 'deliver')
//                    $c_invoice[] = $item->invoice;
//            }
//        }
//
//        $p_invoice = array_diff($all_invoice, $c_invoice);
//
//        $p_orders = OrderItem::whereIn('invoice', $p_invoice)->orderBy('id', 'DESC')->get()->groupby('invoice');
//        $this->_data['p_orders'] = [];
//        foreach ($p_orders as $inv => $p_order) {
//            $this->_data['p_orders'] [] = [
//                'invoice' => $inv,
//                'order' => $p_order[0]->getOrder,
//                'merchant' => $p_order[0]->getProduct->getBusiness,
//                'items' => $p_order
//            ];
//        }
//        $c_orders = OrderItem::whereIn('invoice', $c_invoice)->orderBy('id', 'DESC')->get()->groupby('invoice');
//        $this->_data['c_orders'] = [];
//        foreach ($c_orders as $inv => $c_order) {
//            $this->_data['c_orders'] [] = [
//                'invoice' => $inv,
//                'order' => $c_order[0]->getOrder,
//                'merchant' => $c_order[0]->getProduct->getBusiness,
//                'items' => $c_order
//            ];
//        }


        $this->_data['user'] = User::find(Auth::user()->id);
        return view($this->_path . '.order-list', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function cancelOrder($id)
    {
        Order::find($id)->update(['order_status_id' => 'cancel']);
        $this->stockManage($id, 'up');

        return redirect()->to(route('order-detail', Order::find($id)->invoice_number));
    }

    protected function shoppingLog($orderItem)
    {
//        $this->shoppingLogAfterDeliver($orderItem);
        return true;
    }

}
