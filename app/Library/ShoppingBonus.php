<?php

namespace App\Library;

use App\Http\Traits\WalletsHistoryTrait;
use App\Models\Commisions\MonthlyBonus;
use App\Models\Commisions\Shopping;
use App\Models\Commisions\ShoppingBonusAuto;
use App\Models\Commisions\ShoppingBonusSpecial;
use App\Models\Commisions\ShoppingBonusStandard;
use App\Models\Commisions\ShoppingMerchant;
use App\Models\Members\MemberAsset;
use App\Models\Members\MemberAutoTree;
use App\Models\Members\MemberSpecialTree;
use App\Models\Members\MemberStandardTree;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\UserPayLog;
use App\Models\UserPayment;

class ShoppingBonus
{
    use WalletsHistoryTrait;

    public function customerBonus($orderItem, $customer_id)
    {
        $rate = Shopping::all()->keyBy('key');

        $merchant_id = $orderItem->getProduct->getBusiness->getMerchant->id;
        $total = ($orderItem->sell_price * $orderItem->quantity) * (1 + ((env('TAX_PERCENT') ?? 0) / 100));

        $ShoppingMerchant = ShoppingMerchant::where('merchant_id', $merchant_id)->first();
        $merchant = (($ShoppingMerchant->merchant_rate ?? (95)) / 100) * $total;
        $remain = $total - $merchant;
//        $shopping = ($rate['customer_bonus']->value / 100) * $remain;

//        $admin = $remain - $shopping;
//        $monthly_bonus = ($rate['bonus_rate']->value / 100) * $admin;
//        $this->monthlyBonus($orderItem->id, $monthly_bonus);

        $bonus = ($rate['customer_bonus']->value / 100) * $remain;
        $asset = MemberAsset::where('member_id', $customer_id)->first();
        if ($asset) {
            $asset->update(['ecash_wallet' => $asset->ecash_wallet + $bonus]);
            $this->createWalletReport($customer_id, $bonus, 'Customer Shopping Bonus', 'ecash', 'IN');
        }
        return [
            'customer_bonus' => $bonus
        ];
    }

    public function monthlyBonus($item_id, $total)
    {
        $rate = Shopping::all()->keyBy('key');

        MonthlyBonus::create([
            'order_item_id' => $item_id,
            'bonus' => ($total),
            'hk' => (($rate['hk_bonus']->value / $rate['bonus_rate']->value) * $total),
            'asia' => (($rate['asia_bonus']->value / $rate['bonus_rate']->value) * $total),
            'top_shopper' => (($rate['top_shopper_bonus']->value / $rate['bonus_rate']->value) * $total),
        ]);
    }

    public function assignBonus($item_id, $member_id, $totalBonus)
    {
        $rate = Shopping::all()->keyBy('key');

        $standard = ($rate['standard_shopping_bonus']->value / $rate['shopping_bonus_rate']->value) * $totalBonus;
        $auto = ($rate['auto_shopping_bonus']->value / $rate['shopping_bonus_rate']->value) * $totalBonus;
        $special = ($rate['special_shopping_bonus']->value / $rate['shopping_bonus_rate']->value) * $totalBonus;

        $item = OrderItem::find($item_id);

//      monthly bonus calculate
        $merchant_id = $item->getProduct->getBusiness->getMerchant->id;
        $total = ($item->sell_price * $item->quantity) * (1 + ((env('TAX_PERCENT') ?? 0) / 100));
        $ShoppingMerchant = ShoppingMerchant::where('merchant_id', $merchant_id)->first();
        $merchant = (($ShoppingMerchant->merchant_rate ?? (95)) / 100) * $total;
        $remain = $total - $merchant;
        $shopping = ($rate['shopping_bonus_rate']->value / 100) * $remain;
        $admin = $remain - $shopping;
        $monthly_bonus = ($rate['bonus_rate']->value / 100) * $admin;
        $this->monthlyBonus($item->id, $monthly_bonus);

        return [
            'standard' => $this->standardBonus($member_id, $standard),
            'auto' => $this->autoBonus($member_id, $auto),
            'special' => $this->specialBonus($member_id, $special)
        ];
    }

