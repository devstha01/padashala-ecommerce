<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\ProductWithOptionOnly;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Color;
use App\Models\ColorImage;
use App\Models\Country;
use App\Models\HomeBanner;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    use ProductWithOptionOnly;

    function Products()
    {
        $products = $this->validProductWithOption(Product::latest()->where('status', 1)->get());
        $json = $this->productApiFormat($products);
        return response()->json([
            'status' => true,
            'count' => count($json),
            'data' => $json,
            'message' => 200
        ]);
    }


    function FeaturedProducts()
    {
        $products = $this->validProductWithOption(Product::where('is_featured', 1)->where('status', 1)->orderBy('name', 'ASC')->get());
        $json = $this->productApiFormat($products);

        return response()->json([
            'status' => true,
            'count' => count($json),
            'data' => $json,
            'message' => 200
        ]);
    }

    function FlashProducts()
    {

        $items = OrderItem::orderBy('id', 'DESC')->take(100)->get();
        $items = $items->groupBy('product_id')->toArray();
        $data = [];
        foreach ($items as $key => $item) {
            $data[$key]['id'] = $key;
            $data[$key]['qty'] = 0;
            foreach ($item as $qty) {
                $data[$key]['qty'] += $qty['quantity'];
            }
        }
        $data = collect($data)->sortByDesc('qty')->take(25);
        $data = $data->pluck('id')->toArray();

        $flashJSON = $this->validProductWithOption(Product::whereIn('id', $data)->where('status', '1')->get());
        if (count($flashJSON) === 0)
            $flashJSON = $this->validProductWithOption(Product::orderBy('id', 'DESC')->where('status', 1)->get())->take(12);
        $json = $this->productApiFormat($flashJSON);

        return response()->json([
            'status' => true,
            'count' => count($json),
            'data' => $json,
            'message' => 200
        ]);
    }

    function home()
    {
        $user = $this->getAuthenticatedUser();
        if ($user['status']) {
            $cart = Cart::where('user_id', $user['data']->id)->get();
            $cartCount = count($cart);
        } else
            $cartCount = 0;
        $categories = Category::where('is_highlighted', 1)->where('status', 1)->inRandomOrder()->take(8)->get();
        if (count($categories) < 8)
            $addCategories = Category::whereIn('is_highlighted', [0, 1])->where('status', 1)->inRandomOrder()->take(8)->get();
        for ($i = count($categories), $j = 0; $i < 8; $i++, $j++) {
            if (isset($addCategories[$j]))
                $categories[$i] = $addCategories[$j];
        }
        foreach ($categories as $key => $cat) {
            if ($cat['image'] !== null)
                $categories[$key]['image'] = url('/') . '/image/admin/category/' . $cat['image'];
        }

        $featureds = $this->validProductWithOption(Product::where('is_featured', 1)->where('status', 1)->inRandomOrder()->get())->take(6);
        $featuredJSON = $this->productApiFormat($featureds);


        $items = OrderItem::orderBy('id', 'DESC')->take(100)->get();
        $items = $items->groupBy('product_id')->toArray();
        $data = [];
        foreach ($items as $key => $item) {
            $data[$key]['id'] = $key;
            $data[$key]['qty'] = 0;
            foreach ($item as $qty) {
                $data[$key]['qty'] += $qty['quantity'];
            }
        }
        $data = collect($data)->sortByDesc('qty')->take(12);
        $data = $data->pluck('id')->toArray();
        $flashJSON = $this->validProductWithOption(Product::whereIn('id', $data)->where('status', 1)->where('quantity', '>', '0')->get())->take(6);

        $products = $this->validProductWithOption(Product::latest()->where('status', 1)->get())->take(6);
        $productsJSON = $this->productApiFormat($products);

        $banners = HomeBanner::where('status', 1)->get();
        $bannerJSON = [];
        foreach ($banners as $banner) {
            $bannerJSON[] = [
                'image' => url('/') . '/image/homebanner/' . $banner->image,
                'url' => $banner->url,
                'type' => $banner->type,
                'slug' => $banner->slug,
            ];
        }

        return response()->json([
            'status' => true,
            'categories' => [
                'count' => count($categories),
                'data' => $categories,
            ],
            'featured' => [
                'count' => count($featuredJSON),
                'data' => $featuredJSON,
            ],
            'flash' => [
                'count' => count($flashJSON),
                'data' => $flashJSON,
            ],
            'products' => [
                'count' => count($productsJSON),
                'data' => $productsJSON,
            ],
            'banner' => [
                'count' => count($bannerJSON),
                'data' => $bannerJSON,
            ],
            'cart' => $cartCount,
            'message' => 200
        ]);
    }

    function allCategories()
    {
        $categories = Category::where('status', 1)->get();
        $json = [];
        foreach ($categories as $key => $category) {
            $json[$key]['name'] = $category->name;
            $json[$key]['slug'] = $category->slug;
            $json[$key]['image'] = url('/') . '/image/admin/category/' . $category->image;
            $json[$key]['type'] = 'category';

            if (count($category->getSubCategory->where('status', 1)) !== 0) {
                foreach ($category->getSubCategory->where('status', 1) as $key1 => $subCategory) {
                    $json[$key]['sub_category'] [$key1] = [
                        'name' => $subCategory->name,
                        'slug' => $subCategory->slug,
                        'image' => url('/') . '/image/admin/category/' . $subCategory->image,
                        'type' => 'sub_category',
                    ];
                    if (count($subCategory->getSubChildCategory->where('status', 1)) !== 0) {
                        foreach ($subCategory->getSubChildCategory->where('status', 1) as $key2 => $subChildCategory) {
                            $json[$key]['sub_category'] [$key1]['sub_child_category'] [$key2] = [
                                'name' => $subChildCategory->name,
                                'slug' => $subChildCategory->slug,
                                'image' => url('/') . '/image/admin/category/' . $subChildCategory->image,
                                'type' => 'sub_child_category',
                            ];
                        }
                    } else {
                        $json[$key]['sub_category'] [$key1]['sub_child_category'] = null;
                    }
                }
            } else {
                $json[$key]['sub_category'] = null;
            }
        }
        return response()->json([
            'status' => true,
            'data' => $json,
            'message' => 200
        ]);
    }


    function onlyCategories()
    {

        $categories = Category::where('status', 1)->get();
        $json = [];
        foreach ($categories as $key => $category) {
            $json[$key]['name'] = $category->name;
            $json[$key]['slug'] = $category->slug;
            $json[$key]['image'] = url('/') . '/image/admin/category/' . $category->image;
            $json[$key]['type'] = 'category';
        }
        return response()->json(['status' => true, 'message' => 200, 'data' => [
            'type' => null,
            'category' => $json,
            'paginate' => [],
            'products' => []
        ]]);
    }


    function searchProduct(Request $request)
    {
//        return response()->json($request->all());
        return response()->json($this->searchProcess($request));
    }


    function productDetail(Request $request)
    {
        if (empty($request->slug)) return response()->json(['status' => false, 'message' => 403, 'error' => __('message.missing slug')]);

        $product = Product::where('slug', $request->slug)->where('status', 1)->first() ?? false;
//        $prodImages = Product::where('slug', $request->slug)->first() ?? false;
        if (!$product) return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Product not found')]);
        if (count($product->getProductVariant->where('status', 1)) == 0)
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Product not found')]);

        $colorOptions = ProductVariant::where('status', 1)->where('product_id', $product->id)->get()->groupBy('color_id');
        $colorJson = [];
        foreach ($colorOptions as $color_id => $colorOption) {
            $color = Color::find($color_id);
            $colorImage = ColorImage::where('color_id', $color->id)->where('product_id', $product->id)->first();
            $img_link = null;
            if ($colorImage)
                $img_link = url('image/products/color/' . $colorImage->image);
//            $size  =[];
//            foreach ($colorOption as $item){
//                $size[]=$i
//            }
            $colorJson[] = [
                'color_id' => $color->id,
                'color_name' => $color->name,
                'color_code' => $color->color_code,
                'color_image' => $img_link,
                'sizes' => $colorOption
            ];
        }
        $product->getCategory;
        $product->getSubCategory;
        $product->getSubChildCategory;
        $product->getProductVariant->where('status', 1);

        foreach ($product->getProductImage as $key => $img) {
            $img['image'] = url('/') . '/image/products/' . $img->image;
        }
//        $product->getProductImage;

        $product->getBusiness;
        $json = $product;
        $json['quantity'] = $this->getSoldProduct($product->id);
        $json['colors'] = $colorJson;


        $related = [];
        if ($product->getSubChildCategory !== null) {
            $list1 = Product::where('sub_child_category_id', $product->sub_child_category_id)->where('status', 1)->where('id', '!=', $product->id)->get();
            foreach ($list1 as $li1) {
                if (!in_array($li1, $related))
                    $related[] = $li1;
            }
        }

        if (count($related) < 12) {
            if ($product->getSubCategory !== null) {
                $list2 = Product::where('sub_category_id', $product->sub_category_id)->where('status', 1)->where('id', '!=', $product->id)->get();
                foreach ($list2 as $li2) {
                    if (!in_array($li2, $related))
                        $related[] = $li2;
                }
            }
        }

        if (count($related) < 12) {
            if ($product->getCategory !== null) {
                $list3 = Product::where('category_id', $product->category_id)->where('status', 1)->where('id', '!=', $product->id)->latest()->get();
                foreach ($list3 as $li3) {
                    if (!in_array($li3, $related))
                        $related[] = $li3;
                }
            }
        }

        if (count($related) < 12) {
            $list4 = Product::where('id', '!=', $product->id)->where('status', 1)->latest()->get();
            foreach ($list4 as $li4) {
                if (!in_array($li4, $related))
                    $related[] = $li4;
            }
        }

        $json['featured_image'] = url('/') . '/image/products/' . $json['featured_image'];
        $related = $this->validProductWithOption($related)->take(6);
        return response()->json([
            'status' => true,
            'data' => $json,
            'related_products' => $this->productApiFormat($related),
            'message' => 200
        ]);
    }

    function getCountry()
    {
        $list = Country::select('name', 'id')->get() ?? false;
        if (!$list)
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to retrieve country list')]);
        return response()->json(['status' => true, 'message' => 200, 'data' => $list]);
    }

    function categoryProduct($slug = null)
    {
        if (empty($slug))
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.slug missing')]);

        $category = Category::where('slug', $slug)->first();
        if (!$category)
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid category')]);


        $jsonCat['name'] = $category->name;
        $jsonCat['slug'] = $category->slug;
        $jsonCat['image'] = url('/') . '/image/admin/category/' . $category->image;
        $jsonCat['type'] = 'category';

        $jsonCat['sub_category'] = $category->getSubCategory->where('status', 1);

        foreach ($jsonCat['sub_category'] as $key => $cat) {
            if ($cat['image'] !== null)
                $jsonCat['sub_category'][$key]['image'] = url('/') . '/image/admin/category/' . $cat['image'];
        }

//        products
//        $paginate = Product::where('category_id', $category->id)->where('status', 1)->paginate(10)->toArray();
        $list = $this->validProductWithOption(Product::where('category_id', $category->id)->where('status', 1)->get());
//        unset($paginate['data']);

        return response()->json(['status' => true, 'message' => 200, 'data' => ['category' => $jsonCat,
//            'paginate' => $paginate,
            'products' => $this->productApiFormat($list)]]);
    }


    function subCategoryProduct($slug = null)
    {
        if (empty($slug))
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.missing slug')]);

        $subcategory = SubCategory::where('slug', $slug)->first();
        if (!$subcategory)
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid sub category')]);


        $jsonCat['name'] = $subcategory->name;
        $jsonCat['slug'] = $subcategory->slug;
        $jsonCat['image'] = url('/') . '/image/admin/category/' . $subcategory->image;
        $jsonCat['type'] = 'sub_category';

        $jsonCat['sub_child_category'] = $subcategory->getSubChildCategory->where('status', 1);

        foreach ($jsonCat['sub_child_category'] as $key => $cat) {
            if ($cat['image'] !== null)
                $jsonCat['sub_child_category'][$key]['image'] = url('/') . '/image/admin/category/' . $cat['image'];
        }

//        products
//        $paginate = Product::where('sub_category_id', $subcategory->id)->where('status', 1)->paginate(10)->toArray();
        $list = $this->validProductWithOption(Product::where('sub_category_id', $subcategory->id)->where('status', 1)->get());
//            ->simplePaginate(10);
//        unset($paginate['data']);

        return response()->json(['status' => true, 'message' => 200, 'data' => ['sub_category' => $jsonCat,
//            'paginate' => $paginate,
            'products' => $this->productApiFormat($list)]]);
    }

    function subChildCategoryProduct($slug = null)
    {
        if (empty($slug))
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.missing slug')]);

        $schildcategory = SubChildCategory::where('slug', $slug)->first();
        if (!$schildcategory)
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid sub child category')]);


        $jsonCat['name'] = $schildcategory->name;
        $jsonCat['slug'] = $schildcategory->slug;
        $jsonCat['image'] = url('/') . '/image/admin/category/' . $schildcategory->image;
        $jsonCat['type'] = 'sub_child_category';


//        products
//        $paginate = Product::where('sub_child_category_id', $schildcategory->id)->where('status', 1)->paginate(10)->toArray();
        $list = $this->validProductWithOption(Product::where('sub_child_category_id', $schildcategory->id)->where('status', 1)->get());
//        ->simplePaginate(10);
//        unset($paginate['data']);

        return response()->json(['status' => true, 'message' => 200, 'data' => ['sub_child_category' => $jsonCat,
//            'paginate' => $paginate,
            'products' => $this->productApiFormat($list)]]);
    }

    protected
    function getSoldProduct($product_id)
    {
        $product = Product::find($product_id);
        $sold = 0;
        $available = 0;
        if (count($product->getProductVariant->where('status', 1)) === 0) {
            $available = $product->quantity;
        } else {
            foreach ($product->getProductVariant->where('status', 1) as $variant) {
                $available += $variant->quantity;
            }
        }

        $orderItems = OrderItem::where('product_id', $product_id)->where('updated_at', '>', Carbon::now()->subDays(30))->get();
        foreach ($orderItems as $orderItem) {
            if ($orderItem->getOrder->order_status_id !== 7) {
                $sold += $orderItem->quantity;
            }
        }

        return [
            'total' => (int)($sold + $available),
            'sold' => (int)$sold,
            'available' => intval($available)
        ];
    }


    protected
    function searchProcess($req)
    {
//        return $req->all();
        $var_max = ProductVariant::orderBy('sell_price', 'DESC')->where('status', 1)->first()->sell_price ?? 0;
//        $pro_max = Product::orderBy('sell_price', 'DESC')->where('status', 1)->first()->sell_price ?? 0;
        $max = $var_max;
//        if ($var_max < $pro_max)
//            $max = $pro_max;

        $term = $req->term ?? '';
        $price_min = $req->price_min ?? 0;
        $price_max = $req->price_max ?? $max;

        $selected_cat = json_decode($req->selected_cat) ?? [];

//        sorting
        $order = $req->order ? strtoupper($req->order) : 'ASC';
        $order_type = $req->order_type ? strtolower($req->order_type) : 'name';


//        return $selected_cat;
//        if ($term === null) {
//            return redirect()->to(route('product-list'));
//        }
        if (count($selected_cat) !== 0) {
            $products = Product::where('name', 'LIKE', '%' . $term . '%')->where('status', 1)->whereIn('category_id', $selected_cat);
        } else {
            $products = Product::where('name', 'LIKE', '%' . $term . '%')->where('status', 1);
        }

        if ($order_type == 'name') {
            if ($order == 'DESC')
                $products = $products->orderBy('name', 'DESC')->get();
            else
                $products = $products->orderBy('name', 'ASC')->get();
        } else {
            $products = $products->get();
        }

        $products = $this->validProductWithOption($products);
        $filtered = [];
        foreach ($products as $product) {
            if (count($product->getProductVariant->where('status', 1)) === 0) {
                if ($product->sell_price >= $price_min && $product->sell_price <= $price_max)
                    $filtered[] = $product;

            } else {
                foreach ($product->getProductVariant->where('status', 1) as $variant) {
                    if ($variant->sell_price >= $price_min && $variant->sell_price <= $price_max)
                        if (!in_array($product, $filtered))
                            $filtered[] = $product;
                }
            }
        }
        $sorted = $this->productApiFormat($filtered);

        if ($order_type == 'price') {
            foreach ($sorted as $key => $row) {
                $price[$key] = $row['sell_price'];
            }
            if ($order == 'DESC')
                array_multisort($price, SORT_DESC, $sorted);
            else
                array_multisort($price, SORT_ASC, $sorted);
        }

        return [
            'status' => true,
            'message' => 200,
            'filter_max' => $max,
            'filter_cat' => Category::select('id', 'name')->get(),
            'old_term' => $term,
            'old_min' => $price_min,
            'old_max' => $price_max,
            'old_cat' => $selected_cat,
            'count' => count($filtered),
            'products' => $sorted,
        ];
    }

    protected
    function productApiFormat($products)
    {
        $json = [];
        foreach ($products as $key => $product) {
            $json[$key]['name'] = $product->name;
            $json[$key]['slug'] = $product->slug;
            $json[$key]['image'] = url('/') . '/image/products/' . $product->featured_image;
            if (count($product->getProductVariant->where('status', 1)) === 0) {
                $json[$key]['sell_price'] = $product->sell_price;
                $json[$key]['marked_price'] = $product->marked_price;
                $json[$key]['discount'] = $product->discount;
            } else {
                $json[$key]['sell_price'] = $product->getProductVariant->where('status', 1)->first()->sell_price;
                $json[$key]['marked_price'] = $product->getProductVariant->where('status', 1)->first()->marked_price;
                $json[$key]['discount'] = $product->getProductVariant->where('status', 1)->first()->discount;
            }
            $json[$key]['quantity'] = $this->getSoldProduct($product->id);
        }
        return $json;
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

}
