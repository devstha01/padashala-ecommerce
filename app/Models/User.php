<?php

namespace App\Models;

use App\Models\Members\MemberAsset;
use App\Models\Members\MemberNominee;
use App\Models\Members\MemberStandardTree;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'surname', 'name', 'user_name', 'email', 'password', 'country_id', 'address', 'contact_number',
        'dob', 'gender', 'status', 'transaction_password', 'is_member', 'marital_status', 'joining_date',
        'identification_type', 'identification_number', 'city', 'postal_code', 'qr_code', 'qr_image', 'created_by', 'jwt_token_handle'
    ];
    protected $table = 'users';

    protected $hidden = [
        'password', 'remember_token', 'transaction_password', 'jwt_token_handle'
    ];
    protected $appends = ['package_type'];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    function getCountry()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }

    function getStandardTree()
    {
        return $this->hasOne(MemberStandardTree::class, 'member_id');
    }

    function getWallet()
    {
        return $this->hasOne('App\Models\Members\MemberAsset', 'member_id', 'id');
    }

    function getMemberCashWithdraw()
    {
        return $this->hasOne('App\Models\Members\MemberCashWithdraw', 'member_id', 'id');
    }

    function getAsset()
    {
        return $this->hasOne('App\Models\Members\MemberAsset', 'member_id', 'id');
    }

    function getQrImageAttribute($value)
    {
        return url('image/qr_image') . '/' . $value;
    }

    function getNominee()
    {
        return $this->hasOne(MemberNominee::class, 'member_id', 'id');
    }

    function getPackageTypeAttribute()
    {
        $member = $this->getOriginal('is_member');
        if (!$member)
            return null;
        else {
            switch ($this->hasOne('App\Models\Members\MemberAsset', 'member_id', 'id')->first()->package_id ?? null) {
                case 1:
                    return 'Gold';
                    break;
                case 2:
                    return 'Platinum';
                    break;
                case 3:
                    return 'Diamond';
                    break;
                default:
                    return null;
                    break;
            }
        }
    }
}
