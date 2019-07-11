<?php

namespace App\Http\Traits;

use App\Models\GrantRetain;
use App\Models\MerchantGrantRetain;
use App\Models\WalletHistory;

trait GrantRetainTrait
{
    public function createGrantRetainReport($member_id, $Value, $transactionType)
    {
        if ($Value != 0) {
            $data = [
                'member_id' => $member_id,
                'value' => $Value,
                'transaction_type' => $transactionType,
            ];
            GrantRetain::create($data);
        }
    }
    public function createMerchantGrantRetainReport($merchant_id, $Value, $transactionType)
    {
        if ($Value != 0) {
            $data = [
                'merchant_id' => $merchant_id,
                'value' => $Value,
                'transaction_type' => $transactionType,
            ];
            MerchantGrantRetain::create($data);
        }
    }


}