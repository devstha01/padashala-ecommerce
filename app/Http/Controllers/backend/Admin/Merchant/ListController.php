<?php

namespace App\Http\Controllers\backend\Admin\Merchant;

use App\Http\Traits\OrderDeliverTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Library\ShoppingBonus;
use App\Models\Category;
use App\Models\Color;
use App\Models\ColorImage;
use App\Models\Country;
use App\Models\FeatureProduct;
use App\Models\Members\MemberAsset;
use App\Models\Merchant;
use App\Models\MerchantBusiness;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ShippingOrderItem;
use App\Models\Specification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use DomPDF;

class ListController extends Controller
{
    use WalletsHistoryTrait, OrderDeliverTrait;
    private $_path = 'backend.admin.merchant-master.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    function merchantProduct($id)
    {
        $this->_data['merchant'] = Merchant::find($id);

        $merchant_business_id = MerchantBusiness::where('merchant_id', $id)->first()->id;
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

        return view($this->_path . 'profile', $this->_data);
    }

    function listMerchant()
    {
        $this->_data['merchants'] = Merchant::all();
        $this->_data['countries'] = Country::all();
        return view($this->_path . 'merchant-list', $this->_data);
    }

    function listOrder()
    {
        $orders = OrderItem::orderBy('id', 'DESC')->get();
        $items = [];
        $p_items = [];
        $d_items = [];
        foreach ($orders as $order) {
            $items[] = $order;
            if ($order->order_status_id == 'deliver')
                $d_items[] = $order;
            else
                $p_items[] = $order;
        }
        $this->_data['orders'] = collect($items)->groupBy('invoice');
        $this->_data['p_orders'] = collect($p_items)->groupBy('invoice');
        $this->_data['d_orders'] = collect($d_items)->groupBy('invoice');

        return view($this->_path . 'order-list', $this->_data);
    }

    function addProduct($id)
    {
        $this->_data['merchant'] = Merchant::find($id);
        $this->_data['categories'] = Category::where('status', 1)->get() ?? [];
//        $categories = Category::where('status', 1)->get() ?? [];
//        $data = [];
//        foreach ($categories as $category) {
//            $catStatus = false;
//            foreach ($category->getSubCategory as $subCat) {
//                if (count($subCat->getSubChildCategory) !== 0)
//                    $catStatus = true;
//            }
//            if ($catStatus)
//                $data[] = $category;
//        }
//        $this->_data['categories'] = collect($data);
        return view($this->_path . 'create-product', $this->_data);

    }

    function addProductPost($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|not_in:0',
//            'sub_category_id' => 'required|not_in:0',
//            'sub_child_category_id' => 'required|not_in:0',
//            'merchant_business_id' => 'required|not_in:0',
            'featured_image' => 'required',
            'product_share' => 'sometimes|numeric|min:0:|max:100',
//            'marked_price' => 'required|numeric|min:0',
//            'sell_price' => 'required|numeric|min:0',
//            'discount_price' => 'required|numeric|min:0|max:99'
        ]);

        $uniq_slug = false;
        $i = 1;
        $validated['slug'] = str_slug($request->name);
        do {
            $check = Product::where('slug', $validated['slug'])->first();
            if (!$check)
                $uniq_slug = true;
            else
                $validated['slug'] = str_slug($request->name) . '-' . $i;
            $i++;
        } while ($uniq_slug !== true);


        $detail = '<ul>';
        foreach ($request->detailName as $key => $value) {
            $detail .= "<li><b>"
                . ($request->detailName[$key] ?? '') .
                "</b>"
                . ($request->detailValue[$key] ?? '') .
                "</li>";
        }
        $detail .= "</ul>";

        $validated['sub_category_id'] = $request->sub_category_id;
        $validated['sub_child_category_id'] = $request->sub_child_category_id;
        $validated['detail'] = $detail;
        $validated['description'] = $request->description;
        $validated['marked_price'] = 0;
        $validated['sell_price'] = 0;
        $validated['discount'] = 0;
        $validated['quantity'] = 0;
        $validated['tax'] = $request->tax ?? 0;
        $validated['vat'] = $request->vat ?? 0;
        $validated['excise'] = $request->excise ?? 0;
        $validated['merchant_business_id'] = MerchantBusiness::where('merchant_id', $id)->first()->id;

