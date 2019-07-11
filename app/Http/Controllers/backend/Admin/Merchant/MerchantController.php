<?php

namespace App\Http\Controllers\backend\Admin\Merchant;

use App\Http\Controllers\Controller;
use App\Http\Traits\GrantRetainTrait;
use App\Library\AjaxResponse;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantController extends Controller
{
    use GrantRetainTrait;
    private $_path = 'backend.admin.merchant-master.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function showGrantWallet($id)
    {
        $this->_data['user'] = Merchant::find($id);
        $this->_data['walletType'] = array(
            'ecash_wallet' => 'Cash Wallet',
        );
        return view($this->_path . 'grant', $this->_data);
    }

    public function postGrantWallet(Request $request)
    {
        $inputs = $request->all();
        $memberId = $inputs['userId'];
        $validator = Validator::make($inputs, [
            'wallet_type' => 'required',
            'wallet_value' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return AjaxResponse::sendResponseData(422, 'fails', $validator->getMessageBag()->toArray());
        }
        if ($validator->passes()) {
            $member = Merchant::find($memberId);
            $type = $inputs['wallet_type'];
            $value = $inputs['wallet_value'];
            $memberData = MerchantAsset::where('merchant_id', $memberId)->first();
            $prevBalance = $memberData->$type;
            $newBalance = $value;
            $finalBalance = $prevBalance + $newBalance;

            $data = array(
                $type => $finalBalance,
            );
            $memberData->update($data);
            $this->createMerchantGrantRetainReport($memberId, $newBalance, 'GRANT');
            $successMsg = 'Grant ' . str_replace('_', ' ', $type) . ' For ' . $member->name . ' ' . $member->surname . ' Value ' . $value . ' Is Successfull';
            session()->flash('grantedSuccess', $successMsg);
            return AjaxResponse::sendResponseData('200', 'success', url('/admin/merchant/grant-wallet/' . $memberId), 'Wallet Granted Successfully');
        }
    }

    public function labelForWalletType($name)
    {
        if ($name == 'ecash_wallet') {
            return 'ecash';
        }
    }

    public function showRetainWallet($id)
    {
        $this->_data['user'] = Merchant::find($id);
        $this->_data['walletType'] = array(
            'ecash_wallet' => 'Cash Wallet',
        );
        return view($this->_path . 'retain', $this->_data);
    }

    public function postRetainWallet(Request $request)
    {
        $inputs = $request->all();
        $memberId = $inputs['userId'];
        $validator = Validator::make($inputs, [
            'wallet_type' => 'required',
            'wallet_value' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return AjaxResponse::sendResponseData(422, 'fails', $validator->getMessageBag()->toArray());
        }
        if ($validator->passes()) {
            $member = Merchant::find($memberId);
            $type = $inputs['wallet_type'];
            $value = $inputs['wallet_value'];
            $memberData = MerchantAsset::where('merchant_id', $memberId)->first();
            $prevBalance = $memberData->$type;
            $newBalance = $value;
            if ($prevBalance >= $newBalance) {
                $finalBalance = $prevBalance - $newBalance;


                $data = array(
                    $type => $finalBalance,
                );
                $memberData->update($data);
                $this->createMerchantGrantRetainReport($memberId, $newBalance, 'RETAIN');
                $successMsg = 'Retain ' . str_replace('_', ' ', $type) . ' For ' . $member->name . ' ' . $member->surname . ' Value ' . $value . ' Is Successfull';
                session()->flash('retainSuccess', $successMsg);
                return AjaxResponse::sendResponseData('200', 'success', url('/admin/merchant/retain-wallet'), 'Wallet Retain Successfully');
            } else {
                session()->flash('fail', 'Balance is less then Retain amount');
                return AjaxResponse::sendResponseData('200', 'success', url('/admin/merchant/retain-wallet'), 'Wallet Retain Successfully');

            }
        }
    }
}
