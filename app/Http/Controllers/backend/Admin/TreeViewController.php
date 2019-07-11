<?php

namespace App\Http\Controllers\backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Members\MemberAutoTree;
use App\Models\Members\MemberSpecialTree;
use App\Models\Members\MemberStandardTree;
use App\Models\User;
use App\Repositories\MemberRepository;
use Illuminate\Http\Request;
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
}
