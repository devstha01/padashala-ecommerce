<?php

namespace App\Http\Controllers\backend\Member;

use App\Http\Controllers\Controller;
use App\Library\AjaxResponse;
use App\Models\Country;
use App\Models\Members\MemberAsset;
use App\Models\Members\MemberAutoTree;
use App\Models\Members\MemberSpecialTree;
use App\Models\Members\MemberStandardTree;
use App\Models\Package;
use App\Models\PlacementPosition;
use App\Models\User;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;

class TreeViewController extends Controller
{
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
     * @var MemberAutoTree
     */
    private $autoTree;
    /**
     * @var MemberSpecialTree
     */
    private $specialTree;

    public function __construct(MemberRepository $memberRepository,MemberStandardTree $memberStandardTree,User $member,MemberAutoTree $autoTree,MemberSpecialTree $specialTree) {

        $this->memberRepository = $memberRepository;
        $this->memberStandardTree = $memberStandardTree;
        $this->member = $member;
        $this->autoTree = $autoTree;
        $this->specialTree = $specialTree;
    }
    public function Standardplacementtree(Request $request)
    {
        $defaultMember=$this->member->orderby('id','asc')->where('is_member',1)->first();
        return view('backend.member.placementTree.standard',compact('defaultMember'));
    }
    public function Autoplacementtree(Request $request)
    {
        $defaultMember=$this->member->orderby('id','asc')->where('is_member',1)->first();
        return view('backend.member.placementTree.auto',compact('defaultMember'));
    }
    public function Specialplacementtree(Request $request)
    {
        $defaultMember=$this->member->orderby('id','asc')->where('is_member',1)->first();
        return view('backend.member.placementTree.special',compact('defaultMember'));
    }

    public function getStandardTree(Request $request){

//        if (!request()->ajax()) {
//            return back();
//        }
       $userName=$request->get('username');

        if(!\Auth::guard('admin')->check() )
        {
            $checkValidSearch=$this->validStandardMemberSearch($userName);
            if($checkValidSearch){
                return 'false';
            }
        }
        $member=$this->member
            ->where('user_name',$userName)
            ->with('getStandardTree')
            ->first();
        $maxNode=$member->getStandardTree->node+3;
        $memberAsset = MemberAsset::where('member_id',$member->id)->first();
        $className = 'Gold';
        if($memberAsset->package_id == 2){
            $className = 'Platinum';
        }elseif($memberAsset->package_id == 3){
            $className = 'Diamond';
        }
        $totalPlacement=$this->memberStandardTree
            ->where('parent_id',$member->id)
            ->orderby('placement_position_id','asc')
            ->pluck('placement_position_id')
            ->toArray();
         $totalChildren=$this->memberStandardTree
            ->where('parent_id',$member->id)
             ->orderby('placement_position_id','asc')
             ->get()
             ->toArray();

         $defaultChild=$this->createDefaultChild($totalPlacement,$member->id);
         $totalChildrenFinal=array_merge($totalChildren,$defaultChild);
         array_multisort(array_column($totalChildrenFinal, 'placement_position_id'), SORT_ASC, $totalChildrenFinal);
         $child_values = $this->get_all_childs_for_standard($totalChildrenFinal,$maxNode);
         $tree=[
            'id'=>$member->id,
            'className'=>$className,
            'name'=>$member->user_name,
            'date'=> date('jS F Y',strtotime($member->created_at)) ,
            'parent'=>$this->getParentForTree($member->getStandardTree->parent_id,$member->id),
            'children'=>
                $child_values

        ];

        return \Response::json(
            $tree
        );


    }

