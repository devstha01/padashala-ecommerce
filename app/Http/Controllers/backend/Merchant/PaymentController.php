<?php

namespace App\Http\Controllers\backend\Merchant;

use App\Http\Traits\MinMaxConfig;
use App\Http\Traits\NotificationTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Http\Traits\WalletSuccess;
use App\Models\Members\MemberAsset;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\MerchantBankInfo;
use App\Models\MerchantCashWithdraw;
use App\Models\MerchantWalletTransfer;
use App\Models\User;
use App\Models\WithdrawConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Zxing\QrReader;

class PaymentController extends Controller
{
    use WalletsHistoryTrait, WalletSuccess, MinMaxConfig, NotificationTrait;
    private $_data = [];
    private $_merchant_id = '';

    public function __construct()
    {
        $this->middleware('merchant');
        $this->middleware(function ($request, $next) {
            $this->_merchant_id = Auth::guard('merchant')->user()->id;
            return $next($request);
        });
    }


    public function managePayment()
    {
        $this->_data['wallet'] = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
//        $this->_data['request'] = UserPayment::where('to_merchant_id', $this->_merchant_id)->where('flag', 0)->get();
        return view('backend.merchant.business.payment-request', $this->_data);
    }

    public function managePaymentList()
    {
        $this->_data['wallet'] = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
        $this->_data['request'] = UserPayment::where('to_merchant_id', $this->_merchant_id)->where('flag', 0)->get();
        return view('backend.merchant.business.payment-request-list', $this->_data);
    }

    function qrCheckCustomer(Request $request)
    {

        if (!request()->ajax())
            return redirect()->back();
        if ($request->has('qr_data')) {
            $member_id = str_replace('user:', '', $request->qr_data);
        } else {
            $member_id = 0;
        }
        $member = User::where('id', $member_id)->first();
        if ($member)
            return response()->json(['status' => true, 'success' => 'QR scan success! Member: ' . $member->name . ' ' . $member->surname, 'user_name' => $member->user_name]);
        return response()->json(['status' => false, 'error' => __('message.Username not found')]);


//        $member = User::where('id', $member_id)->first();
//        if (!$member) {
//            session()->flash('name', ' - ');
//            session()->flash('user_name', __('message.Username not found'));
//            session()->flash('qr_payment_to', '');
//            session()->flash('qr_status', false);
//        } else {
//            session()->flash('name', $member->name . ' ' . $member->surname);
//            session()->flash('user_name', $member->user_name);
//            session()->flash('qr_payment_to', $member->user_name);
//            session()->flash('qr_status', true);
//        }
//        return redirect()->back();
    }

    function checkCustomer()
    {
        $input = Input::get('memberId');
        $user = User::where('user_name', $input)->first();
        if ($user) {
            return response()->json([
                'status' => true,
                'name' => $user->name . ' ' . $user->surname
            ]);
        }
        return response()->json([
            'status' => false
        ]);
    }


    function walletTransfer()
    {
        $this->_data['wallet'] = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
//        $this->_data['request'] = UserPayment::where('to_merchant_id', $this->_merchant_id)->where('flag', 0)->get();
        return view('backend.merchant.business.wallet-transfer', $this->_data);
    }

