<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'category_id', 'sub_category_id', 'sub_child_category_id', 'merchant_business_id', 'name', 'detail', 'slug', 'featured_image',
        'marked_price', 'sell_price', 'discount', 'quantity', 'status', 'is_featured', 'description'
    ];

    protected $appends = ['eng_name', 'eng_detail', 'eng_description'];


    function getCategory()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    function getSubCategory()
    {
        return $this->hasOne(SubCategory::class, 'id', 'sub_category_id');
    }

    function getSubChildCategory()
    {
        return $this->hasOne(SubChildCategory::class, 'id', 'sub_child_category_id');
    }

    function getProductVariant()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    function getProductImage()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    function getBusiness()
    {
        return $this->hasOne(MerchantBusiness::class, 'id', 'merchant_business_id');
    }

    function getFeatureReq()
    {
        return $this->hasMany(FeatureProduct::class, 'product_id', 'id')->orderBy('feature_till', 'DESC');
    }


    function getNameAttribute($value)
    {
        $locale = App::getLocale() ?? 'en';
        switch ($locale) {
            case 'en':
                return $value;
                break;
            case 'ch':
                return $this->hasOne(CHProduct::class, 'product_id', 'id')->first()->name ?? $value;
                break;
            case 'tr-ch':
                return $this->hasOne(TRCHProduct::class, 'product_id', 'id')->first()->name ?? $value;
                break;
            default:
                return $value;
                break;
        }
    }

    function getDetailAttribute($value)
    {
        $locale = App::getLocale() ?? 'en';
        switch ($locale) {
            case 'en':
                return $value;
                break;
            case 'ch':
                return $this->hasOne(CHProduct::class, 'product_id', 'id')->first()->detail ?? $value;
                break;
            case 'tr-ch':
                return $this->hasOne(TRCHProduct::class, 'product_id', 'id')->first()->detail ?? $value;
                break;
            default:
                return $value;
                break;
        }
    }

    function getDescriptionAttribute($value)
    {
        $locale = App::getLocale() ?? 'en';
        switch ($locale) {
            case 'en':
                return $value;
                break;
            case 'ch':
                return $this->hasOne(CHProduct::class, 'product_id', 'id')->first()->description ?? $value;
                break;
            case 'tr-ch':
                return $this->hasOne(TRCHProduct::class, 'product_id', 'id')->first()->description ?? $value;
                break;
            default:
                return $value;
                break;
        }
    }

    function getEngNameAttribute()
    {
        return $this->getOriginal('name');
    }

    function getEngDetailAttribute()
    {
        return $this->getOriginal('detail');
    }

    function getEngDescriptionAttribute()
    {
        return $this->getOriginal('description');
    }
}
