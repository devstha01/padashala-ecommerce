<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\WalletsHistoryTrait;
use App\Library\ShoppingBonus;
use App\Models\CustomerWalletTransfer;
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


class TransferController extends Controller
{
    use WalletsHistoryTrait;

    function transferCustomer(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'wallet_id' => 'required',
                'amount' => 'required|numeric|min:0',
//                'to_member' => 'required',
                'to_customer' => 'required',
                'transaction_pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {
                if ($json['data']->user_name !== $request->to_customer) {
                    if ($toCustomerid = $this->ToCustomerId($request->to_customer)) {
                        $response = $this->proceesTransfer($json['data']->id, $toCustomerid, $request);
                        return response()->json($response);
                    }
                }
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid Username')]);

            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
            }
        }
        return response()->json($json);
    }

    function customerExist(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'user_name' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if ($json['data']->user_name !== $request->user_name) {
                if ($toCustomerid = $this->ToCustomerId($request->user_name)) {
                    $json['data'] = User::find($toCustomerid);
                    return response()->json($json);
                }
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid Username')]);
        }
        return response()->json($json);
    }


    function qrCustomerExist(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'qr_data' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }
            $user_id = str_replace('user:', '', $request->qr_data);
            $user = User::find($user_id);
            if ($user) {
                if ($json['data']->user_name !== $user->user_name) {
                    if ($toCustomerid = $this->ToCustomerId($user->user_name)) {
                        $json['data'] = User::find($toCustomerid);
                        return response()->json($json);
                    }
                }
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid Username')]);
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

        if ($user->is_member)
            return ['status' => false, 'message' => 401, 'error' => __('message.Unauthorized User'), 'redirect' => true];

        // the token is valid and we have found the user via the sub claim
        return ['status' => true, 'message' => 200, 'data' => $user, 'message-detail' => __('message.success')];
    }

    protected function ToCustomerId($username)
    {
        $user = User::where('user_name', $username)->where('is_member',0)->first();
        if ($user)
            if (!$user->is_member) {
                $asset = MemberAsset::where('member_id', $user->id)->first();
                if (!$asset) MemberAsset::create(['member_id' => $user->id, 'chip' => 0, 'package_id' => 0]);
                return $user->id;
            }
        return false;
    }


    protected
    function proceesTransfer($fromMemberId, $toId, $req)
    {
        $fromMemberAsset = MemberAsset::where('member_id', $fromMemberId)->first();
        $wallet = Wallet::find($req->wallet_id)->name ?? 'no_name';

        switch (strtolower($wallet)) {
            case 'ecash_wallet':
                if (($fromMemberAsset['ecash_wallet'] ?? 0) < $req->amount) {
                    return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];
                }
                break;

            default:
                return ['status' => false, 'message' => 400, 'error' => __('message.Invalid wallet Id')];
                break;
        }

        $lastId = CustomerWalletTransfer::create([
            'from_id' => $fromMemberId,
            'to_id' => $toId,
            'amount' => $req->amount,
            'wallet_id' => $req->wallet_id,
//            'qr_token' => str_random(10) . microtime()
        ]);

        if ($this->processConfirmPay($lastId, $wallet))
            return ['status' => true, 'message' => 200, 'message-detail' => __('message.Transfer made successfully'),
                'data' => [
                    'from_member' => $lastId->getFrom->user_name,
                    'to_member' => $lastId->getTo->user_name,
                    'amount' => $lastId->amount,
                    'remarks' => $lastId->remarks,
                    'wallet_name' => $lastId->getWallet->detail,
                ]];
        return ['status' => false, 'message' => 400, 'error' => __('message.Failed to transfer wallet')];
    }

    protected
    function processConfirmPay($walletPay, $walletType)
    {
        $fromMemberAsset = MemberAsset::where('member_id', $walletPay->from_id)->first();
        $toMemberAsset = MemberAsset::where('member_id', $walletPay->to_id)->first();

        switch ($walletType) {
            case 'ecash_wallet':
                $toMemberAsset->update(['ecash_wallet' => $toMemberAsset->ecash_wallet + $walletPay->amount]);
                $fromMemberAsset->update(['ecash_wallet' => $fromMemberAsset->ecash_wallet - $walletPay->amount]);
                $this->createWalletReport($walletPay->from_id, $walletPay->amount, 'Customer Wallet Transfer', 'ecash', 'OUT');
                $this->createWalletReport($walletPay->to_id, $walletPay->amount, 'Customer Wallet Transfer', 'ecash', 'IN');
                break;
            default:
                return false;
                break;
        }
        $walletPay->update(['flag' => 1]);
        return true;

    }
}