    function walletTransferPost(Request $request)
    {
        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'member_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if ($validator->passes()) {

            $memberId = User::where('user_name', $request->member_id)->first();
            if ($memberId) {
                $memberAsset = MemberAsset::where('member_id', $memberId->id)->first();
                if ($memberAsset) {

                    $merchantAsset = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
                    if (($merchantAsset->ecash_wallet ?? 0) < $request->amount) {
                        $validator->errors()->add('amount',
                            __('message.Insufficient Amount'));
                        return response()->json(array(
                            'status' => 'fails',
                            'errors' => $validator->getMessageBag()->toArray()
                        ));
                    }

                    $memberAsset->update(['ecash_wallet' => $memberAsset->ecash_wallet + $request->amount]);
                    $merchantAsset->update(['ecash_wallet' => $merchantAsset->ecash_wallet - $request->amount]);

                    MerchantWalletTransfer::create([
                        'from_merchant_id' => $this->_merchant_id,
                        'to_member_id' => $memberId->id,
                        'amount' => $request->amount,
                        'qr_token' => 'Wallet transfer by merchant to Customer',
                        'wallet_id' => 1,
                    ]);

//                    session()->flash('message', __('message.Wallet transfer done successfully'));
                    $this->flashSuccessPage(
                        __('dashboard.Wallet Transfer'),
                        __('message.Wallet transfer done successfully'),
                        'Wallet transfer amount $' . $request->amount . ' to ' . $request->member_id);
                    return response()->json(array(
                        'status' => 'success',
                        'url' => url('merchant/success')
                    ));
                }
            }
            $validator->errors()->add('member_id',
                __('message.Username not found'));
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));

        }
    }


    function walletTransferMerchant()
    {
        $this->_data['wallet'] = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
        //        $this->_data['request'] = UserPayment::where('to_merchant_id', $this->_merchant_id)->where('flag', 0)->get();
        return view('backend.merchant.business.wallet-transfer-merchant', $this->_data);
    }

    function walletTransferMerchantPost(Request $request)
    {
        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'member_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if ($validator->passes()) {

            $memberId = Merchant::where('user_name', $request->member_id)->first();
            if ($memberId) {
                $memberAsset = MerchantAsset::where('merchant_id', $memberId->id)->first();
                if ($memberAsset) {

                    $merchantAsset = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
                    if (($merchantAsset->ecash_wallet ?? 0) < $request->amount) {
                        $validator->errors()->add('amount',
                            __('message.Insufficient Amount'));
                        return response()->json(array(
                            'status' => 'fails',
                            'errors' => $validator->getMessageBag()->toArray()
                        ));
                    }

                    $memberAsset->update(['ecash_wallet' => $memberAsset->ecash_wallet + $request->amount]);
                    $merchantAsset->update(['ecash_wallet' => $merchantAsset->ecash_wallet - $request->amount]);

                    MerchantWalletTransferMerchant::create([
                        'from_merchant_id' => $this->_merchant_id,
                        'to_merchant_id' => $memberId->id,
                        'amount' => $request->amount,
                        'qr_token' => 'Paid by merchant to Merchant',
                        'wallet_id' => 1,
                    ]);

//                    session()->flash('message', __('message.Wallet transfer done successfully'));
                    $this->flashSuccessPage(
                        __('dashboard.Wallet Transfer'),
                        __('message.Wallet transfer done successfully'),
                        'Wallet transfer amount $' . $request->amount . ' to ' . $request->member_id);
                    return response()->json(array(
                        'status' => 'success',
                        'url' => url('merchant/success')
                    ));
                }
            }
            $validator->errors()->add('member_id',
                __('message.Username not found'));
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));

        }
    }


    function qrCheckMerchant(Request $request)
    {
        if (!request()->ajax())
            return redirect()->back();
        if ($request->has('qr_data')) {
            $member_id = str_replace('merchant:', '', $request->qr_data);
        } else {
            $member_id = 0;
        }
        if ($this->_merchant_id == $member_id)
            $member_id = 0;
        $member = Merchant::find($member_id);
        if ($member)
            return response()->json(['status' => true, 'success' => 'QR scan success! Merchant: ' . $member->name . ' ' . $member->surname, 'user_name' => $member->user_name]);
        return response()->json(['status' => false, 'error' => __('message.Username not found')]);

    }

    function checkMerchant()
    {
        $input = Input::get('memberId');
        $user = Merchant::where('user_name', $input)->first();
        if ($user->id !== $this->_merchant_id) {
            if ($user) {
                return response()->json([
                    'status' => true,
                    'name' => $user->name . ' ' . $user->surname
                ]);
            }
        }
        return response()->json([
            'status' => false
        ]);
    }


    function walletWithdraw()
    {
        $this->_data['wallet'] = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
        $this->_data['bank'] = MerchantBankInfo::where('merchant_id', $this->_merchant_id)->first();
        $this->_data['withdr'] = WithdrawConfig::where('name', 'wallet_withdraw')->first();
        $this->_data['min'] = ($withdr->min ?? 0) + 0;
        $this->_data['max'] = ($withdr->max ?? 5000) + 0;

        if (!$this->_data['bank'])
            return redirect()->to(route('merchant-edit-bank'))->with('info', __('message.Bank info required for withdraw'));
        return view('backend.merchant.business.withdraw', $this->_data);
    }

    function submitWalletWithdraw(Request $request)
    {
        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'contact_number' => 'required',
            'bank_name' => 'required',
            'acc_name' => 'required',
            'acc_number' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        $minmaxCheck = $this->validationMinMax('wallet_withdraw', $request->amount);

        if ($minmaxCheck['status'] === false) {
            $validator->errors()->add('amount',
                $minmaxCheck['error']);
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }


        $amount = MerchantAsset::where('merchant_id', $this->_merchant_id)->first()->ecash_wallet ?? 0;

        if ($amount < $request->amount) {
            $validator->errors()->add('amount',
                __('message.Insufficient Amount'));
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));

        }

        $checkWithdraw = false;
