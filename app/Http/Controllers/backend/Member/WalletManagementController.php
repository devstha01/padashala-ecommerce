<?php

namespace App\Http\Controllers\backend\Member;

use App\Http\Traits\MinMaxConfig;
use App\Http\Traits\NotificationTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Http\Traits\WalletSuccess;
use App\Models\Commisions\Shopping;
use App\Models\Commisions\ShoppingWithdraw;
use App\Models\Members\DividendWithdraw;
use App\Models\Members\MemberAsset;
use App\Models\Members\MemberBankInfo;
use App\Models\Members\MemberCashWithdraw;
use App\Models\Members\MemberWalletConvert;
use App\Models\Members\MemberWalletTransfer;
use App\Models\Merchant;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawConfig;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Zxing\QrReader;

class WalletManagementController extends Controller
{
    use WalletsHistoryTrait, WalletSuccess, MinMaxConfig, NotificationTrait;
    private $_path = 'backend.member.wallet';

    public function __construct()
    {
        $this->middleware('member');
    }

    public function convertWallet()
    {
        $member = Auth::user();
        $wallet = MemberAsset::where('member_id', $member->id)->first();
        return view($this->_path . '.convert', compact('member', 'wallet'))->with('title', __('message.Convert wallet'));
    }

    function qrCheckMember(Request $request)
    {
        if (!request()->ajax())
            return redirect()->back();
        if ($request->has('qr_data')) {
            $member_id = str_replace('user:', '', $request->qr_data);
        } else {
            $member_id = 0;
        }
        $member = User::where('id', $member_id)->where('is_member', 1)->first();
        if ($member)
            return response()->json(['status' => true, 'success' => 'QR scan success! Member: ' . $member->name . ' ' . $member->surname, 'user_name' => $member->user_name]);
        return response()->json(['status' => false, 'error' => __('message.Username not found')]);
//
//        if ($request->hasFile('qr_image')) {
//            $image = $request->file('qr_image')->getRealPath();
//            $qrcode = new QrReader($image);
//            $text = $qrcode->text(); //return decoded text from QR Code
//            $member_id = str_replace('user:', '', $text);
//        } else {
//            $member_id = 0;
//        }
//        $member = User::where('id', $member_id)->where('is_member', 1)->first();
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

    public function postWalletConvert(Request $request)
    {
        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'transferto' => 'required|in:ecash_wallet,evoucher_wallet',
            'wallet' => 'required',
            'amount' => 'required|numeric|min:100|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if ($validator->passes()) {
            $memberId = Auth::user()->id;
            $memberAsset = MemberAsset::where('member_id', $memberId)->first();

            if ($request->transferto == 'ecash_wallet') {

                if ($memberAsset['ecash_wallet'] < $request->amount) {
                    $validator->errors()->add('amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
                }
            } else if ($request->transferto == 'evoucher_wallet') {
                if ($memberAsset['evoucher_wallet'] < $request->amount) {
                    $validator->errors()->add('amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
                }
            }


            if ($request->transferto == 'ecash_wallet') {

                switch ($request->wallet) {

                    case 'evoucher_wallet':

                        $memberAsset->update([
                            'evoucher_wallet' => $memberAsset->evoucher_wallet + $request->amount
                        ]);

                        $memberAsset->update([
                            'ecash_wallet' => $memberAsset->ecash_wallet - $request->amount
                        ]);

                        $this->createWalletReport($memberId, $request->amount, 'Wallet Convert - Ecash to Evoucher', 'ecash', 'OUT');
                        $this->createWalletReport($memberId, $request->amount, 'Wallet Convert - Ecash to Evoucher', 'evoucher', 'IN');
                        $this->fillWalletConvert($request);

                        break;

                    case 'r_point':

                        $memberAsset->update([
                            'r_point' => $memberAsset->r_point + $request->amount
                        ]);
                        $memberAsset->update([
                            'ecash_wallet' => $memberAsset->ecash_wallet - $request->amount
                        ]);
                        $this->createWalletReport($memberId, $request->amount, 'Wallet Convert - Ecash to R point', 'ecash', 'OUT');
                        $this->createWalletReport($memberId, $request->amount, 'Wallet Convert - Ecash to R point', 'rpoint', 'IN');
                        $this->fillWalletConvert($request);

                        break;

                    case 'chip':

                        $memberAsset->update([
                            'chip' => $memberAsset->chip + round($request->amount / 10)
                        ]);
                        $memberAsset->update([
                            'ecash_wallet' => $memberAsset->ecash_wallet - $request->amount
                        ]);
                        $this->createWalletReport($memberId, $request->amount, 'Wallet Convert - Ecash to Chip', 'ecash', 'OUT');
                        $this->createWalletReport($memberId, round($request->amount / 10), 'Wallet Convert - Ecash to Chip', 'chip', 'IN');
                        $this->fillWalletConvert($request);

                        break;

                    default:
                        break;
                }
            } else {

                switch ($request->wallet) {

                    case 'r_point':

                        $memberAsset->update([
                            'r_point' => $memberAsset->r_point + $request->amount
                        ]);
                        $memberAsset->update([
                            'evoucher_wallet' => $memberAsset->evoucher_wallet - $request->amount
                        ]);
                        $this->createWalletReport($memberId, $request->amount, 'Wallet Convert - Evoucher to R point', 'evoucher', 'OUT');
                        $this->createWalletReport($memberId, $request->amount, 'Wallet Convert - Evoucher to R point', 'rpoint', 'IN');
                        $this->fillWalletConvert($request);

                        break;

                    case 'chip':

                        $memberAsset->update([
                            'chip' => $memberAsset->chip + round($request->amount / 10)
                        ]);
                        $memberAsset->update([
                            'evoucher_wallet' => $memberAsset->evoucher_wallet - $request->amount
                        ]);
                        $this->createWalletReport($memberId, $request->amount, 'Wallet Convert - Evoucher to Chip', 'evoucher', 'OUT');
                        $this->createWalletReport($memberId, round($request->amount / 10), 'Wallet Convert - Evoucher to Chip', 'chip', 'IN');
                        $this->fillWalletConvert($request);

                        break;

                    default:
                        break;
                }
            }


//            session()->flash('message', __('message.Wallet converted successfully'));
            return response()->json(array(
                'status' => 'success',
                'url' => url('member/success')
            ));
        }
    }

    public function transferWallet()
    {
        $member = Auth::user();
        $wallet = MemberAsset::where('member_id', $member->id)->first();
        return view($this->_path . '.transfer', compact('member', 'wallet'))->with('title', __('message.Transfer wallet'));
    }

    public function getWalletValueFromSelection()
    {
        $input = Input::get('option');
        $asset = MemberAsset::where('member_id', Auth::user()->id)->first();
        switch ($input) {
            case 'ecash_wallet':
                $value = $asset->ecash_wallet;
                break;
            case 'evoucher_wallet':
                $value = $asset->evoucher_wallet;
                break;
            case 'r_point':
                $value = $asset->r_point;
                break;
            case 'chip';
                $value = $asset->chip;
                break;
            default:
                $value = null;
                break;
        }
        return response()->json(['value' => $value]);
    }

    public function getNewAmount()
    {
        $input = Input::get('transfer_amount');
        $wallet = Input::get('wallet');
        $asset = MemberAsset::where('member_id', Auth::user()->id)->first();
        switch ($wallet) {
            case 'ecash_wallet':
                $value = $asset->ecash_wallet - $input;
                break;
            case 'evoucher_wallet':
                $value = $asset->evoucher_wallet - $input;
                break;
            case 'r_point':
                $value = $asset->r_point - $input;
                break;
            case 'chip';
                $value = $asset->chip - $input;
                break;
            default:
                $value = null;
                break;
        }
        return response()->json($value);
    }

    public function checkMember()
    {
        $input = Input::get('memberId');
        $user = User::where('user_name', $input)->where('is_member', 1)->first();
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

    public function postWalletTransfer(Request $request)
    {

        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'wallet' => 'required',
            'amount' => 'required|numeric|min:0',
            'member_id' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        $minmaxCheck = $this->validationMinMax($request->wallet, $request->amount);

        if ($minmaxCheck['status'] === false) {
            $validator->errors()->add('amount',
                $minmaxCheck['error']);
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if ($validator->passes()) {

            $memberId = User::where('user_name', $request->member_id)->where('is_member', 1)->first();


            if ($memberId) {

                $toMemberAsset = MemberAsset::where('member_id', $memberId->id)->first();

                $authUser = Auth::user();

                $fromMemberAsset = MemberAsset::where('member_id', $authUser->id)->first();

                if ($fromMemberAsset[$request->wallet] < $request->amount) {
                    $validator->errors()->add('amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
                }

                switch ($request->wallet) {

                    case 'ecash_wallet':

                        $toMemberAsset->update([
                            'ecash_wallet' => $toMemberAsset->ecash_wallet + $request->amount
                        ]);
                        $fromMemberAsset->update([
                            'ecash_wallet' => $fromMemberAsset->ecash_wallet - $request->amount
                        ]);
                        $this->createWalletReport($memberId->id, $request->amount, 'Wallet Transfer', 'evoucher', 'IN');
                        $this->createWalletReport($authUser->id, $request->amount, 'Wallet Transfer', 'evoucher', 'OUT');
                        $this->fillWalletTransfer($request);

                        break;

                    case 'evoucher_wallet':

                        $toMemberAsset->update([
                            'evoucher_wallet' => $toMemberAsset->evoucher_wallet + $request->amount
                        ]);

                        $fromMemberAsset->update([
                            'evoucher_wallet' => $fromMemberAsset->evoucher_wallet - $request->amount
                        ]);
                        $this->fillWalletTransfer($request);
                        $this->createWalletReport($memberId->id, $request->amount, 'Wallet Transfer', 'ecash', 'IN');
                        $this->createWalletReport($authUser->id, $request->amount, 'Wallet Transfer', 'ecash', 'OUT');

                        break;

                    case 'r_point':

                        $toMemberAsset->update([
                            'r_point' => $toMemberAsset->r_point + $request->amount
                        ]);
                        $fromMemberAsset->update([
                            'r_point' => $fromMemberAsset->r_point - $request->amount
                        ]);
                        $this->createWalletReport($memberId->id, $request->amount, 'Wallet Transfer', 'rpoint', 'IN');
                        $this->createWalletReport($authUser->id, $request->amount, 'Wallet Transfer', 'rpoint', 'OUT');
                        $this->fillWalletTransfer($request);

                        break;

                    case 'chip';

                        $toMemberAsset->update([
                            'chip' => $toMemberAsset->chip + $request->amount
                        ]);

                        $fromMemberAsset->update([
                            'chip' => $fromMemberAsset->chip - $request->amount
                        ]);
                        $this->createWalletReport($memberId->id, $request->amount, 'Wallet Transfer', 'chip', 'IN');
                        $this->createWalletReport($authUser->id, $request->amount, 'Wallet Transfer', 'chip', 'OUT');
                        $this->fillWalletTransfer($request);

                        break;

                    default:
                        break;
                }

                $wallet = Wallet::where('name', $request->wallet)->first()->detail ?? '';

                $this->flashSuccessPage(
                    __('dashboard.Wallet Transfer'),
                    __('message.Wallet transfer done successfully')
                    , "Wallet transfer amount $" . $request->amount . " in " . $wallet . " to " . $request->member_id);
//                session()->flash('message', __('message.Wallet transfer done successfully'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('member/success')
                ));
            } else {
                $validator->errors()->add('member_id',
                    __('message.Member not found'));
                return response()->json(array(
                    'status' => 'fails',
                    'errors' => $validator->getMessageBag()->toArray()
                ));
            }

        }
    }

    function walletWithdraw()
    {
        $member = Auth::user();
        $wallet = MemberAsset::where('member_id', $member->id)->first();
        $bank = MemberBankInfo::where('member_id', $member->id)->first();
        $withdr = WithdrawConfig::where('name', 'wallet_withdraw')->first();
        $min = ($withdr->min ?? 0) + 0;
        $max = ($withdr->max ?? 5000) + 0;

        if (!$bank)
            return redirect()->to(route('edit-bank', $member->id))->with('info', __('message.Bank info required for withdraw'));
        return view($this->_path . '.withdraw', compact('member', 'wallet', 'bank', 'min', 'max'))->with('title', __('message.E-cash Withdraw'));
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


        $amount = MemberAsset::where('member_id', Auth::user()->id)->first()->ecash_wallet ?? 0;

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
            if ($withdraw = MemberCashWithdraw::create([
                'member_id' => Auth::user()->id,
                'contact_number' => $request->contact_number,
                'amount' => $request->amount,
                'bank_name' => $request->bank_name,
                'acc_name' => $request->acc_name,
                'acc_number' => $request->acc_number,
                'remarks' => $request->remarks,
                'updated_by' => 1
            ])) {
                $asset = MemberAsset::where('member_id', $withdraw->member_id)->first();
                $asset->update([
                    'ecash_wallet' => $asset->ecash_wallet - $withdraw->amount
                ]);
                $this->createWalletReport($withdraw->member_id, $request->amount, 'Cash Withdrawal', 'ecash', 'OUT');

                $this->flashSuccessPage(
                    __('dashboard.Withdrawal Request'),
                    __('message.Cash withdraw requested successfully'),
                    'Withdraw request amount $' . $request->amount);

                $this->createNotificaton('admin', Auth::id(), 'Wallet Withdrawal request by Member');

//                session()->flash('message', __('message.Cash withdraw requested successfully'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('member/success')
                ));
            } else {
                session()->flash('error', __('message.Something went wrong'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('member/wallet-withdraw')
                ));
            }
        }
        session()->flash('error', __('message.Something went wrong'));
//        session()->flash('error', __('message.Invalid Withdraw request! Try again after 14 days of previous request!'));
        //        return redirect()->back()->with('fail', 'Withdraw request exists! Try again after 14 days of previous request!');
        return response()->json(array(
            'status' => 'success',
            'url' => url('member/wallet-withdraw')
        ));
    }

