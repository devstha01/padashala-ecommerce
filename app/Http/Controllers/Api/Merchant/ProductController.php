<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Models\Category;
use App\Models\CHProduct;
use App\Models\CHProductVariant;
use App\Models\FeatureProduct;
use App\Models\MerchantBusiness;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use App\Models\TRCHProduct;
use App\Models\TRCHProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    function addProduct(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'name' => 'required',
                'detail' => 'required',
                'description' => 'required',
//                'category_id' => 'required',
//            'sub_category_id' => 'required|not_in:0',
//            'sub_child_category_id' => 'required|not_in:0',
                'featured_image' => 'required',
                'marked_price' => 'required|numeric|min:0',
                'sell_price' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0|max:99',
                'quantity' => 'required|numeric|min:0'
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);


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

            $validated['name'] = $request->name;
            $validated['category_id'] = $request->category_id ?? 1;
            $validated['sub_category_id'] = $request->sub_category_id ?? null;
            $validated['sub_child_category_id'] = $request->sub_child_category_id ?? null;
            $validated['detail'] = $request->detail;
            $validated['description'] = $request->description;
            $validated['marked_price'] = number_format($request->marked_price ?? 0, 2, '.', '');
            $validated['sell_price'] = number_format($request->sell_price ?? 0, 2, '.', '');
            $validated['discount'] = $request->discount ?? 0;
            $validated['quantity'] = $request->quantity ?? 0;
            $validated['merchant_business_id'] = MerchantBusiness::where('merchant_id', $merchant['data']['id'])->first()->id;

            $imageUploadStatus = $this->uploadProductImage($request->featured_image);
            if ($imageUploadStatus['status'] === false) return $imageUploadStatus;
            $validated['featured_image'] = $imageUploadStatus['image'];

            if ($prod = Product::create($validated))
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product created successfully',
                    'data' => $this->productFormat($prod)]);
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);
    }


    function viewProduct(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $business_id = MerchantBusiness::where('merchant_id', $merchant['data']['id'])->first()->id;
            $products = Product::where('merchant_business_id', $business_id)->where('status', 1)->get();
            foreach ($products as $product) {
                $product['image_link'] = url('image/products/' . $product->featured_image);

                if ($product->is_featured)
                    $featStatus = ['request' => false, 'name' => 'Featured'];
                else {
                    $feat = FeatureProduct::where('product_id', $product->id)->first();
                    if (!$feat)
                        $featStatus = ['request' => true, 'name' => 'Request'];
                    elseif (!$feat->flag)
                        $featStatus = ['request' => false, 'name' => 'Pending'];
                    else
                        $featStatus = ['request' => true, 'name' => 'Request'];
                }
                $product['feature'] = $featStatus;

            }
            $merchant['data'] = $products;
        }
        return response()->json($merchant);
    }


    function editProduct(Request $request, $id = null)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'name' => 'required',
                'detail' => 'required',
                'description' => 'required',
                'category_id' => 'required',
