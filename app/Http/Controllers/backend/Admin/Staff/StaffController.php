<?php

namespace App\Http\Controllers\backend\Admin\Staff;

use App\Models\Admin;
use App\Models\Country;
use App\Models\Members\MemberAsset;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;

class StaffController extends Controller
{
    private $_path = 'backend.admin.staff-master.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    function showStaffRegisterForm()
    {
        $this->_data['countries'] = Country::all();
        return view('backend.admin.staff-master.register', $this->_data);
    }

    function postStaffRegisterForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'user_name' => 'required|unique:admins,user_name',
            'email' => 'required|email|unique:admins,email',
            'permission' => 'required',
            'country_id' => 'required|not_in:0',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required|numeric',
            'dob_date' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
//            'dob_date' => 'required',
            'marital_status' => 'required',
            'new_password' => 'required|same:retype_password|min:6',
            'retype_password' => 'required|min:6',
//            'transaction_password' => 'required|same:retype_transaction_password|min:6',
//            'retype_transaction_password' => 'required|min:6',
            'identification_type' => 'required',
            'identification_number' => 'required',
            'position' => 'required',
        ]);

        $input = [
            'name' => $request->name,
            'surname' => $request->surname,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'role' => 'staff',
            'country_id' => $request->country_id,
            'gender' => $request->gender,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'dob' => Carbon::parse($request->dob_date)->format('Y-m-d'),
            'marital_status' => $request->marital_status,
            'password' => bcrypt($request->new_password),
//            'transaction_password' => bcrypt($request->transaction_password),
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
            'joining_date' => Carbon::now()->format('Y-m-d'),
            'position' => $request->position,
        ];

        if ($staff = Admin::create($input)) {

            $permissions = Permission::where('guard_name', 'admin')->get();
            switch ($request->permission) {
                case 'view':
                    $staff->assignRole('Staff');
                    foreach ($permissions as $permission) {
                        $permissionControl = explode('.', $permission->name);
                        if (isset($permissionControl[2])) {
                            if ($permissionControl[0] == 1)
                                $staff->givePermissionTo($permission->name);
                        }
                    }
                    break;
                case 'all':
                    foreach ($permissions as $permission) {
                        $staff->givePermissionTo($permission->name);
                    }
                    break;
                default:
                    $staff->assignRole('Staff');
                    break;
            }
            return redirect()->to(route('admin-staff-list'))->with('success', __('message.New staff created successfully'));
        }
        return redirect()->back()->with('fail', __('message.Failed to create staff'));
    }

    function listStaff(Request $request)
    {

//        $this->_data['term'] = $request->term ?? null;
//        if (empty($request->term))
        $this->_data['admins'] = Admin::all();
//        else
//            $this->_data['admins'] = Admin::where('name', 'like', '%' . $request->term . '%')->orWhere('surname', 'like', '%' . $request->term . '%')->get() ?? [];
//        $this->_data['countries'] = Country::all();
        return view($this->_path . 'staffs-list', $this->_data);
    }


    function editStaff($id)
    {
        $this->_data['admin'] = Admin::find($id);
        $this->_data['countries'] = Country::all();
        return view($this->_path . 'edit', $this->_data);
    }

    function changeStatus($id)
    {
        if (empty($id)) return redirect()->back();
        $staff = Admin::find($id);

        if ($staff->status === 1) {
            if ($staff->update(['status' => 0])) {
                return redirect()->back()->with('success', __('message.Status of :user disabled', ['user' => $staff->user_name]));
            }
        } else {
            if ($staff->update(['status' => 1])) {
                return redirect()->back()->with('success', __('message.Status of :user enabled', ['user' => $staff->user_name]));
            }
        }
        return redirect()->back()->with('fail', __('message.Failed to change status'));
    }

    function submitProfileEdit(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'user_name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
//            'role' => 'required',
            'country_id' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required',
            'dob_date' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
//            'dob_date' => 'required',
            'marital_status' => 'required',
            'identification_type' => 'required',
            'identification_number' => 'required',
            'position' => 'required',
        ]);

        $admin = Admin::find($id);

        $validated ['name'] = $request->name;
        $validated ['surname'] = $request->surname;
        $validated ['user_name'] = $request->user_name;
        $validated ['email'] = $request->email;

//        if ($admin->id === Auth::guard('admin')->id())
//            $request->role = $admin->role;

