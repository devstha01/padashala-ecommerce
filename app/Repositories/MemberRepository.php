<?php

namespace App\Repositories;

use App\Http\Traits\GenerationBonusTrait;
use App\Http\Traits\NotificationTrait;
use App\Http\Traits\WalletsHistoryTrait;
use App\Jobs\CreateSpecialTreeQueue;


use App\Models\ChipsConfig;
use App\Models\GenerationBonusDistribution;
use App\Models\Members\MemberAsset;
use App\Models\Members\MemberAutoTree;
use App\Models\Members\MemberNominee;
use App\Models\Members\MemberSpecialTree;
use App\Models\Members\MemberStandardTree;
use App\Models\Members\ReferalBonusRegister;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MemberRepository
{
    use NotificationTrait;
    use GenerationBonusTrait;
    use WalletsHistoryTrait;



    /**
     * @var Member
     */
    private $member;
    /**
     * @var MemberStandardTree
     */
    private $memberStandardTree;
    /**
     * @var MemberAutoTree
     */
    private $autoTree;
    /**
     * @var MemberSpecialTree
     */
    private $memberSpecialTree;

    public function __construct(User $member, MemberStandardTree $memberStandardTree, MemberAutoTree $autoTree, MemberSpecialTree $memberSpecialTree)
    {

        $this->member = $member;
        $this->memberStandardTree = $memberStandardTree;
        $this->autoTree = $autoTree;
        $this->memberSpecialTree = $memberSpecialTree;
    }


    public function processRegistration($request, $is_staff = false)
    {

        $dob = date('Y-m-d', strtotime($request->dob));
        if(\Auth::guard('admin')->check() ){
            $createdBy='admin';
        }else{
            $createdBy=Auth::id();
        }
        $memberPersonnelDetail = [
            'surname' => $request->surname,
            'name' => $request->name,
            'country_id' => $request->country,
            'user_name' => $request->user_name,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
            'email' => $request->email,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'dob' => $dob,
            'joining_date' => date('Y-m-d', strtotime($request->joining_date)),
            'country' => $request->country,
            'password' => bcrypt($request->password),
            'transaction_password' => bcrypt($request->transaction_password),
            'is_member' => 1,
            'created_by' => $createdBy,
            'status'=>0
        ];
        $member = User::create($memberPersonnelDetail);
        if ($member) {
            $packageData = Package::where('id', $request->package_id)->first();
            $packageValue =$packageData->amount;
            $chipUnit=ChipsConfig::first();
            $chip= $packageValue/$chipUnit->price_per_chips;
            $nominee = MemberNominee::create([
                'nominee_name' => $request->nominee_name,
                'member_id' => $member->id,
                'identification_type' => $request->nominee_identification_type_id,
                'identification_number' => $request->nominee_identification_number,
                'relationship' => $request->relationship,
                'contact_number' => $request->nominee_contact_number
            ]);

            $MemberAsset = MemberAsset::create([
                'member_id' => $member->id,
                'ecash_wallet' =>0,
                'evoucher_wallet' =>0,
                'r_point' =>0,
                'chip' => $chip,
                'dividend' => $packageData->dividend,
                'capital_amount' => $packageData->capital_value,
                'package_id' => $request->package_id
            ]);
            $sponsor = $this->member->where('user_name', $request->sponser_id)->first();
            $sponsorAsset=MemberAsset::where('member_id',$sponsor->id)->first();
            $newRpointSponsorValue=$sponsorAsset->r_point-$packageValue;
            $newRpointSponsor = array(
                'r_point' => $newRpointSponsorValue,
            );
            $update_data =$sponsorAsset->update($newRpointSponsor);


            $this->createWalletReport($member->id,$chip,'Registration','chip','IN');
            $this->createWalletReport($sponsor->id,$packageValue,'Registration','rpoint','OUT');

            $this->saveQrImage($member);
            $this->createStandardTree($request, $member);
            $this->createAutoTree($member);
            MemberSpecialTree::query()->truncate();
//            $this->createSpecialTree($member);
            $this->assignBonus($request->sponser_id, $request->package_id,$member->id);
            $this->createNotificaton('admin',$member->id,'Member ' .$member->user_name.' has been created .');
            $this->creteNewSpecial();

            return $member->id;


        }

    }

    public function creteNewSpecial(){
        $totalMembers=$this->autoTree->orderBy('id','desc')->get();
        foreach ($totalMembers as $newMember){
            $specMemberCount=$this->memberSpecialTree->count();
            if(!$specMemberCount){
                $specTreeCreate = MemberSpecialTree::create([
                    'member_id' => $newMember->member_id,
                    'parent_id' => NULL,
                    'node' => 0,
                    'placement_position_id' => NULL,
                ]);
            }else{
                $lastMember=$this->memberSpecialTree->orderBy('id','desc')->first();

                $specData = $this->getSpec($lastMember);
                $specData = MemberSpecialTree::create([
                    'member_id' => $newMember->member_id,
                    'parent_id' => $specData['parent_id'],
                    'node' => $specData['node'],
                    'placement_position_id' => $specData['placement'],
                ]);
            }
        }
    }
    public function getSpec($lastMember)
    {
        $parent_id = '';
        $node = '';
        $placement = '';

        if ($lastMember->parent_id == NULL) {
            $parent_id = $lastMember->member_id;
            $node = $lastMember->node + 1;
            $placement = 1;
        } else {
            $lastMemberValue = $this->memberSpecialTree
                ->where('node', $lastMember->node)
                ->where('parent_id', $lastMember->parent_id)
                ->orderBy('id', 'desc')->first();
            if ($lastMemberValue->placement_position_id < 5) {
                $parent_id = $lastMemberValue->parent_id;
                $node = $lastMemberValue->node;
                $placement = $lastMemberValue->placement_position_id + 1;
            } else {
                $totalNodeChildPossible = pow(5, $lastMemberValue->node);
                $CurrentNodeChilds = $this->memberSpecialTree->where('node', $lastMemberValue->node)->count();
                $conditioncheck = '';
                if ($totalNodeChildPossible == $CurrentNodeChilds) {
                    $conditioncheck = 'equals';
                }
                if ($CurrentNodeChilds < $totalNodeChildPossible) {
                    $conditioncheck = 'less';
                }
                switch ($conditioncheck) {
                    case 'equals':
                        $parent_id = $this->memberSpecialTree->where('node', $lastMemberValue->node)->orderBy('id', 'asc')->first()->member_id;
                        $node = $lastMemberValue->node + 1;
                        $placement = 1;
                        break;
                    case 'less':
                        $nodeChildParentPlacement = $this->memberSpecialTree->where('member_id', $lastMemberValue->parent_id)->first();
                        $parent_id = $this->memberSpecialTree
                            ->where('node', $nodeChildParentPlacement->node)
                            ->where('placement_position_id', $nodeChildParentPlacement->placement_position_id + 1)
                            ->first()
                            ->member_id;
                        $node = $lastMemberValue->node;
                        $placement = 1;
                        break;
                    default:
                }
            }
        }

        $autoData = [
            'parent_id' => $parent_id,
            'node' => $node,
            'placement' => $placement,
        ];

        return $autoData;

    }






    protected function saveQrImage($user)
    {
        $mkString = 'user:' . $user->id;
        $data = QrCode::format('png')->size(500)->generate($mkString);

        $destination = public_path('image/qr_image/');
        if (!File::exists($destination))
            File::makeDirectory($destination);
        $qr_name = str_random(10) . '.png';
        $path = $destination . $qr_name;

        File::put($path, $data);
        $user->update(['qr_image' => $qr_name]);
    }

    private function createStandardTree($request, $member)
    {
        $parent = $this->member->where('user_name', $request->parent_id)->first();
        $sponsor = $this->member->where('user_name', $request->sponser_id)->first();
        $standardTree = MemberStandardTree::create([
            'member_id' => $member->id,
            'sponser_id' => $sponsor->id,
            'parent_id' => $parent->id,
            'node' => $this->getNodeValue($parent->id),
            'placement_position_id' => $request->position_id,
            // 'sponser_parent_id' => $request->position_id ,
        ]);
    }

    public function createAutoTree($member)
    {
        $lastMember = $this->autoTree->orderBy('id', 'desc')->first();
        $autoData = $this->getAutoData($lastMember);
        $autoTree = MemberAutoTree::create([
            'member_id' => $member->id,
            'parent_id' => $autoData['parent_id'],
            'node' => $autoData['node'],
            'placement_position_id' => $autoData['placement'],
        ]);
    }

    public function getAutoData($lastMember)
    {
        $parent_id = '';
        $node = '';
        $placement = '';

        if ($lastMember->parent_id == NULL) {
            $parent_id = $lastMember->member_id;
            $node = $lastMember->node + 1;
            $placement = 1;
        } else {
            $lastMemberValue = $this->autoTree
                ->where('node', $lastMember->node)
                ->where('parent_id', $lastMember->parent_id)
                ->orderBy('id', 'desc')->first();
            if ($lastMemberValue->placement_position_id < 5) {
                $parent_id = $lastMemberValue->parent_id;
                $node = $lastMemberValue->node;
                $placement = $lastMemberValue->placement_position_id + 1;
            } else {
                $totalNodeChildPossible = pow(5, $lastMemberValue->node);
                $CurrentNodeChilds = $this->autoTree->where('node', $lastMemberValue->node)->count();
                $conditioncheck = '';
                if ($totalNodeChildPossible == $CurrentNodeChilds) {
                    $conditioncheck = 'equals';
                }
                if ($CurrentNodeChilds < $totalNodeChildPossible) {
                    $conditioncheck = 'less';
                }
                switch ($conditioncheck) {
                    case 'equals':
                        $parent_id = $this->autoTree->where('node', $lastMemberValue->node)->orderBy('id', 'asc')->first()->member_id;
                        $node = $lastMemberValue->node + 1;
                        $placement = 1;
                        break;
                    case 'less':
                        $nodeChildParentPlacement = $this->autoTree->where('member_id', $lastMemberValue->parent_id)->first();
                        $parent_id = $this->autoTree
                            ->where('node', $nodeChildParentPlacement->node)
                            ->where('placement_position_id', $nodeChildParentPlacement->placement_position_id + 1)
                            ->first()
                            ->member_id;
                        $node = $lastMemberValue->node;
                        $placement = 1;
                        break;
                    default:
                }
            }
        }

        $autoData = [
            'parent_id' => $parent_id,
            'node' => $node,
            'placement' => $placement,
        ];

        return $autoData;

    }

//    public function getSpecialData($memberOld, $memberNew)
//    {
//        $parent_id = '';
//        $node = '';
//        $placement = '';
//        $lastUpdate = '';
//
//        $lastMemberValue = $this->memberSpecialTree
//            ->where('last_updated', 1)
//            ->where('parent_id', '!=', NULL)
//            ->first();
//        if ($memberOld->parent_id == NULL) {
//            $parent_id = $memberNew->id;
//            $node = 1;
//            $placement = 1;
//            $lastUpdate = 1;
//        } else {
//            if ($lastMemberValue->placement_position_id < 5) {
//                $parent_id = $lastMemberValue->parent_id;
//                $node = $lastMemberValue->node;
//                $placement = $lastMemberValue->placement_position_id + 1;
//                $lastUpdate = 1;
//            } else {
//                $totalNodeChildPossible = pow(5, $lastMemberValue->node) + 1;
//                $CurrentNodeChilds = $this->memberSpecialTree->where('node', $lastMemberValue->node)->count();
//                switch (true) {
//                    case $CurrentNodeChilds == $totalNodeChildPossible :
//                        $parent_id = $this->memberSpecialTree->where('node', $lastMemberValue->node)->orderBy('id', 'desc')->first()->member_id;
//                        $node = $lastMemberValue->node + 1;
//                        $placement = 1;
//                        $lastUpdate = 1;
//                        break;
//                    case $CurrentNodeChilds < $totalNodeChildPossible :
//                        $nodeChildParentPlacement = $this->memberSpecialTree->where('member_id', $lastMemberValue->parent_id)->first();
//                        $parent_id = $this->memberSpecialTree
//                            ->where('node', $nodeChildParentPlacement->node)
//                            ->where('placement_position_id', $nodeChildParentPlacement->placement_position_id + 1)
//                            ->first()
//                            ->member_id;
//                        $node = $lastMemberValue->node;
//                        $placement = 1;
//                        $lastUpdate = 1;
//                        break;
//                    default:
//                }
//            }
//        }
//        $specData = [
//            'parent_id' => $parent_id,
//            'node' => $node,
//            'placement' => $placement,
//            'last_updated' => $lastUpdate,
//        ];
//
//        return $specData;
//    }


    private function getNodeValue($parent_id)
    {
        $parentnodeValue = $this->memberStandardTree->where('member_id', $parent_id)->first()->node;
        $CurrentNodeValue = $parentnodeValue + 1;
        return $CurrentNodeValue;
    }

//    public function update_special_tree($member_id, $data)
//    {
//        $updateStatus = array(
//            'last_updated' => 0,
//        );
//        $getPreviousUpdatedData = $this->memberSpecialTree->where('last_updated', 1)->first();
//        if ($getPreviousUpdatedData) {
//            $getPreviousUpdatedData->update($updateStatus);
//        }
//        $special = $this->memberSpecialTree->where('member_id', $member_id)->first();
//        if ($special) {
//            $special->update($data);
//        }
//    }

    public function assignBonus($sponsor_id, $packageId,$newMemberId)
    {
        $sponsor = $this->member->where('user_name', $sponsor_id)->first();
        $bonusUsers = $this->getBonusDetails($sponsor->id);
        if ($bonusUsers) {
            foreach ($bonusUsers as $key => $value) {
                $memberAsset=MemberAsset::where('member_id', $value['member_id'])->first();
                $chipUnit=ChipsConfig::first();;
                $referalPercentage = ReferalBonusRegister::where('package_id', $memberAsset->package_id)->where('generation_position', $value['generation'])->first()->refaral_percentage;
                $packageValue = Package::where('id', $memberAsset->package_id)->first()->amount;
                $bonusCashValue = ($referalPercentage / 100) * $packageValue;
                $bonusConfig=GenerationBonusDistribution::first();
                $memberOldEcash = $memberAsset->ecash_wallet;
                $memberOldEVoucher = $memberAsset->evoucher_wallet;
                $memberOldChip = $memberAsset->chip;
                $memberOldRpoint = $memberAsset->r_point;
                $newEcashValue=($bonusConfig->ecash_percentage / 100) * $bonusCashValue;
                $updatedEcash = $memberOldEcash + $newEcashValue;
                $newEvoucherValue=($bonusConfig->evoucher_percentage / 100) * $bonusCashValue;
                $updatedEvoucher = $memberOldEVoucher + $newEvoucherValue;
                $updatedchipVal = ($bonusConfig->chip_percentage / 100) * $bonusCashValue;
                $newChip=$updatedchipVal/$chipUnit->price_per_chips;
                $updatedchip= $memberOldChip+$updatedchipVal/$chipUnit->price_per_chips;

                $newRpointValue=($bonusConfig->rpoint_percentage / 100) * $bonusCashValue;
                $updatedRpoint = $memberOldRpoint + $newRpointValue;
                $memberStatus = $this->member->where('id', $value['member_id'])->first()->status;
                if($memberStatus){
//                    $this->createNotificaton('member',$value['member_id'],'You Got '.$value['generation'].'Generation Bonus value ' . $bonusCashValue);
                   $this->createGenerationBonus($value['member_id'],$value['generation'],$bonusCashValue,$newMemberId);
                    $this->createWalletReport($value['member_id'],$newEcashValue,'By Bonus','ecash','IN');
                    $this->createWalletReport($value['member_id'],$newEvoucherValue,'By Bonus','evoucher','IN');
                    $this->createWalletReport($value['member_id'],$newChip,'By Bonus','chip','IN');
                    $this->createWalletReport($value['member_id'],$newRpointValue,'By Bonus','rpoint','IN');
                }
                if (!$memberStatus) {
                    $updatedEcash = $memberOldEcash;
                }
                $data = array(
                    'ecash_wallet' => $updatedEcash,
                    'evoucher_wallet' => $updatedEvoucher,
                    'chip' => $updatedchip,
                    'r_point' => $updatedRpoint,
                );
                $update_asset = MemberAsset::where('member_id', $value['member_id'])->update($data);
            }
        }
    }

    public function getBonusDetails($sponsor_id)
    {

        $check = true;
        $memberId = $sponsor_id;
        $bonusUsers = [];
        $generationCount = 1;
        do {
            $getNextSponser = memberStandardTree::where('member_id', $memberId)->first();
            if ($getNextSponser) {
                $bonusUsers[] = array(
                    'member_id' => $memberId,
                    'generation' => $generationCount,
                );
                $generationCount++;
                $memberId = $getNextSponser->sponser_id;
            } else {
                $check = false;
            }
        } while ($check);
        return $bonusUsers;
    }

    public function getAutoAndSpecialTreeBonus($member_id)
    {

        $check = true;
        $memberId = $member_id;
        $bonusUsers = [];
        $generationCount = 1;
        do {
            $getNextSponser = MemberAutoTree::where('member_id', $memberId)->first();
            if ($getNextSponser) {
                $bonusUsers[] = array(
                    'member_id' => $getNextSponser->parent_id,
                    'generation' => $generationCount,
                );
                $generationCount++;
                $memberId = MemberAutoTree::where('member_id', $getNextSponser->parent_id)->first();
            } else {
                $check = false;
            }
        } while ($check);
        return $bonusUsers;
    }


}
