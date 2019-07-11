<?php

namespace App\Http\Controllers\Api;

use App\Mail\PassRecoveryEmail;
use App\Mail\VerifyEmail;
use App\Mail\WelcomeEmail;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Members\Member;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use JWTAuthException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->user = new User;
    }

    public function login(Request $request)
    {
        $credentials = $request->only('user_name', 'password');

        $token = null;
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => false,
                    'response' => 'error',
                    'error' => __('message.Invalid Login name or Password'),
                ]);
            }
        } catch (JWTAuthException $e) {
            return response()->json([
                'status' => false,
                'response' => 'error',
                'error' => __('message.Failed to create token'),
            ]);
        }

        $status = User::where('user_name', $request->user_name)->first()->status ?? false;
        if (!$status)
            return response()->json(['status' => false, 'message' => 403, 'error' => __('email.Email verification required!')]);

        return $this->respondWithToken($token);

        // return response()->json([


        // 'response' => 'success',
        // 'result' => [
        //     'token' => $token,
        //     'message' => 'customer logged in successfully',
        // ],
        // ]);
    }


    public function customerRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required|min:3',
            'name' => 'required',
            'user_name' => 'required|unique:users,user_name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm_password|min:6',
            'confirm_password' => 'required|min:6',
            'transaction_password' => 'required|same:confirm_transaction_password|min:6',
            'confirm_transaction_password' => 'required|min:6',
            'country' => 'required',
            'contact_number' => 'required|numeric',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
        }

        $user = User::create([
            'surname' => $request->surname,
            'name' => $request->name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'transaction_password' => bcrypt($request->transaction_password),
            'country_id' => Country::where('name', $request->country)->first()->id,
            'contact_number' => $request->contact_number,
            'city' => $request->city ?? null,
            'address' => $request->address ?? null,
            'status' => 0
        ]);

        $mkString = 'user:' . $user->id;
        $data = QrCode::format('png')->size(500)->generate($mkString);

        $destination = public_path('image/qr_image/');
        if (!File::exists($destination))
            File::makeDirectory($destination);

        $qr_name = str_random(10) . '.png';
        $path = $destination . $qr_name;
        File::put($path, $data);

        $user->update(['qr_image' => $qr_name]);

        $url = url('verify-email');
        Mail::to($request->email)->send(new VerifyEmail($user, $url));
//        Mail::to($request->email)->send(new WelcomeEmail('customer', $user->name . ' ' . $user->surname));

        return response()->json([
            'response' => 'success',
            'status' => true,
            'message-detail' => __('message.New Customer created successfully') . '! ' . __('email.Email verification required!')
        ]);
    }

    function verifyEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
        }

        $user = User::where('user_name', $request->user_name)->first();
        if (!$user)
            return response()->json(['status' => false, 'message' => 403, 'error' => __('message.Unregistered User')]);
        $url = url('verify-email');
        Mail::to($user->email)->send(new VerifyEmail($user, $url));
        return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('email.Email verification sent!')]);
    }

    public function refresh(Request $request)
    {
//        $token = $request->header('Authorization');
//        return $token;
        return $this->respondWithToken(auth('api')->refresh());
//        return JWTAuth::setToken(JWTAuth::refresh());
    }

    protected function respondWithToken($token)
    {
        $user = auth()->user();
        User::find($user->id)->update(['jwt_token_handle' => $token]);

        if ($user->is_member == 1) {
            $role = 'member';
        } else {
            $role = 'customer';
        }
        return response()->json([
            'status' => true,
            'access_token' => $token,
            'token_type' => 'bearer',
            'role' => $role,
//            'expires_in' => auth('api')->factory()->getTTL() * 600,
            'detail' => $user
        ]);
    }

    public function getAuthenticatedUser(Request $request)
    {
//        $headers = $request->headers->all();
//        return $headers;
        return response()->json(auth()->user());
    }

    public function logout()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return ['status' => false, 'message' => 401, 'error' => __('message.Unauthorized User'), 'redirect' => true];
//                return ['status' => false, 'message' => 400, 'error' => __('message.Invalid Login name or Password')];
            }
        } catch (TokenExpiredException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Token Expired'), 'redirect' => true];
        } catch (TokenInvalidException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Invalid Token'), 'redirect' => true];
        } catch (JWTException $e) {
            return ['status' => false, 'message' => 401, 'error' => __('message.Unauthorized User'), 'redirect' => true];
        }

        Auth::guard('api')->logout();
        return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Successfully logged out')]);
    }

    function passRecovery(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json(['status' => false, 'message' => 422, 'error' => $validate->errors()->first()]);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user)
            return response()->json(['status' => false, 'message' => 403, 'error' => __('message.Unregistered Email')]);
        $url = url('reset-password');
        Mail::to($request->email)->send(new PassRecoveryEmail($user, $url));
        return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Recovery request submitted successfully')]);
    }

}