//        admin share
        $validated['share_percentage'] = $request->product_share;

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $validated['featured_image'] = md5(time() . $image->getClientOriginalName()) . '.png';
//            . $image->getClientOriginalExtension();
            $destinationPath = public_path('image/products');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($image->getRealPath());
            $img->crop($request->w1, $request->h1, $request->x1, $request->y1)->resize(800, 800)->save($destinationPath . '/' . $validated['featured_image']);
        }

        $count = count($request->color ?? []);
        if ($prod = Product::create($validated)) {
            for ($i = 0; $i < $count; $i++) {
                $options = [
                    'name' => 'Color:' . Color::find($request->color[$i])->name . ' | Size: ' . $request->size[$i],
                    'color_id' => $request->color[$i],
                    'size' => $request->size[$i],
                    'marked_price' => ($request->marked_price[$i] ?? $request->sell_price[$i]),
                    'sell_price' => $request->sell_price[$i],
                    'discount' => $request->discount_price[$i] ?? 0,
                    'quantity' => $request->quantity[$i],
                    'stock_option' => ($request->stock_option[$i] == 'true') ? 1 : 0,
                    'product_id' => $prod->id,
                ];
                ProductVariant::create($options);
            }
            return redirect()->to(route('admin-edit-product', $prod->slug))->with('success', __('message.Product created successfully'));
        }
        return redirect()->back()->with('fail', __('message.Failed to create Product'));
    }

    function editProductGeneralTab($slug)
    {
        return $this->edit($slug);
    }

    function editProductImageTab($slug)
    {
        session()->flash('active', 'image');
        return $this->edit($slug);
    }

    function editProductVariantTab($slug)
    {
        session()->flash('active', 'variant');
        return $this->edit($slug);
    }

    function editProductSpecsTab($slug)
    {
        session()->flash('active', 'specs');
        return $this->edit($slug);
    }


    function edit($slug)
    {
        // $this->_data['merchant'] = Merchant::where('slug',$slug)->first();
        $this->_data['categories'] = Category::where('status', 1)->get() ?? [];
//        $categories = Category::where('status', 1)->get() ?? [];
//        $data = [];
//        foreach ($categories as $category) {
//            $catStatus = false;
//            foreach ($category->getSubCategory as $subCat) {
//                if (count($subCat->getSubChildCategory) !== 0)
//                    $catStatus = true;
//            }
//            if ($catStatus)
//                $data[] = $category;
//        }
//        $this->_data['categories'] = collect($data);
        if ($this->_data['product'] = Product::where('slug', $slug)->first()) {
            $this->_data['merchant'] = $this->_data['product']->getBusiness->getMerchant;
            $this->_data['options'] = ProductVariant::where('product_id', $this->_data['product']->id)->where('status', 1)->get()->groupBy('color_id');
            $this->_data['colors'] = ProductVariant::where('product_id', $this->_data['product']->id)->where('status', 1)->pluck('color_id')->toArray();

            $detail = $this->_data['product']->detail;
            $detail = str_replace('<ul>', '', $detail);
            $detail = str_replace('</ul>', '', $detail);
            $detail = explode('</li>', $detail);
            $this->_data['detailName'] = [];
            $this->_data['detailValue'] = [];

            foreach ($detail as $item) {
                if ($item !== '')
                    if (str_contains($item, '<b>')) {
                        $item = str_replace('<li>', '', $item);
                        $item = explode('</b>', $item);
                        $this->_data['detailName'] [] = str_replace('<b>', '', $item[0] ?? '');
                        $this->_data['detailValue'] [] = $item[1] ?? '';
                    } else {
                        $this->_data['detailName'] [] = '';
                        $this->_data['detailValue'] [] = $item;
                    }
            }


            switch (session('active')) {
                case 'image':
                    return view($this->_path . '.tab-content.edit-product-image', $this->_data);
                    break;
                case 'variant':
                    return view($this->_path . '.tab-content.edit-product-variant', $this->_data);
                    break;
                case 'specs':
                    return view($this->_path . '.tab-content.edit-product-specs', $this->_data);
                    break;
                default:
                    return view($this->_path . '.tab-content.edit-product', $this->_data);
                    break;
            }

//            return view($this->_path . '.edit-product', $this->_data);
        }
        return redirect()->to(route('merchant-list-admin'));
    }

    function editProductPost($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|not_in:0',
//            'sub_category_id' => 'required|not_in:0',
//            'sub_child_category_id' => 'required|not_in:0',
//            'merchant_business_id' => 'required|not_in:0',
            'product_share' => 'sometimes|numeric|min:0|max:100',
//            'marked_price' => 'required|numeric|min:0',
//            'sell_price' => 'required|numeric|min:0',
//            'discount_price' => 'required|numeric|min:0|max:99'
        ]);

        $detail = '<ul>';
        foreach ($request->detailName as $key => $value) {
            $detail .= "<li><b>"
                . ($request->detailName[$key] ?? '') .
                "</b>"
                . ($request->detailValue[$key] ?? '') .
                "</li>";
        }
        $detail .= "</ul>";


        $validated['sub_category_id'] = $request->sub_category_id;
        $validated['sub_child_category_id'] = $request->sub_child_category_id;
        $validated['detail'] = $detail;
        $validated['description'] = $request->description;
