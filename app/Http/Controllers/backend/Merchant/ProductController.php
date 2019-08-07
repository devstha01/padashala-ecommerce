<?php

namespace App\Http\Controllers\backend\Merchant;

use App\ConvertCurrencyFacade;
use App\Models\Category;
use App\Models\Color;
use App\Models\ColorImage;
use App\Models\Merchant;
use App\Models\MerchantBusiness;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\CHProduct;
use App\Models\CHProductVariant;
use App\Models\TRCHProduct;
use App\models\TRCHProductVariant;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use Carbon\Carbon;
use App\Models\FeatureProduct;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use App\Models\FlashSale;


class ProductController extends Controller
{
    private $_data = [];
    private $_path = 'backend.merchant.product.';
    private $_merchant_id = '';

    public function __construct()
    {
        $this->middleware('merchant');
        $this->middleware(function ($request, $next) {
            $this->_merchant_id = Auth::guard('merchant')->user()->id;
            return $next($request);
        });
    }
//
//    function purchaseOption(Request $request)
//    {
//        $option = ($request->delivery_option == 'true') ? 1 : 0;
//        $merchant = Merchant::find($this->_merchant_id);
//        $merchant->update(['purchase_option' => $option]);
//        $products = $merchant->getBusiness->getProducts;
//        foreach ($products as $item)
//            $item->update(['delivery_option' => $option]);
//        return redirect()->back()->with('info', 'Purchase option updated');
//    }


    function viewProduct()
    {
        $business_id = MerchantBusiness::where('merchant_id', $this->_merchant_id)->first()->id ?? false;
        if (!$business_id) return redirect()->back();
        $this->_data['products'] = Product::where('merchant_business_id', $business_id)->where('admin_flag', 1)->where('status', 1)->get();
        return view($this->_path . 'view-product', $this->_data);
    }



    function viewProductRequest()
    {
        $business_id = MerchantBusiness::where('merchant_id', $this->_merchant_id)->first()->id ?? false;
        if (!$business_id) return redirect()->back();
        $this->_data['products'] = Product::where('merchant_business_id', $business_id)->where('admin_flag', 0)->get();
        return view($this->_path . 'request-product', $this->_data);
    }


    function createProduct()
    {
        $this->_data['merchant'] = Merchant::find($this->_merchant_id);
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

    function editProductGeneralTab($slug)
    {
        return $this->editProduct($slug);
    }

    function editProductImageTab($slug)
    {
        session()->flash('active', 'image');
        return $this->editProduct($slug);
    }

    function editProductVariantTab($slug)
    {
        session()->flash('active', 'variant');
        return $this->editProduct($slug);
    }

    function editProduct($slug)
    {
        $this->_data['merchant'] = Merchant::find($this->_merchant_id);
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
            $this->_data['options'] = ProductVariant::where('product_id', $this->_data['product']->id)->where('status', 1)->get()->groupBy('color_id');
            $this->_data['colors'] = ProductVariant::where('product_id', $this->_data['product']->id)->where('status', 1)->pluck('color_id')->toArray();

            switch (session('active')) {
                case 'image':
                    return view($this->_path . 'tab-content.edit-product-image', $this->_data);
                    break;
                case 'variant':
                    return view($this->_path . 'tab-content.edit-product-variant', $this->_data);
                    break;
                default:
                    return view($this->_path . 'tab-content.edit-product', $this->_data);
                    break;
            }
        }
        return redirect()->to(route('create-product-merchant'));
    }


    function createProductFirst(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'category_id' => 'required|not_in:0',
            'detail' => 'max:100',
//            'sub_category_id' => 'required|not_in:0',
//            'sub_child_category_id' => 'required|not_in:0',
//            'merchant_business_id' => 'required|not_in:0',
            'featured_image' => 'required',
            'product_share' => 'required|numeric|min:0|max:100',

//            'delivery_option' => 'required',
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

        $validated['sub_category_id'] = $request->sub_category_id;
        $validated['sub_child_category_id'] = $request->sub_child_category_id;
        $validated['detail'] = $request->detail;
        $validated['description'] = $request->description;
        $validated['marked_price'] = 0;
        $validated['sell_price'] = 0;
        $validated['discount'] = 0;
        $validated['quantity'] = 0;
        $validated['merchant_business_id'] = MerchantBusiness::where('merchant_id', $this->_merchant_id)->first()->id;

        $validated['delivery_option'] = Merchant::find($this->_merchant_id)->purchase_option ?? 1;
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

            //
//            if ($img->height() > $img->width()) {
//                $img = $img->resize(null, 800, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//            } else {
//                $img = $img->resize(800, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//            }
//            $saveimage = Image::canvas(800, 800)->insert($img, 'center');
//            $saveimage->save($destinationPath . '/' . $validated['featured_image']);
        }

