<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Http\Traits\WalletsHistoryTrait;
use App\Models\Members\MemberAsset;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\MerchantWalletTransfer;
use App\Models\MerchantWalletTransferMerchant;
use App\Models\User;
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

    function userExist(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $validator = Validator::make($request->all(), [
                'user_name' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }
            if ($id = User::where('user_name', $request->user_name)->where('is_member', 1)->first()->id ?? false) {
                if (MemberAsset::where('member_id', $id)->first()) {
                    $merchant['data'] = User::find($id);
                    return response()->json($merchant);
                }
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Member not found')]);
        }
        return response()->json($merchant);
    }

    function qrUserExist(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
                'qr_data' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            $user_id = str_replace('user:', '', $request->qr_data);

            if ($id = User::where('id', $user_id)->where('is_member', 1)->first()->id ?? false) {
                if (MemberAsset::where('member_id', $id)->first() ?? false) {
                    $merchant['data'] = User::find($id);
                    return response()->json($merchant);
                }
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Member not found')]);
        }
        return response()->json($merchant);
    }

    function generateTransfer(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
//                'wallet_id' => 'required',
                'amount' => 'required|numeric|min:0',
                'to_member' => 'required',
                'transaction_pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, Merchant::find($merchant['data']->id)->transaction_password)) {

                if ($tomemberid = $this->toMemberId($request->to_member)) {

                    $response = $this->processGenerateTransfer($merchant['data']->id, $tomemberid, $request);
                    return response()->json($response);
                } else {
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid member')]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
            }
        }
        return response()->json($merchant);
    }


    protected
    function processGenerateTransfer($fromMerchantId, $toMemberId, $req)
    {
        $fromMerchantAsset = MerchantAsset::where('merchant_id', $fromMerchantId)->first();
//        $wallet = Wallet::find($req->wallet_id)->name ?? 'no_name';
        $wallet = 'ecash_wallet';
        if (($fromMerchantAsset['ecash_wallet'] ?? 0) < $req->amount) {
            return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];
        }

        $lastID = MerchantWalletTransfer::create([
            'from_merchant_id' => $fromMerchantId,
            'to_member_id' => $toMemberId,
            'amount' => $req->amount,
            'qr_token' => 'Paid by merchant to Member',
            'wallet_id' => $req->wallet_id,
        ]);
        $memberAsset = MemberAsset::where('member_id', $toMemberId)->first();
        switch ($wallet) {
            case'ecash_wallet':
                $fromMerchantAsset->update(['ecash_wallet' => $fromMerchantAsset->ecash_wallet - $req->amount]);
                $memberAsset->update(['ecash_wallet' => $memberAsset->ecash_wallet + $req->amount]);
                $this->createWalletReport($toMemberId, $req->amount, 'Wallet Transfer by Merchant', 'ecash', 'IN');
                return ['status' => true, 'message' => 200, 'message-detail' => __('message.Wallet transfer done successfully'),
                    'data' => [
                        'from_member' => $lastID->getFromMerchant->user_name,
                        'to_member' => $lastID->getToMember->user_name,
                        'amount' => $lastID->amount,
                        'remarks' => $lastID->remarks,
                        'wallet_name' => $lastID->getWallet->detail,
                    ]];
                break;
        }
        return ['status' => false, 'message' => 400, 'error' => __('message.Failed to transfer wallet')];
    }

    protected
    function toMemberId($username)
    {
        $id = User::where('user_name', $username)->where('is_member', 1)->first()->id ?? false;
        if (MemberAsset::where('member_id', $id)->first() ?? false)
            return $id;
        return false;
    }


//    merchant transfer


    function merchantExist(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $validator = Validator::make($request->all(), [
                'user_name' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if ($exist = Merchant::where('user_name', $request->user_name)->first()) {
                if ($exist->id !== $merchant['data']->id) {
                    $merchant['data'] = $exist;
                    return response()->json($merchant);
                }
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Merchant not found')]);
        }
        return response()->json($merchant);
    }

    function qrMerchantExist(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
                'qr_data' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            $user_id = str_replace('merchant:', '', $request->qr_data);

            if ($exist = Merchant::find($user_id)) {
                if ($exist->id !== $merchant['data']->id) {
                    $merchant['data'] = $exist;
                    return response()->json($merchant);
                }
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Merchant not found')]);
        }
        return response()->json($merchant);
    }

    function generateTransferMerchant(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
//                'wallet_id' => 'required',
                'amount' => 'required|numeric|min:0',
                'to_merchant' => 'required',
                'transaction_pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, Merchant::find($merchant['data']->id)->transaction_password)) {

                if ($exist = Merchant::where('user_name', $request->to_merchant)->first()) {
                    if ($exist->id !== $merchant['data']->id) {
                        $response = $this->processWalletTransferMerchant($merchant['data']->id, $exist->id, $request);
                        return response()->json($response);
                    }
                }
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid merchant')]);

            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
            }
        }
        return response()->json($merchant);
    }


    protected
    function processWalletTransferMerchant($fromMerchantId, $toMerchantId, $req)
    {
        $fromMerchantAsset = MerchantAsset::where('merchant_id', $fromMerchantId)->first();
//        $wallet = Wallet::find($req->wallet_id)->name ?? 'no_name';
        $wallet = 'ecash_wallet';
        if (($fromMerchantAsset['ecash_wallet'] ?? 0) < $req->amount) {
            return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];
        }

        $lastID = MerchantWalletTransferMerchant::create([
            'from_merchant_id' => $fromMerchantId,
            'to_merchant_id' => $toMerchantId,
            'amount' => $req->amount,
            'qr_token' => 'Paid by merhcant to merchant',
            'wallet_id' => 1
        ]);
        $toMerchantAsset = MerchantAsset::where('merchant_id', $toMerchantId)->first();
        switch ($wallet) {
            case'ecash_wallet':
                $fromMerchantAsset->update(['ecash_wallet' => $fromMerchantAsset->ecash_wallet - $req->amount]);
                $toMerchantAsset->update(['ecash_wallet' => $toMerchantAsset->ecash_wallet + $req->amount]);
                return ['status' => true, 'message' => 200, 'message-detail' => __('message.Wallet transfer done successfully'),
                    'data' => [
                        'from_merchant' => $lastID->getFromMerchant->user_name,
                        'to_merchant' => $lastID->getToMerchant->user_name,
                        'amount' => $lastID->amount,
                        'remarks' => $lastID->remarks,
                        'wallet_name' => $lastID->getWallet->detail,
                    ]];
                break;
        }
        return ['status' => false, 'message' => 400, 'error' => __('message.Failed to transfer wallet')];
    }

}