    function paymentCustomerBonus($customer_id, $payment)
    {
        $ShoppingMerchant = ShoppingMerchant::where('merchant_id', $payment->to_merchant_id)->first();
        $rate = Shopping::all()->keyBy('key');
        $total = $payment->amount;
        $merchant = (($ShoppingMerchant->merchant_rate ?? (95)) / 100) * $total;
        $remain = $total - $merchant;

        $bonus = ($rate['customer_bonus']->value / 100) * $remain;
        $asset = MemberAsset::where('member_id', $customer_id)->first();
        if ($asset) {
            $asset->update(['ecash_wallet' => $asset->ecash_wallet + $bonus]);
            $this->createWalletReport($customer_id, $bonus, 'Customer Payment Bonus', 'ecash', 'IN');
        }
        $array = [
            'customer_bonus' => $bonus
        ];
        UserPayLog::create([
            'user_id' => $customer_id,
            'user_payment_id' => $payment->id,
            'total' => $total,
            'merchant' => $merchant,
            'bonus_list' => serialize($array)
        ]);

        return $merchant;
    }


    //payemnt bonus for member
    function paymentMemberBonus($member_id, $payment)
    {
//        $payment = UserPayment::find($payment_id);
        $ShoppingMerchant = ShoppingMerchant::where('merchant_id', $payment->to_merchant_id)->first();
        $rate = Shopping::all()->keyBy('key');
        $total = $payment->amount;
        $merchant = (($ShoppingMerchant->merchant_rate ?? (95)) / 100) * $total;
        $remain = $total - $merchant;
        $totalBonus = ($rate['shopping_bonus_rate']->value / 100) * $remain;
        $standard = ($rate['standard_shopping_bonus']->value / $rate['shopping_bonus_rate']->value) * $totalBonus;
        $auto = ($rate['auto_shopping_bonus']->value / $rate['shopping_bonus_rate']->value) * $totalBonus;
        $special = ($rate['special_shopping_bonus']->value / $rate['shopping_bonus_rate']->value) * $totalBonus;

        $array = [
            'standard' => $this->standardBonus($member_id, $standard),
            'auto' => $this->autoBonus($member_id, $auto),
            'special' => $this->specialBonus($member_id, $special)
        ];

        UserPayLog::create([
            'user_id' => $member_id,
            'user_payment_id' => $payment->id,
            'total' => $total,
            'merchant' => $merchant,
            'bonus_list' => serialize($array)
        ]);
        return $merchant;
    }


    protected function standardBonus($member_id, $bonusValue)
    {
        $bonusUsers = $this->getStandardTreeBonus($member_id);
        $dataArray = [];
        if ($bonusUsers) {
//            dd($bonusUsers);
            foreach ($bonusUsers as $key => $value) {
                $packageId = MemberAsset::where('member_id', $value['member_id'])->first()->package_id;
                $bonusPercentage = ShoppingBonusStandard::where('package_id', $packageId)->where('generation_position', $value['generation'])->first()->percentage ?? 0;
                $bonusCashValue = (($bonusPercentage / 100) * $bonusValue) * 1000;
                $memberOldEcash = MemberAsset::where('member_id', $value['member_id'])->first()->shop_point;
                $updatedEcash = $memberOldEcash + $bonusCashValue;
                $memberStatus = User::where('id', $value['member_id'])->first()->status;
                if (!$memberStatus) {
                    $updatedEcash = $memberOldEcash;
                } else {
                    $dataArray[] = ['member_id' => $value['member_id'], 'shop_point' => $bonusCashValue];
                }
                $data = array(
                    'shop_point' => $updatedEcash,
                );
                $update_asset = MemberAsset::where('member_id', $value['member_id'])->update($data);
            }
        }
        return $dataArray;
    }