//            'sub_category_id' => 'required|not_in:0',
//            'sub_child_category_id' => 'required|not_in:0',
//                'featured_image' => 'required',
                'marked_price' => 'required|numeric|min:0',
                'sell_price' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0|max:99',
                'quantity' => 'required|numeric|min:0'
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);

            if ($id === null) {
                $valid1 = Validator::make($request->all(), [
                    'product_id' => 'required'
                ]);

                if ($valid1->fails())
                    return response()->json(['status' => false, 'message' => 422, 'error' => $valid1->errors()->first()]);


                $product = Product::find($request->product_id);
                if (!$product)
                    return response()->json(['status' => false, 'message' => 404, 'error' => 'Product not found']);
            } else {
                $product = Product::find($id);
                if (!$product)
                    return response()->json(['status' => false, 'message' => 404, 'error' => 'Product not found']);
            }
            $validated['name'] = $request->name;
            $validated['category_id'] = $request->category_id;
            $validated['sub_category_id'] = $request->sub_category_id ?? null;
            $validated['sub_child_category_id'] = $request->sub_child_category_id ?? null;
            $validated['detail'] = $request->detail;
            $validated['description'] = $request->description;
            $validated['marked_price'] = number_format($request->marked_price ?? 0, 2, '.', '');
            $validated['sell_price'] = number_format($request->sell_price ?? 0, 2, '.', '');
            $validated['discount'] = $request->discount ?? 0;
            $validated['quantity'] = $request->quantity ?? 0;

            if (!empty($request->featured_image)) {
                $imageUploadStatus = $this->uploadProductImage($request->featured_image);
                if ($imageUploadStatus['status'] === false) return $imageUploadStatus;
                $validated['featured_image'] = $imageUploadStatus['image'];

                $old_img = public_path('image/products/' . $product->featured_image);
                if (File::exists($old_img)) {
                    File::delete($old_img);
                }
            }
            if ($product->update($validated)) {
                $prod = Product::find($product->id);
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product updated successfully',
                    'data' => $this->productFormat($prod)]);
            }
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);
    }

    function addProductImages($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'image' => 'required',
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);

            $imageUploadStatus = $this->uploadProductImage($request->image);
            if ($imageUploadStatus['status'] === false) return $imageUploadStatus;
            $input['product_id'] = $id;
            $input['image'] = $imageUploadStatus['image'];

            if (ProductImage::create($input))
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Image uploaded']);
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);
    }

    function addProductVariant($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'name' => 'required',
                'marked_price' => 'required|numeric|min:0',
                'sell_price' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0|max:99'
            ]);

            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);

            $input = [
                'product_id' => $id,
                'name' => $request->name,
                'marked_price' => number_format($request->marked_price ?? 0, 2, '.', ''),
                'sell_price' => number_format($request->sell_price ?? 0, 2, '.', ''),
                'discount' => $request->discount ?? 0,
                'quantity' => $request->quantity ?? 0,
            ];
            if (ProductVariant::create($input))
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product variant added']);

            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);
    }

    function editProductVariant($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'name' => 'required',
                'marked_price' => 'required|numeric|min:0',
                'sell_price' => 'required|numeric|min:0',
                'discount' => 'required|numeric|min:0|max:99'
            ]);

            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);

            $variant = ProductVariant::find($id);
            if (!$variant)
                return response()->json(['status' => false, 'message' => 404, 'error' => 'Variant not found']);
            $input = [
                'name' => $request->name,
                'marked_price' => number_format($request->marked_price ?? 0, 2, '.', ''),
                'sell_price' => number_format($request->sell_price ?? 0, 2, '.', ''),
                'discount' => $request->discount ?? 0,
                'quantity' => $request->quantity ?? 0,
            ];
            if ($variant->update($input))
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product variant updated']);
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);
    }

    function deleteProductVariant($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            if (ProductVariant::find($id)->update(['quantity' => 0, 'status' => 0]))
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product variant removed']);
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);

    }

    function deleteProduct($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $prod = Product::find($id);
            if ($prod->update(['quantity' => 0, 'status' => 0]))
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product removed']);
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);
    }

    function deleteProductImage($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $prodImg = ProductImage::find($id);
            $img = public_path('image/products/' . $prodImg->image);
            if (File::exists($img)) {
                File::delete($img);
            }
            if ($prodImg->delete())
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product image removed']);
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);
    }


    public
    function featureProductRequest($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

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
            return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'product feature requested']);
        }
        return response()->json($merchant);
    }


    function editCHProductPost($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $chprod = CHProduct::where('product_id', $id)->first();
            if (!$chprod) {
                CHProduct::create([
                    'product_id' => $id,
                    'name' => $request->name,
                    'detail' => $request->detail,
                    'description' => $request->description]);
            } else {
                $chprod->update([
                    'name' => $request->name,
                    'detail' => $request->detail,
                    'description' => $request->description]);
            }
            return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Chinese product language updated']);
        }
        return response()->json($merchant);
    }


    function editCHProductVariant($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $var = CHProductVariant::where('product_variant_id', $id)->first();
            if (!$var) {
                CHProductVariant::create([
                    'product_variant_id' => $id,
                    'name' => $request->name]);
            } else {
                $var->update(['name' => $request->name]);
            }
            return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Chinese product variant language updated']);
        }
        return response()->json($merchant);

    }

    function editTRCHProductPost($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $chprod = TRCHProduct::where('product_id', $id)->first();
            if (!$chprod) {
                TRCHProduct::create([
                    'product_id' => $id,
                    'name' => $request->name,
                    'detail' => $request->detail,
                    'description' => $request->description]);
            } else {
                $chprod->update([
                    'name' => $request->name,
                    'detail' => $request->detail,
                    'description' => $request->description]);
            }
            return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Traditional Chinese product language updated']);
        }
        return response()->json($merchant);
    }


    function editTRCHProductVariant($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $var = TRCHProductVariant::where('product_variant_id', $id)->first();
            if (!$var) {
                TRCHProductVariant::create([
                    'product_variant_id' => $id,
                    'name' => $request->name]);
            } else {
                $var->update(['name' => $request->name]);
            }
            return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Traditional Chinese product variant language updated']);
        }
        return response()->json($merchant);
    }


    function getCategory()
    {
        $data = Category::all();
        return response()->json(['status' => true, 'message' => 200, 'data' => $data]);
    }

    function getSubCategory(Request $request)
    {
        if (empty($request->category_id))
            $data = [];
        else
            $data = SubCategory::where('category_id', $request->category_id)->where('status', 1)->get() ?? [];
        return response()->json(['status' => true, 'message' => 200, 'data' => $data]);
    }

    function getSubChildCategory(Request $request)
    {
        if (empty($request->sub_category_id))
            $data = [];
        else
            $data = SubChildCategory::where('sub_category_id', $request->sub_category_id)->where('status', 1)->get() ?? false;
        return response()->json(['status' => true, 'message' => 200, 'data' => $data]);
    }

    function detailProduct($id, Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $product = Product::find($id);
            if ($product)
                return response()->json(['status' => true, 'message' => 200, 'data' => $this->productFormat($product)]);
            return response()->json(['status' => false, 'message' => 404, 'error' => 'Product not found']);
        }
        return response()->json($merchant);

    }

