<?php
namespace App\Http\Traits;

use App\Models\DailyBonus;

trait DailyBonusTrait {
    public function createDailyBonus($member_id,$packageId,$bonusValue) {
        $data = [
            'member_id' =>$member_id,
            'package_id' => $packageId,
            'value' => $bonusValue,
            ];
        DailyBonus::create($data);

    }
}