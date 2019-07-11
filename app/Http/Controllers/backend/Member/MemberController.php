<?php

namespace App\Http\Controllers\backend\Member;

use App\Http\Controllers\Controller;
use App\Http\Traits\DailyBonusTrait;
use App\Http\Traits\NotificationTrait;
use App\Library\AjaxResponse;
use App\Mail\WelcomeEmail;
use App\Models\Country;
use App\Models\Holiday;
use App\Models\Members\MemberAsset;
use App\Models\Members\MemberNominee;
use App\Models\Members\MemberStandardTree;
use App\Models\Package;
use App\Models\PlacementPosition;
use App\Models\User;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;

class MemberController extends Controller
{
    use NotificationTrait;
    use DailyBonusTrait;

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

    public function postMemberRegister(Request $request)
    {
//        if (!request()->ajax()) {
//            return back();
//        }
//        return $request->all();

        $validator = Validator::make($request->all(), [
            'gender' => 'required',
            'surname' => 'required',
            'name' => 'required',
            'marital_status' => 'required',
            'contact_number' => 'required|numeric',
            'identification_type' => 'required',
            'identification_number' => 'required',
            'email' => 'required|email|unique:users,email',
            'dob' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
            'joining_date' => 'required',
            'address' => 'sometimes',
//            'nominee_name' => 'required',
//            'nominee_contact_number' => 'required|numeric',
//            'nominee_identification_type_id'=>'required',
//            'nominee_identification_number' => 'required',
//            'relationship' => 'required',
            'user_name' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'transaction_password' => 'required|min:6|confirmed',
            'transaction_password_confirmation' => 'required|min:6',
            'sponser_id' => 'required',
            'package_id' => 'required',
            'position_id' => 'required|in:1,2,3,4,5',
            'country' => 'required',
        ]);
        if ($validator->fails()) {
            return AjaxResponse::sendResponseData(422, 'fails', $validator->getMessageBag()->toArray());
        }


        $checkProcess = $this->checkMemberRegisterValidationProcess($request, $validator);
        if ($checkProcess) {
            return $checkProcess;
        }
        if ($validator->passes()) {
            $memberRegistration = $this->memberRepository->processRegistration($request);
//            session()->flash('message',__('message.Member registered successfully'));
            $package = $this->package->where('id', $request->package_id)->first();
            $newMember = User::where('id', $memberRegistration)->where('is_member', 1)->first();
            $successMessage = 'Member ' . $newMember->name . ' ' . '(' . $newMember->user_name . ')' . ' has been created successfully on ' . $package->name . ' package';
            session()->flash('successMemberRegister', $successMessage);
            $redirect = '/member/add-new-member';
            if ($request->role == 'admin' && \Auth::guard('admin')->check()) {
                $redirect = '/admin/member-profile/' . $memberRegistration;
            }
//            Mail::to($request->email)->send(new WelcomeEmail('member', $newMember->name . ' ' . $newMember->surname));

            return AjaxResponse::sendResponseData('200', 'success', url($redirect), __('message.Member registered successfully'));
        }

        return redirect()->back();

    }

