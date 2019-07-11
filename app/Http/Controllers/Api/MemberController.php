<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\backend\Member\TreeViewController;
use App\Http\Traits\NotificationTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Models\Members\Member;
use App\Models\Members\MemberAsset;
use App\Models\Members\MemberBankInfo;
use App\Models\Members\MemberCashWithdraw;
use App\Models\Members\MemberWalletConvert;
use App\Models\Members\MemberWalletTransfer;
use App\Models\Package;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WithdrawConfig;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;


class MemberController extends Controller
{
    use WalletsHistoryTrait,NotificationTrait;
    /**
     * @var \App\Http\Controllers\backend\Member\MemberController
     */
    private $memberController;
    /**
     * @var TreeViewController
     */
    private $treeViewController;

    public function __construct(\App\Http\Controllers\backend\Member\MemberController $memberController, TreeViewController $treeViewController)
    {


        $this->memberController = $memberController;
        $this->treeViewController = $treeViewController;
    }

    function memberDetail()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $json['data'] = $this->getMemberDetail($json['data']->id);
        }
        return response()->json($json);
    }

    function memberById(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $valid = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($valid->fails())
                return response()->json(['status' => false, 'message' => 422, 'error' => $valid->errors()->first()]);
            if (($detail = $this->getMemberDetail($request->id)) !== null)
                $json['data'] = $detail;
            else
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid id')]);
        }
        return response()->json($json);
    }

    function walletDetail()
    {
        $json = $this->getUser();
        if ($json['status']) {
            if (!($data = $this->getWalletDetail($json['data']->id)))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to retrieve Member asset')]);
            else
                $json['data'] = $data;
        }
        return response()->json($json);
    }

    function bankDetail()
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            if (!($data = $this->getBankDetail($json['data']->id)))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Empty Bank detail')]);
            else
                $json['data'] = $data;
        }
        return response()->json($json);
    }


    function convertWallet(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'towallet' => 'required',
                'amount' => 'required|numeric|min:100|max:5000',
                'transaction_pass' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }
            if ($validator->passes()) {
                if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {
                    $response = $this->processWalletConvert($json['data']->id, $request);
                    return response()->json($response);
                } else {
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
                }
            }
        }
        return response()->json($json);
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
            if ($id = User::where('user_name', $request->user_name)->where('is_member', 1)->first()->id ?? false) {
                if (MemberAsset::where('member_id', $id)->first()) {
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


    function qrUserExist(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {

            $validator = Validator::make($request->all(), [
                'qr_data' => 'required']);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            $user_id = str_replace('user:', '', $request->qr_data);

            if ($id = User::where('id', $user_id)->where('is_member', 1)->first()->id ?? false) {
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

    function generateTransfer(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'wallet_id' => 'required',
                'amount' => 'required|numeric|min:0',
                'to_member' => 'required',
                'transaction_pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {

                if ($tomemberid = $this->toMemberId($request->to_member)) {

                    $response = $this->processGenerateTransfer($json['data']->id, $tomemberid, $request);
                    return response()->json($response);
                } else {
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid member')]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
            }
        }
        return response()->json($json);
    }

//    function confirmTransfer(Request $request)
//    {
//        $json = $this->getAuthenticatedUser();
//        if ($json['status']) {
//            $validator = Validator::make($request->all(), [
//                'qr_token' => 'required',
//            ]);
//
//            if ($validator->fails()) {
//                return response()->json(['status' => false, 'message' => 422, 'message-detail' => $validator->errors()->first()],422);
//            }
//
//            if (!($transferData = $this->processConfirmTransfer($json['data']->id))) {
//                return response()->json(['status' => false, 'message' => 400, 'error' => 'Invalid wallet transfer'],400);
//            } else {
//                return response()->json(['status' => true, 'message' => 200, 'data' => $transferData]);
//            }
//        }
//        return response()->json($json);
//    }


    function walletWithdraw(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
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

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {
                $response = $this->processWalletWithdraw($json['data']->id, $request);
                return response()->json($response);
            } else
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);

        }
        return response()->json($json);
    }

    function walletList()
    {
        $json = $this->getUser();
        if ($json['status']) {
            if ($json['data']->is_member === 0)
                return response()->json(Wallet::select('id', 'name', 'detail')->whereIn('name', ['ecash_wallet'])->get());
            else
                return response()->json(Wallet::select('id', 'name', 'detail')->whereIn('name', ['ecash_wallet', 'evoucher_wallet'])->get());
        }
        return response()->json(Wallet::select('id', 'name', 'detail')->whereIn('name', ['ecash_wallet', 'evoucher_wallet', 'r_point', 'chip'])->get());
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
        //check member vs customer
        if ($user->is_member)
            return ['status' => true, 'message' => 200, 'data' => $user, 'message-detail' => __('message.success')];
        return ['status' => false, 'message' => 400, 'error' => __('message.not member')];
    }

    protected
    function getUser()
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
        //check member vs customer
        return ['status' => true, 'message' => 200, 'data' => $user, 'message-detail' => __('message.success')];
    }

    protected
    function getMemberDetail($id)
    {
        $member = User::find($id);
        if ($member)
            $member['country'] = User::find($id)->getCountry->name;
        return $member;
    }

    protected
    function getWalletDetail($id)
    {
        $member = MemberAsset::select('id', 'member_id', 'ecash_wallet', 'evoucher_wallet', 'r_point', 'chip', 'shop_point', 'capital')->where('member_id', $id)->first() ?? false;
        if (!$member)
            return false;
        return $member;
//            ['id' => $member->id, 'package' => $member->getPackage->name, 'wallet' => MemberAsset::select('member_id', 'ecash_wallet', 'evoucher_wallet', 'r_point', 'chip')->where('member_id', $id)->first()];
    }

    protected
    function getBankDetail($id)
    {
        $bank = MemberBankInfo::select('id', 'member_id', 'bank_name', 'acc_name', 'acc_number', 'contact_number')->where('member_id', $id)->first() ?? false;
        if (!$bank)
            return false;
        $bank['ecash_wallet'] = MemberAsset::where('member_id', $id)->first()->ecash_wallet ?? 0;
        return $bank;
    }

    protected
    function processWalletConvert($memberId, $req)
    {
        $status = false;
        $memberAsset = MemberAsset::where('member_id', $memberId)->first();
        if ($req->transferfrom == 'ecash_wallet') {
            if ($memberAsset['ecash_wallet'] < $req->amount) {
                return [
                    'status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')
                ];
            }
        } else if ($req->transferfrom == 'evoucher_wallet') {
            if ($memberAsset['evoucher_wallet'] < $req->amount) {
                return [
                    'status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')
                ];
            }
        }

        if (strtolower($req->transferfrom) == 'ecash_wallet') {
            switch (strtolower($req->towallet)) {
                case 'evoucher_wallet':
                    $memberAsset->update([
                        'evoucher_wallet' => $memberAsset->evoucher_wallet + $req->amount
                    ]);
                    $memberAsset->update([
                        'ecash_wallet' => $memberAsset->ecash_wallet - $req->amount
                    ]);
                    $this->createWalletReport($memberId, $req->amount, 'Wallet Convert - Ecash to Evoucher', 'ecash', 'OUT');
                    $this->createWalletReport($memberId, $req->amount, 'Wallet Convert - Ecash to Evoucher', 'evoucher', 'IN');
                    $status = $this->fillMemberWalletConvert($memberId, $req);
                    break;

                case 'r_point':

                    $memberAsset->update([
                        'r_point' => $memberAsset->r_point + $req->amount
                    ]);
                    $memberAsset->update([
                        'ecash_wallet' => $memberAsset->ecash_wallet - $req->amount
                    ]);
                    $this->createWalletReport($memberId, $req->amount, 'Wallet Convert - Ecash to R point', 'ecash', 'OUT');
                    $this->createWalletReport($memberId, $req->amount, 'Wallet Convert - Ecash to R point', 'rpoint', 'IN');
                    $status = $this->fillMemberWalletConvert($memberId, $req);
                    break;

                case 'chip':

                    $memberAsset->update([
                        'chip' => $memberAsset->chip + round($req->amount / 10)
                    ]);
                    $memberAsset->update([
                        'ecash_wallet' => $memberAsset->ecash_wallet - $req->amount
                    ]);
                    $this->createWalletReport($memberId, $req->amount, 'Wallet Convert - Ecash to Chip', 'ecash', 'OUT');
                    $this->createWalletReport($memberId, round($req->amount / 10), 'Wallet Convert - Ecash to Chip', 'chip', 'IN');
                    $status = $this->fillMemberWalletConvert($memberId, $req);

                    break;

                default:
                    break;
            }
        } elseif (strtolower($req->transferfrom) == 'evoucher_wallet') {

            switch (strtolower($req->towallet)) {

                case 'r_point':

                    $memberAsset->update([
                        'r_point' => $memberAsset->r_point + $req->amount
                    ]);
                    $memberAsset->update([
                        'evoucher_wallet' => $memberAsset->evoucher_wallet - $req->amount
                    ]);
                    $this->createWalletReport($memberId, $req->amount, 'Wallet Convert - Evoucher to R point', 'evoucher', 'OUT');
                    $this->createWalletReport($memberId, $req->amount, 'Wallet Convert - Evoucher to R point', 'rpoint', 'IN');
                    $status = $this->fillMemberWalletConvert($memberId, $req);

                    break;

                case 'chip':

                    $memberAsset->update([
                        'chip' => $memberAsset->chip + round($req->amount / 10)
                    ]);
                    $memberAsset->update([
                        'evoucher_wallet' => $memberAsset->evoucher_wallet - $req->amount
                    ]);
                    $this->createWalletReport($memberId, $req->amount, 'Wallet Convert - Evoucher to Chip', 'evoucher', 'OUT');
                    $this->createWalletReport($memberId, round($req->amount / 10), 'Wallet Convert - Evoucher to Chip', 'chip', 'IN');
                    $status = $this->fillMemberWalletConvert($memberId, $req);

                    break;

                default:
                    break;
            }
        } else {
            return [
                'status' => false, 'message' => 400, 'error' => __('message.Invalid from member Wallet')
            ];
        }
        if ($status)
            return ['status' => true, 'message' => 200, 'data' => $this->getWalletDetail($memberId), 'message-detail' => __('message.Wallet converted successfully')];
        return [
            'status' => false, 'message' => 400, 'error' => __('message.Invalid convert wallet type'), 'data' => $this->getWalletDetail($memberId)
        ];
    }

    protected
    function fillMemberWalletConvert($memberId, $req)
    {
        MemberWalletConvert::create([
            'member_id' => $memberId,
            'from_wallet_id' => Wallet::where('name', $req->transferfrom)->first()->id,
            'to_wallet_id' => Wallet::where('name', $req->towallet)->first()->id,
            'amount' => $req->amount,
        ]);
        return true;
    }

    protected
    function toMemberId($username)
    {
        $id = User::where('user_name', $username)->where('is_member', 1)->first()->id ?? false;
        if (MemberAsset::where('member_id', $id)->first() ?? false)
            return $id;
        return false;
    }

    protected
    function processGenerateTransfer($fromMemberId, $toMemberId, $req)
    {
//        $toMemberAsset = MemberAsset::where('member_id', $toMemberId)->first();
        if ($fromMemberId === $toMemberId) return ['status' => false, 'message' => 400, 'error' => __('message.Member cannot be same')];
        $fromMemberAsset = MemberAsset::where('member_id', $fromMemberId)->first();
        $wallet = Wallet::find($req->wallet_id)->name ?? 'no_name';
        if (($fromMemberAsset[$wallet] ?? 0) < $req->amount) {
            return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];
        }

        $lastID = MemberWalletTransfer::create([
            'from_member_id' => $fromMemberId,
            'to_member_id' => $toMemberId,
            'amount' => $req->amount,
            'wallet_id' => $req->wallet_id,
//            'qr_token' => str_random(10) . microtime()
        ]);
        if (!$this->processConfirmTransfer($lastID->id))
            return ['status' => false, 'message' => 400, 'error' => __('message.Failed to transfer wallet')];
        return ['status' => true, 'message' => 200, 'message-detail' => __('message.Wallet transfer done successfully'),
            'data' => [
                'from_member' => $lastID->getFromMember->user_name,
                'to_member' => $lastID->getToMember->user_name,
                'amount' => $lastID->amount,
                'remarks' => $lastID->remarks,
                'wallet_name' => $lastID->getwallet->detail,
            ]];
    }

    protected
    function processConfirmTransfer($transfer_id)
    {
        $walletTransfer = MemberWalletTransfer::find($transfer_id);

        if (!$walletTransfer) return false;

//        if ($id !== $walletTransfer->to_member_id) return false;
//        if (!$walletTransfer->status) return false;
//        if ($walletTransfer->flag) return false;

        $toMemberAsset = MemberAsset::where('member_id', $walletTransfer->to_member_id)->first();
        $fromMemberAsset = MemberAsset::where('member_id', $walletTransfer->from_member_id)->first();
        $wallet = Wallet::find($walletTransfer->wallet_id)->name ?? 'no_name';

        if (($fromMemberAsset[$wallet] ?? 0) < $walletTransfer->amount) return false;

        switch ($wallet) {

            case 'ecash_wallet':

                $toMemberAsset->update([
                    'ecash_wallet' => $toMemberAsset->ecash_wallet + $walletTransfer->amount
                ]);
                $fromMemberAsset->update([
                    'ecash_wallet' => $fromMemberAsset->ecash_wallet - $walletTransfer->amount
                ]);
                $this->createWalletReport($walletTransfer->to_member_id, $walletTransfer->amount, 'Wallet Transfer', 'ecash', 'IN');
                $this->createWalletReport($walletTransfer->from_member_id, $walletTransfer->amount, 'Wallet Transfer', 'ecash', 'OUT');
                break;

            case 'evoucher_wallet':

                $toMemberAsset->update([
                    'evoucher_wallet' => $toMemberAsset->evoucher_wallet + $walletTransfer->amount
                ]);

                $fromMemberAsset->update([
                    'evoucher_wallet' => $fromMemberAsset->evoucher_wallet - $walletTransfer->amount
                ]);
                $this->createWalletReport($walletTransfer->to_member_id, $walletTransfer->amount, 'Wallet Transfer', 'evoucher', 'IN');
                $this->createWalletReport($walletTransfer->from_member_id, $walletTransfer->amount, 'Wallet Transfer', 'evoucher', 'OUT');

                break;

            case 'r_point':

                $toMemberAsset->update([
                    'r_point' => $toMemberAsset->r_point + $walletTransfer->amount
                ]);
                $fromMemberAsset->update([
                    'r_point' => $fromMemberAsset->r_point - $walletTransfer->amount
                ]);
                $this->createWalletReport($walletTransfer->to_member_id, $walletTransfer->amount, 'Wallet Transfer', 'rpoint', 'IN');
                $this->createWalletReport($walletTransfer->from_member_id, $walletTransfer->amount, 'Wallet Transfer', 'rpoint', 'OUT');
                break;

            case 'chip';

                $toMemberAsset->update([
                    'chip' => $toMemberAsset->chip + $walletTransfer->amount
                ]);

                $fromMemberAsset->update([
                    'chip' => $fromMemberAsset->chip - $walletTransfer->amount
                ]);
                $this->createWalletReport($walletTransfer->to_member_id, $walletTransfer->amount, 'Wallet Transfer', 'chip', 'IN');
                $this->createWalletReport($walletTransfer->from_member_id, $walletTransfer->amount, 'Wallet Transfer', 'chip', 'OUT');

                break;

            default:
                return false;
                break;
        }
        $walletTransfer->update(['flag' => 1]);
        return true;
    }

    protected
    function processWalletWithdraw($id, $req)
    {
        $amount = MemberAsset::where('member_id', $id)->first()->ecash_wallet ?? 0;

        if ($amount < $req->amount) {
            return ['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')];
        }

