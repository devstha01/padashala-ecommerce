<?php

namespace App\Http\Traits;

trait ProductWithOptionOnly
{
    function validProductWithOption($products)
    {
        $data = [];
        foreach ($products as $product) {
            if (count($product->getProductVariant->where('status', 1)) != 0)
                $data[] = $product;
        }
        return collect($data);
    }
}