    public function postMemberValidation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gender' => 'required',
            'surname' => 'required',
            'name' => 'required',
            'marital_status' => 'required',
            'contact_number' => 'required|numeric',
            'identification_type' => 'required',
            'identification_number' => 'required',
            'email' => 'required|email|unique:users,email',
            'dob' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
            'joining_date' => 'required',
            'address' => 'sometimes',
            'user_name' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'transaction_password' => 'required|min:6|confirmed',
            'transaction_password_confirmation' => 'required|min:6',
            'sponser_id' => 'required',
            'package_id' => 'required',
            'position_id' => 'required|in:1,2,3,4,5',
            'country' => 'required',
        ]);
        if ($validator->fails()) {
            return AjaxResponse::sendResponseData(422, 'fails', $validator->getMessageBag()->toArray());
        }
        $checkProcess = $this->checkMemberRegisterValidationProcess($request, $validator);
        if ($checkProcess) {
            return $checkProcess;
        }
        if ($validator->passes()) {
            return AjaxResponse::sendResponseData('200', 'success', url('/member/add-new-member'), 'Validatoion Success');
        }
    }

    //if Member exist then return  member_id else return false

    public function checkMemberRegisterValidationProcess($request, $validator)
    {
        if (!empty($request->sponser_id)) {
            $sponsor = $this->member->where('user_name', $request->sponser_id)->first();
            if (empty($sponsor)) {
                $validation_errors = $validator->getMessageBag()->toArray();
                $validation_errors['sponser_id'] = __('message.Sponsor DoesNot Exist');
                return AjaxResponse::sendResponseData(422, 'fails', $validation_errors);
            }
            if (!\Auth::guard('admin')->check()) {
                $oppoSponsorCheck = $this->checkOppositeMemberSpill($sponsor);
                if (!$oppoSponsorCheck) {
                    $validation_errors = $validator->getMessageBag()->toArray();
                    $validation_errors['sponser_id'] = __('message.Cannot Use this Sponsor');
                    return AjaxResponse::sendResponseData(422, 'fails', $validation_errors);
                }
            }
            $sponsorMinMemberCheck = $this->checkSponsorMinimumMember($sponsor,$request->parent_id);
            if (!$sponsorMinMemberCheck) {
                $validation_errors = $validator->getMessageBag()->toArray();
                $validation_errors['sponser_id'] = ('Cannot use this sponsor at the moment');
                return AjaxResponse::sendResponseData(422, 'fails', $validation_errors);
            }

        }
        if (!empty($request->parent_id)) {
            $parent = $this->member->where('user_name', $request->parent_id)->first();
            if (empty($parent)) {
                $validation_errors = $validator->getMessageBag()->toArray();
                $validation_errors['parent_id'] = __('message.Parent DoesNot Exist');
                return AjaxResponse::sendResponseData(422, 'fails', $validation_errors);
            }
            if (!\Auth::guard('admin')->check()) {
                $check = $this->checkOppositeMemberSpill($parent);
                if (!$check) {
                    $validation_errors = $validator->getMessageBag()->toArray();
                    $validation_errors['parent_id'] = __('message.Cannot create under this Spill');
                    return AjaxResponse::sendResponseData(422, 'fails', $validation_errors);
                }
            }


        }
        if (!empty($request->package_id)) {
            $package = $this->package->where('id', $request->package_id)->first();
            if (empty($package)) {
                $validation_errors = $validator->getMessageBag()->toArray();
                $validation_errors['parent_id'] = __('message.Package DoesNot Exist');
                return AjaxResponse::sendResponseData(422, 'fails', $validation_errors);
            }
            if (!empty($package)) {
                $packageValue = $package->amount;
                $sponsor = $this->member->where('user_name', $request->sponser_id)->first();
                $sponsorRpoint = $this->asset->where('member_id', $sponsor->id)->first()->r_point;
                if ($sponsorRpoint < $packageValue) {
                    $validation_errors = $validator->getMessageBag()->toArray();
                    $validation_errors['sponser_id'] = __('message.Sponsor DoesNot Have Enough R wallet To Create Member On this Package');
                    return AjaxResponse::sendResponseData(422, 'fails', $validation_errors);
                }
            }

        }
        if (!empty($request->position_id)) {
            $parent = $this->member->where('user_name', $request->parent_id)->first();
            $No_available_placement = $this->memberStandardTree
                ->where('parent_id', $parent->id)
                ->where('placement_position_id', $request->position_id)
                ->first();
            if ($No_available_placement or $request->position_id > 5) {
                $validation_errors = $validator->getMessageBag()->toArray();

//                $validation_errors['placement_position_id'] = __('message.Placement Not Available For This Parent');

                $validation_errors['position_id'] = ' Placement Position Not Available For This Parent';

                return AjaxResponse::sendResponseData(422, 'fails', $validation_errors);
            }
        }


    }
    public function checkSponsorMinimumMember($sponsor,$spillName){
        $sponsorId=$sponsor->id;
        $sponsorName=$sponsor->user_name;
        $totalChild = $this->memberStandardTree
            ->where('parent_id', $sponsorId)->count();

        if($totalChild < 5 && $sponsorName !=$spillName){
            return 0;
        }
        return 1;


    }

    public function checkOppositeMemberSpill($spill)
    {
        $sameNodeMemberChild = [];
        $findNodeOfMember = $this->memberStandardTree
            ->where('member_id', Auth::id())
            ->first()->node;
        $sameNodeMember = $this->memberStandardTree
            ->where('node', $findNodeOfMember)
            ->where('member_id', '!=', Auth::id())
            ->pluck('member_id')->toArray();
        if ($sameNodeMember) {
            $sameNodeMemberChild = $this->memberStandardTree
                ->whereIn('parent_id', $sameNodeMember)
                ->pluck('member_id')->toArray();
        }
        $checkMember = array_merge($sameNodeMember, $sameNodeMemberChild);

        if (in_array($spill->id, $checkMember)) {
            return 0;
        }

        return 1;


    }

    public function checkMemberExist(Request $request)
    {
        $userName = $request->user_name;
        $user = User::where('user_name', $userName)->first();
        if ($user) {
            return $user->id;
        } else {
            return 'false';
        }
    }

    public function getPosition(Request $request)
    {
        $parent = $request->parent;
        $parentMember = User::where('user_name', $parent)->first();

        if ($parentMember) {
            $bookedPositons = $this->memberStandardTree->where('parent_id', $parentMember->id)->pluck('placement_position_id');

            $positionList = collect(PlacementPosition::orderBy('id', 'ASC')->whereNotIN('position', $bookedPositons)->get()->toArray())->pluck('position', 'position_name');
            return $positionList->all();
        } else {
            return $emptyValue = array();
        }
    }

    public function getPositionToMobile(Request $request)
    {
        $parent = $request->parent;
        $parentMember = User::where('user_name', $parent)->first();

        if ($parentMember) {
            $bookedPositions = $this->memberStandardTree->where('parent_id', $parentMember->id)->pluck('placement_position_id');

            $positionList = collect(PlacementPosition::orderBy('id', 'ASC')->whereNotIN('position', $bookedPositions)->get()->toArray())->pluck('position', 'position_name');
            if (empty($positionList->all())) {
                return response()->json(['status' => false, 'message' => 403, 'error' => 'Position Reserved']);
            }
            return response()->json([
                'status' => true,
                'message' => 200,
                'data' => $positionList->all()
            ]);

        } else {
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Uplink Not Found']);
        }
    }

    public function getIosPositionToMobile(Request $request)
    {
        $parent = $request->parent;
        $parentMember = User::where('user_name', $parent)->first();

        if ($parentMember) {
            $bookedPositions = $this->memberStandardTree->where('parent_id', $parentMember->id)->pluck('placement_position_id');

            $positionList = collect(PlacementPosition::orderBy('id', 'ASC')->whereNotIN('position', $bookedPositions)->get()->toArray())->pluck('position');
            if (empty($positionList->all())) {
                return response()->json(['status' => false, 'message' => 403, 'error' => 'Position Reserved']);
            }
            return response()->json([
                'status' => true,
                'message' => 200,
                'data' => $positionList
            ]);

        } else {
            return response()->json(['status' => false, 'message' => 403, 'error' => 'Uplink Not Found']);
        }
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

//    public function editMember($memberId){
//        if(!\Auth::guard('admin')->check() ) {
//            abort(401);
//        }
//
//        $data=[];
//
//        $data['nominee']=MemberNominee::where('member_id',$memberId)->first();
//        $data['asset']=MemberAsset::where('member_id',$memberId)->first();
//        $data['member']=User::findorfail($memberId);
//        $data['countries']= Country::all()->pluck('name', 'id');
//        $data['positions'] = PlacementPosition::pluck('position', 'id');
//        $data['packages'] = Package::where('status',1)->pluck('name', 'id');
//        $data['identificationType']=array(
//            'citizenship'=>'Citizenship',
//            'passport'=>'Passport',
//        );
//
//        return view('backend.member.register.edit',$data);
//    }

    public function updateMember(Request $request, $id)
    {
        if (!\Auth::guard('admin')->check()) {
            abort(401);
        }
        if (!request()->ajax()) {
            return back();
        }

        $validator = Validator::make($request->all(), [
            'gender' => 'required',
            'surname' => 'required',
            'name' => 'required',
            'marital_status' => 'required',
            'contact_number' => 'required',
            'identification_number' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'dob' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
            'address' => 'sometimes',
            'nominee_name' => 'sometimes',
            'nominee_contact_number' => 'sometimes',
            'nominee_identification_number' => 'sometimes',
            'relationship' => 'sometimes',
            'country' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'status' => 'fails',
                'errors' => $validator->getMessageBag()->toArray()
            ));
        }

        if ($validator->passes()) {

            $user = User::find($id);
            $nominee = MemberNominee::where('member_id', $id)->first();
            $asset = MemberAsset::where('member_id', $id)->first();

            $dob = date('Y-m-d', strtotime($request->dob));
            $user->update([
                'surname' => $request->surname,
                'name' => $request->name,
                'country_id' => $request->country,
                'gender' => $request->gender,
                'marital_status' => $request->marital_status,
                'identification_type' => $request->identification_type_id,
                'identification_number' => $request->identification_number,
                'email' => $request->email,
                'address' => $request->address,
                'contact_number' => $request->contact_number,
                'dob' => $dob,
                'country' => $request->country,
                'password' => bcrypt($request->password),
            ]);

            $nominee->update([
                'nominee_name' => $request->nominee_name,
                'identification_type' => $request->nominee_identification_type_id,
                'identification_number' => $request->nominee_identification_number,
                'relationship' => $request->relationship,
                'contact_number' => $request->nominee_contact_number
            ]);

            $asset->update([
                'package_id' => $request->package_id,

            ]);

            session()->flash('message', __('message.Member updated successfully'));

            return response()->json(array(
                'status' => 'success',
                'url' => url('member/edit-member/' . $id),
            ));
        }
    }

    public function viewMember($memberId)
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

        return view('backend.member.register.view', $data);
    }

    public function updateDailyBonus()
    {
        $today = date('Y-m-d');
        $holidayCheck = Holiday::where('holiday_date', $today)->first();
        if (!$holidayCheck) {
            User::orderBy('id', 'asc')->where('is_member', 1)->chunk('100', function ($members) {
                foreach ($members as $member) {
                    $packageName = MemberAsset::where('member_id', $member->id)->with('getPackage')->first();

                    if ($packageName->getPackage->name == 'Platinum' && $packageName->capital <= $packageName->capital_amount) {
                        $updateCapitalPlat = $packageName->capital + $packageName->dividend;
                        $packageName->update([
                            'capital' => $updateCapitalPlat
                        ]);
                        //  $this->createNotificaton('member',$member->id,'You Got Daily Bonus Value 1.5');
                        $this->createDailyBonus($member->id, $packageName->getPackage->id, $packageName->dividend);

                    }
                    if ($packageName->getPackage->name == 'Diamond' && $packageName->capital <= $packageName->capital_amount) {
                        $updateCapitalPlat = $packageName->capital + $packageName->dividend;
                        $packageName->update([
                            'capital' => $updateCapitalPlat
                        ]);
//                        $this->createNotificaton('member',$member->id,'You Got Daily Bonus Value 3');
                        $this->createDailyBonus($member->id, $packageName->getPackage->id, $packageName->dividend);
                    }
                }
            });
        }
    }
}
