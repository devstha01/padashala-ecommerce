<?php

namespace App\Http\Traits;

use App\Models\MerchantAsset;
use App\Models\ShoppingLog;

trait OrderDeliverTrait
{
    function shoppingLogAfterDeliver($orderItem)
    {
        $category_share = $orderItem->category_share;
        $product_share = $orderItem->product_share;

        if ($product_share > 0) {
            $total_percentage = ($product_share) / 100;
        } else {
            $total_percentage = ($category_share) / 100;
        }
        $user_id = $orderItem->getOrder->user_id;
        $merchant_id = $orderItem->getProduct->getBusiness->merchant_id;

        $merchantAsset = MerchantAsset::where('merchant_id', $merchant_id)->first();

        $total = $orderItem->quantity * $orderItem->sell_price;
        $merchant_amount = $total * (100 - $total_percentage);
        $admin_amount = $total * $total_percentage;

        $merchantAsset->update(['ecash_wallet' => $merchantAsset->ecash_wallet + $merchant_amount]);
        ShoppingLog::create([
            'order_item_id' => $orderItem->id,
            'user_id' => $user_id,
            'merchant_id' => $merchant_id,
            'total' => $total,
            'merchant_amount' => $merchant_amount,
            'admin_amount' => $admin_amount,
        ]);
    }
}