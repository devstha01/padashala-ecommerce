<?php

namespace App\Http\Controllers\backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\GrantRetainTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Library\AjaxResponse;
use App\Models\Country;
use App\Models\Holiday;
use App\Models\Members\MemberAsset;
use App\Models\Members\MemberNominee;
use App\Models\Members\MemberStandardTree;
use App\Models\Package;
use App\Models\PlacementPosition;
use App\Models\UpgradeCustomer;
use App\Models\User;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    use WalletsHistoryTrait;
    use GrantRetainTrait;

    //
    /**
     * @var MemberRepository
     */
    private $memberRepository;
    /**
     * @var MemberStandardTree
     */
    private $memberStandardTree;
    /**
     * @var User
     */
    private $member;
    /**
     * @var Package
     */
    private $package;
    /**
     * @var MemberAsset
     */
    private $asset;

    public function __construct(MemberRepository $memberRepository, MemberStandardTree $memberStandardTree, User $member, Package $package, MemberAsset $asset)
    {

        $this->memberRepository = $memberRepository;
        $this->memberStandardTree = $memberStandardTree;
        $this->member = $member;
        $this->package = $package;
        $this->asset = $asset;
    }

    public function showRegister()
    {


        $data = [];
        $data['countries'] = Country::all()->pluck('name', 'id');
        $data['positions'] = PlacementPosition::pluck('position', 'id');
        $data['packages'] = Package::where('status', 1)->pluck('name', 'id');
        $data['identificationType'] = array(
            'citizenship' => 'Citizenship',
            'passport' => 'Passport',
        );
        return view('backend.member.register.create', $data);
    }


    public function memberLists(Request $request)
    {
        $data = [];
        $memberData = $this->member->where('is_member', 1);
        if ($request->input('firstName') != '') {
            $memberData = $memberData->where('name', $request->input('firstName'));
        }
        if ($request->input('surname') != '') {
            $memberData = $memberData->where('surname', $request->input('surname'));
        }

        if ($request->input('loginid') != '') {
            $memberData = $memberData->where('user_name', $request->input('loginid'));
        }
        if ($request->input('IDPassport') != '') {
            $memberData = $memberData->where('identification_number', $request->input('IDPassport'));
        }

        if ($request->input('startdate') != '') {
            $memberData = $memberData->where('created_at', '>=', date('Y-m-d', strtotime($request->input('startdate'))) . ' 00:00:00');
        }

        if ($request->input('enddate') != '') {
            $memberData = $memberData->where('created_at', '<=', date('Y-m-d', strtotime($request->input('enddate'))) . ' 00:00:00');
        }
        $data['members'] = $memberData->paginate(20);
        $data['defaultMember'] = $this->member->orderby('id', 'asc')->where('is_member', 1)->first();
        return view('backend.member.register.list', $data);
    }

    public function editMember($memberId)
    {
        if (!\Auth::guard('admin')->check()) {
            abort(401);
        }

        $data = [];

        $data['nominee'] = MemberNominee::where('member_id', $memberId)->first();
        $data['asset'] = MemberAsset::where('member_id', $memberId)->first();
        $data['member'] = User::findorfail($memberId);
        $data['countries'] = Country::all()->pluck('name', 'id');
        $data['positions'] = PlacementPosition::pluck('position', 'id');
        $data['packages'] = Package::where('status', 1)->pluck('name', 'id');
        $data['identificationType'] = array(
            'citizenship' => 'Citizenship',
            'passport' => 'Passport',
        );

        return view('backend.member.register.edit', $data);
    }


    function memberProfile($memberId)
    {
        $data = [];
        $data['user'] = User::where('id', $memberId)->where('is_member', 1)->first();
        return view('backend.member.register.profile', $data);

    }

    public function showGrantWallet($memberId)
    {
        $data = [];
        $data['user'] = User::where('id', $memberId)->where('is_member', 1)->first();
        $data['walletType'] = array(
            'ecash_wallet' => 'Cash Wallet',
            'evoucher_wallet' => 'Voucher Wallet',
            'r_point' => 'R Wallet',
            'chip' => 'Chip',
        );
        return view('backend.member.register.grant', $data);
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
            $member = User::where('id', $memberId)->where('is_member', 1)->first();
            $type = $inputs['wallet_type'];
            $value = $inputs['wallet_value'];
            $memberData = MemberAsset::where('member_id', $memberId)->first();
            $prevBalance = $memberData->$type;
            $newBalance = $value;
            $finalBalance = $prevBalance + $newBalance;

            $data = array(
                $type => $finalBalance,
            );
            $memberData->update($data);
            $this->createWalletReport($memberId, $newBalance, 'Granted By Admin', $this->labelForWalletType($type), 'IN');
            $this->createGrantRetainReport($memberId, $newBalance, 'GRANT');
            $successMsg = 'Grant ' . str_replace('_', ' ', $type) . ' For ' . $member->name . ' Value ' . $value . ' Is Successfull';
            session()->flash('grantedSuccess', $successMsg);
            return AjaxResponse::sendResponseData('200', 'success', url('/admin/grant-member-wallet'), 'Wallet Granted Successfully');
        }


    }

    public function labelForWalletType($name)
    {
        if ($name == 'ecash_wallet') {
            return 'ecash';
        }
        if ($name == 'evoucher_wallet') {
            return 'evoucher';
        }
        if ($name == 'r_point') {
            return 'rpoint';
        }
        if ($name == 'chip') {
            return 'chip';
        }
    }

    public function showRetainWallet($memberId)
    {
        $data = [];
        $data['user'] = User::where('id', $memberId)->where('is_member', 1)->first();
        $data['walletType'] = array(
            'ecash_wallet' => 'Cash Wallet',
            'evoucher_wallet' => 'Voucher Wallet',
            'r_point' => 'R Wallet',
            'chip' => 'Chip',
        );
        return view('backend.member.register.retain', $data);
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
            $member = User::where('id', $memberId)->where('is_member', 1)->first();
            $type = $inputs['wallet_type'];
            $value = $inputs['wallet_value'];
            $memberData = MemberAsset::where('member_id', $memberId)->first();
            $prevBalance = $memberData->$type;
            $newBalance = $value;
            if ($prevBalance >= $newBalance) {
                $finalBalance = $prevBalance - $newBalance;


                $data = array(
                    $type => $finalBalance,
                );
                $memberData->update($data);
                $this->createWalletReport($memberId, $newBalance, 'Wallet Retain', $this->labelForWalletType($type), 'OUT');
                $this->createGrantRetainReport($memberId, $newBalance, 'RETAIN');
                $successMsg = 'Retain ' . str_replace('_', ' ', $type) . ' For ' . $member->name . ' Value ' . $value . ' Is Successfull';
                session()->flash('retainSuccess', $successMsg);
                return AjaxResponse::sendResponseData('200', 'success', url('/admin/retain-member-wallet'), 'Wallet Retain Successfully');
            } else {
                session()->flash('fail', 'Balance is less then Retain amount');
                return AjaxResponse::sendResponseData('200', 'success', url('/admin/retain-member-wallet'), 'Wallet Retain Successfully');

            }
        }


    }


    function upgradeCustomer()
    {
        $data = [];
        $data['users'] = UpgradeCustomer::all();
        return view('backend.admin.dashboard.upgrade', $data);
    }


}