//
    protected function productFormat($product)
    {
        $product['featured_image'] = url('image/products/' . $product->featured_image);
        $product->getCategory;
        $product->getSubCategory;
        $product->getSubChildCategory;
        $product->getProductImage;
        $product->getProductVariant;


        if ($product->is_featured)
            $featStatus = ['request' => false, 'name' => 'Featured'];
        else {
            $feat = FeatureProduct::where('product_id', $product->id)->first();
            if (!$feat)
                $featStatus = ['request' => true, 'name' => 'Request'];
            elseif (!$feat->flag)
                $featStatus = ['request' => false, 'name' => 'Pending'];
            else
                $featStatus = ['request' => true, 'name' => 'Request'];
        }
        $product['feature'] = $featStatus;;
        return $product;
    }

//base64 image save
    protected
    function uploadProductImage($image)
    {
        // your base64 encoded

        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            $image = substr($image, strpos($image, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                return ['status' => false, 'message' => 401, 'error' => 'Invalid image type'];
            }
            $image = base64_decode($image);
            if ($image === false) {
                return ['status' => false, 'message' => 401, 'error' => 'base64_decode failed'];
            }
            $image_name = time() . str_random(16) . '.' . $type;

            $destinationPath = public_path('image/products');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }


            $img = Image::make($image);

            if ($img->height() > $img->width()) {
                $img = $img->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $img = $img->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $saveimage = Image::canvas(800, 800)->insert($img, 'center');
            $saveimage->save($destinationPath . '/' . $image_name);

//            File::put($destinationPath . '/' . $image_name, $image);
            return ['status' => true, 'image' => $image_name];
        }
        return ['status' => false, 'message' => 401, 'error' => 'did not match data URI with image data'];
    }


    function addProductMP(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
//            return response()->json($request->all());
            $valid = Validator::make($request->all(), [
                'name' => 'required',
                'detail' => 'required',
                'description' => 'required',
//                'category_id' => 'required',
//            'sub_category_id' => 'required|not_in:0',
//            'sub_child_category_id' => 'required|not_in:0',
                'featured_image' => 'required',
                'marked_price' => 'required',
                'sell_price' => 'required',
                'discount' => 'required',
                'quantity' => 'required'
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);
            if ($response = $this->numericMinMaxValidation('marked price', $request->marked_price, 0)) return $response;
            if ($response = $this->numericMinMaxValidation('sell price', $request->sell_price, 0)) return $response;
            if ($response = $this->numericMinMaxValidation('discount', $request->discount, 0, 100)) return $response;
            if ($response = $this->numericMinMaxValidation('quantity', $request->quantity, 0)) return $response;

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

            $validated['name'] = $request->name;
            $validated['category_id'] = $request->category_id ?? 1;
            $validated['sub_category_id'] = $request->sub_category_id ?? null;
            $validated['sub_child_category_id'] = $request->sub_child_category_id ?? null;
            $validated['detail'] = $request->detail;
            $validated['description'] = $request->description;
            $validated['marked_price'] = number_format(floatval($request->marked_price ?? 0), 2, '.', '');
            $validated['sell_price'] = number_format(floatval($request->sell_price ?? 0), 2, '.', '');
            $validated['discount'] = floatval($request->discount ?? 0);
            $validated['quantity'] = intval($request->quantity ?? 0);
            $validated['merchant_business_id'] = MerchantBusiness::where('merchant_id', $merchant['data']['id'])->first()->id;

            $imageUploadStatus = $this->uploadProductImageMP($request);
            if ($imageUploadStatus['status'] === false) return $imageUploadStatus;
            $validated['featured_image'] = $imageUploadStatus['image'];

            if ($prod = Product::create($validated))
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product created successfully',
                    'data' => $this->productFormat($prod)]);
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);

    }

    function editProductMP(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'product_id' => 'required',
                'name' => 'required',
                'detail' => 'required',
                'description' => 'required',
                'category_id' => 'required',
//            'sub_category_id' => 'required|not_in:0',
//            'sub_child_category_id' => 'required|not_in:0',
//                'featured_image' => 'required',
                'marked_price' => 'required',
                'sell_price' => 'required',
                'discount' => 'required',
                'quantity' => 'required'
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);
            if ($response = $this->numericMinMaxValidation('marked price', $request->marked_price, 0)) return $response;
            if ($response = $this->numericMinMaxValidation('sell price', $request->sell_price, 0)) return $response;
            if ($response = $this->numericMinMaxValidation('discount', $request->discount, 0, 100)) return $response;
            if ($response = $this->numericMinMaxValidation('quantity', $request->quantity, 0)) return $response;

            $product = Product::find($request->product_id);
            if (!$product)
                return response()->json(['status' => false, 'message' => 404, 'error' => 'Product not found']);

            $validated['name'] = $request->name;
            $validated['category_id'] = $request->category_id;
            $validated['sub_category_id'] = $request->sub_category_id ?? null;
            $validated['sub_child_category_id'] = $request->sub_child_category_id ?? null;
            $validated['detail'] = $request->detail;
            $validated['description'] = $request->description;
            $validated['marked_price'] = number_format(floatval($request->marked_price ?? 0), 2, '.', '');
            $validated['sell_price'] = number_format(floatval($request->sell_price ?? 0), 2, '.', '');
            $validated['discount'] = floatval($request->discount ?? 0);
            $validated['quantity'] = intval($request->quantity ?? 0);

            if (!empty($request->featured_image)) {
                $imageUploadStatus = $this->uploadProductImageMP($request);
                if ($imageUploadStatus['status'] === false) return $imageUploadStatus;
                $validated['featured_image'] = $imageUploadStatus['image'];

                $old_img = public_path('image/products/' . $product->featured_image);
                if (File::exists($old_img)) {
                    File::delete($old_img);
                }
            }
            if ($product->update($validated)) {
                $prod = Product::find($product->id);
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Product updated successfully',
                    'data' => $this->productFormat($prod)]);
            }
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);
    }

    function addProductImagesMP(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $valid = Validator::make($request->all(), [
                'product_id' => 'required',
                'image' => 'required',
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);

            $imageUploadStatus = $this->uploadProductImageMP($request);
            if ($imageUploadStatus['status'] === false) return $imageUploadStatus;

            $product = Product::find($request->product_id);
            if (!$product)
                return response()->json(['status' => false, 'message' => 404, 'error' => 'Product not found']);
            $input['product_id'] = $request->product_id;
            $input['image'] = $imageUploadStatus['image'];

            if (ProductImage::create($input))
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Image uploaded']);
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
        }
        return response()->json($merchant);

    }

