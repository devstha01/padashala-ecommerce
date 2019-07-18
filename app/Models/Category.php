<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'name', 'slug', 'image', 'status', 'is_highlighted', 'ch_name', 'trch_name','share_percentage'
    ];

    protected $appends = ['eng_name'];

    function getSubCategory()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }

    function getEngNameAttribute()
    {
        return $this->getOriginal('name');
    }
    function getNameAttribute($value)
    {
        $locale = App::getLocale() ?? 'en';
        switch ($locale) {
            case 'en':
                return $value;
                break;
//            case 'ch':
//                return $this->ch_name ?? $value;
//                break;
//            case 'tr-ch':
//                return $this->trch_name ?? $value;
//                break;
            default:
                return $value;
                break;
        }
    }
}
