<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Http\Traits\ProductWithOptionOnly;
use App\Models\Color;
use App\Models\ColorImage;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubChildCategory;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    use ProductWithOptionOnly;
    private $_path = 'frontend.product';
    private $_data = [];

    public function __construct()
    {
        $this->_data['featured_products'] = $this->featuredProduct();
        $this->_data['flash_sales'] = $this->flashSales();

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

//        $this->_data['products'] = $this->validProductWithOption(Product::all());
    }

    public function detail($slug)
    {
        $this->_data['product'] = Product::where('slug', $slug)->where('status', 1)->first();
        if (!$this->_data['product'])
            return redirect()->back();
        if (count($this->_data['product']->getProductVariant->where('status', 1)) != 0) {
            $variants = ProductVariant::where('product_id', $this->_data['product']->id)->where('status', 1)->orderBy('color_id', 'ASC')->get();
            $this->_data['colors'] = Color::whereIn('id', $variants->pluck('color_id')->toArray() ?? [])->get();
            $this->_data['color_images'] = [];
            foreach ($this->_data['colors'] as $color) {
                if ($prod_color_image = ColorImage::where('product_id', $this->_data['product']->id)->where('color_id', $color->id)->first())
                    $this->_data['color_images'][] = $prod_color_image;
            }
            $this->_data['options'] = $variants;
            $this->_data['category'] = $this->_data['product']->getCategory->name ?? null;
            $this->_data['subCat'] = $this->_data['product']->getSubCategory->name ?? null;
            $this->_data['subChildCat'] = $this->_data['product']->getSubChildCategory->name ?? null;
            $this->_data['related'] = $this->relatedProducts($this->_data['product']);
            return view($this->_path . '.detail', $this->_data)->with('title', $this->_data['product']->name);
        }
        return redirect()->back();
    }

    function productCategory(Request $request)
    {
        if (empty($request->slug)) return redirect()->to(url('/'));

        $this->_data['products'] = [];
        if ($request->type === 'category') {
            $id = Category::where('slug', $request->slug)->first()->id ?? false;
            if ($id !== false) {
                $this->_data['products'] = Product::where('category_id', $id)->where('status', 1)->get();

                $category = Category::where('slug', $request->slug)->first();
                $this->_data['more_category'] = $category;
                $this->_data['category'] = $category;
                $this->_data['subCat'] = null;
                $this->_data['subChildCat'] = null;

            } else
                return redirect()->to(url('/'));

        } else if ($request->type === 'sub-category') {
            $id = SubCategory::where('slug', $request->slug)->first()->id ?? false;
            if ($id !== false) {
                $this->_data['products'] = Product::where('sub_category_id', $id)->where('status', 1)->get();

                $subcat = SubCategory::where('slug', $request->slug)->first();
                $this->_data['more_category'] = $subcat;
                $this->_data['category'] = $subcat->getParentCategory;
                $this->_data['subCat'] = $subcat;
                $this->_data['subChildCat'] = null;
            } else
                return redirect()->to(url('/'));
        } else if ($request->type === 'sub-child-category') {
            $id = SubChildCategory::where('slug', $request->slug)->first()->id ?? false;
            if ($id !== false) {
                $this->_data['products'] = Product::where('sub_child_category_id', $id)->where('status', 1)->get();

                $subChildCat = SubChildCategory::where('slug', $request->slug)->first();
                $this->_data['more_category'] = $subChildCat->getParentSubCategory;
                $this->_data['category'] = $subChildCat->getParentSubCategory->getParentCategory;
                $this->_data['subCat'] = $subChildCat->getParentSubCategory;
                $this->_data['subChildCat'] = $subChildCat;
            } else
                return redirect()->to(url('/'));
        } else {
            return redirect()->to(url('/'));
        }

        $this->_data['type'] = $request->type;
        $this->_data['slug'] = $request->slug;

//        $this->searchProcess($this->_data['products'], $request);
        $this->_data['products'] = $this->searchProcess($this->validProductWithOption($this->_data['products']), $request);
        return view($this->_path . '.products-category', $this->_data)->with('title', $request->slug ?? '');
    }


    protected function searchProcess($products, $req)
    {
        $var_max = ProductVariant::orderBy('sell_price', 'DESC')->where('status', 1)->first()->sell_price ?? 0;
//        $pro_max = Product::orderBy('sell_price', 'DESC')->where('status', 1)->first()->sell_price ?? 0;
        $max = $var_max;
//        if ($var_max < $pro_max)
//            $max = $pro_max;

        $price_min = $req->price_min ?? 0;
        $price_max = $req->price_max ?? $max;

        $this->_data['price_min'] = intval($price_min);
        $this->_data['price_max'] = intval($price_max);
        $this->_data['filter_max'] = intval($max);
        $this->_data['old_sorting'] = $req->sorting ?? null;


//        if (isset($req->sorting)) {
//            switch ($req->sorting) {
//                case 'asd':
//                    $products = $products->orderBy('name', 'ASC');
//                    break;
//                case 'desc':
//                    $products = $products->orderBy('name', 'DESC');
//                    break;
//            }
//        }

        $filtered = [];
        foreach ($products as $product) {
            if (count($product->getProductVariant) === 0) {
                if ($product->sell_price >= $price_min && $product->sell_price <= $price_max)
                    $filtered[] = $product;

            } else {
                foreach ($product->getProductVariant as $variant) {
                    if ($variant->sell_price >= $price_min && $variant->sell_price <= $price_max)
                        if (!in_array($product, $filtered))
                            $filtered[] = $product;
                }
            }
        }

//            Sorting
        if (isset($req->sorting)) {

            $price = [];
            foreach ($filtered as $key => $row) {
                if (count($row->getProductVariant) === 0) {
                    $row['sort_price'] = $row->sell_price;
                } else {
                    $row['sort_price'] = $row->getProductVariant[0]->sell_price;
                }
                $price[$key] = $row['sort_price'];
            }

            $name = [];
            foreach ($filtered as $key => $row) {
                $row['sort_name'] = $row->name;
                $name[$key] = $row['sort_name'];
            }


            switch ($req->sorting) {
                case'asc':
                    array_multisort($name, SORT_ASC, $filtered);
                    break;
                case 'desc':
                    array_multisort($name, SORT_DESC, $filtered);
                    break;
                case 'low':
                    array_multisort($price, SORT_ASC, $filtered);
                    break;
                case 'high':
                    array_multisort($price, SORT_DESC, $filtered);
                    break;
            }
        }

//            Pagination
        $this->_data['page'] = $req->page ?? 1;
        $this->_data['perPage'] = 20;
        $this->_data['total'] = count($filtered);

        return collect($filtered)->forPage($this->_data['page'], $this->_data['perPage']);
    }


    protected
    function featuredProduct()
    {
        $products = $this->validProductWithOption(Product::where('is_featured', 1)->where('status', 1)->inRandomOrder()->get())->take(12);
        return $products;
    }

    public
    function productlist()
    {
        $this->_data['products'] = $this->validProductWithOption(Product::where('status', 1)->get());
        $var_max = ProductVariant::orderBy('sell_price', 'DESC')->where('status', 1)->first()->sell_price ?? 0;
//        $pro_max = Product::orderBy('sell_price', 'DESC')->where('status', 1)->first()->sell_price ?? 0;
        $max = $var_max;
//        if ($var_max < $pro_max)
//            $max = $pro_max;

        $term = $request->term ?? null;
        $type = $request->type ?? 'product';
        $checkbox_categories = $request->categories ?? [];
        $price_min = $request->price_min ?? 0;
        $price_max = $request->price_max ?? $max;
        $this->_data['search'] = $term;
        $this->_data['type'] = $type;
        $this->_data['checkbox_categories'] = $checkbox_categories;
        $this->_data['price_min'] = intval($price_min);
        $this->_data['price_max'] = intval($price_max);
        $this->_data['filter_max'] = intval($max);

        return view('frontend.product.product-list', $this->_data)->with('title', __('message.Golden Gate'));
    }