        $count = count($request->color ?? []);
        if ($prod = Product::create($validated)) {
            for ($i = 0; $i < $count; $i++) {
                $options = [
                    'name' => 'Color:' . Color::find($request->color[$i])->name . ' | Size: ' . $request->size[$i],
                    'color_id' => $request->color[$i],
                    'size' => $request->size[$i],
                    'marked_price' => ($request->marked_price[$i] ?? $request->sell_price[$i]),
                    'sell_price' => ($request->sell_price[$i]),
                    'discount' => $request->discount_price[$i] ?? 0,
                    'quantity' => $request->quantity[$i] ?? 0,
                    'stock_option' => ($request->stock_option[$i] == 'true') ? 1 : 0,
                    'product_id' => $prod->id,
                ];
                ProductVariant::create($options);
            }
            return redirect()->to(route('edit-product-merchant', $prod->slug))->with('success', __('message.Product created successfully'));
        }
        return redirect()->back()->with('fail', __('message.Failed to create Product'));
    }

    function editProductPost($id, Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
//            'delivery_option' => 'required',
            'category_id' => 'required|not_in:0',
            'detail' => 'max:100',
//            'sub_category_id' => 'required|not_in:0',
//            'sub_child_category_id' => 'required|not_in:0',
//            'merchant_business_id' => 'required|not_in:0',
//            'marked_price' => 'required|numeric|min:0',
//            'sell_price' => 'required|numeric|min:0',
//            'discount_price' => 'required|numeric|min:0|max:99'
        ]);

        $validated['sub_category_id'] = $request->sub_category_id;
        $validated['sub_child_category_id'] = $request->sub_child_category_id;
        $validated['detail'] = $request->detail;
        $validated['description'] = $request->description;
//        $validated['marked_price'] = number_format($request->marked_price ?? 0, 2, '.', '');
//        $validated['sell_price'] = number_format($request->sell_price ?? 0, 2, '.', '');
//        $validated['discount'] = number_format($request->discount_price ?? 0, 2, '.', '');
//        $validated['quantity'] = $request->quantity;
//        $validated['delivery_option'] = Merchant::find($this->_merchant_id)->purchase_option ?? 1;

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
            $img
                ->crop($request->w1, $request->h1, $request->x1, $request->y1)
                ->resize(800, 800)
                ->save($destinationPath . '/' . $validated['featured_image']);
//            $img = Image::make($image->getRealPath());
//
//            if ($img->height() > $img->width()) {
//                $img = $img->resize(null, 800, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//            } else {
//                $img = $img->resize(800, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//            }
//            $saveimage = Image::canvas(800, 800)->insert($img, 'center');
//            $saveimage->save($destinationPath . '/' . $validated['featured_image']);

            $old_img = public_path('image/products/' . $prod->featured_image);
            if (File::exists($old_img)) {
                File::delete($old_img);
            }
        }
        if ($prod->update($validated)) {
            return redirect()->to(route('edit-product-merchant', $prod->slug))->with('success', __('message.Product updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Failed to update Product'));

    }

    function addProductImages(Request $request)
    {
        session()->flash('active', 'image');
        if (!isset($request->id))
            return redirect()->back();
        $input['product_id'] = $request->id;
        if ($request->hasFile('image')) {
            $image = $request->image;
//            foreach ($request->image as $image) {
            $destinationPath = public_path('image/products');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $input['image'] = md5(time() . $image->getClientOriginalName()) . '.png';
//                . $image->getClientOriginalExtension();

            $img = Image::make($image->getRealPath());
            $img->crop($request->w1, $request->h1, $request->x1, $request->y1)->resize(800, 800)->save($destinationPath . '/' . $input['image']);

//            $img = Image::make($image->getRealPath());
//
//            if ($img->height() > $img->width()) {
//                $img = $img->resize(null, 800, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//            } else {
//                $img = $img->resize(800, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//            }
//            $saveimage = Image::canvas(800, 800)->insert($img, 'center');
//            $saveimage->save($destinationPath . '/' . $input['image']);

            ProductImage::create($input);
//            }
            return redirect()->back()->with('success', __('message.Images added successfully'));
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
                'sell_price' => ($request->sell_price[$i]),
                'discount' => $request->discount_price[$i] ?? 0,
                'quantity' => $request->quantity[$i] ?? 0,
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
            'quantity' => 'sometimes|numeric|min:0',
        ]);
        if ($valid->fails()) return response()->json(['status' => false, 'message' => $valid->errors()->first()]);
        $variant = ProductVariant::find($request->option_id);
        if (!$variant) return response()->json(['status' => false, 'message' => 'Invalid option!']);


        $variant->update([
            'name' => 'Color:' . Color::find($variant->color_id)->name . ' | Size: ' . $request->size,
            'size' => $request->size,
            'quantity' => $request->quantity ?? 0,
            'marked_price' => ($request->marked_price ?? $request->sell_price),
            'sell_price' => ($request->sell_price),
            'discount' => $request->discount_price ?? 0,
            'stock_option' => ($request->stock_option == 'true') ? 1 : 0,
        ]);

        if ($variant) return response()->json(['status' => true, 'message' => 'Option updated!']);
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

