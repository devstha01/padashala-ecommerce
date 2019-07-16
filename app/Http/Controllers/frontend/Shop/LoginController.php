<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Http\Traits\ProductWithOptionOnly;
use App\Mail\PassRecoveryEmail;
use App\Mail\VerifyEmail;
use App\Mail\WelcomeEmail;
use App\Models\Country;
use App\Models\Members\MemberAsset;
use App\Models\Merchant;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LoginController extends Controller
{
    use ProductWithOptionOnly;
    private $_path = 'frontend.auth';
    private $_data = [];

    public function __construct()
    {
        $this->_data['featured_products'] = $this->featuredProduct();
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

    public function loginPage()
    {
        $user = isset(Auth::user()->id) ? true : false;
        if ($user === true) return redirect()->to(url('/'));

        return view($this->_path . '.login', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function loginCustomer(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required'
        ]);

        if ($validate->fails()) {
            return back()->withInput($request->only('user_name'))->withErrors([
                'no-match-error' => __('message.Login name/ Password must be entered')
            ]);
        }
        $user = User::where('user_name', $request->user_name)->first();
        $member = $user->is_member ?? 1;
        if ($member ===1)
            return back()->withInput($request->only('user_name'))->withErrors([
                'no-match-error' => __('message.Login name/ Password must be entered')
            ]);

        $status = $user->status ?? false;
        if (!$status)
            return redirect()->back()->with('verify-email', __('email.Email verification required!'));

        if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
            User::find(Auth::id())->update(['jwt_token_handle' => '']);
            $this->mergeCartFromDB();
            $this->refreshCart();
            return redirect()->to($request->url);
        }
        return back()->withInput($request->only('user_name'))->withErrors([
            'no-match-error' => __('message.Invalid Login name or Password')
        ]);
    }

    protected function featuredProduct()
    {
        $products = $this->validProductWithOption(Product::select('name', 'slug', 'featured_image', 'sell_price')
            ->where('is_featured', 1)->where('status', 1)->inRandomOrder()->get())->take(12);
        return $products;
    }

    protected function mergeCartFromDB()
    {
        $id = Auth::user()->id;
        $carts = Cart::content();
        foreach ($carts as $cart) {
            $matchCartDB = \App\Models\Cart::where('user_id', $id)->where('product_id', $cart->id)->where('variant_id', $cart->options->variant_id)->first();
            $input = [
                'user_id' => $id,
                'product_id' => $cart->id,
                'quantity' => $cart->qty,
                'variant_id' => $cart->options->variant_id ?? null
            ];
            if ($matchCartDB) {
                $matchCartDB->update($input);
            } else {
                \App\Models\Cart::create($input);
            }
        }

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


    function frontendLogout(Request $request)
    {

        Auth::guard('web')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/');
    }


    function registerPage()
    {
        $user = isset(Auth::user()->id) ? true : false;
        if ($user === true) return redirect()->to(url('/'));
        $this->_data['countries'] = Country::all();
        return view($this->_path . '.register', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function registerPagePost(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'user_name' => 'required|unique:users,user_name',
            'email' => 'required|email|max:255|unique:users,email',
            'country_id' => 'required|not_in:0',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required|numeric',
            'dob_date' => 'required|date_format:d-m-Y|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
//            'dob_date' => 'required',
//            'marital_status' => 'required',
            'new_password' => 'required|same:retype_password|min:6',
            'retype_password' => 'required|min:6',
//            'transaction_password' => 'required|same:retype_transaction_password|min:6',
//            'retype_transaction_password' => 'required|min:6',
//            'identification_type' => 'required',
//            'identification_number' => 'required',
        ]);

        $input = [
            'name' => $request->name,
            'surname' => $request->surname,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'gender' => $request->gender,
            'city' => $request->city ?? null,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'dob' => Carbon::parse($request->dob_date)->format('Y-m-d'),
//            'marital_status' => $request->marital_status,
            'password' => bcrypt($request->new_password),
//            'identification_type' => $request->identification_type,
//            'identification_number' => $request->identification_number,
            'joining_date' => Carbon::now()->format('Y-m-d'),

//        customer email verification needed when status 0
            'status' => 0,
        ];
        if ($user = User::create($input)) {

            $mkString = 'user:' . $user->id;
            $data = QrCode::format('png')->size(500)->generate($mkString);

            $destination = public_path('image/qr_image/');
            if (!File::exists($destination))
                File::makeDirectory($destination);
            $qr_name = str_random(10) . '.png';
            $path = $destination . $qr_name;

            File::put($path, $data);
            $user->update(['qr_image' => $qr_name]);
            MemberAsset::create([
                'member_id' => $user->id,
            ]);
            $url = url('verify-email');
            Mail::to($request->email)->send(new VerifyEmail($user, $url));
//            Mail::to($request->email)->send(new WelcomeEmail('customer', $user->name . ' ' . $user->surname));
//            Mail::to($request->email)->send(new WelcomeEmail('customer', $user->name . ' ' . $user->surname));

            return redirect()->to(route('checkout-login'))->with('success', __('message.New Customer created successfully') . '! ' . __('email.Email verification required!'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function verifyEmail($token = null)
    {
        if ($token === null)
            return redirect()->to(route('checkout-login'))->with('fail', __('email.Invalid email verification'));

        if ((bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $token)) {
            $token_data = explode('&i=', base64_decode($token));
            $user = User::find($token_data[1] ?? 0);
            if ($user) {
                if (!$user->status) {
                    $user->update(['status' => 1]);

                    Mail::to($user->email)->send(new WelcomeEmail('member', $user->name . ' ' . $user->surname));

                    return redirect()->to(route('checkout-login'))->with('success', __('email.Email successfully verified!'));
                } else
                    return redirect()->to(route('checkout-login'))->with('info', __('email.Email already verified!'));
            }
        }
        return redirect()->to(route('checkout-login'))->with('fail', __('email.Invalid email verification'));
    }


    function customerVerify()
    {
        return view($this->_path . '.recovery.form-verify', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function customerVerifyPost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('fail', $validate->errors()->first());
        }

        $user = User::where('email', $request->email)->first();
        if (!$user)
            return redirect()->back()->with('fail', __('message.Invalid Email'));
        $url = url('verify-email');
        Mail::to($request->email)->send(new VerifyEmail($user, $url));
        return redirect()->to(route('checkout-login'))->with('success', __('email.Email verification sent!'));
    }


    function passRecoveryForm()
    {
        return view($this->_path . '.recovery.pass-recovery', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function passRecoveryPost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('fail', $validate->errors()->first());
        }

        $user = User::where('email', $request->email)->first();
        if (!$user)
            return redirect()->back()->with('fail', __('message.Invalid Email'));
        $url = url('reset-password');
        Mail::to($request->email)->send(new PassRecoveryEmail($user, $url));
        return redirect()->back()->with('info', __('message.Recovery request submitted successfully'));
    }

    function resetPasswordForm($token = null)
    {
        if ($token == null) return redirect()->to(route('checkout-login'));
        $data = base64_decode($token);
        $arr = explode('&t=', $data);

        if (Carbon::parse($arr[1])->toDateString() < Carbon::now()->toDateString()) return redirect()->to(route('frontend-recovery'))->with('info', __('message.Reset password link expired'));
        $username = str_replace('u=', '', $arr[0]);

        $this->_data['recover'] = $token;
        return view($this->_path . '.recovery.form-recovery', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function resetPasswordPost($token = null, Request $request)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'required|confirmed||min:6',
        ]);

        if ($validate->fails()) return redirect()->back()->with('fail', $validate->errors()->first());
        if ($token == null) return redirect()->to(route('checkout-login'));
        $data = base64_decode($token);
        $arr = explode('&t=', $data);

        if (Carbon::parse($arr[1])->toDateString() < Carbon::now()->toDateString()) return redirect()->to(route('frontend-recovery'))->with('info', __('message.Reset password link expired'));
        $username = str_replace('u=', '', $arr[0]);
        $user = User::where('user_name', $username)->where('status', 1)->first();
        if (!$user) return redirect()->to(route('frontend-recovery'))->with('info', __('message.Invalid reset password link'));

        $user->update(['password' => bcrypt($request->password)]);

        return redirect()->to(route('checkout-login'))->with(['title' => __('message.Golden Gate'), 'success' => __('message.Password changed successfully')]);
    }

    function confirmTransactionPassword(Request $request)
    {
        $TransactionPassword = $request->transactionpassword;
        return response()->json(Hash::check($TransactionPassword, Auth::user()->transaction_password, []));
    }


//    Merchant
    function verifyEmailMerchant($token = null)
    {
        if ($token === null)
            return redirect()->to(url('merchant/login'))->with('fail', __('email.Invalid email verification'));

        if ((bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $token)) {
            $token_data = explode('&i=', base64_decode($token));
            $user = Merchant::find($token_data[1] ?? 0);
            if ($user) {
                if (!$user->status) {
                    $user->update(['status' => 1]);
                    Mail::to($user->email)->send(new WelcomeEmail('merchant', $user->name . ' ' . $user->surname));
                    return redirect()->to(url('merchant/login'))->with('success', __('email.Email successfully verified!'));
                } else
                    return redirect()->to(url('merchant/login'))->with('info', __('email.Email already verified!'));
            }
        }
        return redirect()->to(url('merchant/login'))->with('fail', __('email.Invalid email verification'));
    }

//
    function merchantVerify()
    {
        return view('backend.auth.form-verify', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function merchantVerifyPost(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('fail', $validate->errors()->first());
        }

        $user = Merchant::where('email', $request->email)->first();
        if (!$user)
            return redirect()->back()->with('fail', __('message.Invalid Email'));
        $url = url('verify-email/merchant');
        Mail::to($request->email)->send(new VerifyEmail($user, $url));
        return redirect()->to(url('merchant/login'))->with('success', __('email.Email verification sent!'));
    }

}
