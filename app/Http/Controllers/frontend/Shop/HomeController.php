<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Http\Traits\NotificationTrait;
use App\Http\Traits\ProductWithOptionOnly;
use App\Models\Category;
use App\Models\Country;
use App\Models\MerchantBusiness;
use App\Models\HomeBanner;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\Subscriber;
use App\Models\UpgradeCustomer;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\About;
use App\Models\Blog;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    use NotificationTrait, ProductWithOptionOnly;
    private $_path = 'frontend.home';
    private $_data = [];

    public function __construct()
    {
        $this->_data['featured_products'] = $this->featuredProduct();
        $this->_data['flash_sales'] = $this->flashSales();

        $categories = Category::where('status', 1)->get() ?? [];
        $this->_data['home_categories'] = collect($categories)->take(8);
        $this->_data['all_categories'] = collect($categories);
        $data = [];
        foreach ($categories as $category) {
            $catStatus = false;
            if (count($category->getSubCategory->where('status', 1)) !== 0)
                $catStatus = true;
            if ($catStatus)
                $data[] = $category;
        }
        $this->_data['categories'] = collect($data);

        $this->_data['merchants'] = Merchant::all();
        $this->_data['abouts'] = About::all();
    }

    public function home()
    {
        $this->_data['homebanners'] = HomeBanner::where('status', 1)->get();
        $this->_data['latest_products'] = $this->validProductWithOption(Product::latest()->where('status', 1)->get())->take(12);
        $this->_data['merchants'] = MerchantBusiness::inRandomOrder()->take(3)->get();
        return view($this->_path . '.home', $this->_data)->with('title', __('message.Golden Gate'));
    }

    public function cartView()
    {
        $this->_data['carts'] = Cart::content();
        return view($this->_path . '.view-cart', $this->_data)->with('title', __('message.Golden Gate'));
    }

    public function merchant($slug, Request $request)
    {
        $id = MerchantBusiness::where('slug', $slug)->first()->id ?? false;
        if ($id === false) return redirect()->to('/');
        $this->_data['featured_products'] = $this->validProductWithOption(Product::where('merchant_business_id', $id)->where('is_featured', 1)->where('status', 1)->get());
        $products = Product::where('merchant_business_id', $id)->latest();
        $this->_data['business'] = MerchantBusiness::find($id);

        $this->_data['others'] = MerchantBusiness::where('id', '!=', $id)->inRandomOrder()->take(6)->get();
        $this->_data['homebanners'] = HomeBanner::where('status', 1)->get();

        if (isset($request->merchant_tab))
            $this->_data['merchant_tab'] = 'active';

        $this->_data['products'] = $this->processMerchantProductFilter($request, $products);

        return view('frontend.merchants.merchant_info', $this->_data)->with('title', __('message.Golden Gate'));
    }

    protected function processMerchantProductFilter($req, $products)
    {
        $var_max = ProductVariant::orderBy('sell_price', 'DESC')->where('status', 1)->first()->sell_price ?? 0;
//        $pro_max = Product::orderBy('sell_price', 'DESC')->where('status', 1)->first()->sell_price ?? 0;
        $max = $var_max;
//        if ($var_max < $pro_max)
//            $max = $pro_max;

        $checkbox_categories = $req->categories ?? [];
        $price_min = $req->price_min ?? 0;
        $price_max = $req->price_max ?? $max;

        $this->_data['checkbox_categories'] = $checkbox_categories;
        $this->_data['price_min'] = intval($price_min);
        $this->_data['price_max'] = intval($price_max);
        $this->_data['filter_max'] = intval($max);
        $this->_data['old_sorting'] = $req->sorting ?? '';

        if (count($checkbox_categories) !== 0) {
            $products = $products->whereIn('category_id', $checkbox_categories);
        }
        $products = $products->get();
        $products = $this->validProductWithOption($products);
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


    public function merchantlist(Request $request)
    {
        $this->_data['search'] = $request->term ?? null;
        $this->_data['type'] = $request->type ?? 'merchant';
        $this->_data['old_sorting'] = $request->sorting ?? null;
        $this->_data['selected_country'] = [];

        $merchantFiltered = MerchantBusiness::orderBy('name', 'ASC')->get();

        $countries = $merchantFiltered->pluck('country_id')->toArray() ?? [];
        $this->_data['country_filter'] = Country::whereIn('id', $countries)->get();


        //            Pagination
        $this->_data['page'] = $request->page ?? 1;
        $this->_data['perPage'] = 8;
        $this->_data['total'] = count($merchantFiltered);

        $this->_data['merchants'] = $merchantFiltered->forPage($this->_data['page'], $this->_data['perPage']);

        return view('frontend.merchants.merchant_list', $this->_data)->with('title', __('message.Golden Gate'));
    }

    protected function featuredProduct()
    {
        $products = $this->validProductWithOption(Product::where('is_featured', 1)->where('status', 1)->inRandomOrder()->get())->take(12);
        return $products;
    }

    protected function flashSales()
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
            $products = Product::orderBy('id', 'DESC')->where('status', 1)->get();
        return $this->validProductWithOption($products)->take(12);
    }

    //search product / merchant by request type
    public function search(Request $request)
    {
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
        $this->_data['old_sorting'] = $request->sorting ?? null;
        $this->_data['selected_country'] = $request->country_select ?? [];

        $search_related_categories = Category::where('name', 'like', '%' . $term . '%')->pluck('id')->toArray();
        //search merchants
        if ($type === 'merchant') {
//            if ($term === null) {
//                return redirect()->to(route('merchant-list'));
//            }
            $merchants = MerchantBusiness::where('name', 'LIKE', '%' . $term . '%');

//            Sorting
            if (isset($request->sorting)) {

                switch ($request->sorting) {
                    case'asc':
                        $merchants = $merchants->orderBy('name', 'ASC');
                        break;
                    case 'desc':
                        $merchants = $merchants->orderBy('name', 'DESC');
                        break;
                }
            }
            if (count($this->_data['selected_country']) !== 0)
                $merchants = $merchants->whereIn('country_id', $this->_data['selected_country']);

            $merchants = $merchants->get();
            $merchantFiltered = [];
            foreach ($merchants as $merchant) {
                if ($merchant->getMerchant)
                    $merchantFiltered[] = $merchant;
            }
            $merchantFiltered = collect($merchantFiltered);

            $countries = MerchantBusiness::all()->pluck('country_id')->toArray() ?? [];
            $this->_data['country_filter'] = Country::whereIn('id', $countries)->get();

//            Pagination
            $this->_data['page'] = $request->page ?? 1;
            $this->_data['perPage'] = 8;
            $this->_data['total'] = count($merchantFiltered);
            $this->_data['merchants'] = $merchantFiltered->forPage($this->_data['page'], $this->_data['perPage']);
            return view('frontend.merchants.merchant_list', $this->_data)->with('title', __('message.Golden Gate'));
            //search products
        } else {
//            if ($term === null) {
//                return redirect()->to(route('product-list'));
//            }

            $products = Product::where('status', 1)->where('name', 'LIKE', '%' . $term . '%');


            if ($type !== 'product')
                $products->where('category_id', $type);
            else {
                if (count($checkbox_categories) !== 0)
                    $products = $products->whereIn('category_id', $checkbox_categories);
                else
                    $products = $products->orWhereIn('category_id', $search_related_categories)->where('status', 1);
            }
            $products = $products->get();
            $products = $this->validProductWithOption($products);
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
            if (isset($request->sorting)) {

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

                switch ($request->sorting) {
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


//            $this->_data['products'] = $filtered;

//            Pagination
            $this->_data['page'] = $request->page ?? 1;
            $this->_data['perPage'] = 20;
            $this->_data['total'] = count($filtered);

            $this->_data['products'] = collect($filtered)->forPage($this->_data['page'], $this->_data['perPage']);

            return view('frontend.product.product-list', $this->_data)->with('title', __('message.Golden Gate'));
        }
    }

    public function aboutus()
    {
        $this->_data['abouts'] = About::all();
        return view('frontend.home.about', $this->_data)->with('title', __('message.Golden Gate'));
    }

    public function contactus()

    {

        return view('frontend.home.contact', $this->_data)->with('title', __('message.Golden Gate'));
    }

    public function blog()
    {
        $this->_data['blogs'] = Blog::all();
        return view('frontend.home.blog', $this->_data)->with('title', __('message.Golden Gate'));
    }

    public function singleBlog()
    {
        $this->_data['blogs'] = Blog::all();
        return view('frontend.home.single_blog', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function allCategories()
    {
        return view('frontend.product.categories', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function bidWin()
    {
        return view('frontend.bid.bidwin', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function saveSubscriber(Request $request)
    {
        if (!request()->ajax()) return back();

        $validate = Validator::make($request->all(), [
            'sub_email' => 'email'
        ]);

        if ($validate->fails())
            return response()->json(['status' => false, 'message' => __('message.Invalid Email') . '!']);

        $email = Subscriber::where('email', $request->sub_email)->first();
        if ($email)
            return response()->json(['status' => false, 'message' => __('message.Subscription exists for email!')]);

        Subscriber::create(['email' => $request->sub_email]);
        return response()->json(['status' => true, 'message' => __('message.Subscription success!')]);
    }

    function sellOnGoldenGate()
    {
        return view('frontend.business_oppertunity.sell-on', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function becomeAffiliate()
    {
        if (Auth::check()) {
            if (Auth::user()->is_member === 1) {
                return redirect(url('/'));
            }
        }
        return view('frontend.business_oppertunity.become-affiliate', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function upgradeToMember()
    {
        if (Auth::check()) {
            if (Auth::user()->is_member === 1) {
                return redirect(url('/'));
            }
        }

        return view('frontend.business_oppertunity.upgrade', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function upgradeToMemberPost(Request $request)
    {
        $valid = $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'contact_number' => 'required',
        ]);

        if (UpgradeCustomer::create($valid)) {
            $this->createNotificaton('admin', Auth::id(), 'Upgrade Membership request by Customer');
            return redirect()->back()->with('success', __('message.Customer upgrade submitted'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }


    function privacyPolicy()
    {
        return view('frontend.home.docs.privacy-policy', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function termmOfUse()
    {
        return view('frontend.home.docs.terms-of-use', $this->_data)->with('title', __('message.Golden Gate'));
    }
}

