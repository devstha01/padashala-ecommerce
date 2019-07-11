<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserPayLog extends Model
{
    protected $table = 'user_payment_log';
    protected $fillable = ['user_id', 'user_payment_id', 'bonus_list', 'total', 'merchant', 'status'];

    protected $appends = ['user_bonus'];

    function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    function getUserPayment()
    {
        return $this->hasOne(UserPayment::class, 'id', 'user_payment_id');
    }

    function getUserBonusAttribute()
    {


        $bonus = [
            'customer_bonus' => false,
            'auto' => false,
            'special' => false,
            'standard' => false,
        ];

        foreach (unserialize($this->bonus_list) as $key => $type) {
            $bonus[$key][] = $type;
        }
        $bonus = collect($bonus);
        if ($bonus['customer_bonus']) {
            $sum = 0;
            foreach ($bonus['customer_bonus'] as $customer_bonus) {
                $sum = $customer_bonus;
            }
        }

        if ($bonus['standard']) {
            $sum1 = 0;
            foreach ($bonus['standard'] as $standard_bonus) {
                $stan = collect($standard_bonus)->first();
                $sum1 += $stan['shop_point'];
            }
        }

        if ($bonus['auto']) {
            $sum2 = 0;
            foreach ($bonus['auto'] as $auto_bonus) {
                $aut = collect($auto_bonus)->first();
                $sum2 += $aut['shop_point'];
            }
        }

        if ($bonus['special']) {
            $sum3 = 0;
            foreach ($bonus['special'] as $special_bonus) {
                $special = collect($special_bonus)->first();
                $sum3 += $special['shop_point'];
            }
        }

        $string = '';
        $sum = $sum ?? false;
        $sum1 = $sum1 ?? false;
        $sum2 = $sum2 ?? false;
        $sum3 = $sum3 ?? false;

        if ($sum !== false) $string = $string . '$' . $sum;
        if ($sum1 !== false) $string = $string . 'Standard: ' . $sum1;
        if ($sum2 !== false) $string = $string . '/ Auto: ' . $sum2;
        if ($sum3 !== false) $string = $string . '/ Special: ' . $sum3;
        return $string;
    }
}