    protected function fillWalletConvert($req)
    {
        $memberId = Auth::user()->id;
        $convert = MemberWalletConvert::create([
            'member_id' => $memberId,
            'from_wallet_id' => Wallet::where('name', $req->transferto)->first()->id,
            'to_wallet_id' => Wallet::where('name', $req->wallet)->first()->id,
            'amount' => $req->amount,
            'remarks' => ''
        ]);
        $from = $convert->getFromWallet->detail;
        $to = $convert->getToWallet->detail;
        $detail = "Wallet convert amount $" . $req->amount . " from " . $from . " to " . $to;
        $this->flashSuccessPage(__('dashboard.Wallet Convert'), __('message.Wallet converted successfully'), $detail);
    }

    protected function fillWalletTransfer($req)
    {
        $frommemberId = Auth::user()->id;
        $tomemberId = User::where('user_name', $req->member_id)->first()->id;

        MemberWalletTransfer::create([
            'from_member_id' => $frommemberId,
            'to_member_id' => $tomemberId,
            'amount' => $req->amount,
            'wallet_id' => Wallet::where('name', $req->wallet)->first()->id,
            'flag' => 1,
            'qr_token' => 'transferred from web',
            'remarks' => ''
        ]);
    }


    function transferRequestWallet()
    {
        $member = Auth::user();
        $wallet = MemberAsset::where('member_id', $member->id)->first();
        $request = MemberWalletTransfer::where('from_member_id', $member->id)->where('flag', 0)->where('status', 1)->get();
        return view($this->_path . '.transfer-request', compact('member', 'wallet', 'request'))->with('title', __('message.Transfer Wallet Request'));
    }