//        $validated['marked_price'] = number_format($request->marked_price ?? 0, 2, '.', '');
//        $validated['sell_price'] = number_format($request->sell_price ?? 0, 2, '.', '');
//        $validated['discount'] = number_format($request->discount_price ?? 0, 2, '.', '');
//        $validated['quantity'] = $request->quantity;

        //        admin share
        $validated['share_percentage'] = $request->product_share;
        $validated['tax'] = $request->tax ?? 0;
        $validated['vat'] = $request->vat ?? 0;
        $validated['excise'] = $request->excise ?? 0;

        $prod = Product::find($id);

        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $validated['featured_image'] = md5(time() . $image->getClientOriginalName()) . '.png';
//            . $image->getClientOriginalExtension();

//            $validated['featured_image'] = time() . '.' . $image->getClientOriginalName();
            $destinationPath = public_path('image/products');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            $img = Image::make($image->getRealPath());
            $img->crop($request->w1, $request->h1, $request->x1, $request->y1)->resize(800, 800)->save($destinationPath . '/' . $validated['featured_image']);

            $old_img = public_path('image/products/' . $prod->featured_image);
            if (File::exists($old_img)) {
                File::delete($old_img);
            }
        }
        if ($prod->update($validated)) {
            return redirect()->back()->with('success', __('message.Product updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function addProductImages(Request $request)
    {
        session()->flash('active', 'image');
        if (!isset($request->id))
            return redirect()->back();
        $input['product_id'] = $request->id;
        if ($request->hasFile('image')) {
            foreach ($request->image as $image) {
                $destinationPath = public_path('image/products');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $input['image'] = md5(time() . $image->getClientOriginalName()) . '.png';

                $img = Image::make($image->getRealPath());
                $img->crop($request->w1, $request->h1, $request->x1, $request->y1)->resize(800, 800)->save($destinationPath . '/' . $input['image']);

                ProductImage::create($input);
            }
            return redirect()->back()->with('success', __('message.Image updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Image field is required'));
    }


    function addProductVariant($id, Request $request)
    {
        session()->flash('active', 'variant');
        $count = count($request->color ?? []);
        for ($i = 0; $i < $count; $i++) {
            $options = [
                'name' => 'Color:' . Color::find($request->color[$i])->name . ' | Size: ' . $request->size[$i],
                'color_id' => $request->color[$i],
                'size' => $request->size[$i],
                'marked_price' => ($request->marked_price[$i] ?? $request->sell_price[$i]),
                'sell_price' => $request->sell_price[$i],
                'discount' => $request->discount_price[$i] ?? 0,
                'quantity' => $request->quantity[$i],
                'stock_option' => ($request->stock_option[$i] == 'true') ? 1 : 0,
                'product_id' => $id,
            ];
            ProductVariant::create($options);
        }
        return redirect()->back()->with('success', __('message.Product options added successfully'));
    }

    function updateProductVariant(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'option_id' => 'required',
//            'marked_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
//            'discount_price' => 'required|numeric|min:0|max:99',
            'quantity' => 'required|numeric|min:0',
        ]);
        if ($valid->fails()) return response()->json(['status' => false, 'message' => $valid->errors()->first()]);
        $variant = ProductVariant::find($request->option_id);
        if (!$variant) return response()->json(['status' => false, 'message' => 'Invalid option!']);
        $variant->update([
            'name' => 'Color:' . Color::find($variant->color_id)->name . ' | Size: ' . $request->size,
            'size' => $request->size,
            'quantity' => $request->quantity,
            'marked_price' => ($request->marked_price ?? $request->sell_price),
            'sell_price' => $request->sell_price,
            'stock_option' => ($request->stock_option == 'true') ? 1 : 0,
            'discount' => $request->discount_price ?? 0,
        ]);
        if ($variant) return response()->json(['status' => true, 'message' => 'Option updated!']);
    }

    function editProductVariant(Request $request)
    {
        session()->flash('active', 'variant');
        if (!isset($request->id))
            return redirect()->back();

        $validate = Validator::make($request->all(), [
            'name' => 'required',
//            'marked_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
//            'discount_price' => 'required|numeric|min:0|max:99'
        ]);

        if ($validate->fails()) return redirect()->back()->with('fail', $validate->errors()->first());

        $input = [
            'name' => $request->name,
            'marked_price' => ($request->marked_price ?? $request->sell_price),
            'sell_price' => $request->sell_price,
            'discount' => $request->discount_price ?? 0,
            'stock_option' => ($request->stock_option == 'true') ? 1 : 0,
            'quantity' => $request->quantity,
        ];
        $var = ProductVariant::find($request->id);
        if ($var->update($input))
            return redirect()->back()->with('success', __('message.Product variant updated successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }


    function updateProductVariantImage(Request $request)
    {
        session()->flash('active', 'variant');
        $validated = $request->validate([
            'color_id' => 'required',
            'product_id' => 'required',
            'image' => 'required',
        ]);


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validated['image'] = md5(time() . $image->getClientOriginalName()) . '.png';
//            . $image->getClientOriginalExtension();
            $destinationPath = public_path('image/products/color');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }

            $img = Image::make($image->getRealPath());
            $img->crop($request->w1, $request->h1, $request->x1, $request->y1)->resize(800, 800)->save($destinationPath . '/' . $validated['image']);

            $update = ColorImage::where('product_id', $request->product_id)->where('color_id', $request->color_id)->first();
            if (!$update)
                ColorImage::create($validated);
            else {
                $old_img = public_path('image/products/color' . $update->image);
                if (File::exists($old_img)) {
                    File::delete($old_img);
                }
                $update->update($validated);
            }
            return redirect()->back()->with('success', __('message.Product Image added successfully'));
        }
        return redirect()->back()->with('fail', 'Something went wrong');
    }

    function deleteProductVariant($id)
    {
        session()->flash('active', 'variant');
        if (ProductVariant::find($id)->update(['quantity' => 0, 'status' => 0]))
            return redirect()->back()->with('success', __('message.Product variant removed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

//    change status
    function deleteProduct($id)
    {
        $prod = Product::find($id);
        if (!$prod->admin_flag)
            $prod->update(['admin_flag' => 1]);
        if ($prod->update(['quantity' => 0, 'status' => (($prod->status == 1) ? 0 : 1)]))
            return redirect()->back()->with('success', __('message.Product status updated successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function deleteProductImage($id)
    {
        session()->flash('active', 'image');
        $prodImg = ProductImage::find($id);
        $img = public_path('image/products/' . $prodImg->image);
        if (File::exists($img)) {
            File::delete($img);
        }
        if ($prodImg->delete())
            return redirect()->back()->with('success', __('message.Product Image removed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }


    public
    function orderdetails($id, $m_id)
    {
        $order = Order::find($id);
        $this->_data['merchant'] = Merchant::find($m_id);
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
            if ($m_id == $merchant_id) {
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
//            number_format($total * (env('TAX_PERCENT') ?? 0) / 100, 2, '.', '');
        $this->_data['net_total'] = number_format($total + ($tax), 2, '.', '');

        $this->_data['total'] = number_format($total, 2, '.', '');
        return view($this->_path . 'admin-order-details', $this->_data);
    }

    function itemStatusChange($id, Request $request)
    {
        $orderItem = OrderItem::find($id);
        $orderItem->update([
            'payment_status' => $request->payment_status,
            'merchant_status' => $request->merchant_status,
        ]);
        if ($orderItem->order_status_id != 'deliver') {
            if ($request->order_status == 'deliver') {
                $orderItem->update(['order_status_id' => $request->order_status, 'deliver_date' => Carbon::now()]);
                $this->shoppingLogAfterDeliver($orderItem);
//                $this->bonusOnCashDelivery($orderItem);
//                $bonus = new ShoppingBonus();
//                $bonus->assignCashBonus($id);
            } else {
                $orderItem->update(['order_status_id' => $request->order_status]);
            }
        } elseif ($orderItem->order_status_id == 'deliver') {
            if ($request->order_status != 'deliver') {
//                $orderItem->update(['order_status_id' => $request->action, 'deliver_date' => null]);
//                $this->commisionAfterReturn($id);
            }
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

        return redirect()->back();
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
                if ($request->merchant_id == $merchant_id) {
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

    function featuredProductRequest($id)
    {
        $check = Product::find($id);
        if (!$check)
            return redirect()->back();

        FeatureProduct::create([
            'product_id' => $id,
            'admin_id' => 1,
            'feature_from' => Carbon::now()->toDateString(),
            'feature_till' => Carbon::now()->addDays(5)->toDateString(),
        ]);
        return redirect()->back()->with('success', __('message.Feature request sent'));
    }

    function productApprovalList()
    {
        $this->_data['products'] = Product::where('admin_flag', 0)->get();
        return view($this->_path . 'approval-list', $this->_data);
    }

    function approveProduct($id)
    {
        if (Product::find($id)->update(['admin_flag' => 1, 'status' => 1]))
            return redirect()->back()->with('success', 'Product approved successfully');
        return redirect()->back()->with('fail', 'Something went wrong');
    }

    function deleteProductBefore($id)
    {
        if (Product::find($id)->update(['admin_flag' => 1, 'status' => 0]))
            return redirect()->back()->with('success', 'Product declined successfully');
        return redirect()->back()->with('fail', 'Something went wrong');
    }


//invoice
    function orderInvoice($id, $m_id)
    {

        $order = Order::find($id);
        $this->_data['merchant'] = Merchant::find($m_id);
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
        foreach ($order->getOrderItem as $item) {
            $merchant_id = $item->getProduct->getBusiness->getMerchant->id;
            if ($m_id == $merchant_id) {
                $item['net_price'] = number_format($item->quantity * $item->sell_price, 2, '.', '');
                $orderItem[] = $item;
                $total += $item['net_price'];
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
        $this->_data['tax'] = number_format($total * (env('TAX_PERCENT') ?? 0) / 100, 2, '.', '');
        $this->_data['net_total'] = number_format($total + ($total * (env('TAX_PERCENT') ?? 0) / 100) + ($count * (env('DELIVERY_COST') ?? 0)), 2, '.', '');

        $this->_data['total'] = number_format($total, 2, '.', '');
//        $pdf = DomPDF::loadView('pdf.invoice', $this->_data);
//        return $pdf->download('invoice.pdf');
        return view('pdf.invoice', $this->_data);
    }


//    specifiaction

    function addSpecs($id, Request $request)
    {
        Specification::create([
            'product_id' => $id,
            'name' => $request->name,
            'detail' => $request->detail,
        ]);
        return redirect()->back()->with('success', 'Product specification added');
    }

    function updateSpecs($id, Request $request)
    {
        $spec = Specification::find($id);
        if ($spec)
            $spec->update([
                'name' => $request->name,
                'detail' => $request->detail,
            ]);
        return redirect()->back()->with('success', 'Product specification updated');
    }

    function deleteSpecs($id)
    {
        $spec = Specification::find($id);
        if ($spec)
            $spec->delete();
        return redirect()->back()->with('success', 'Product specification removed');
    }


    function merchantStandardProduct($id)
    {
        $this->_data['merchant'] = Merchant::find($id);
        $this->_data['products'] = Product::where('standard_product', 1)->where('admin_flag', 1)->where('status', 1)->get();

        return view($this->_path . 'view-standard', $this->_data);
    }

    function merchantStandardProductPost($merchant_id,$id)
    {
        $business_id = MerchantBusiness::where('merchant_id', $merchant_id)->first()->id ?? false;
        if (!$business_id) return redirect()->back();
        $product = Product::find($id);
        $product->merchant_business_id = $business_id;
        $uniq_slug = false;
        $i = 1;
        $slug = str_slug($product->name);
        do {
            $check = Product::where('slug', $slug)->first();
            if (!$check)
                $uniq_slug = true;
            else
                $slug = str_slug($product->name) . '-' . $i;
            $i++;
        } while ($uniq_slug !== true);

        $product->slug = $slug;
        $product->admin_flag = 0;
        $product->is_featured = 0;
        $product->created_at = Carbon::now();
        $product->updated_at = Carbon::now();
        $product->standard_product = 0;

        if (Product::create($product->toArray()))
            return $this->edit($slug)->with('success','Product created successfully');
        return redirect()->back();

    }
}
