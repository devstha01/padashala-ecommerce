<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = [
        'category_id', 'name', 'slug', 'image', 'status','ch_name','trch_name'
    ];
    protected $appends = ['eng_name'];

    function getSubChildCategory()
    {
        return $this->hasMany(SubChildCategory::class, 'sub_category_id', 'id');
    }

    function getEngNameAttribute()
    {
        return $this->getOriginal('name');
    }
    function getParentCategory()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
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
    }}