//        $checkWithdraw = MemberCashWithdraw::where('member_id', $id)->where('created_at', '>', Carbon::now()->subDays(14))->first();
        $checkWithdraw = false;
        if (!$checkWithdraw) {
            if ($withdraw = MemberCashWithdraw::create([
                'member_id' => $id,
                'contact_number' => $req->contact_number,
                'amount' => $req->amount,
                'bank_name' => $req->bank_name,
                'acc_name' => $req->acc_name,
                'acc_number' => $req->acc_number,
                'remarks' => $req->remarks,
            ])) {
                $asset = MemberAsset::where('member_id', $withdraw->member_id)->first();
                $asset->update([
                    'ecash_wallet' => $asset->ecash_wallet - $withdraw->amount
                ]);
                $this->createWalletReport($withdraw->member_id, $withdraw->amount, 'Cash Withdrawal', 'ecash', 'OUT');
                $this->createNotificaton('admin', $withdraw->member_id, 'Wallet Withdrawal request by Member');

                return ['status' => true, 'message' => 200, 'message-detail' => __('message.Cash withdraw requested successfully')];
            } else {
                return ['status' => false, 'message' => 400, 'error' => __('message.MemberCashWithdraw error')];
            }
        }
//        return ['status' => false, 'message' => 400, 'error' => __('message.Invalid Withdraw request! Try again after 14 days of previous request!')];
        return ['status' => false, 'message' => 400, 'error' => __('message.Something went wrong')];
    }

    function postMemberRegister(Request $request)
    {

//        return $request->all();

//        return response()->json($request->all());
        $memberCreateProcess = $this->memberController->postMemberRegister($request);
        return $memberCreateProcess;

    }

    function checkMemberExistence(Request $request)
    {
        //PASS username name from request
        $memberExist = $this->memberController->checkMemberExist($request);
        if ($memberExist === 'false') {
            return ['status' => true, 'message' => 200, 'message-detail' => __('message.Login ID available!')];
        } else {
            return ['status' => false, 'message' => 400, 'error' => __('message.Login ID already used!')];
        }
    }

    function getPosition(Request $request)
    {
        //PASS parent name from request
        $position = $this->memberController->getPosition($request);
        return $position;
    }

    public function getPositionToMobile(Request $request)
    {
        return $this->memberController->getPositionToMobile($request);
    }

    public
    function getPackages()
    {
        $list = Package::select('name', 'id')->get() ?? false;
        if (!$list)
            return response()->json(['status' => false, 'message' => 400, 'error' => __('message.packages unavailable')]);
        return response()->json(['status' => true, 'message' => 200, 'data' => $list]);
    }

    function getStandardTree(Request $request)
    {
        //PASS username name from request
        if (empty($request->username)) {
            return response()->json([
                'status' => false,
                'message'=> 400,
                'error' => __('message.Username field is required')
            ], 422);
        }
        $member = User::where('user_name', $request->username)
            ->first();

        if ($member) {
            $standard = $this->treeViewController->getStandardTreeForMobile($request);
            return $standard;
        }
        return response()->json([
            'status' => false,
            'message' => 400,
            'error' => __('message.User not found')
        ]);
    }

    function getAutoTree(Request $request)
    {
        if (empty($request->username)) {
            return response()->json([
                'status' => false,
                'error' => __('message.Username field is required')
            ]);
        }
        $member = User::where('user_name', $request->username)
            ->first();
        if ($member) {
            $auto = $this->treeViewController->getAutoTreeForMobile($request);
            return $auto;

        }
        return response()->json([
            'status' => false,
            'error' => __('message.User not found')
        ]);

    }

    function getSpecialTree(Request $request)
    {
        if (empty($request->username)) {
            return response()->json([
                'status' => false,
                'message' => 400,
                'error' => __('message.Username field is required')
            ]);
        }
        $member = User::where('user_name', $request->username)
            ->first();
        if ($member) {
            $spec = $this->treeViewController->getSpecialTreeForMobile($request);
            return $spec;
        }
        return response()->json([
            'status' => false,
            'message' => 400,
            'error' => __('message.User not found')
        ]);
    }

    function walletRequestList()
    {
        $json = $this->getUser();
        if ($json['status']) {
            $list = MemberWalletTransfer::where('from_member_id', $json['data']->id)->where('flag', 0)->where('status', 1)->get();

            $json['requests'] = [];
            foreach ($list as $item) {
                $json['requests'][] = [
                    'id' => $item->id,
                    'name' => $item->getToMember->name,
                    'surname' => $item->getToMember->surname,
                    'user_name' => $item->getToMember->user_name,
                    'wallet' => $item->getWallet->detail,
                    'amount' => $item->amount,
                    'status' => $item->status,
                    'flag' => $item->flag,
                ];
            }
            if (!($data = $this->getWalletDetail($json['data']->id)))
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Failed to retrieve Member asset')]);
            else
                $json['data'] = $data;
        }
        return response()->json($json);
    }

    function requestTransfer(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'wallet_id' => 'required',
                'amount' => 'required|numeric|min:0',
                'from_member' => 'required',
                'transaction_pass' => 'required'
//                'remarks' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {

                if ($fromMemberId = $this->toMemberId($request->from_member)) {
                    $walletReq = MemberWalletTransfer::create([
                        'from_member_id' => $fromMemberId,
                        'to_member_id' => $json['data']->id,
                        'amount' => $request->amount,
                        'qr_token' => 'Transfer Request from app',
                        'wallet_id' => $request->wallet_id,
                        'remarks' => $request->remarks,
                    ]);
                    if ($walletReq)
                        return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Transfer requested successfully')]);
                    else
                        return response()->json(['status' => false, 'message' => 400, 'message-detail' => __('message.Failed to submit Wallet Request')]);
                } else {
                    return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid member')]);
                }
            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
            }
        }
        return response()->json($json);
    }


    function requestTransferApprove(Request $request)
    {
        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'transfer_id' => 'required',
                'transaction_pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {
                $transfer = MemberWalletTransfer::where('id', $request->transfer_id)->where('flag', 0)->where('status', 1)->first();
                if ($transfer) {
                    $toMemberAsset = MemberAsset::where('member_id', $transfer->to_member_id)->first();
                    $fromMemberAsset = MemberAsset::where('member_id', $transfer->from_member_id)->first();

                    $wallet = Wallet::find($transfer->wallet_id)->name;
                    if ($fromMemberAsset[$wallet] < $transfer->amount) {
                        return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Insufficient Amount')]);
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
                    return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Wallet transfer done successfully')]);
                }
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid Transfer Id')]);
            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
            }
        }
        return response()->json($json);
    }

    function requestTransferDecline(Request $request)
    {

        $json = $this->getAuthenticatedUser();
        if ($json['status']) {
            $validator = Validator::make($request->all(), [
                'transfer_id' => 'required',
                'transaction_pass' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 422, 'error' => $validator->errors()->first()]);
            }

            if (Hash::check($request->transaction_pass, User::find($json['data']->id)->transaction_password)) {
                $transfer = MemberWalletTransfer::where('id', $request->transfer_id)->where('flag', 0)->where('status', 1)->first();
                if ($transfer) {
                    $transfer->update(['flag' => 1, 'status' => 0, 'remarks' => 'Declined Request']);
                    return response()->json(['status' => true, 'message' => 200, 'message-detail' => __('message.Request declined successfully')]);
                }
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid Transfer Id')]);
            } else {
                return response()->json(['status' => false, 'message' => 400, 'error' => __('message.Invalid transaction password')]);
            }
        }
        return response()->json($json);
    }

    public function getIosPositionToMobile(Request $request)
    {
        return $this->memberController->getIosPositionToMobile($request);
    }
}
