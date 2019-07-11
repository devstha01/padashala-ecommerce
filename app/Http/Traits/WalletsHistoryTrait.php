<?php

namespace App\Http\Traits;

use App\Models\WalletHistory;

trait WalletsHistoryTrait
{
    public function createWalletReport($member_id, $Value, $desc, $transactionType, $cashFlow)
    {
        if ($Value != 0) {
            $data = [
                'member_id' => $member_id,
                'value' => $Value,
                'desc' => $desc,
                'transaction_type' => $transactionType,
                'cash_flow' => $cashFlow,
            ];
            WalletHistory::create($data);
        }
    }
}