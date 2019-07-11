<?php

namespace App\Http\Controllers\Auth;

use App\Models\Category;
use App\Models\Country;
use App\Models\Members\MemberBankInfo;
use App\Models\Members\MemberNominee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $_data = [];

    public function __construct()
    {
        $this->_data['countries'] = Country::all();

        $categories = Category::where('status', 1)->get() ?? [];
        $this->_data['all_categories'] = collect($categories);
        $this->_data['home_categories'] = collect($categories)->take(8);
        $data = [];
        foreach ($categories as $category) {
            $catStatus = false;
            if (count($category->getSubCategory->where('status', 1)) !== 0)
                $catStatus = true;
            if ($catStatus)
                $data[] = $category;
        }
        $this->_data['categories'] = collect($data);
    }

    public function editProfile($id)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        $users = User::find($id);
        return view('frontend.auth.profile-edit', compact('users'), $this->_data)->with('title', __('message.Golden Gate'));
    }


    public function updateProfile(Request $request)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        $request->validate([
//            'name' => 'required',
//            'surname' => 'required',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::user()->id,
            'country_id' => 'required|not_in:0',
            'address' => 'required',
            'contact_number' => 'required|numeric',
            'marital_status' => 'required',
            'identification_type' => 'required',
            'identification_number' => 'required',
        ]);


        $input = [
//            'name' => $request->name,
//            'surname' => $request->surname,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'city' => $request->city ?? null,
            'address' => $request->address,
            'contact_number' => $request->contact_number,
            'marital_status' => $request->marital_status,
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
        ];

        $nominee_input = [
            'nominee_name' => $request->nominee_name ?? null,
            'contact_number' => $request->nominee_contact_number ?? null,
            'identification_type' => $request->nominee_identification_type ?? null,
            'identification_number' => $request->nominee_identification_number ?? null,
            'relationship' => $request->nominee_relationship ?? null,
        ];
        if (User::find(Auth::user()->id)->update($input)) {
            if (Auth::user()->is_member === 1) {
                $nominee = MemberNominee::where('member_id', Auth::id())->first();
                if ($nominee)
                    $nominee->update($nominee_input);
            }
            return redirect()->back()->with('success', __('message.Profile updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }



    public function changePasswordForm()
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        return view('frontend.auth.changepassword', $this->_data)->with('title', __('message.Golden Gate'));
    }

    public function changePasswordUser(Request $request)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        $user = User::find(Auth::user()->id);
        if (!(Hash::check($request->old_password, $user->password))) {
            // The passwords does not matches
            return redirect()->back()->with('old_password', __('message.Invalid previous password'));
        }

        if (strcmp($request->new_password, $request->old_password) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("new_password", __('message.New Password cannot be same as your current password'));
        }
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|same:confirm_password|min:6',
            'confirm_password' => 'required|min:6',
        ]);

        if ($user->update(['password' => bcrypt($request->new_password)]))
            return redirect()->back()->with("success", __('message.Password changed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    public function changeTransactionPassword(Request $request)
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        $user = User::find(Auth::user()->id);
        if (!(Hash::check($request->old_transaction_password, $user->transaction_password))) {
            // The transaction_passwords does not matches
            return redirect()->back()->with('old_transaction_password', __('message.Invalid previous transaction password'));
        }

        if (strcmp($request->new_transaction_password, $request->old_transaction_password) == 0) {
            //Current transaction_password and new transaction_password are same
            return redirect()->back()->with("new_transaction_password", __('message.New Password cannot be same as your current password'));
        }
        $request->validate([
            'old_transaction_password' => 'required',
            'new_transaction_password' => 'required|same:confirm_transaction_password|min:6',
            'confirm_transaction_password' => 'required|min:6',
        ]);

        if ($user->update(['transaction_password' => bcrypt($request->new_transaction_password)]))
            return redirect()->back()->with("success", __('message.Transaction password changed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function viewProfile()
    {
        if (Auth::user() === null) return redirect()->to(route('checkout-login'));
        $this->_data['user'] = User::find(Auth::user()->id);
        return view('frontend.home.profile', $this->_data)->with('title', __('message.Golden Gate'));
    }
}
