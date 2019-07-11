<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Members\MemberSpecialTree;
use App\Repositories\MemberRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class CreateSpecialTreeQueue extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $member_id;
    /**
     * @var MemberRepository
     */
    private $memberRepository;

    /**
     * Create a new job instance.
     * Run a job for 100 tree data at a time
     * @param $member_id
     * @param MemberRepository $memberRepository
     */
    public function __construct($member_id,MemberRepository $memberRepository)
    {


        $this->member_id = $member_id;
        $this->memberRepository = $memberRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $member = $this->member_id;

        MemberSpecialTree::orderBy('id', 'asc')->chunk('100', function ($specialTrees) {


            foreach ($specialTrees as $special) {
                $specialData=$this->memberRepository->getSpecialData($special);
                $data = array(
                    'member_id' => $specialData['member_id'],
                    'parent_id' => $specialData['parent_id'],
                    'node' =>$specialData['node'],
                    'placement_position_id' =>$specialData['placement'],
                );
                $this->memberRepository->update_special_tree($special->member_id,$data);
            }

        });
        $specialTree = MemberSpecialTree::create([
            'member_id' => $member,
            'parent_id' => NULL,
            'node' => '0',
            'placement_position_id' => NULL,
        ]);



    }
}

