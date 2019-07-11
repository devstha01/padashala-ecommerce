<?php

namespace App\Http\Traits;

use App\Models\WithdrawConfig;

trait MinMaxConfig
{

    function validationMinMax($wallet, $amount)
    {
        if (empty($amount))
            return ['status' => false, 'message' => 403, 'error' => 'Amount is required'];
        if (!is_numeric($amount))
            return ['status' => false, 'message' => 403, 'error' => 'Amount must be a number'];
        switch (strtolower($wallet)) {
            case 'wallet_withdraw':
                $config = WithdrawConfig::where('name', 'wallet_withdraw')->first();
                $min = ($config->min ?? 0) + 0;
                $max = ($config->max ?? 5000) + 0;
                if ($min > $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount must be minimum  $' . $min];
                if ($max < $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount exceeds the maximum limit $' . $max];
                break;
            case 'ecash_wallet':
                $config = WithdrawConfig::where('name', 'transfer_ecash')->first();
                $min = ($config->min ?? 0) + 0;
                $max = ($config->max ?? 5000) + 0;
                if ($min > $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount must be minimum $' . $min];
                if ($max < $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount exceeds the maximum limit $' . $max];
                break;
            case 'evoucher_wallet':
                $config = WithdrawConfig::where('name', 'transfer_evoucher')->first();
                $min = ($config->min ?? 0) + 0;
                $max = ($config->max ?? 5000) + 0;
                if ($min > $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount must be minimum $' . $min];
                if ($max < $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount exceeds the maximum limit $' . $max];
                break;
            case 'r_point':
                $config = WithdrawConfig::where('name', 'transfer_r_point')->first();
                $min = ($config->min ?? 0) + 0;
                $max = ($config->max ?? 5000) + 0;
                if ($min > $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount must be minimum ' . $min];
                if ($max < $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount exceeds the maximum limit ' . $max];
                break;
            case 'chip':
                $config = WithdrawConfig::where('name', 'transfer_chip')->first();
                $min = ($config->min ?? 0) + 0;
                $max = ($config->max ?? 5000) + 0;
                if ($min > $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount must be minimum ' . $min];
                if ($max < $amount) return ['status' => false, 'message' => 403, 'error' => 'Amount exceeds the maximum limit ' . $max];
                break;
            default:
                break;
        }
        return ['status' => true, 'message' => 200, 'message-detail' => 'success'];
    }
}