    public function postWalletTransferRequest(Request $request)
    {
        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'wallet' => 'required',
            'amount' => 'required|numeric|min:0',
            'member_id' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        $minmaxCheck = $this->validationMinMax($request->wallet, $request->amount);

        if ($minmaxCheck['status'] === false) {
            $validator->errors()->add('amount',
                $minmaxCheck['error']);
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if ($validator->passes()) {

            $memberId = User::where('user_name', $request->member_id)->where('is_member', 1)->first();

            if ($memberId) {
                MemberWalletTransfer::create([
                    'from_member_id' => $memberId->id,
                    'to_member_id' => Auth::id(),
                    'amount' => $request->amount,
                    'qr_token' => 'Transfer Request from web',
                    'wallet_id' => Wallet::where('name', $request->wallet)->first()->id,
                ]);

                $wallet = Wallet::where('name', $request->wallet)->first()->detail ?? '';

                $this->flashSuccessPage(
                    __('dashboard.Wallet Transfer Request'),
                    __('message.Transfer requested successfully')
                    , "Wallet transfer request amount $" . $request->amount . " in " . $wallet . " from " . $request->member_id);

                return response()->json(array(
                    'status' => 'success',
                    'url' => url('member/success')
                ));
            } else {
                $validator->errors()->add('member_id',
                    __('message.Member not found'));
                return response()->json(array(
                    'status' => 'fails',
                    'errors' => $validator->getMessageBag()->toArray()
                ));
            }

        }
    }

    function WalletTransferApprove($id)
    {
        $transfer = MemberWalletTransfer::where('id', $id)->where('flag', 0)->where('status', 1)->first();
        if ($transfer) {

            $toMemberAsset = MemberAsset::where('member_id', $transfer->to_member_id)->first();
            $fromMemberAsset = MemberAsset::where('member_id', $transfer->from_member_id)->first();

            $wallet = Wallet::find($transfer->wallet_id)->name;
            if ($fromMemberAsset[$wallet] < $transfer->amount) {
                session()->flash('message', __('message.Insufficient Amount'));
                return response()->json(array(
                    'status' => 'fails',
                    'url' => url('member/wallet-transfer-request')
                ));
            }

            switch ($wallet) {

                case 'ecash_wallet':

                    $toMemberAsset->update([
                        'ecash_wallet' => $toMemberAsset->ecash_wallet + $transfer->amount
                    ]);
                    $fromMemberAsset->update([
                        'ecash_wallet' => $fromMemberAsset->ecash_wallet - $transfer->amount
                    ]);
                    $this->createWalletReport($transfer->to_member_id, $transfer->amount, 'Wallet Transfer', 'ecash', 'IN');
                    $this->createWalletReport($transfer->from_member_id, $transfer->amount, 'Wallet Transfer', 'ecash', 'OUT');
                    break;

                case 'evoucher_wallet':

                    $toMemberAsset->update([
                        'evoucher_wallet' => $toMemberAsset->evoucher_wallet + $transfer->amount
                    ]);

                    $fromMemberAsset->update([
                        'evoucher_wallet' => $fromMemberAsset->evoucher_wallet - $transfer->amount
                    ]);

                    $this->createWalletReport($transfer->to_member_id, $transfer->amount, 'Wallet Transfer', 'evoucher', 'IN');
                    $this->createWalletReport($transfer->from_member_id, $transfer->amount, 'Wallet Transfer', 'evoucher', 'OUT');
                    break;

                case 'r_point':

                    $toMemberAsset->update([
                        'r_point' => $toMemberAsset->r_point + $transfer->amount
                    ]);
                    $fromMemberAsset->update([
                        'r_point' => $fromMemberAsset->r_point - $transfer->amount
                    ]);

                    $this->createWalletReport($transfer->to_member_id, $transfer->amount, 'Wallet Transfer', 'rpoint', 'IN');
                    $this->createWalletReport($transfer->from_member_id, $transfer->amount, 'Wallet Transfer', 'rpoint', 'OUT');
                    break;

                case 'chip';

                    $toMemberAsset->update([
                        'chip' => $toMemberAsset->chip + $transfer->amount
                    ]);

                    $fromMemberAsset->update([
                        'chip' => $fromMemberAsset->chip - $transfer->amount
                    ]);

                    $this->createWalletReport($transfer->to_member_id, $transfer->amount, 'Wallet Transfer', 'chip', 'IN');
                    $this->createWalletReport($transfer->from_member_id, $transfer->amount, 'Wallet Transfer', 'chip', 'OUT');
                    break;

                default:
                    break;
            }

            $transfer->update(['flag' => 1]);

            $detail = "Wallet transfer amount $" . $transfer->amount . " in " . $transfer->getWallet->detail . " to " . $transfer->getToMember->user_name;
            $this->flashSuccessPage(__('dashboard.Wallet Transfer'), __('message.Transfer approved successfully'), $detail);

//            session()->flash('message', __('message.Transfer approved successfully'));
            return response()->json(array(
                'status' => 'success',
                'url' => url('member/success')
            ));
        }
        session()->flash('message', __('message.Transfer failed'));
        return response()->json(array(
            'status' => 'fails',
            'url' => url('member/wallet-transfer-request')
        ));

    }

    function WalletTransferDecline($id)
    {
        $transfer = MemberWalletTransfer::where('id', $id)->where('flag', 0)->where('status', 1)->first();
        if ($transfer) {
            $transfer->update(['flag' => 1, 'status' => 0, 'remarks' => 'Declined Request']);

            $detail = "Decline wallet transfer amount $" . $transfer->amount . " in " . $transfer->getWallet->detail . " to " . $transfer->getToMember->user_name;
            $this->flashSuccessPage(__('dashboard.Wallet Transfer'), __('message.Request declined successfully'), $detail);

//            session()->flash('message', __('message.Request declined successfully'));
            return response()->json(array(
                'status' => 'success',
                'url' => url('member/success')
            ));

        }
        session()->flash('message', __('message.Action failed'));
        return response()->json(array(
            'status' => 'fails',
            'url' => url('member/wallet-transfer-request')
        ));

    }

    function shopWithdraw()
    {
        $member = Auth::user();
        $wallet = MemberAsset::where('member_id', $member->id)->first();
        return view($this->_path . '.shopping', compact('member', 'wallet'))->with('title', __('message.Transfer wallet'));

    }

    function shopCalculate(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0'
        ]);
        if ($valid->fails())
            return response()->json(['status' => false, 'data' => ['cash' => 0, 'voucher' => 0, 'bid' => 0]]);

