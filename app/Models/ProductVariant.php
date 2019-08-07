<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ProductVariant extends Model
{
    protected $table = 'product_variants';

    protected $fillable = [
        'name', 'product_id', 'marked_price', 'sell_price', 'discount', 'quantity', 'status', 'color_id', 'size', 'stock_option'
    ];

    protected $appends = ['detail', 'eng_name', 'color_data'];

    public function getDetailAttribute()
    {
        return $this->name;
    }

    public function getEngNameAttribute()
    {
        return $this->getOriginal('name');
    }

    function getNameAttribute($value)
    {
        $locale = App::getLocale() ?? 'en';
//        switch ($locale) {
//            case 'en':
//                break;
//            case 'ch':
//                $value = $this->hasOne(CHProductVariant::class, 'product_variant_id', 'id')->first()->name ?? $value;
//                break;
//            case 'tr-ch':
//                $value = $this->hasOne(TRCHProductVariant::class, 'product_variant_id', 'id')->first()->name ?? $value;
//                break;
//        }
//        return str_limit($value, 12, '');
        return $value;
    }

    function getColor()
    {
        return $this->hasOne(Color::class, 'id', 'color_id');
    }

    function getColorDataAttribute()
    {
        $color_id = $this->getOriginal('color_id');
        $color = Color::find($color_id);
        return [
            'name'=>$color->name??'No name',
            'code'=>$color->color_code??'#ffffff',
        ];
    }
}
