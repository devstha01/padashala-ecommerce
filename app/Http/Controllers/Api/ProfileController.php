<?php

namespace App\Http\Controllers\Api;

use App\Models\Country;
use App\Models\Members\MemberBankInfo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class ProfileController extends Controller
{
    function changePass(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required|same:confirm_password|min:6',
                'confirm_password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (!(Hash::check($request->old_password, $json['data']->password))) {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid previous password')]);
            }

            if (strcmp($request->new_password, $request->old_password) == 0) {
                //Current password and new password are same
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.New Password cannot be same as your current password')]);
            }

            if (User::find($json['data']->id)->update(['password' => bcrypt($request->new_password)])) {
                $token = auth('api')->refresh();
//                auth()->logout();
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Password changed successfully'), 'token' => $token]);
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Password Update error!')]);
        }
        return response()->json($json);
    }

    function changeTransactionPass(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required|same:confirm_password|min:6',
                'confirm_password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (!(Hash::check($request->old_password, $json['data']->transaction_password))) {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid previous password')]);
            }

            if (strcmp($request->new_password, $request->old_password) == 0) {
                //Current password and new password are same
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.New Password cannot be same as your current password')]);
            }

            if (User::find($json['data']->id)->update(['transaction_password' => bcrypt($request->new_password)])) {
//                auth()->logout();
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Transaction password changed successfully')]);
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Password Update error!')]);
        }
        return response()->json($json);
    }

//    Account Information -  set mobile
    function getUser()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
//            $json['data'] = $json['data']->contact_number;
        }
        return response()->json($json);
    }

    function getUserCountry()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $json['country'] = Country::select('name', 'id')->get();
//            select('id', 'name')->get();
        }
        return response()->json($json);
    }

    function setMobile(Request $request)
    {
        $state = false;
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'phone' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => __('message.missing phone')]);
            }
            if (!User::find($json['data']->id)->update(['contact_number' => $request->phone]))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update phone')]);
            $state = true;
        }
        $response = $this->getAuthenticatedUser();
        if ($state)
            $response['message-detail'] = __('message.Phone updated successfully');
        return response()->json($response);
    }


//    Account Information - set email

    function setEmail(Request $request)
    {
        $state = false;
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|max:255|unique:users,email,' . $json['data']->id,
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }
            if (!User::find($json['data']->id)->update(['email' => $request->email]))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update email')]);
            $state = true;
        }
        $response = $this->getAuthenticatedUser();
        if ($state)
            $response['message-detail'] = __('message.Email updated successfully');
        return response()->json($response);
    }

    function setAddress(Request $request)
    {
        $state = false;
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'surname' => 'required|max:255',
//                'phone'=>'required'
                'country' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }
            if (!User::find($json['data']->id)->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'country_id' => Country::where('name', $request->country)->first()->id,
                'contact_number' => $request->phone ?? null,
                'city' => $request->city ?? null,
                'address' => $request->address ?? null,
            ]))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update address')]);
            $state = true;
        }
        $jsonCountry = $this->getAuthenticatedUser();
        if ($jsonCountry['status']) {
            $jsonCountry['country'] = Country::select('name')->get();
        }
        if ($state)
            $jsonCountry['message-detail'] = __('message.Address updated successfully');
        return response()->json($jsonCountry);
    }


    function setGeneral(Request $request)
    {
        $state = false;
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'surname' => 'required',
                'identification_type' => 'required',
                'gender' => 'required',
                'marital_status' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }
            if (!User::find($json['data']->id)->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'identification_type' => $request->identification_type,
                'identification_number' => $request->identification_number ?? null,
                'dob' => $request->dob ? Carbon::parse($request->dob)->format('Y-m-d') : null,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
            ]))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update general info')]);
            $state = true;
        }
        $response = $this->getAuthenticatedUser();
        if ($state)
            $response['message-detail'] = __('message.General info updated successfully');
        return response()->json($response);
    }

    function setBank(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            if ($json['data']->is_member !== 1)
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Not member')]);

            $validator = Validator::make($request->all(), [
                'bank_name' => 'required',
                'acc_name' => 'required',
                'acc_number' => 'required',
                'contact_number' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            $input = [
                'member_id' => Auth::user()->id,
                'bank_name' => $request->bank_name,
                'acc_name' => $request->acc_name,
                'acc_number' => $request->acc_number,
                'contact_number' => $request->contact_number,
            ];

            $first = MemberBankInfo::where('member_id', $json['data']->id)->first();
            if ($first) {
                if ($first->update($input)) {
                } else
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update bank info')]);

            } else {
                if (MemberBankInfo::create($input)) {
                } else
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update bank info')]);
            }
            $json['data'] = MemberBankInfo::select('id', 'member_id', 'bank_name', 'acc_name', 'acc_number', 'contact_number')->where('member_id', $json['data']->id)->first() ?? false;
            $json['message-detail'] = __('message.Bank info updated successfully');
        }
        return response()->json($json);
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
        $json = JWTAuth::parseToken()->authenticate();

        $json['country'] = $user->getCountry->name;
        return ['status' => true, 'message' => 200, 'data' => $json, 'message-detail' => __('message.success')];
    }
}
