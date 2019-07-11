<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Http\Traits\WalletsHistoryTrait;
use App\Library\ShoppingBonus;
use App\Models\Category;
use App\Models\Members\MemberAsset;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\UserPayLog;
use App\Models\UserPayment;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Zxing\QrReader;

class PaymentController extends Controller
{
    use WalletsHistoryTrait;
    private $_path = 'frontend.merchants';
    private $_data = [];

    public function __construct()
    {
        $categories = Category::where('status', 1)->get() ?? [];
        $this->_data['all_categories'] = collect($categories);
        $this->_data['home_categories'] = collect($categories)->take(8);
        $data = [];
        foreach ($categories as $category) {
            $catStatus = false;
            if (count($category->getSubCategory->where('status', 1)) !== 0)
                $catStatus = true;
            if ($catStatus)
                $data[] = $category;
        }
        $this->_data['categories'] = collect($data);
    }

    function paymentForm()
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        $this->_data['user'] = Auth::user();
        $this->_data['request'] = UserPayment::where('from_member_id', Auth::user()->id)->where('status', 1)->where('flag', 0)->get();
//        $this->_data['reports'] = UserPayment::where('from_member_id', Auth::id())->latest()->get();
        return view($this->_path . '.payment-form', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function merchantExist(Request $request)
    {
        $data = $this->checkMerchant($request->payment_to);
        if (!$data)
            return response()->json(['status' => false]);
        return response()->json(['status' => true, 'data' => $data]);
    }


    function qrMerchantExist(Request $request)
    {
//        if ($request->hasFile('qr_image')) {
//            $image = $request->file('qr_image')->getRealPath();
//            $qrcode = new QrReader($image);
//            $text = $qrcode->text(); //return decoded text from QR Code
//            $merchant_id = str_replace('merchant:', '', $text);
//        } else {
//            $merchant_id = 0;
//        }
//        $merchant = Merchant::find($merchant_id);
//        if (!$merchant) {
//            session()->flash('name', ' - ');
//            session()->flash('user_name', __('message.Username not found'));
//            session()->flash('qr_payment_to', '');
//            session()->flash('qr_status', false);
//        } else {
//            session()->flash('name', $merchant->name . ' ' . $merchant->surname);
//            session()->flash('user_name', $merchant->user_name);
//            session()->flash('qr_payment_to', $merchant->user_name);
//            session()->flash('qr_status', true);
//        }
//        session()->flash('qr', 'active');
//        return redirect()->back();
        if (!request()->ajax())
            return redirect()->back();
        if ($request->has('qr_data')) {
            $merchant_id = str_replace('merchant:', '', $request->qr_data);
        } else {
            $merchant_id = 0;
        }
        $merchant = Merchant::find($merchant_id);
        if ($merchant)
            return response()->json(['status' => true, 'success' => 'QR scan success! Merchant: '.$merchant->name . ' ' . $merchant->surname, 'user_name' => $merchant->user_name]);
        return response()->json(['status' => false, 'error' => __('message.Username not found')]);
    }

    function manualMakePayment(Request $request)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));

        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'payment_to' => 'required',
            'payment_method' => 'required',
            'amount' => 'required|numeric|min:0',
//            'transaction_pass' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

