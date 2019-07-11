<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\WalletsHistoryTrait;
use App\Library\ShoppingBonus;
use App\Models\Members\MemberAsset;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\User;
use App\Models\UserPayment;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class PaymentController extends Controller
{
    use WalletsHistoryTrait;
    function generatePay(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'wallet_id' => 'required',
                'amount' => 'required|numeric|min:0',
//                'to_member' => 'required',
                'to_merchant' => 'required',
                'transaction_pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {
                if ($toMerchantid = $this->ToMerchantId($request->to_merchant)) {
                    $response = $this->processGeneratePay('merchant', $json['data']->id, $toMerchantid, $request);
                    return response()->json($response);
                } else {
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid Merchant')]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
            }
        }
        return response()->json($json);
    }


    function requestList()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $list = UserPayment::where('from_member_id', $json['data']->id)->where('status', 1)->where('flag', 0)->get();
            $json['data'] = [];
            foreach ($list as $item) {
                $json['data'][] = [
                    'id' => $item->id,
                    'name' => $item->getToMerchant->name,
                    'surname' => $item->getToMerchant->surname,
                    'user_name' => $item->getToMerchant->user_name,
                    'wallet' => $item->getWallet->detail,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'flag' => $item->flag,
                ];
            }
        }
        return response()->json($json);
    }

    function merchantExist(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'user_name' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if ($id = Merchant::where('user_name', $request->user_name)->first()->id ?? false) {
                if (MerchantAsset::where('merchant_id', $id)->first() ?? false) {
                    $json['data'] = Merchant::find($id);
                    return response()->json($json);
                } else {
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Merchant Asset Missing')]);
                }
            }
            return response()->json(['status' => false, 'message' => 404, 'error' => __('message.Merchant not found')]);
        }
        return response()->json($json);
    }


    function qrMerchantExist(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'qr_data' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }
            $merchant_id = str_replace('merchant:', '', $request->qr_data);
            if ($id = Merchant::find($merchant_id)->id ?? false) {
                if (MerchantAsset::where('merchant_id', $id)->first() ?? false) {
                    $json['data'] = Merchant::find($id);
                    return response()->json($json);
                } else {
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Merchant Asset Missing')]);
                }
            }
            return response()->json(['status' => false, 'message' => 404, 'error' => __('message.Merchant not found')]);
        }
        return response()->json($json);
    }