//            $img = Image::make($image->getRealPath());
//
//            if ($img->height() > $img->width()) {
//                $img = $img->resize(null, 800, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//            } else {
//                $img = $img->resize(800, null, function ($constraint) {
//                    $constraint->aspectRatio();
//                });
//            }
//            $saveimage = Image::canvas(800, 800)->insert($img, 'center');
//            $saveimage->save($destinationPath . '/' . $validated['image']);

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
//
//    function editProductVariant(Request $request)
//    {
//        session()->flash('active', 'variant');
//        if (!isset($request->id))
//            return redirect()->back();
//
//        $validate = Validator::make($request->all(), [
//            'name' => 'required',
////            'marked_price' => 'required|numeric|min:0',
//            'sell_price' => 'required|numeric|min:0',
////            'discount_price' => 'required|numeric|min:0|max:99'
//        ]);
//
//        if ($validate->fails()) return redirect()->back()->with('fail', $validate->errors()->first());
//
//        $input = [
//            'name' => $request->name,
//            'marked_price' => number_format($request->marked_price ?? $request->sell_price, 2, '.', ''),
//            'sell_price' => number_format($request->sell_price ?? 0, 2, '.', ''),
//            'discount' => number_format($request->discount_price ?? 0, 2, '.', ''),
//            'quantity' => $request->quantity ?? 0,
//        ];
//        $var = ProductVariant::find($request->id);
//        if ($var->update($input))
//            return redirect()->back()->with('success', __('message.Product variant updated successfully'));
//        return redirect()->back()->with('fail', __('message.Something went wrong'));
//    }

    function deleteProductVariant($id)
    {
        session()->flash('active', 'variant');
        if (ProductVariant::find($id)->update(['quantity' => 0, 'status' => 0]))
            return redirect()->back()->with('success', __('message.Product variant removed successfully'));
        return redirect()->back()->with('fail', 'Something went wrong');
    }

    function deleteProduct($id)
    {
        $prod = Product::find($id);
        if (!$prod)
            return redirect()->back();
        if ($prod->update(['quantity' => 0, 'status' => 0]))
            return redirect()->back()->with('success', __('message.Product removed successfully'));
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
            return redirect()->back()->with('success', __('message.Product image removed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }


    public function merchantRequest($id)
    {
        $check = Product::find($id);
        if (!$check)
            return redirect()->back();

        $feat = FeatureProduct::where('product_id', $id)->first();
        if (!$feat)
            FeatureProduct::create([
                'product_id' => $id,
                'admin_id' => 1,
                'feature_from' => Carbon::now()->toDateString(),
                'feature_till' => Carbon::now()->addDays(5)->toDateString(),
                'flag' => 0
            ]);
        else
            $feat->update([
                'feature_from' => Carbon::now()->toDateString(),
                'feature_till' => Carbon::now()->addDays(5)->toDateString(),
                'flag' => 0
            ]);
        return redirect()->back()->with('success', __('message.Feature request sent'));
    }
}