    protected function autoBonus($member_id, $bonusValue)
    {
        $bonusUsers = $this->getAutoTreeBonus($member_id);
//        $packageId = MemberAsset::where('member_id', $member_id)->first()->package_id;
        $dataArray = [];
        if ($bonusUsers) {
//            dd($bonusUsers);
            foreach ($bonusUsers as $key => $value) {
                $bonusPercentage = ShoppingBonusAuto::where('generation_position', $value['generation'])->first()->percentage ?? 0;
                $bonusCashValue = (($bonusPercentage / 100) * $bonusValue) * 1000;
                $memberOldEcash = MemberAsset::where('member_id', $value['member_id'])->first()->shop_point;
                $updatedEcash = $memberOldEcash + $bonusCashValue;
                $memberStatus = User::where('id', $value['member_id'])->first()->status;
                if (!$memberStatus) {
                    $updatedEcash = $memberOldEcash;
                } else {
                    $dataArray[] = ['member_id' => $value['member_id'], 'shop_point' => $bonusCashValue];
                }
                $data = array(
                    'shop_point' => $updatedEcash,
                );
                $update_asset = MemberAsset::where('member_id', $value['member_id'])->update($data);
            }
        }
        return $dataArray;
    }

    protected function specialBonus($member_id, $bonusValue)
    {
        $bonusUsers = $this->getSpecialTreeBonus($member_id);
//        $packageId = MemberAsset::where('member_id', $member_id)->first()->package_id;
        $dataArray = [];

        if ($bonusUsers) {
//            dd($bonusUsers);
            foreach ($bonusUsers as $key => $value) {
                $bonusPercentage = ShoppingBonusSpecial::where('generation_position', $value['generation'])->first()->percentage ?? 0;
                $bonusCashValue = (($bonusPercentage / 100) * $bonusValue) * 1000;
                $memberOldEcash = MemberAsset::where('member_id', $value['member_id'])->first()->shop_point;
                $updatedEcash = $memberOldEcash + $bonusCashValue;
                $memberStatus = User::where('id', $value['member_id'])->first()->status;
                if (!$memberStatus) {
                    $updatedEcash = $memberOldEcash;
                } else {
                    $dataArray[] = ['member_id' => $value['member_id'], 'shop_point' => $bonusCashValue];
                }
                $data = array(
                    'shop_point' => $updatedEcash,
                );
                $update_asset = MemberAsset::where('member_id', $value['member_id'])->update($data);
            }
        }
        return $dataArray;
    }


    protected function getStandardTreeBonus($memberId)
    {

        $check = true;
        $bonusUsers = [];
        $generationCount = 0;
        $parent_id = $memberId;

        do {
            $getNextSponser = memberStandardTree::where('member_id', $parent_id)->first();
            if ($getNextSponser) {
                $bonusUsers[] = array(
                    'member_id' => $memberId,
                    'generation' => $generationCount,
                );
                $generationCount++;
                $memberId = $getNextSponser->sponser_id;
                $parent_id = $getNextSponser->parent_id;
            } else {
                $check = false;
            }
        } while ($check);
        return $bonusUsers;
    }

    protected function getAutoTreeBonus($member_id)
    {
        $check = true;
        $memberId = $member_id;
        $bonusUsers = [];
        $bonusUsers[] = [
            'member_id' => $member_id,
            'generation' => 0,
        ];
        $generationCount = 1;
        do {
            $getNextSponser = MemberAutoTree::where('member_id', $memberId)->first();
            if ($getNextSponser) {
                if ($getNextSponser->parent_id === null) {
                    $check = false;
                } else {
                    $bonusUsers[] = array(
                        'member_id' => $getNextSponser->parent_id,
                        'generation' => $generationCount,
                    );
                }
                $generationCount++;
                $memberId = $getNextSponser->parent_id;
//                MemberAutoTree::where('member_id', $getNextSponser->parent_id)->first();
            } else {
                $check = false;
            }
        } while ($check);
        return $bonusUsers;
    }


    protected function getSpecialTreeBonus($member_id)
    {

        $check = true;
        $memberId = $member_id;
        $bonusUsers = [];
        $bonusUsers[] = [
            'member_id' => $member_id,
            'generation' => 0,
        ];
        $generationCount = 1;
        do {
            $getNextSponser = MemberSpecialTree::where('member_id', $memberId)->first();
            if ($getNextSponser) {
                if ($getNextSponser->parent_id === null) {
                    $check = false;
                } else {
                    $bonusUsers[] = array(
                        'member_id' => $getNextSponser->parent_id,
                        'generation' => $generationCount,
                    );
                }
                $generationCount++;
                $memberId = $getNextSponser->parent_id;
//                    MemberSpecialTree::where('member_id', $getNextSponser->parent_id)->first();

            } else {
                $check = false;
            }
        } while ($check);
        return $bonusUsers;
    }
}

?>