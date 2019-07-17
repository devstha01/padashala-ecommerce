<?php
namespace App\Http\Traits;

use App\Models\GenerationBonus;

trait GenerationBonusTrait {
    public function createGenerationBonus($member_id,$generation,$bonusValue,$createdMemberId) {
//        $data = [
//            'member_id' =>$member_id,
//            'generation' => $generation,
//            'bonus_value' => $bonusValue,
//            'created_member_id' => $createdMemberId
//            ];
//        GenerationBonus::create($data);
    }
}