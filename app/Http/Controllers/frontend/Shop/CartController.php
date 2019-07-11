<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
//        Cart::destroy();
        $carts = Cart::content();
        $cartsT = Cart::total();;
        var_dump([$carts, $cartsT]);
//        dd($carts);
    }

    public function store(Product $product, Request $request)
    {
//        return response()->json($this->StoreCartDB());
        $duplicates = Cart::search(function ($cartItem, $rowId) use ($product, $request) {
            if ($request->variant !== null)
                return $cartItem->options->variant_id == $request->variant['id'];
            else
                return $cartItem->id == $product->id;
        });

        if ($request->variant !== null) {
            $recent_price = $request->variant['sell_price'];
        } else {
            $recent_price = $product->sell_price;
        }

        //check quantity
        if ($request->variant !== null) {
            $variant = $request->variant;
            $available = $variant['quantity'];
        } else {
            $available = $product->quantity;
        }
        if ($request->quantity > $available) {
            return response()->json(['success' => false, 'message' => ' ' . __('message.Not enough item in stock')]);
        }

        if ($duplicates->isNotEmpty()) {
            foreach ($duplicates as $key => $duplicate)
                $val = $duplicate;
            $update = false;

            //update quantity
            if ($val->qty !== $request->quantity) {
                Cart::update($val->rowId, $request->quantity);
                $update = true;
            }
            if ($update) {
                $this->StoreCartDB();
                return response()->json([
                    'success' => true,
                    'url' => $product->slug,
                    'message' => __('message.Quantity of product updated in your cart')
                ]);
            } else
                return response()->json(['success' => false, 'message' => __('message.Item is already in your cart')]);
        }

        $data = Cart::add($product->id, $product->name, $request->quantity, $recent_price, ['slug' => $product->slug, 'image' => $product->featured_image, 'variant_name' => $request->variant['name'] ?? null, 'variant_id' => $request->variant['id'] ?? null, 'status' => true])->associate('App\Models\Product');
        $this->StoreCartDB();
        return response()->json([
            'success' => true,
            'url' => $data,
            'message' => ' ' . __('message.Item added to your cart')
        ]);
    }

    protected function StoreCartDB()
    {
        $user = Auth::user();
        if ($user !== null) {
            $user_id = Auth::user()->id;
            $carts = Cart::content();

            foreach ($carts as $cart) {
                $matchCartDB = \App\Models\Cart::where('user_id', $user_id)->where('product_id', $cart->id)->where('variant_id', $cart->options->variant_id)->first();

                $input = [
                    'user_id' => $user_id,
                    'product_id' => $cart->id,
                    'quantity' => $cart->qty,
                    'variant_name' => $cart->options->variant_name ?? null,
                    'variant_id' => $cart->options->variant_id ?? null
                ];
                if ($matchCartDB) {
                    $matchCartDB->update($input);
                } else {
                    \App\Models\Cart::create($input);
                }
            }

            $this->refreshCart();
        } else {
            return 'ok';
        }
    }

    function getCartSession()
    {

        $carts = Cart::content();

        $user = Auth::user();
        foreach ($carts as $rowId => $cart) {

            $status = true;
            if ($cart->options->variant_id !== null) {
                if ($cart->qty > ProductVariant::find($cart->options->variant_id)->quantity) {
                    $status = false;
                }
            } else {
                if ($cart->qty > Product::find($cart->id)->quantity) {
                    $status = false;
                }
            }
            Cart::update($rowId, ['options' => [
                'slug' => $cart->options->slug ?? null,
                'image' => $cart->options->image ?? null,
                'variant_name' => $cart->options->variant_name ?? null,
                'variant_id' => $cart->options->variant_id ?? null,
                'status' => $status
            ]]);

            //db quantity status check
            if ($user !== null) {
                $user_id = Auth::user()->id;
                $update = \App\Models\Cart::where('user_id', $user_id)->where('product_id', $cart->id)->where('variant_id', $cart->options->variant_id ?? null)->first();
                if ($update)
                    $update->update(['status' => $status]);
            }
        }

        $this->StoreCartDB();
        $data = [
            'count' => Cart::count(),
            'list' => Cart::content(),
            'total' => Cart::subtotal(),
            'net_total' => Cart::total(),
            'tax' => Cart::tax(),
        ];
        return response()->json($data);
    }

    function removeCartProduct(Request $request)
    {
        $user = Auth::user();
        $removeProd = false;
        if ($user !== null) {
            $user_id = Auth::user()->id;
            $cart = Cart::get($request->row_id);
            $removeProd = \App\Models\Cart::where('user_id', $user_id)->where('product_id', $cart->id)->where('variant_id', $cart->options->variant_id)->first();
        }
        if ($removeProd)
            $removeProd->delete();
        Cart::remove($request->row_id);
        return response()->json(['status' => true]);
    }

    function destroyAll()
    {
        Cart::destroy();
        return redirect()->to(url('/'));
    }

    function checkoutItemUp(Request $request)
    {
        if (empty($request->row_id)) return response()->json(['status' => false]);
        $cartItem = Cart::get($request->row_id);
        if ($cartItem->options->variant_id === null)
            $quantity = Product::find($cartItem->id);
        else
            $quantity = ProductVariant::find($cartItem->options->variant_id);

        $quantity = $quantity->quantity ?? 0;
        if ($request->qty >= $quantity)
            return response()->json(['status' => false]);

        Cart::update($request->row_id, $request->qty + 1);
        $this->StoreCartDB();
        return response()->json(['status' => true]);
    }

    function checkoutItemDown(Request $request)
    {
        if (empty($request->row_id)) return response()->json(['status' => false]);
        if ($request->qty < 2) return response()->json(['status' => false]);
        Cart::update($request->row_id, $request->qty - 1);
        $this->StoreCartDB();
        return response()->json(['status' => true]);
    }

    protected function refreshCart()
    {
        if (Auth::user()) {
            Cart::destroy();
            $DBcarts = \App\Models\Cart::where('user_id', Auth::user()->id)->get() ?? [];
            foreach ($DBcarts as $DBcart) {
                $product = Product::find($DBcart->product_id);

                if ($DBcart->variant_id === null) {
                    $recent_price = $product->sell_price;
                    Cart::add($product->id, $product->name, $DBcart->quantity, $recent_price, ['slug' => $product->slug, 'image' => $product->featured_image, 'variant_name' => null, 'variant_id' => null, 'status' => $DBcart->status])->associate('App\Models\Product');
                } else {
                    $variant = ProductVariant::find($DBcart->variant_id);
                    Cart::add($product->id, $product->name, $DBcart->quantity, $variant->sell_price, ['slug' => $product->slug, 'image' => $product->featured_image, 'variant_name' => $variant->name, 'variant_id' => $variant->id, 'status' => $DBcart->status])->associate('App\Models\Product');
                }
            }
        }
    }


}