//        $checkWithdraw = MemberCashWithdraw::where('member_id', Auth::user()->id)->where('created_at', '>', Carbon::now()->subDays(14))->first();
        if (!$checkWithdraw) {
            if ($withdraw = MerchantCashWithdraw::create([
                'merchant_id' => $this->_merchant_id,
                'contact_number' => $request->contact_number,
                'amount' => $request->amount,
                'bank_name' => $request->bank_name,
                'acc_name' => $request->acc_name,
                'acc_number' => $request->acc_number,
                'remarks' => $request->remarks,
                'updated_by' => 1
            ])) {
                $asset = MerchantAsset::where('merchant_id', $withdraw->merchant_id)->first();
                $asset->update([
                    'ecash_wallet' => $asset->ecash_wallet - $withdraw->amount
                ]);

                $this->flashSuccessPage(
                    __('dashboard.Withdrawal Request'),
                    __('message.Cash withdraw requested successfully'),
                    'Withdraw request amount $' . $request->amount);

                $this->createNotificaton('admin', $this->_merchant_id, 'Wallet Withdrawal request by Merchant');

//                session()->flash('message', __('message.Cash withdraw requested successfully'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('merchant/success')
                ));
            } else {
                session()->flash('error', __('message.Something went wrong'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('merchant/wallet-withdraw')
                ));
            }
        }
        session()->flash('error', __('message.Something went wrong'));
//        session()->flash('error', __('message.Invalid Withdraw request! Try again after 14 days of previous request!'));
        //        return redirect()->back()->with('fail', 'Withdraw request exists! Try again after 14 days of previous request!');
        return response()->json(array(
            'status' => 'success',
            'url' => url('merchant/wallet-withdraw')
        ));
    }

    public function editBank()
    {
        $this->_data['wallet'] = MerchantAsset::where('merchant_id', $this->_merchant_id)->first();
        $this->_data['banks'] = MerchantBankInfo::where('merchant_id', $this->_merchant_id)->first();
        return view('backend.merchant.business.bank-edit', $this->_data);
    }

    public function updateBank(Request $request)
    {
        $input = $request->validate([
            'bank_name' => 'required',
            'acc_name' => 'required|string',
            'acc_number' => 'required|numeric',
            'contact_number' => 'required|numeric',
        ]);

        $input['merchant_id'] = $this->_merchant_id;

        session()->flash('update', false);
        $first = MerchantBankInfo::where('merchant_id', $this->_merchant_id)->first();
        if ($first) {
            if ($first->update($input)) {
                session()->flash('update', true);
                return redirect()->back()->with('success', __('message.Bank Info updated successfully'));
            }
        } else {
            if (MerchantBankInfo::create($input)) {
                session()->flash('update', true);
                return redirect()->back()->with('success', __('message.Bank info registered successfully'));
            }
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }
}
