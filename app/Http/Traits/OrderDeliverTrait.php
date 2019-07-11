<?php

namespace App\Http\Traits;
use App\Library\ShoppingBonus;
use App\Models\Commisions\Shopping;
use App\Models\Commisions\ShoppingBonusDistribution;
use App\Models\Commisions\ShoppingLog;
use App\Models\Commisions\ShoppingMerchant;
use App\Models\Members\MemberAsset;
use App\Models\MerchantAsset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait OrderDeliverTrait
{
    function shoppingLogAfterDeliver($orderItem){
        $merchant_id = $orderItem->getProduct->getBusiness->getMerchant->id;
        $admin_id = 1;
        $total = ($orderItem->sell_price * $orderItem->quantity) * (1 + ((env('TAX_PERCENT') ?? 0) / 100));

        $ShoppingMerchant = ShoppingMerchant::where('merchant_id', $merchant_id)->first();

        //        Shopping Bonus distribution
        $merchant = (($ShoppingMerchant->merchant_rate ?? (95)) / 100) * $total;

//        dd($merchant);
        $remain = $total - $merchant;

        $rates = Shopping::all()->keyBy('key');

        $shopping = ($rates['shopping_bonus_rate']->value / 100) * $remain;

        $admin = $remain - $shopping;

        $administration = ($rates['admin_rate']->value / 100) * $admin;
        $bonus = $admin - $administration;

        $distribute = ShoppingLog::create([
            'merchant_id' => $merchant_id,
            'admin_id' => $admin_id,
            'total' => $total,
            'merchant' => $merchant,
            'shopping_bonus' => $shopping,
            'administration' => $administration,
            'bonus' => $bonus,
            'order_item_id' => $orderItem->id,
//            'payment_method' => serialize($pay_method),
        ]);

        $user = User::find($orderItem->getOrder->user_id);
        //Payment
        $merchantAsset = MerchantAsset::where('merchant_id', $merchant_id)->first();
//        $memberA = MemberAsset::where('member_id', $user->id)->first();

        $member = $user->is_member;
        switch ($member) {
            case 0:
                if (str_contains($orderItem->getOrder->payment_method, 'ecash_wallet')) {
                    //            Assign Bonus
                    $customerRep = new ShoppingBonus();
                    $bonus_list = serialize($customerRep->customerBonus($orderItem, $user->id));
                    ShoppingBonusDistribution::create([
                        'buyer_id' => $user->id,
                        'item_id' => $orderItem->id,
                        'bonus_list' => $bonus_list,
                    ]);
//                    $memberA->update(['ecash_wallet' => ($total * .05) + $memberA->ecash_wallet]);
                }
                break;
            case 1:
//            Assign Bonus
                $memberRep = new ShoppingBonus();
                $bonus_list = serialize($memberRep->assignBonus($orderItem->id, $user->id, $shopping));
                ShoppingBonusDistribution::create([
                    'buyer_id' => $user->id,
                    'item_id' => $orderItem->id,
                    'bonus_list' => $bonus_list,
                ]);
                break;
        }
//        Reward
        if ($merchantAsset)
            $merchantAsset->update(['ecash_wallet' => ($merchant + $merchantAsset->ecash_wallet + env('DELIVERY_COST') ?? 0)]);
        else
            MerchantAsset::create(['merchant_id' => $merchant_id, 'ecash_wallet' => $merchant + env('DELIVERY_COST') ?? 0]);


    }
}