    public function getStandardTreeForMobile(Request $request)
    {
        $userName=$request->get('username');

        if(!\Auth::guard('admin')->check() )
        {
            $checkValidSearch=$this->validStandardMemberSearch($userName);
            if($checkValidSearch){
                return response()->json([
                    'status' => false,
                    'message' => 400,
                    'error' => 'Member Not in branch'
                ]);
            }
        }
        $member=$this->member
            ->where('user_name',$userName)
            ->with('getStandardTree')
            ->first();
        $maxNode=$member->getStandardTree->node+3;
        $memberAsset = MemberAsset::where('member_id',$member->id)->first();
        $className = 'Gold';
        if($memberAsset->package_id == 2){
            $className = 'Platinum';
        }elseif($memberAsset->package_id == 3){
            $className = 'Diamond';
        }
        $totalPlacement=$this->memberStandardTree
            ->where('parent_id',$member->id)
            ->orderby('placement_position_id','asc')
            ->pluck('placement_position_id')
            ->toArray();
        $totalChildren=$this->memberStandardTree
            ->where('parent_id',$member->id)
            ->orderby('placement_position_id','asc')
            ->get()
            ->toArray();

        $defaultChild=$this->createDefaultChild($totalPlacement,$member->id);
        $totalChildrenFinal=array_merge($totalChildren,$defaultChild);
        array_multisort(array_column($totalChildrenFinal, 'placement_position_id'), SORT_ASC, $totalChildrenFinal);
        $child_values = $this->get_all_childs_for_standard($totalChildrenFinal,$maxNode);
        $tree=[
            'id'=>$member->id,
            'className'=>$className,
            'name'=>$member->user_name,
            'date'=> date('jS F Y',strtotime($member->created_at)) ,
            'parent'=>$this->getParentForTree($member->getStandardTree->parent_id,$member->id),
            'children'=>
                $child_values

        ];

        return \Response::json([
            'status'=>true,
            'message'=>200,
            'data'=>$tree
            ]
        );
    }

    public function getMemberList(Request $request){

//        if (!request()->ajax()) {
//            return back();
//        }
        $userName=$request->get('username');

        if(!\Auth::guard('admin')->check() )
        {
            $checkValidSearch=$this->validStandardMemberSearch($userName);
            if($checkValidSearch){
                return 'false';
            }
        }
        $member=$this->member
            ->where('user_name',$userName)
            ->with('getStandardTree')
            ->first();
        $maxNode=$member->getStandardTree->node+3;
        $memberAsset = MemberAsset::where('member_id',$member->id)->first();
        $className = 'Gold';
        if($memberAsset->package_id == 2){
            $className = 'Platinum';
        }elseif($memberAsset->package_id == 3){
            $className = 'Diamond';
        }
        $totalPlacement=$this->memberStandardTree
            ->where('parent_id',$member->id)
            ->orderby('placement_position_id','asc')
            ->pluck('placement_position_id')
            ->toArray();
        $totalChildren=$this->memberStandardTree
            ->where('parent_id',$member->id)
            ->orderby('placement_position_id','asc')
            ->get()
            ->toArray();
        $defaultChild=$this->createDefaultChild($totalPlacement,$member->id);
        $totalChildrenFinal=array_merge($totalChildren,$defaultChild);
        array_multisort(array_column($totalChildrenFinal, 'placement_position_id'), SORT_ASC, $totalChildrenFinal);
        $child_values = $this->get_all_childs_for_memList($totalChildrenFinal);
        $tree=[
            'text'=>$member->user_name,
            'className'=>$className,
            'children'=>
                $child_values

        ];

        return \Response::json(
            $tree
        );


    }

    public function getParentForTree($parentId,$memberId){

        if(!$parentId OR $parentId=='null'){
            return 'None';
        }

        if($memberId != Auth::id()){
            return $this->getUserName($parentId);
        }
        return 'None';

    }

    public function validStandardMemberSearch($memberName){


        $member=$this->member
            ->where('user_name',$memberName)
            ->first();
        $subChildren=$this->memberStandardTree
            ->where('parent_id',Auth::id())
            ->orderby('placement_position_id','asc')
            ->get()
            ->toArray();


        $validMemberList=$this->getValidMemberStandardArray($subChildren);
        $validMemberSearchValue=$this->searchArray($validMemberList,'member_id',$member->id);
        if($validMemberSearchValue OR $member->id== Auth::id()){
            return 0;
        }
        return 1;

    }