//    function confirmPay(Request $request)
//    {
//        $json = $this->getAuthenticatedUser();
//        if ($json['status']) {
//            $validator = Validator::make($request->all(), [
//                'qr_token' => 'required',
//            ]);
//
//            if ($validator->fails()) {
//                return response()->json(['status' => false, 'message' => 400, 'message-detail' => $validator->errors()->first()]);
//            }
//
//            if (!($payData = $this->processConfirmPay($json['data']->id, $request->qr_token))) {
//                return response()->json(['status' => false, 'message' => 400, 'error' => 'Invalid wallet transfer']);
//            } else {
//                return response()->json(['status' => true, 'message' => 200, 'data' => $payData]);
//            }
//        }
//        return response()->json($json);
//    }


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
        return ['status' => true, 'message' => 200, 'data' => $user, 'message-detail' => __('message.success')];
    }

    protected
    function toUserId($username)
    {
        $user = User::where('user_name', $username)->first();
        if ($user) {
            $asset = MemberAsset::where('member_id', $user->id)->first();
            if ($asset)
                return $user->id;
            else {
                if ($user->is_member === 0) {
                    MemberAsset::create(['member_id' => $user->id, 'chip' => 0, 'package_id' => 0]);
                    return $user->id;
                }
            }
        }
        return false;
    }

    protected
    function toMerchantId($username)
    {
        $user = Merchant::where('user_name', $username)->first();
        if ($user)
            if (MerchantAsset::where('merchant_id', $user->id)->first())
                return $user->id;

        return false;
    }


    protected
    function processGeneratePay($type, $fromMemberId, $toId, $req)
    {
//        $toMemberAsset = MemberAsset::where('member_id', $toMemberId)->first();
//        switch ($type) {
//            case 'member':
//                if ($fromMemberId === $toId) return ['status' => false, 'message' => 400, 'error' => 'Member From and to cannot be same'];
//                $toMemberId = $toId;
//                $toMerchantId = null;
//                break;

//            case 'merchant':
//                $toMemberId = null;
//                $toMerchantId = $toId;
//                break;
//        }
        $fromMemberAsset = MemberAsset::where('member_id', $fromMemberId)->first();
        $wallet = Wallet::find($req->wallet_id)->name ?? 'no_name';

        switch (strtolower($wallet)) {
            case 'ecash_wallet':
                if (($fromMemberAsset['ecash_wallet'] ?? 0) < $req->amount) {
                    return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];
                }
                break;

            case 'evoucher_wallet':
                if (($fromMemberAsset['evoucher_wallet'] ?? 0) < $req->amount) {
                    return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];
                }
                break;

            default:
                return ['status' => false, 'message' => 400, 'error' => __('message.Invalid wallet Id')];
                break;
        }

        $lastId = UserPayment::create([
            'from_member_id' => $fromMemberId,
            'to_merchant_id' => $toId,
            'amount' => $req->amount,
            'wallet_id' => $req->wallet_id,
//            'qr_token' => str_random(10) . microtime()
        ]);

        if ($this->processConfirmPay($lastId, $wallet))
            return ['status' => true, 'message' => 200, 'message-detail' => __('message.Payment made successfully'),
                'data' => [
                    'from_member' => $lastId->getFromMember->user_name,
                    'to_merchant' => $lastId->getToMerchant->user_name,
                    'amount' => $lastId->amount,
                    'remarks' => $lastId->remarks,
                    'wallet_name' => $lastId->getWallet->detail,
                ]];
        return ['status' => false, 'message' => 400, 'error' => __('message.Failed to pay merchant')];
    }

    protected
    function processConfirmPay($walletPay, $walletType)
    {
        $fromMemberAsset = MemberAsset::where('member_id', $walletPay->from_member_id)->first();
        $toMerchantAsset = MerchantAsset::where('merchant_id', $walletPay->to_merchant_id)->first();

        $user = User::find($walletPay->from_member_id);
        if ($user->is_member === 1) {
            $shopPayBonus = new ShoppingBonus();
            $amount = $shopPayBonus->paymentMemberBonus($walletPay->from_member_id, $walletPay);
        } else {
            $shopPayBonus = new ShoppingBonus();
            $amount = $shopPayBonus->paymentCustomerBonus($walletPay->from_member_id, $walletPay);
        }


        switch ($walletType) {
            case 'ecash_wallet':
                $toMerchantAsset->update(['ecash_wallet' => $toMerchantAsset->ecash_wallet + $amount]);
                $fromMemberAsset->update(['ecash_wallet' => $fromMemberAsset->ecash_wallet - $walletPay->amount]);
                $this->createWalletReport($walletPay->from_member_id, $walletPay->amount, 'Merchant Payment', 'ecash', 'OUT');
                break;

            case 'evoucher_wallet':
                $toMerchantAsset->update(['ecash_wallet' => $toMerchantAsset->ecash_wallet + $amount]);
                $fromMemberAsset->update(['evoucher_wallet' => $fromMemberAsset->evoucher_wallet - $walletPay->amount]);
                $this->createWalletReport($walletPay->from_member_id, $walletPay->amount, 'Merchant Payment', 'evoucher', 'OUT');
                break;
            default:
                return false;
                break;
        }

//        } else return false;
        $walletPay->update(['flag' => 1]);
        return true;
    }


    function userExist(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'user_name' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if ($id = User::select('id', 'name', 'surname', 'user_name')->where('user_name', $request->user_name)->first()->id ?? false) {
                if (MemberAsset::where('member_id', $id)->first() ?? false) {
                    if ($id == $json['data']->id)
                        return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Member cannot be same')]);
                    $json['data'] = User::find($id);
                    return response()->json($json);
                }
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Member not found')]);
        }
        return response()->json($json);
    }


    function acceptPayment(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'request_id' => 'required',
                'transaction_pass' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {
                $transfer = UserPayment::where('id', $request->request_id)->where('flag', 0)->where('status', 1)->first();
                if ($transfer) {

                    $fromMemberAsset = MemberAsset::where('member_id', $transfer->from_member_id)->first();

                    $wallet = Wallet::find($transfer->wallet_id)->name;
                    if ($fromMemberAsset[$wallet] < $transfer->amount) {
                        return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')]);
                    }

                    $this->processConfirmPay($transfer, 'ecash_wallet');

                    return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Payment made successfully'),
                        'wallet' => MemberAsset::select('id', 'member_id', 'ecash_wallet', 'evoucher_wallet', 'r_point', 'chip', 'shop_point', 'capital')->where('member_id', $json['data']->id)->first()]);
                }
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid request')]);
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
        }
        return response()->json($json);
    }


    function declinePayment(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'request_id' => 'required',
                'transaction_pass' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {
                $transfer = UserPayment::where('id', $request->request_id)->where('flag', 0)->where('status', 1)->first();
                if ($transfer) {
                    $transfer->update(['flag' => 1, 'status' => 0, 'remarks' => 'Declined Request']);
                    return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Request declined successfully'),
                        'wallet' => MemberAsset::select('id', 'member_id', 'ecash_wallet', 'evoucher_wallet', 'r_point', 'chip', 'shop_point', 'capital')->where('member_id', $json['data']->id)->first()]);
                }
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid request')]);
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
        }
        return response()->json($json);
    }
}