//    custom validation
    protected function numericMinMaxValidation($name, $value, $min = null, $max = null)
    {
        $intValue = floatval($value);
        if ($intValue) {
            if ($min !== null) {
                if ($intValue < $min)
                    return ['status' => false, 'message' => 422, 'error' => $name . ' should be minimum ' . $min];
            }
            if ($max !== null) {
                if ($intValue > $max)
                    return ['status' => false, 'message' => 422, 'error' => $name . ' should be maximum ' . $max];
            }

            return false;
        }
        return ['status' => false, 'message' => 422, 'error' => $name . ' - Not a number'];
    }

    protected function uploadProductImageMP($request)
    {
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $image_name = md5(time() . $image->getClientOriginalName()) . '.png';
//            . $image->getClientOriginalExtension();
            $destinationPath = public_path('image/products');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($image->getRealPath());

            if ($img->height() > $img->width()) {
                $img = $img->resize(null, 800, function ($constraint) {
                    $constraint->aspectRatio();
                });
            } else {
                $img = $img->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $saveimage = Image::canvas(800, 800)->insert($img, 'center');
            $saveimage->save($destinationPath . '/' . $image_name);
            return ['status' => true, 'image' => $image_name];
        }
        return ['status' => false, 'message' => 422, 'error' => 'Featured image is required'];
    }
}
