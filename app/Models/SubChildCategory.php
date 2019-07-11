<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SubChildCategory extends Model
{
    protected $table = 'sub_child_categories';
    protected $fillable = [
        'sub_category_id', 'name', 'slug', 'image', 'status', 'ch_name', 'trch_name'
    ];
    protected $appends = ['eng_name'];

    function getParentSubCategory()
    {
        return $this->hasOne(SubCategory::class, 'id', 'sub_category_id');
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
            case 'ch':
                return $this->ch_name ?? $value;
                break;
            case 'tr-ch':
                return $this->trch_name ?? $value;
                break;
            default:
                return $value;
                break;
        }
    }
}