//        $validated ['role'] = $request->role;
        $validated ['country_id'] = $request->country_id;
        $validated ['gender'] = $request->gender;
        $validated ['address'] = $request->address;
        $validated ['contact_number'] = $request->contact_number;
        $validated ['dob'] = Carbon::parse($request->dob_date)->format('Y-m-d');
        $validated ['marital_status'] = $request->marital_status;
        $validated ['identification_type'] = $request->identification_type;
        $validated ['identification_number'] = $request->identification_number;
        $validated ['position'] = $request->position;

        if ($admin->update($validated))
            return redirect()->back()->with('success', __('message.Staff profile updated successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function submitPasswordEdit(Request $request, $id)
    {
        session()->flash('edit-profile-staff', 'pass');
        $staff = Admin::find($id);
//        if (!(Hash::check($request->old_password, $staff->password))) {
//            // The passwords does not matches
//            return redirect()->back()->with('old_password', __('message.Invalid previous password'));
//        }

//        if (strcmp($request->new_password, $request->old_password) == 0) {
//            //Current password and new password are same
//            return redirect()->back()->with("new_password", __('message.New Password cannot be same as your current password'));
//        }
        $request->validate([
            'old_password' => 'required|min:6',
            'new_password' => 'required|same:retype_password|min:6',
            'retype_password' => 'required|min:6',
        ]);

        if ($staff->update(['password' => bcrypt($request->new_password)]))
            return redirect()->back()->with("success", __('message.Password changed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function submitTrannsactionPasswordEdit(Request $request, $id)
    {
        session()->flash('edit-profile-merchant', 'pass');
        $staff = Admin::find($id);
//        if (!(Hash::check($request->old_transaction_password, $staff->transaction_password))) {
//            // The transaction_passwords does not matches
//            return redirect()->back()->with('old_transaction_password', __('message.Invalid previous transaction password'));
//        }
//
//        if (strcmp($request->new_transaction_password, $request->old_transaction_password) == 0) {
//            //Current transaction_password and new transaction_password are same
//            return redirect()->back()->with("new_transaction_password", __('message.New Password cannot be same as your current password'));
//        }
        $request->validate([
            'old_transaction_password' => 'required|min:6',
            'new_transaction_password' => 'required|same:retype_transaction_password|min:6',
            'retype_transaction_password' => 'required|min:6',
        ]);

        if ($staff->update(['transaction_password' => bcrypt($request->new_transaction_password)]))
            return redirect()->back()->with("success", __('message.Transaction password changed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

//    function searchStaff(Request $request)
//    {
//        $data['staffs'] = Admin::where('name', 'like', '%' . $request->term . '%')->orWhere('surname', 'like', '%' . $request->term . '%')->get() ?? [];
//        return response()->json($data);
//    }

    function permissionStaff($id)
    {
        $this->_data['staff'] = Admin::find($id);
        if (!$this->_data['staff']) return redirect()->back();
        $permissions = Permission::where('guard_name', 'admin')->get();
        $permissionList = [];
        foreach ($permissions as $permission) {
            $permissionControl = explode('.', $permission->name);
            if (isset($permissionControl[2])) {
                $fillPermision = [
                    'master' => $permissionControl[1],
                    'name' => $permissionControl[2],
                ];
                if (!in_array($fillPermision, $permissionList))
                    $permissionList[] = $fillPermision;
            }
        }
        $this->_data['permissions'] = collect($permissionList)->groupBy('master');
        $this->_data['available_permissions'] = collect($permissions)->pluck('name')->toArray() ?? [];
        $this->_data['access'] = $this->_data['staff']->getPermissionNames()->toArray() ?? [];
        return view('backend.admin.staff-master.staff-permission', $this->_data);
    }

    function changePermission($id, Request $request)
    {
        $staff = Admin::find($id);
        $permission = $request->permission ?? [];
        $staff->syncPermissions($permission);
        return redirect()->back()->with('success', __('message.Permission updated successfully'));
    }


//    top up
    function topUp()
    {
        return view('backend.admin.staff-master.top-up');
    }

    function topUpPost(Request $request)
    {
        $request->validate([
            'user_name' => 'required',
            'type' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);

        switch (strtolower($request->type)) {
            case 'customer':
                $user = User::where('user_name', $request->user_name)->first();
                if ($user) {
                    $asset = MemberAsset::where('member_id', $user->id)->first();
                    $asset->update(['ecash_wallet' => $asset->ecash_wallet + $request->amount]);
                } else
                    return redirect()->back()->with('fail', 'User not found');

                break;
            case 'merchant':
                $user = Merchant::where('user_name', $request->user_name)->first();
                if ($user) {
                    $asset = MerchantAsset::where('merchant_id', $user->id)->first();
                    $asset->update(['ecash_wallet' => $asset->ecash_wallet + $request->amount]);
                } else
                    return redirect()->back()->with('fail', 'Merchant not found');
                break;
            default:
                return redirect()->back()->with('fail', 'Invalid Top up');
                break;
        }
        return redirect()->back()->with('success', 'Top up amount Rs.' . $request->amount . ' to ' . $request->user_name    );
    }
}
