<?php namespace App\Library;

use Illuminate\Support\Facades\Auth;

class StaffPermission
{
    function staffHasPermission($permission)
    {
        if (!(!Auth::guard('admin')->user()->hasRole('Admin') && !Auth::guard('admin')->user()->can($permission))) {
            return true;
        }
        return false;
    }

    function adminHasRole()
    {
        if (Auth::guard('admin')->user()->hasRole('Admin')) {
            return true;
        }
        return false;
    }

}