//        if (!Hash::check($request->transaction_pass, Auth::user()->transaction_password))
//            return redirect()->back()->withErrors(['transaction_pass' => __('message.Invalid transaction password')]);

        if (!($merchant = $this->checkMerchant($request->payment_to))) {
            $validator->errors()->add('payment_to',
                __('message.Invalid Merchant username'));
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }
//        return redirect()->back()->withErrors(['payment_to' => __('message.Invalid Merchant username')]);

        $fromMemberAsset = MemberAsset::where('member_id', Auth::id())->first();
        switch ($request->payment_method) {
            case 'ecash_wallet':
                if (($fromMemberAsset['ecash_wallet'] ?? 0) < $request->amount) {

                    $validator->errors()->add('amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
//                    return redirect()->back()->withErrors(['amount' => __('message.Insufficient Amount')]);
                }
                break;

            case 'evoucher_wallet':
                if (($fromMemberAsset['evoucher_wallet'] ?? 0) < $request->amount) {
                    $validator->errors()->add('amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
//                    return redirect()->back()->withErrors(['amount' => __('message.Insufficient Amount')]);
                }
                break;

            default:
                $validator->errors()->add('amount',
                    __('message.Insufficient Amount'));
                return response()->json(array(
                    'status' => 'fails',
                    'errors' => $validator->getMessageBag()->toArray()
                ));
//                return redirect()->back()->withErrors(['amount' => __('message.Insufficient Amount')]);
                break;
        }
        $wallet = Wallet::where('name', $request->payment_method)->first();

        $lastId = UserPayment::create([
            'from_member_id' => Auth::id(),
            'to_merchant_id' => $merchant->id,
            'amount' => $request->amount,
            'wallet_id' => $wallet->id,
//            'qr_token' => str_random(10) . microtime()
        ]);

        session()->flash('name', $merchant->name . ' ' . $merchant->surname);
        session()->flash('user_name', $merchant->user_name);

        if ($this->processConfirmPay($lastId, $request->payment_method)) {
            session()->flash('success', __('message.Successfully paid $:amount in :wallet', ['amount' => $request->amount, 'wallet' => $wallet->detail]));
            return response()->json(array(
                'status' => 'success',
                'url' => url('make-payment')
            ));

//            return redirect()->back()->withErrors(['success' => __('message.Successfully paid $:amount in :wallet', ['amount' => $request->amount, 'wallet' => $wallet->detail])]);
        }
        $validator->errors()->add('payment_to',
            __('message.Something went wrong'));
        return response()->json(array(
            'status' => 'fails',
            'errors' => $validator->getMessageBag()->toArray()
        ));
//                      return redirect()->back()->withErrors(['fail' => __('message.Something went wrong')]);
    }


    function qrMakePayment(Request $request)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        if (!request()->ajax()) {
            return back();
        }
        session()->flash('qr', 'active');
        $validator = Validator::make($request->all(), [
            'qr_payment_to' => 'required',
            'qr_payment_method' => 'required',
            'qr_amount' => 'required|numeric|min:0',
//            'qr_transaction_pass' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        $merchant = Merchant::where('user_name', $request->qr_payment_to)->first();
        session()->flash('name', $merchant->name . ' ' . $merchant->surname);
        session()->flash('user_name', $merchant->user_name);
        session()->flash('qr_payment_to', $merchant->user_name);
        session()->flash('qr_status', true);

//        if (!Hash::check($request->qr_transaction_pass, Auth::user()->transaction_password))
//            return redirect()->back()->withErrors(['qr_transaction_pass' => __('message.Invalid transaction password')]);

        if (!($merchant = $this->checkMerchant($request->qr_payment_to))) {
            $validator->errors()->add('qr_payment_to',
                __('message.Invalid Merchant username'));
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }
//        return redirect()->back()->withErrors(['qr_payment_to' => __('message.Invalid Merchant username')]);

        $fromMemberAsset = MemberAsset::where('member_id', Auth::id())->first();
        switch ($request->qr_payment_method) {
            case 'ecash_wallet':
                if (($fromMemberAsset['ecash_wallet'] ?? 0) < $request->qr_amount) {
                    $validator->errors()->add('qr_amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
//                    return redirect()->back()->withErrors(['qr_amount' => __('message.Insufficient Amount')]);
                }
                break;

            case 'evoucher_wallet':
                if (($fromMemberAsset['evoucher_wallet'] ?? 0) < $request->qr_amount) {
                    $validator->errors()->add('qr_amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
//                    return redirect()->back()->withErrors(['qr_amount' => __('message.Insufficient Amount')]);
                }
                break;

            default:
                $validator->errors()->add('qr_amount',
                    __('message.Insufficient Amount'));
                return response()->json(array(
                    'status' => 'fails',
                    'errors' => $validator->getMessageBag()->toArray()
                ));
//                return redirect()->back()->withErrors(['qr_amount' => __('message.Insufficient Amount')]);
                break;
        }
        $wallet = Wallet::where('name', $request->qr_payment_method)->first();

        $lastId = UserPayment::create([
            'from_member_id' => Auth::id(),
            'to_merchant_id' => $merchant->id,
            'amount' => $request->qr_amount,
            'wallet_id' => $wallet->id,
//            'qr_token' => str_random(10) . microtime()
        ]);

        session()->flash('name', $merchant->name . ' ' . $merchant->surname);
        session()->flash('user_name', $merchant->user_name);

        if ($this->processConfirmPay($lastId, $request->qr_payment_method)) {
            session()->flash('success', __('message.Successfully paid $:amount in :wallet', ['amount' => $request->qr_amount, 'wallet' => $wallet->detail]));
            return response()->json(array(
                'status' => 'success',
                'url' => url('make-payment')
            ));
//            return redirect()->back()->withErrors(['success' => __('message.Successfully paid $:amount in :wallet', ['amount' => $request->qr_amount, 'wallet' => $wallet->detail])]);
        }

        $validator->errors()->add('payment_to',
            __('message.Something went wrong'));
        return response()->json(array(
            'status' => 'fails',
            'errors' => $validator->getMessageBag()->toArray()
        ));
//        return redirect()->back()->withErrors(['fail' => __('message.Something went wrong')]);
    }


    protected
    function checkMerchant($username)
    {
        $user = Merchant::where('user_name', $username)->first();
        if ($user)
            if (MerchantAsset::where('merchant_id', $user->id)->first())
                return $user;
        return false;
    }

    protected
    function processConfirmPay($walletPay, $walletType)
    {
        $fromMemberAsset = MemberAsset::where('member_id', $walletPay->from_member_id)->first();
        $toMerchantAsset = MerchantAsset::where('merchant_id', $walletPay->to_merchant_id)->first();

        if (Auth::user()->is_member === 1) {
            $shopPayBonus = new ShoppingBonus();
            $amount = $shopPayBonus->paymentMemberBonus(Auth::id(), $walletPay);
        } else {
            $shopPayBonus = new ShoppingBonus();
            $amount = $shopPayBonus->paymentCustomerBonus(Auth::id(), $walletPay);
        }

        switch ($walletType) {
            case 'ecash_wallet':
                $toMerchantAsset->update(['ecash_wallet' => $toMerchantAsset->ecash_wallet + $amount]);
                $fromMemberAsset->update(['ecash_wallet' => $fromMemberAsset->ecash_wallet - $walletPay->amount]);
                $this->createWalletReport(Auth::id(), $walletPay->amount, 'Merchant Payment', 'ecash', 'OUT');
                break;

            case 'evoucher_wallet':
                $toMerchantAsset->update(['ecash_wallet' => $toMerchantAsset->ecash_wallet + $amount]);
                $fromMemberAsset->update(['evoucher_wallet' => $fromMemberAsset->evoucher_wallet - $walletPay->amount]);
                $this->createWalletReport(Auth::id(), $walletPay->amount, 'Merchant Payment', 'evoucher', 'OUT');
                break;
            default:
                return false;
                break;
        }
        $walletPay->update(['flag' => 1]);
        return true;
    }


    function acceptRequest($id, Request $request)
    {
        if (!request()->ajax()) {
            return back();
        }
        $transfer = UserPayment::where('id', $id)->where('flag', 0)->where('status', 1)->first();
        if ($transfer) {

            $toMemberAsset = MerchantAsset::where('merchant_id', $transfer->to_merchant_id)->first();
            $fromMemberAsset = MemberAsset::where('member_id', $transfer->from_member_id)->first();

            $wallet = Wallet::find($transfer->wallet_id)->name;
            if ($fromMemberAsset[$wallet] < $transfer->amount) {
                session()->flash('fail', __('message.Insufficient Amount'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('make-payment')
                ));
            }
            $this->processConfirmPay($transfer, 'ecash_wallet');

            session()->flash('success', __('message.Successfully paid $:amount in :wallet', ['amount' => $transfer->amount, 'wallet' => $transfer->getWallet->detail]));
            return response()->json(array(
                'status' => 'success',
                'url' => url('make-payment')
            ));
//            session()->flash('success', __('message.Payment made successfully'));
//            return redirect()->back();
        }

        session()->flash('fail', 'Payment Failed');
        return response()->json(array(
            'status' => 'success',
            'url' => url('make-payment')
        ));
    }


    function declineRequest($id)
    {
        if (!request()->ajax()) {
            return back();
        }
        $transfer = UserPayment::where('id', $id)->where('flag', 0)->where('status', 1)->first();
        if ($transfer) {
            $transfer->update(['flag' => 1, 'status' => 0, 'remarks' => 'Declined Request']);
            session()->flash('success', __('message.Request declined successfully'));
            return response()->json(array(
                'status' => 'success',
                'url' => url('make-payment')
            ));
        }
        session()->flash('fail', __('message.Something went wrong'));
        return response()->json(array(
            'status' => 'success',
            'url' => url('make-payment')
        ));
    }
}
