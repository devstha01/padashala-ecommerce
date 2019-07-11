<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Mail\VerifyEmail;
use App\Mail\WelcomeEmail;
use App\Models\Commisions\ShoppingMerchant;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\MerchantBusiness;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\Members\Member;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use JWTAuthException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 400, 'error' => $validator->errors()->first(),]);
        }
        $check = Merchant::where('user_name', $request->user_name)->first();
        if ($check) {
            if (!$check->status) {
                return response()->json(['status' => false, 'message' => 400, 'error' => 'Email verification required']);
            }
            if (Hash::check($request->password, $check->password)) {
                $token = $this->generateToken($check->id);
                $check->update(['qr_code' => $token]);
                return response()->json(['status' => true, 'message' => 200,
                    'data' => [
                        'token' => $token,
                        'merchant' => $check,
                        'token_type' => 'bearer',
                        'role' => 'merchant',
                    ]
                ]);
            }
        }
        return response()->json(['status' => false, 'message' => 400, 'error' => 'Invalid username or password']);
    }


    public function generateToken($id)
    {
        return base64_encode(Carbon::now()->addHours(24)->toDateTimeString()) . '.' . base64_encode('s1e2c3u4r55i6t7y7m8e89r9c0h0a1t2g3o5l6d6e6n7g72a37te=' . $id);
    }

    public function getAuthenticatedMerchant($request, $logout = false)
    {
        $header_token = $request->header('Authorization');
        $token = explode(' ', $header_token);
        $token_exist = $token[1] ?? false;
        if (!$token_exist) return ['status' => false, 'message' => 401, 'error' => 'Invalid Token'];

        $token_data = explode('.', $token_exist);

        $expire_time = $token_data[0] ?? false;
        $expire_time = $this->is_base64($expire_time) ? $expire_time : false;

        if (!$expire_time) return ['status' => false, 'message' => 401, 'error' => 'Invalid Token'];
        $expire_time = base64_decode($expire_time);

        if (Carbon::parse($expire_time) < Carbon::now()) return ['status' => false, 'message' => 401, 'error' => 'Token Expired'];

        $user_id_token = $token_data[1] ?? false;
        $user_id_token = $this->is_base64($user_id_token) ? $user_id_token : false;
        if (!$user_id_token) return ['status' => false, 'message' => 401, 'error' => 'Invalid Token'];

        $user_id = str_replace('s1e2c3u4r55i6t7y7m8e89r9c0h0a1t2g3o5l6d6e6n7g72a37te=', '', base64_decode($user_id_token));
        $user = Merchant::find($user_id);
        if (!$user) return ['status' => false, 'message' => 401, 'error' => 'Unauthorized User'];
        if ($logout !== true)
            if ($user->qr_code != $token_exist) return ['status' => false, 'message' => 401, 'error' => 'Unauthorized User'];

        return ['status' => true, 'message' => 200, 'data' => $user];
    }

    function is_base64($s)
    {
        return (bool)preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $s);
    }

    function logout(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request, true);
        if ($merchant['status']) {
            $mer = Merchant::find($merchant['data']->id);
            $mer->update(['qr_code' => false]);
            return ['status' => true, 'message' => 200, 'message-detail' => 'Logout success!'];
        }
        return response()->json($merchant);
    }

    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'surname' => 'required|min:3',
            'user_name' => 'required|unique:merchants,user_name',
            'email' => 'required|email|max:255|unique:merchants,email',
            'country_id' => 'required',
//            'city' => 'required',
            'address' => 'required',
            'contact_number' => 'required|numeric',
            'dob_date' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
            'gender' => 'required|in:male,female',
            'marital_status' => 'required',

            'password' => 'required|same:confirm_password|min:6',
            'confirm_password' => 'required|min:6',
            'transaction_password' => 'required|same:confirm_transaction_password|min:6',
            'confirm_transaction_password' => 'required|min:6',

            'identification_type' => 'required',
            'identification_number' => 'required',
//            'joining_date' => 'required',
            'business_name' => 'required',
            'merchant_share' => 'required|numeric|min:0|max:98',
            'admin_share' => 'required|numeric|min:2|max:100',
//            'registration_number' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 400, 'error' => $validator->errors()->first(), 'errors' => $validator->errors()]);
        }


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
//            Mail::to($request->email)->send(new WelcomeEmail('merchant', $merchant_create->name . ' ' . $merchant_create->surname));

            $check = Merchant::find($id);
            if ($check) {
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Merchant registered. Email verification required']);
            }
        }
        return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
    }

    function tokenCheck(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        return response()->json($merchant);
    }
}
