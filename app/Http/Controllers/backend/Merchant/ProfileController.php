<?php

namespace App\Http\Controllers\backend\Merchant;

use App\Models\Country;
use App\Models\Merchant;
use App\Models\MerchantBusiness;
use App\Models\MerchantDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class ProfileController extends Controller
{
    private $_path = 'backend.merchant.dashboard.user_profile.';
    private $_data = [];
    private $_merchant_id = '';

    public function __construct()
    {
        $this->middleware('merchant');
        $this->middleware(function ($request, $next) {
            $this->_merchant_id = Auth::guard('merchant')->user()->id;
            return $next($request);
        });
    }

    function profile()
    {
        $this->_data['merchant'] = Merchant::find($this->_merchant_id);
        return view($this->_path . 'profile', $this->_data);
    }

    function editMerchant()
    {
        $this->_data['merchant'] = Merchant::find($this->_merchant_id);
        $this->_data['countries'] = Country::all();
        return view($this->_path . 'edit-profile', $this->_data);
    }

    function viewMerchant()
    {
        $this->_data['merchant'] = Merchant::find($this->_merchant_id);
        return view($this->_path . 'profile', $this->_data);
    }

    function changeStatus()
    {
        $mer = Merchant::find($this->_merchant_id);

        if ($mer->status === 1) {
            if ($mer->update(['status' => 0])) {
                return redirect()->back()->with('success', __('message.Status disabled'));
            }
        } else {
            if ($mer->update(['status' => 1])) {
                return redirect()->back()->with('success', __('message.Status enabled'));
            }
        }
        return redirect()->back()->with('fail', __('message.Action failed'));
    }


    function submitProfileEdit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
//            'user_name' => 'required|unique:merchants,user_name,' . $this->_merchant_id,
            'email' => 'required|email|unique:merchants,email,' . $this->_merchant_id,
            'country_id' => 'required|not_in:0',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required|numeric',
            'dob_date' => 'required|before:' . Carbon::parse('-17 years 364 days')->format('Y-m-d'),
//            'dob_date' => 'required',
            'marital_status' => 'required',
            'identification_type' => 'required',
            'identification_number' => 'required',
//            'joining_date' => 'required',
            'business_name' => 'required'
        ]);

        $input = [
            'name' => $request->name,
            'surname' => $request->surname,
//            'user_name' => $request->user_name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'contact_number' => $request->contact_number,
            'dob' => Carbon::parse($request->dob_date)->format('Y-m-d'),
            'marital_status' => $request->marital_status,
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
//            'joining_date' => Carbon::parse($request->joining_date)->format('Y-m-d'),
        ];
        $mer = Merchant::find($this->_merchant_id);

        if ($mer->update($input)) {

            $business_input = [
                'name' => $request->business_name,
                'country_id' => $request->country_id,
                'address' => $request->address,
                'city' => $request->city,
                'contact_number' => $request->contact_number,
                'registration_number' => $request->registration_number ?? null,
                'pan' => $request->pan,
                'vat' => $request->vat,
            ];

            MerchantBusiness::where('merchant_id', $this->_merchant_id)->first()->update($business_input);

            return redirect()->back()->with('success', __('message.Profile updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function submitPasswordEdit(Request $request)
    {
        session()->flash('edit-profile-merchant', 'pass');
        $mer = Merchant::find($this->_merchant_id);
        if (!(Hash::check($request->old_password, $mer->password))) {
            // The passwords does not matches
            return redirect()->back()->with('old_password', __('message.Invalid previous password'));
        }

        if (strcmp($request->new_password, $request->old_password) == 0) {
            //Current password and new password are same
            return redirect()->back()->with("new_password", __('message.New Password cannot be same as your current password'));
        }
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|same:retype_password|min:6',
            'retype_password' => 'required|min:6',
        ]);

        if ($mer->update(['password' => bcrypt($request->new_password)]))
            return redirect()->back()->with("success", __('message.Password changed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function submitTrannsactionPasswordEdit(Request $request)
    {
        session()->flash('edit-profile-merchant', 'pass');
        $mer = Merchant::find($this->_merchant_id);
        if (!(Hash::check($request->old_transaction_password, $mer->transaction_password))) {
            // The transaction_passwords does not matches
            return redirect()->back()->with('old_transaction_password', __('message.Invalid previous transaction password'));
        }

        if (strcmp($request->new_transaction_password, $request->old_transaction_password) == 0) {
            //Current transaction_password and new transaction_password are same
            return redirect()->back()->with("new_transaction_password", __('message.New Password cannot be same as your current password'));
        }
        $request->validate([
            'old_transaction_password' => 'required',
            'new_transaction_password' => 'required|same:retype_transaction_password',
            'retype_transaction_password' => 'required',
        ]);

        if ($mer->update(['transaction_password' => bcrypt($request->new_transaction_password)]))
            return redirect()->back()->with("success", __('message.Transaction password changed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function updateImage(Request $request)
    {

        session()->flash('edit-profile-merchant', 'image');
        $validated = $request->validate([
            'logo' => 'required',
        ]);
        $merchant = Merchant::find($this->_merchant_id);
        if (!$merchant)
            return redirect()->back();
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $validated['logo'] = md5(time() . $logo->getClientOriginalName()) . '.' . $logo->getClientOriginalExtension();

            $destinationPath = public_path('image/merchantlogo');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($logo->getRealPath());
            $img->save($destinationPath . '/' . $validated['logo']);
            $old_img = public_path('image/merchantlogo/' . $merchant->logo);
            if (File::exists($old_img)) {
                File::delete($old_img);
            }

            $merchant->update(['logo' => $validated['logo']]);
            return redirect()->back()->with('success', __('message.Image updated successfully'));
        }

        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }
    function updateSignatureImage(Request $request)
    {

        session()->flash('edit-profile-merchant', 'image');
        $validated = $request->validate([
            'signature' => 'required',
        ]);
        $merchant = Merchant::find($this->_merchant_id);
        if (!$merchant)
            return redirect()->back();
        if ($request->hasFile('signature')) {
            $signature = $request->file('signature');
            $validated['signature'] = md5(time() . $signature->getClientOriginalName()) . '.' . $signature->getClientOriginalExtension();

            $destinationPath = public_path('image/merchant_signature');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($signature->getRealPath());
            $img->save($destinationPath . '/' . $validated['signature']);
            $old_img = public_path('image/merchant_signature/' . $merchant->signature);
            if (File::exists($old_img)) {
                File::delete($old_img);
            }

            $merchant->update(['signature' => $validated['signature']]);
            return redirect()->back()->with('success', __('message.Image updated successfully'));
        }

        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function merchantDoc(Request $request)
    {
        session()->flash('edit-profile-merchant', 'documents');
        $validated = $request->validate([
            'file' => 'required',
        ]);
        $merchant = Merchant::find($this->_merchant_id);
        if (!$merchant)
            return redirect()->back();
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = md5(time() . $file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();

            $destinationPath = public_path('image/merchant_documents');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $mime = $file->getMimeType();
            $file->move($destinationPath, $file_name);
            $create = MerchantDocument::create([
                'merchant_id' => $this->_merchant_id,
                'name' => $request->name,
                'file' => $file_name,
                'mime' => $mime,
            ]);
            if ($create)
                return redirect()->back()->with('success', 'File uploaded successfully');
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function deleteDoc($id)
    {
        session()->flash('edit-profile-merchant', 'documents');
        $docs = MerchantDocument::find($id);
        $docs_path = public_path('image/merchant_documents/' . $docs->file ?? null);
        if (File::exists($docs_path)) {
            File::delete($docs_path);
        }
        $docs->delete();
        return redirect()->back();
    }
}
