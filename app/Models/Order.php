<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'payment_method', 'total_price', 'order_date',
        'deliver_date', 'order_status_id', 'address', 'city', 'country_id',
        'delivery_price', 'sub_total', 'tax', 'contact_number', 'email'
    ];
    protected $appends = ['payment_array'];

    function getOrderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    function getUser()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


    function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::class, 'key', 'order_status_id');
    }

    function getCountry()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    function getPaymentArrayAttribute()
    {
        $unserialized = unserialize($this->getOriginal('payment_method'));
        $data = [
            'ecash_wallet' => ['name' => 'Cash Wallet', 'amount' => 0, 'status' => 0],
            'evoucher_wallet' => ['name' => 'Voucher Wallet', 'amount' => 0, 'status' => 0],
            'cash' => ['name' => 'Cash', 'amount' => 0, 'status' => 0],
        ];
        if (is_array($unserialized)) {
            foreach ($unserialized as $met => $pay) {
                switch ($met) {
                    case 'ecash_wallet':
                        $data['ecash_wallet']['amount'] = $pay;
                        $data['ecash_wallet']['status'] = 1;
                        break;

                    case 'evoucher_wallet':
                        $data['evoucher_wallet']['amount'] = $pay;
                        $data['evoucher_wallet']['status'] = 1;
                        break;

                    case 'cash':
                        $data['cash']['amount'] = $pay;
                        $data['cash']['status'] = 1;
                        break;
                }
            }
        }
        return $data;
    }

}