   public function searchArray($array, $key, $value)
    {
        $results = array();

        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $results[] = $array;
            }

            foreach ($array as $subarray) {
                $results = array_merge($results, $this->searchArray($subarray, $key, $value));
            }
        }

        return $results;
    }

    public function getValidMemberStandardArray($totalChildren)
    {
        $validMemberArray = array();
//        \Log::info($validMemberArray);
//        array_push($validMemberArray,Auth::id());

        foreach($totalChildren as $key=> $child)
        {
            $validMemberArray[] = [
                'member_id'=>$child['member_id'],
            ];
            $subChildren=$this->memberStandardTree
                ->where('parent_id',$child['member_id'])
                ->orderby('placement_position_id','asc')
                ->get();
            if(!empty($subChildren)){
                $validMemberArray[$key]['children']=$this->getValidMemberStandardArray($subChildren);
            }
        }
        return $validMemberArray;

    }



    public function getAutoTree(Request $request){

//        if (!request()->ajax()) {
//            return back();
//        }
        $userName=$request->get('username');

        if(!\Auth::guard('admin')->check() )
        {
            $checkValidSearch=$this->validAutoMemberAutoSearch($userName);
            if($checkValidSearch){
                return 'false';
            }
        }
        $member=$this->member
            ->where('user_name',$userName)
            ->first();

        $parentValue='';
        $autoData=$this->autoTree->where('member_id',$member->id)->first();
        if($autoData) {
            $parentValue = $autoData->parent_id;
        }


        $memberAsset = MemberAsset::where('member_id',$member->id)->first();
        $className = 'Gold';
        if($memberAsset->package_id == 2){
            $className = 'Platinum';
        }elseif($memberAsset->package_id == 3){
            $className = 'Diamond';
        }

        $totalChildren=$this->autoTree
            ->where('parent_id',$member->id)->orderby('placement_position_id','asc')->get()->toArray();
        $child_values = $this->get_all_childs_for_auto($totalChildren);
        $tree=[
            'id'=>$member->id,
            'className'=>$className,
            'name'=>$member->user_name,
            'date'=> date('jS F Y',strtotime($member->created_at)) ,
            'parent'=>$this->getParentForTree($parentValue,$member->id),
            'children'=>
                $child_values

        ];


        return \Response::json(
            $tree
        );


    }

    public function getAutoTreeForMobile(Request $request){
        $userName=$request->get('username');

        if(!\Auth::guard('admin')->check() )
        {
            $checkValidSearch=$this->validAutoMemberAutoSearch($userName);
            if($checkValidSearch){
                return response()->json([
                    'status' => false,
                    'message' => 400,
                    'error' => 'Member Not in branch'
                ]);
            }
        }
        $member=$this->member
            ->where('user_name',$userName)
            ->first();

        $parentValue='';
        $autoData=$this->autoTree->where('member_id',$member->id)->first();
        if($autoData) {
            $parentValue = $autoData->parent_id;
        }


        $memberAsset = MemberAsset::where('member_id',$member->id)->first();
        $className = 'Gold';
        if($memberAsset->package_id == 2){
            $className = 'Platinum';
        }elseif($memberAsset->package_id == 3){
            $className = 'Diamond';
        }

        $totalChildren=$this->autoTree
            ->where('parent_id',$member->id)->orderby('placement_position_id','asc')->get()->toArray();
        $child_values = $this->get_all_childs_for_auto($totalChildren);
        $tree=[
            'id'=>$member->id,
            'className'=>$className,
            'name'=>$member->user_name,
            'date'=> date('jS F Y',strtotime($member->created_at)) ,
            'parent'=>$this->getParentForTree($parentValue,$member->id),
            'children'=>
                $child_values

        ];


        return \Response::json([
            'status'=>true,
            'message'=>200,
            'data'=> $tree
        ]);

    }


    public function validAutoMemberAutoSearch($memberName){


        $member=$this->member
            ->where('user_name',$memberName)
            ->first();
        $subChildren=$this->autoTree
            ->where('parent_id',Auth::id())
            ->orderby('placement_position_id','asc')
            ->get()
            ->toArray();

        $validMemberList=$this->getValidMemberAutoArray($subChildren);
        $validMemberSearchValue=$this->searchArray($validMemberList,'member_id',$member->id);
        if($validMemberSearchValue OR $member->id== Auth::id()){
            return 0;
        }
        return 1;

    }

    public function getValidMemberAutoArray($totalChildren)
    {
        $validMemberArray = array();
//        \Log::info($validMemberArray);
//        array_push($validMemberArray,Auth::id());

        foreach($totalChildren as $key=> $child)
        {
            $validMemberArray[] = [
                'member_id'=>$child['member_id'],
            ];
            $subChildren=$this->memberStandardTree
                ->where('parent_id',$child['member_id'])
                ->orderby('placement_position_id','asc')
                ->get();
            if(!empty($subChildren)){
                $validMemberArray[$key]['children']=$this->getValidMemberStandardArray($subChildren);
            }
        }
        return $validMemberArray;

    }

    public function getSpecialTree(Request $request){

//        if (!request()->ajax()) {
//            return back();
//        }
        $userName=$request->get('username');
        if(!\Auth::guard('admin')->check() )
        {
            $checkValidSearch=$this->validAutoMemberSpecialSearch($userName);
            if($checkValidSearch){
                return 'false';
            }
        }
           $member=$this->member
            ->where('user_name',$userName)
            ->first();
           $parentValue='';
           $specialData=MemberSpecialTree::where('member_id',$member->id)->first();
        if($specialData) {
            $parentValue = $specialData->parent_id;
        }

        $memberAsset = MemberAsset::where('member_id',$member->id)->first();
        $className = 'Gold';
        if($memberAsset->package_id == 2){
            $className = 'Platinum';
        }elseif($memberAsset->package_id == 3){
            $className = 'Diamond';
        }

        $totalChildren=$this->specialTree
            ->where('parent_id',$member->id)->orderby('placement_position_id','asc')->get()->toArray();
        $child_values = $this->get_all_childs_for_special($totalChildren);
        $tree=[
            'id'=>$member->id,
            'className'=>$className,
            'name'=>$member->user_name,
            'date'=> date('jS F Y',strtotime($member->created_at)) ,
            'parent'=>$this->getParentForTree($parentValue,$member->id),
            'children'=>
                $child_values

        ];

        return \Response::json(
            $tree
        );


    }

    public function getSpecialTreeForMobile(Request $request){
        $userName=$request->get('username');
        if(!\Auth::guard('admin')->check() )
        {
            $checkValidSearch=$this->validAutoMemberSpecialSearch($userName);
            if($checkValidSearch){
                return response()->json([
                    'status' => false,
                    'message' => 400,
                    'error' => 'Member Not in branch'
                ]);
            }
        }
        $member=$this->member
            ->where('user_name',$userName)
            ->first();
        $parentValue='';
        $specialData=MemberSpecialTree::where('member_id',$member->id)->first();
        if($specialData) {
            $parentValue = $specialData->parent_id;
        }

        $memberAsset = MemberAsset::where('member_id',$member->id)->first();
        $className = 'Gold';
        if($memberAsset->package_id == 2){
            $className = 'Platinum';
        }elseif($memberAsset->package_id == 3){
            $className = 'Diamond';
        }

        $totalChildren=$this->specialTree
            ->where('parent_id',$member->id)->orderby('placement_position_id','asc')->get()->toArray();
        $child_values = $this->get_all_childs_for_special($totalChildren);
        $tree=[
            'id'=>$member->id,
            'className'=>$className,
            'name'=>$member->user_name,
            'date'=> date('jS F Y',strtotime($member->created_at)) ,
            'parent'=>$this->getParentForTree($parentValue,$member->id),
            'children'=>
                $child_values

        ];

        return \Response::json(
            [
                'status'=>true,
                'message'=>200,
                'data'=> $tree
            ]
        );

    }

    public function validAutoMemberSpecialSearch($memberName){


        $member=$this->member
            ->where('user_name',$memberName)
            ->first();
        $subChildren=$this->specialTree
            ->where('parent_id',Auth::id())
            ->orderby('placement_position_id','asc')
            ->get()
            ->toArray();

        $validMemberList=$this->getValidMemberSpecialArray($subChildren);
        $validMemberSearchValue=$this->searchArray($validMemberList,'member_id',$member->id);
        if($validMemberSearchValue OR $member->id== Auth::id()){
            return 0;
        }
        return 1;

    }

    public function getValidMemberSpecialArray($totalChildren)
    {
        $validMemberArray = array();
//        \Log::info($validMemberArray);
//        array_push($validMemberArray,Auth::id());

        foreach($totalChildren as $key=> $child)
        {
            $validMemberArray[] = [
                'member_id'=>$child['member_id'],
            ];
            $subChildren=$this->memberStandardTree
                ->where('parent_id',$child['member_id'])
                ->orderby('placement_position_id','asc')
                ->get();
            if(!empty($subChildren)){
                $validMemberArray[$key]['children']=$this->getValidMemberStandardArray($subChildren);
            }
        }
        return $validMemberArray;

    }

    public function createDefaultChild(Array $existingPlacement,$parentID){
        $defaultChilds = [];
        for($i=1;$i<=5;$i++){
            if(!in_array($i,$existingPlacement) ){
                $defaultChilds[] = [
                    'id'=>'',
                    'member_id'=>'None',
                    'sponser_id'=>'None',
                    'parent_id'=>$this->getUserName($parentID),
                    'name' => '+',
                    'node' => 'None',
                    'placement_position_id'=>$i,
                    'created_at'=>'None',
                    'updated_at'=>'None',
                ];
            }

        }
        return $defaultChilds;

    }
    public function get_all_childs_for_memList(Array $totalChildren)
    {
        $data = [];

        foreach($totalChildren as $key=> $child)
        {
            $defaultChild=[];
            $totalPlacement=$this->memberStandardTree
                ->where('parent_id',$child['member_id'])
                ->orderby('placement_position_id','asc')
                ->pluck('placement_position_id')
                ->toArray();


            if($child['member_id']!='None'){
                $defaultChild=$this->createDefaultChild($totalPlacement,$child['member_id']);

            }

            $subChildren=$this->memberStandardTree
                ->where('parent_id',$child['member_id'])
                ->orderby('placement_position_id','asc')
                ->get()
                ->toArray();

            $subChildren=array_merge($subChildren,$defaultChild);


            array_multisort(array_column($subChildren, 'placement_position_id'), SORT_ASC, $subChildren);


            $memberAsset = MemberAsset::where('member_id',$child['member_id'])->first();

            $className = 'Gold';
            if(isset($memberAsset) && $memberAsset->package_id == 2){
                $className = 'Platinum';
            }elseif(isset($memberAsset) && $memberAsset->package_id == 3){
                $className = 'Diamond';
            }
            if($child['member_id']=='None'){
                $className='Default';
            }
            $data[] = [
                'text'=>$child['member_id']=='None'?$child['name']:$this->getUserName($child['member_id']),
                'className'=>$className
            ];
            if($child['member_id']!='None'){
                $data[$key]['children']=$this->get_all_childs_for_memList($subChildren);
            }
        }

        return $data;
    }

   public function get_all_childs_for_standard(Array $totalChildren,$maxNode)
    {
        $data = [];

        foreach($totalChildren as $key=> $child)
        {
            $defaultChild=[];
            $totalPlacement=$this->memberStandardTree
                ->where('parent_id',$child['member_id'])
                ->orderby('placement_position_id','asc')
                ->pluck('placement_position_id')
                ->toArray();


            if($child['member_id']!='None' && $child['node'] < $maxNode){
                $defaultChild=$this->createDefaultChild($totalPlacement,$child['member_id']);

            }

            $subChildren=$this->memberStandardTree
                ->where('parent_id',$child['member_id'])
                ->where('node','<=',$maxNode)
                ->orderby('placement_position_id','asc')
                ->get()
                ->toArray();

               $subChildren=array_merge($subChildren,$defaultChild);


                array_multisort(array_column($subChildren, 'placement_position_id'), SORT_ASC, $subChildren);


            $memberAsset = MemberAsset::where('member_id',$child['member_id'])->first();

            $className = 'Gold';
            if(isset($memberAsset) && $memberAsset->package_id == 2){
                $className = 'Platinum';
            }elseif(isset($memberAsset) && $memberAsset->package_id == 3){
                $className = 'Diamond';
            }
            if($child['member_id']=='None'){
                $className='Default';
            }
            $data[] = [
                'id'=>$child['member_id'],
                'className'=>$className,
                'name' => $child['member_id']=='None'?$child['name']:$this->getUserName($child['member_id']),
                'spill'=>$child['parent_id'],
                'placement'=>$child['placement_position_id'],
                'date'=>date('jS F Y',strtotime($child['created_at'])),
            ];
            if($child['member_id']!='None'){
                $data[$key]['children']=$this->get_all_childs_for_standard($subChildren,$maxNode);
            }
        }

        return $data;
    }

    public function get_all_childs_for_auto(Array $totalChildren)
    {
        $data = [];

        foreach($totalChildren as $key=> $child)
        {
            $subChildren=$this->autoTree
                ->where('parent_id',$child['member_id'])->orderby('placement_position_id','asc')->get()->toArray();
            $memberAsset = MemberAsset::where('member_id',$child['member_id'])->first();

            $className = 'Gold';
            if($memberAsset->package_id == 2){
                $className = 'Platinum';
            }elseif($memberAsset->package_id == 3){
                $className = 'Diamond';
            }
            $data[] = [
                'id'=>$child['member_id'],
                'className'=>$className,
                'name' => $this->getUserName($child['member_id']),
                'date'=>date('jS F Y',strtotime($child['created_at'])),
            ];
            if($subChildren){
                $data[$key]['children']=$this->get_all_childs_for_auto($subChildren);
            }
        }
        return $data;
    }

    public function get_all_childs_for_special(Array $totalChildren)
    {
        $data = [];

        foreach($totalChildren as $key=> $child)
        {
            $subChildren=$this->specialTree
                ->where('parent_id',$child['member_id'])->orderby('placement_position_id','asc')->get()->toArray();
            $memberAsset = MemberAsset::where('member_id',$child['member_id'])->first();
            $className = 'Default';
            if($memberAsset->package_id == 2){
                $className = 'Platinum';
            }elseif($memberAsset->package_id == 3){
                $className = 'Diamond';
            }
            elseif($memberAsset->package_id == 1){
                $className = 'Gold';
            }
            $data[] = [
                'id'=>$child['member_id'],
                'className'=>$className,
                'name' => $this->getUserName($child['member_id']),
                'date'=>date('jS F Y',strtotime($child['created_at'])),
            ];
            if($subChildren){
                $data[$key]['children']=$this->get_all_childs_for_special($subChildren);
            }
        }
        return $data;
    }



    public function getUserName($memberId){
        $name='';
        $name=$this->member->where('id',$memberId)->first()->user_name;
        return $name;

    }



}
