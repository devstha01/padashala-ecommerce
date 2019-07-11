<?php

namespace App\Http\Controllers\Api\Merchant;

use App\Models\Commisions\CashDeliveryBonusRecord;
use App\Models\MerchantAsset;
use App\Models\User;
use App\Models\UserPayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PayRequestController extends Controller
{
    function getWallet(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);

        if ($merchant['status']) {
            $wallet = MerchantAsset::where('merchant_id', $merchant['data']->id)->first();
            $merchant['wallet'] = [
                'name' => 'Cash Wallet',
                'amount' => $wallet->ecash_wallet,
            ];
        }
        return response()->json($merchant);
    }

    function customerExist(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if ($user = User::where('user_name', $request->user_name)->first() ?? false) {
                $merchant['data'] = $user;
                return response()->json($merchant);
            }
            return response()->json(['status' => false, 'message' => 404, 'error' => 'Customer not found ']);
        }
        return response()->json($merchant);
    }

    function qrCustomerExist(Request $request)
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

            if ($user = User::find($user_id) ?? false) {
                $merchant['data'] = $user;
                return response()->json($merchant);
            }
            return response()->json(['status' => false, 'message' => 404, 'error' => 'Customer not found ']);
        }
        return response()->json($merchant);
    }

    function requestPay(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $validator = Validator::make($request->all(), [
                'amount' => 'required|numeric|min:0',
                'user_name' => 'required',
                'transaction_pass' => 'required',
            ]);

            if ($validator->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);

            if (Hash::check($request->transaction_pass, $merchant['data']->transaction_password)) {
                $memberId = User::where('user_name', $request->user_name)->first();
                if ($memberId) {
                    $pay = UserPayment::create([
                        'from_member_id' => $memberId->id,
                        'to_merchant_id' => $merchant['data']->id,
                        'amount' => $request->amount,
                        'qr_token' => 'Payment Request from app',
                        'wallet_id' => 1,
                        'flag' => 0,
                    ]);
                    if ($pay)
                        return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Request submmitted successfully']);
                    else
                        return response()->json(['status' => false, 'message' => 403, 'error' => 'Something went wrong']);
                } else
                    return response()->json(['status' => false, 'message' => 403, 'error' => 'Invalid Customer ']);
            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => 'transaction password unmatched']);
            }
        }
        return response()->json($merchant);
    }

    function requestList(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);

        if ($merchant['status']) {
            $list = UserPayment::where('to_merchant_id', $merchant['data']->id)->where('flag', 0)->get();
            $merchant['requests'] = [];
            foreach ($list as $item) {
                $merchant['requests'][] = [
                    'id' => $item->id,
                    'name' => $item->getFromMember->name,
                    'surname' => $item->getFromMember->surname,
                    'user_name' => $item->getFromMember->user_name,
                    'wallet' => $item->getWallet->detail,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'flag' => $item->flag,
                ];
            }
        }
        return response()->json($merchant);
    }

    function cancelRequest(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $validator = Validator::make($request->all(), [
                'request_id' => 'required',
                'transaction_pass' => 'required',
            ]);
            if ($validator->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);

            if (Hash::check($request->transaction_pass, $merchant['data']->transaction_password)) {
                $transfer = UserPayment::where('id', $request->request_id)->where('flag', 0)->where('status', 1)->first();
                if ($transfer) {
                    $transfer->update(['flag' => 1, 'status' => 0, 'remarks' => 'Cancelled Request by merchant']);
                    return response()->json(['status' => true, 'message' => 200, 'message-detail' => 'Request cancelled successfully ']);
                }
                return response()->json(['status' => false, 'message' => 403, 'error' => 'Invalid request id ']);
            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => 'transaction password unmatched']);
            }
        }
        return response()->json($merchant);
    }

    function cashDeliveryAdminBonus(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {
            $data = CashDeliveryBonusRecord::where('merchant_id', $merchant['data']->id)->where('paid_status', 0)->where('status', 1)->get();
            $merchant['data'] = [];
            foreach ($data as $item) {
                $merchant['data'][] = [
                    'id' => $item->id,
                    'invoice' => $item->getOrderItem->invoice,
                    'product' => $item->getOrderItem->getProduct->name,
                    'variant' => $item->getOrderItem->getProductVariant->name ?? ' - ',
                    'pay_method' => 'Cash on Delivery',
                    'net_amount' => $item->total,
                    'admin_amount' => $item->admin
                ];
            }
        }
        return response()->json($merchant);
    }

    function cashDeliveryAdminBonusConfirm(Request $request)
    {
        $json = new LoginController();
        $merchant = $json->getAuthenticatedMerchant($request);
        if ($merchant['status']) {

            $validator = Validator::make($request->all(), [
                'submit_id' => 'required',
                'transaction_pass' => 'required',
            ]);
            if ($validator->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);

            if (Hash::check($request->transaction_pass, $merchant['data']->transaction_password)) {
                $record = CashDeliveryBonusRecord::where('id', $request->submit_id)->where('paid_status', 0)->where('status', 1)->first();
                if ($record) {
                    if ($record->status) {
                        if ($record->merchant_id == $merchant['data']->id) {
                            $merchantAsset = MerchantAsset::where('merchant_id', $merchant['data']->id)->first();
                            if ($merchantAsset) {
                                if ($merchantAsset->ecash_wallet < $record->admin) {
                                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')]);
                                } else {
                                    $merchantAsset->update(['ecash_wallet' => $merchantAsset->ecash_wallet - $record->admin]);
                                    $record->update(['paid_status' => 1]);
                                    return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Bonus submitted successfully'),
                                        'data' => ['id' => $record->id,
                                            'invoice' => $record->getOrderItem->invoice,
                                            'product' => $record->getOrderItem->getProduct->name,
                                            'variant' => $record->getOrderItem->getProductVariant->name ?? ' - ',
                                            'pay_method' => 'Cash on Delivery',
                                            'net_amount' => $record->total,
                                            'admin_amount' => $record->admin
                                        ]]);
                                }
                            }
                        }
                    }
                }
                return response()->json(['status' => false, 'message' => 403, 'error' => 'Invalid submit id ']);
            }
            return response()->json(['status' => false, 'message' => 400, 'error' => 'transaction password unmatched']);
        }
        return response()->json($merchant);
    }
}
