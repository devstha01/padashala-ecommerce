<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Traits\NotificationTrait;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\MerchantBankInfo;
use App\Models\MerchantBusiness;
use App\Models\MerchantCashWithdraw;
use App\Models\WithdrawConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    use NotificationTrait;

    function dashboard(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $merchant['business'] = MerchantBusiness::where('merchant_id', $merchant['data']->id)->first();
        }
        return response()->json($merchant);
    }

    function changePass(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required|same:confirm_password|min:6',
                'confirm_password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (!(Hash::check($request->old_password, $merchant['data']->password))) {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid previous password')]);
            }

            if (strcmp($request->new_password, $request->old_password) == 0) {
                //Current password and new password are same
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.New Password cannot be same as your current password')]);
            }
            $mer = Merchant::find($merchant['data']->id);
            if ($mer->update(['password' => bcrypt($request->new_password)])) {
                //                auth()->logout();
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Password changed successfully')]);
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Password Update error!')]);
        }
        return response()->json($merchant);
    }

    function changeTransactionPass(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => 'required|same:confirm_password|min:6',
                'confirm_password' => 'required|min:6',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (!(Hash::check($request->old_password, $merchant['data']->transaction_password))) {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid previous password')]);
            }

            if (strcmp($request->new_password, $request->old_password) == 0) {
                //Current password and new password are same
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.New Password cannot be same as your current password')]);
            }

            if (Merchant::find($merchant['data']->id)->update(['transaction_password' => bcrypt($request->new_password)])) {
//                auth()->logout();
                return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Transaction password changed successfully')]);
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Password Update error!')]);
        }
        return response()->json($merchant);
    }

    function getBank(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $ecash_wallet = MerchantAsset::where('merchant_id', $merchant['data']->id)->first()->ecash_wallet ?? 0;
            $merchant['data'] = MerchantBankInfo::select('id', 'merchant_id', 'bank_name', 'acc_name', 'acc_number', 'contact_number')
                ->where('merchant_id', $merchant['data']->id)->first();
            if (!($merchant['data']))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Empty Bank detail')]);
            $merchant['data']['ecash_wallet'] = $ecash_wallet;
        }
        return response()->json($merchant);
    }

    function setBank(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
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
                'merchant_id' => $merchant['data']->id,
                'bank_name' => $request->bank_name,
                'acc_name' => $request->acc_name,
                'acc_number' => $request->acc_number,
                'contact_number' => $request->contact_number,
            ];

            $first = MerchantBankInfo::where('merchant_id', $merchant['data']->id)->first();
            if ($first) {
                if ($first->update($input)) {
                } else
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update bank info')]);

            } else {
                if (MerchantBankInfo::create($input)) {
                } else
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update bank info')]);
            }
            $merchant['data'] = MerchantBankInfo::select('id', 'merchant_id', 'bank_name', 'acc_name', 'acc_number', 'contact_number')
                    ->where('merchant_id', $merchant['data']->id)->first() ?? false;
            $merchant['message-detail'] = __('message.Bank info updated successfully');
        }
        return response()->json($merchant);
    }

    function setGeneral(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'surname' => 'required',
                'dob' => 'required',
                'gender' => 'required|in:male,female',
                'marital_status' => 'required',
                'identification_type' => 'required',
                'identification_number' => 'required',
                'email' => 'required|email|max:255|unique:merchants,email,' . $merchant['data']->id,
                'contact_number' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (!Merchant::find($merchant['data']->id)->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'identification_type' => $request->identification_type,
                'identification_number' => $request->identification_number ?? null,
                'dob' => Carbon::parse($request->dob)->format('Y-m-d'),
                'gender' => $request->gender,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'marital_status' => $request->marital_status,
            ]))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to update general info')]);
            MerchantBusiness::where('merchant_id', $merchant['data']->id)->first()->update([
                'contact_number' => $request->contact_number,
            ]);
            return response()->json(['status' => true, 'message' => 200, 'error' => __('message.General info updated successfully')]);
        }
        return response()->json($merchant);
    }

    function setBusiness(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
                'business_name' => 'required',
                'country_id' => 'required',
                'address' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            MerchantBusiness::where('merchant_id', $merchant['data']->id)->first()->update([
                'business_name' => $request->business_name,
                'registration_number' => $request->registration_number,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'address' => $request->address,
            ]);
            Merchant::find($merchant['data']->id)->update([
                'country_id' => $request->country_id,
                'city' => $request->city,
                'address' => $request->address,
            ]);
            return response()->json(['status' => true, 'message' => 200, 'error' => __('message.Business info updated successfully')]);
        }
        return response()->json($merchant);
    }

    function walletWithdraw(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $withdr = WithdrawConfig::where('name', 'wallet_withdraw')->first();
            $min = ($withdr->min ?? 0) + 0;
            $max = ($withdr->max ?? 5000) + 0;

            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:' . $min . '|max:' . $max,
                'contact_number' => 'required',
                'bank_name' => 'required',
                'acc_name' => 'required',
                'acc_number' => 'required',
                'transaction_pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, Merchant::find($merchant['data']->id)->transaction_password)) {
                $response = $this->processWalletWithdraw($merchant['data']->id, $request);
                return response()->json($response);
            } else
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
        }
        return response()->json($merchant);
    }


    protected
    function processWalletWithdraw($id, $req)
    {
        $amount = MerchantAsset::where('merchant_id', $id)->first()->ecash_wallet ?? 0;

        if ($amount < $req->amount) {
            return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];
        }

//        $checkWithdraw = MemberCashWithdraw::where('member_id', $id)->where('created_at', '>', Carbon::now()->subDays(14))->first();
        $checkWithdraw = false;
        if (!$checkWithdraw) {
            if ($withdraw = MerchantCashWithdraw::create([
                'merchant_id' => $id,
                'contact_number' => $req->contact_number,
                'amount' => $req->amount,
                'bank_name' => $req->bank_name,
                'acc_name' => $req->acc_name,
                'acc_number' => $req->acc_number,
                'remarks' => $req->remarks,
            ])) {
                $asset = MerchantAsset::where('merchant_id', $withdraw->merchant_id)->first();
                $asset->update([
                    'ecash_wallet' => $asset->ecash_wallet - $withdraw->amount
                ]);

                $this->createNotificaton('admin', $withdraw->merchant_id, 'Wallet Withdrawal request by Merchant');

                return ['status' => true, 'message' => 200, 'message-detail' => __('message.Cash withdraw requested successfully')];
            } else {
                return ['status' => false, 'message' => 400, 'error' => __('message.MemberCashWithdraw error')];
            }
        }
//        return ['status' => false, 'message' => 400, 'error' => __('message.Invalid Withdraw request! Try again after 14 days of previous request!')];
        return ['status' => false, 'message' => 400, 'error' => __('message.Something went wrong')];
    }

}