        $rate = Shopping::all()->keyBy('key');
        $cash = (($rate['ecash_shopping_bonus']->value / 100) * $request->amount) / 1000;
        $voucher = (($rate['evoucher_shopping_bonus']->value / 100) * $request->amount) / 1000;
        $bid = (($rate['bcoin_shopping_bonus']->value / 100) * $request->amount) / 1000;

        $cash = number_format($cash, 4);
        $voucher = number_format($voucher, 4);
        $bid = number_format($bid, 4);

        return response()->json(['status' => true, 'data' => ['cash' => $cash, 'voucher' => $voucher, 'bid' => $bid]]);
    }

    function shopWithdrawPost(Request $request)
    {

        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if ($validator->passes()) {

            $memberId = Auth::id();

            if ($memberId) {

                $memberAsset = MemberAsset::where('member_id', $memberId)->first();

                if ($memberAsset->shop_point < $request->amount) {
                    $validator->errors()->add('amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
                }

                $rate = Shopping::all()->keyBy('key');
                $cash = (($rate['ecash_shopping_bonus']->value / 100) * $request->amount) / 1000;
                $voucher = (($rate['evoucher_shopping_bonus']->value / 100) * $request->amount) / 1000;
                $bid = (($rate['bcoin_shopping_bonus']->value / 100) * $request->amount) / 1000;

                $cash = number_format($cash, 4);
                $voucher = number_format($voucher, 4);
                $bid = number_format($bid, 4);

                $create = ShoppingWithdraw::create([
                    'member_id' => $memberId,
                    'shop_point' => $request->amount,
                    'ecash_wallet' => $cash,
                    'evoucher_wallet' => $voucher,
                    'chip' => $bid,
                    'remarks' => $request->remarks,
                ]);
                if ($create)
                    $this->createWalletReport($memberId, $cash, 'Shopping Point Withdraw', 'ecash', 'IN');
                $this->createWalletReport($memberId, $voucher, 'Shopping Point Withdraw', 'evoucher', 'IN');
                $this->createWalletReport($memberId, $bid, 'Shopping Point Withdraw', 'chip', 'IN');

                $memberAsset->update([
                    'ecash_wallet' => $memberAsset->ecash_wallet + $cash,
                    'evoucher_wallet' => $memberAsset->evoucher_wallet + $voucher,
                    'chip' => $memberAsset->chip + $bid,
                    'shop_point' => $memberAsset->shop_point - $request->amount,
                ]);

                $this->flashSuccessPage(__('dashboard.Shopping Point Withdraw'), __('message.Shopping withdraw done successfully'), 'Shopping point withdraw amount $' . $request->amount);
//                session()->flash('message', __('message.Shopping withdraw done successfully'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('member/success')
                ));
            } else {
                $validator->errors()->add('member_id',
                    __('message.Member not found'));
                return response()->json(array(
                    'status' => 'fails',
                    'errors' => $validator->getMessageBag()->toArray()
                ));
            }

        }
    }

    function dividendWithdraw()
    {
        $member = Auth::user();
        $wallet = MemberAsset::where('member_id', $member->id)->first();
        $current_amount = $wallet->capital - $wallet->capital_withdraw;
        $reports = DividendWithdraw::where('member_id', $member->id)->orderBy('id', 'DESC')->get();
        return view($this->_path . '.withdraw-dividend', compact('member', 'wallet', 'current_amount', 'reports'));
    }

    function dividendWithdrawPost(Request $request)
    {
        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {

            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if ($validator->passes()) {

            $memberId = Auth::id();

            if ($memberId) {

                $memberAsset = MemberAsset::where('member_id', $memberId)->first();
                $current_amount = $memberAsset->capital - $memberAsset->capital_withdraw;

                if ($current_amount < $request->amount) {
                    $validator->errors()->add('amount',
                        __('message.Insufficient Amount'));
                    return response()->json(array(
                        'status' => 'fails',
                        'errors' => $validator->getMessageBag()->toArray()
                    ));
                }

                $create = DividendWithdraw::create([
                    'member_id' => $memberId,
                    'amount' => $request->amount,
                ]);
                if ($create)
                    $this->createWalletReport($memberId, $request->amount, 'Dividend Transform', 'ecash', 'IN');

                $memberAsset->update([
                    'ecash_wallet' => $memberAsset->ecash_wallet + $request->amount,
                    'capital_withdraw' => $memberAsset->capital_withdraw + $request->amount,
                ]);

                $this->flashSuccessPage(__('dashboard.Dividend Transform'), __('message.Dividend Transform done successfully'), 'Dividend transform amount ' . $request->amount);
//                session()->flash('message', __('message.Shopping withdraw done successfully'));
                return response()->json(array(
                    'status' => 'success',
                    'url' => url('member/success')
                ));
            } else {
                $validator->errors()->add('member_id',
                    __('message.Member not found'));
                return response()->json(array(
                    'status' => 'fails',
                    'errors' => $validator->getMessageBag()->toArray()
                ));
            }

        }

    }
}