//    public function searchproduct(Request $request)
//    {
//        $products = $request->get('search');
//         // $data['result']= Merchant::WHERE('name', 'LIKE', '%' .$merchants . '%')->get();
//
//        $this->_data['products'] = Product::WHERE('name', 'LIKE', '%' .$products . '%')->get();
//        // $this->_data['merchant_businesses'] = MerchantBusiness::all();
//           return view('frontend.product.product-list', $this->_data)->with('title', 'Golden Gate');
//    }

    protected
    function flashSales()
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
        $data = collect($data)->sortByDesc('qty');
        $data = $data->pluck('id')->toArray();
        $products = Product::whereIn('id', $data)->where('status', 1)->get();
        if (count($products) === 0)
            $products = Product::orderBy('id', 'DESC')->get();
        return $this->validProductWithOption($products)->take(12);
    }


    protected
    function relatedProducts($product)
    {
        $related = [];
        if ($product->getSubChildCategory !== null) {
            $list1 = Product::where('sub_child_category_id', $product->sub_child_category_id)->where('status', 1)->where('id', '!=', $product->id)->get();
            foreach ($list1 as $li1) {
                if (!in_array($li1, $related))
                    $related[] = $li1;
            }
        }

        if (count($related) < 25) {
            if ($product->getSubCategory !== null) {
                $list2 = Product::where('sub_category_id', $product->sub_category_id)->where('status', 1)->where('id', '!=', $product->id)->get();
                foreach ($list2 as $li2) {
                    if (!in_array($li2, $related))
                        $related[] = $li2;
                }
            }
        }

        if (count($related) < 25) {
            if ($product->getCategory !== null) {
                $list3 = Product::where('category_id', $product->category_id)->where('status', 1)->where('id', '!=', $product->id)->latest()->get();
                foreach ($list3 as $li3) {
                    if (!in_array($li3, $related))
                        $related[] = $li3;
                }
            }
        }

        if (count($related) < 25) {
            if ($product->getCategory !== null) {
                $list4 = Product::where('id', '!=', $product->id)->where('status', 1)->latest()->get();
                foreach ($list4 as $li4) {
                    if (!in_array($li4, $related))
                        $related[] = $li4;
                }
            }
        }
        return $this->validProductWithOption($related)->take(12);
    }
}


