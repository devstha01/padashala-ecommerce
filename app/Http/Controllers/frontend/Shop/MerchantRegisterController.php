<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Mail\VerifyEmail;
use App\Mail\WelcomeEmail;
use App\Models\Category;
use App\Models\Commisions\ShoppingMerchant;
use App\Models\Country;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\MerchantBusiness;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MerchantRegisterController extends Controller
{
    private $_path = 'frontend.merchants.';
    private $_data = [];


    public function __construct()
    {
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
    }

    function showMerchantRegisterForm()
    {
        $this->_data['countries'] = Country::all();
        return view($this->_path . 'register', $this->_data);
    }

    function postMerchantRegisterForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'user_name' => 'required|unique:merchants,user_name',
            'email' => 'required|email|max:255|unique:merchants,email',
            'country_id' => 'required|not_in:0',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required|numeric',
            'dob_date' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
//            'dob_date' => 'required',
            'marital_status' => 'required',
            'new_password' => 'required|same:retype_password|min:6',
            'retype_password' => 'required|min:6',
            'transaction_password' => 'required|same:retype_transaction_password|min:6',
            'retype_transaction_password' => 'required|min:6',
            'identification_type' => 'required',
            'identification_number' => 'required',
//            'joining_date' => 'required',
            'business_name' => 'required',
            'merchant_share' => 'required|numeric|min:0|max:98',
            'admin_share' => 'required|numeric|min:2|max:100',
//            'registration_number' => 'required'
        ]);

        $input = [
            'name' => $request->name,
            'surname' => $request->surname,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'contact_number' => $request->contact_number,
            'dob' => Carbon::parse($request->dob_date)->format('Y-m-d'),
            'marital_status' => $request->marital_status,
            'password' => bcrypt($request->new_password),
            'transaction_password' => bcrypt($request->transaction_password),
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
            'joining_date' => Carbon::now()->format('Y-m-d'),
            'status' => 0,
        ];
        if ($id = Merchant::create($input)->id) {

            $uniq_slug = false;
            $i = 1;
            $slug = str_slug($request->business_name);
            do {
                $check = MerchantBusiness::where('slug', $slug)->first();
                if (!$check)
                    $uniq_slug = true;
                else
                    $slug = str_slug($request->business_name) . '-' . $i;
                $i++;
            } while ($uniq_slug !== true);


            $business_input = [
                'merchant_id' => $id,
                'name' => $request->business_name,
                'slug' => $slug,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'address' => $request->address,
                'contact_number' => $request->contact_number,
                'registration_number' => $request->registration_number ?? null
            ];

            MerchantBusiness::create($business_input);
            MerchantAsset::create(['merchant_id' => $id, 'ecash_wallet' => 0]);

            $mkString = 'merchant:' . $id;
            $data = QrCode::format('png')->size(500)->generate($mkString);

            $destination = public_path('image/qr_image/merchant/');
            if (!File::exists($destination))
                File::makeDirectory($destination);
            $qr_name = str_random(10) . '.png';
            $path = $destination . $qr_name;

            File::put($path, $data);

            $merchant_create = Merchant::find($id);
            $merchant_create->update(['qr_image' => $qr_name]);
            ShoppingMerchant::create(['merchant_id' => $id, 'merchant_rate' => $request->merchant_share, 'admin_rate' => $request->admin_share]);

            $url = url('verify-email/merchant');
            Mail::to($request->email)->send(new VerifyEmail($merchant_create, $url));

            session()->flush();
            session()->regenerate();
            return redirect()->to(url('/merchant/login'))->with('success', __('message.New merchant created successfully') . '! ' . __('email.Email verification required!'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }


    function showIndependentMerchantRegisterForm()
    {
        $this->_data['countries'] = Country::all();
        return view($this->_path . 'independent-register', $this->_data);
    }

    function postIndependentMerchantRegisterForm(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'user_name' => 'required|unique:merchants,user_name',
            'email' => 'required|email|max:255|unique:merchants,email',
            'country_id' => 'required|not_in:0',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required|numeric',
            'dob_date' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
//            'dob_date' => 'required',
            'marital_status' => 'required',
            'new_password' => 'required|same:retype_password|min:6',
            'retype_password' => 'required|min:6',
            'transaction_password' => 'required|same:retype_transaction_password|min:6',
            'retype_transaction_password' => 'required|min:6',
            'identification_type' => 'required',
            'identification_number' => 'required',
//            'joining_date' => 'required',
//            'business_name' => 'required',
            'merchant_share' => 'required|numeric|min:0|max:98',
            'admin_share' => 'required|numeric|min:2|max:100',
//            'registration_number' => 'required'
        ]);

        $input = [
            'name' => $request->name,
            'surname' => $request->surname,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'contact_number' => $request->contact_number,
            'dob' => Carbon::parse($request->dob_date)->format('Y-m-d'),
            'marital_status' => $request->marital_status,
            'password' => bcrypt($request->new_password),
            'transaction_password' => bcrypt($request->transaction_password),
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
            'joining_date' => Carbon::now()->format('Y-m-d'),
            'status' => 0,
            'owner_type' => 0,
        ];
        if ($id = Merchant::create($input)->id) {

            $uniq_slug = false;
            $i = 1;
            $slug = str_slug($request->business_name);
            do {
                $check = MerchantBusiness::where('slug', $slug)->first();
                if (!$check)
                    $uniq_slug = true;
                else
                    $slug = str_slug($request->business_name) . '-' . $i;
                $i++;
            } while ($uniq_slug !== true);


            $business_input = [
                'merchant_id' => $id,
                'name' => $request->name . ' ' . $request->surname,
                'slug' => $slug,
                'country_id' => $request->country_id,
                'address' => $request->address,
                'city' => $request->city,
                'contact_number' => $request->contact_number,
                'registration_number' => null
            ];

            MerchantBusiness::create($business_input);
            MerchantAsset::create(['merchant_id' => $id, 'ecash_wallet' => 0]);

            $mkString = 'merchant:' . $id;
            $data = QrCode::format('png')->size(500)->generate($mkString);

            $destination = public_path('image/qr_image/merchant/');
            if (!File::exists($destination))
                File::makeDirectory($destination);
            $qr_name = str_random(10) . '.png';
            $path = $destination . $qr_name;

            File::put($path, $data);

            $merchant_create = Merchant::find($id);
            $merchant_create->update(['qr_image' => $qr_name]);
            ShoppingMerchant::create(['merchant_id' => $id, 'merchant_rate' => $request->merchant_share, 'admin_rate' => $request->admin_share]);

            $url = url('verify-email/merchant');
            Mail::to($request->email)->send(new VerifyEmail($merchant_create, $url));
//            Mail::to($request->email)->send(new WelcomeEmail('merchant', $merchant_create->name . ' ' . $merchant_create->surname));

            session()->flush();
            session()->regenerate();
            return redirect()->to(url('/merchant/login'))->with('success', __('message.New merchant created successfully') . '! ' . __('email.Email verification required!'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));

    }
}
