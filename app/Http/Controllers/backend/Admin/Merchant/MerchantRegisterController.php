<?php

namespace App\Http\Controllers\backend\Admin\Merchant;

use App\Mail\VerifyEmail;
use App\Mail\WelcomeEmail;
use App\Models\Country;
use App\Models\Merchant;
use App\Models\MerchantAsset;
use App\Models\MerchantDocument;
use App\Models\Product;
use App\Models\Category;
use App\Models\MerchantBusiness;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MerchantRegisterController extends Controller
{
    private $_path = 'backend.admin.merchant-master.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    function showMerchantRegisterForm()
    {
        $this->_data['countries'] = Country::all();
        return view($this->_path . 'register', $this->_data);
    }

    function postMerchantRegisterForm(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
            'user_name' => 'required|unique:merchants,user_name',
            'email' => 'required|email|unique:merchants,email',
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
//            'joining_date' => 'required',
            'business_name' => 'required',
//            'merchant_share' => 'required|numeric|min:0|max:98',
//            'admin_share' => 'required|numeric|min:2|max:100',
//            'registration_number' => 'required'
        ]);

        $input = [
            'name' => $request->name,
            'surname' => $request->surname,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'country_id' => $request->country_id,
            'gender' => $request->gender,
            'address' => $request->address,
            'city' => $request->city,
            'contact_number' => $request->contact_number,
            'dob' => Carbon::parse($request->dob_date)->format('Y-m-d'),
            'marital_status' => $request->marital_status,
            'password' => bcrypt($request->new_password),
//            'transaction_password' => bcrypt($request->transaction_password),
            'identification_type' => $request->identification_type,
            'identification_number' => $request->identification_number,
            'joining_date' => Carbon::now()->format('Y-m-d'),
            'status' => 0,
        ];
        if ($id = Merchant::create($input)->id) {

            $uniq_slug = false;
            $i = 1;
            $slug = str_slug($request->business_name);
            do {
                $check = MerchantBusiness::where('slug', $slug)->first();
                if (!$check)
                    $uniq_slug = true;
                else
                    $slug = str_slug($request->business_name) . '-' . $i;
                $i++;
            } while ($uniq_slug !== true);


            $business_input = [
                'merchant_id' => $id,
                'name' => $request->business_name,
                'slug' => $slug,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'address' => $request->address,
                'contact_number' => $request->contact_number,
                'registration_number' => $request->registration_number ?? null,
                'pan' => $request->pan,
                'vat' => $request->vat,
            ];

            MerchantBusiness::create($business_input);
            MerchantAsset::create(['merchant_id' => $id, 'ecash_wallet' => 0]);

            $mkString = 'merchant:' . $id;
            $data = QrCode::format('png')->size(500)->generate($mkString);

            $destination = public_path('image/qr_image/merchant/');
            if (!File::exists($destination))
                File::makeDirectory($destination);
            $qr_name = str_random(10) . '.png';
            $path = $destination . $qr_name;

            File::put($path, $data);

            $merchant_create = Merchant::find($id);
            $merchant_create->update(['qr_image' => $qr_name]);
//            ShoppingMerchant::create(['merchant_id' => $id, 'merchant_rate' => $request->merchant_share, 'admin_rate' => $request->admin_share]);

            $url = url('verify-email/merchant');
            Mail::to($request->email)->send(new VerifyEmail($merchant_create, $url));
//            Mail::to($request->email)->send(new WelcomeEmail('merchant', $merchant_create->name . ' ' . $merchant_create->surname));
            return redirect()->to(route('merchant-list-admin'))->with('success', __('message.New merchant created successfully'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function editMerchant($id)
    {
        $this->_data['merchant'] = Merchant::find($id);
        $this->_data['countries'] = Country::all();
        return view($this->_path . 'edit-profile', $this->_data);
    }

//    function viewMerchant($id)
//    {
//        if (empty($id)) return redirect()->to(route('merchant-list-admin'));
//        $this->_data['merchant'] = Merchant::find($id);
//         $this->_data['products'] = Product::find($id);
//        return view($this->_path . 'profile', $this->_data);
//    }

    function changeStatus($id)
    {
        if (empty($id)) return redirect()->back();
        $mer = Merchant::find($id);

        if ($mer->status === 1) {
            if ($mer->update(['status' => 0])) {
                foreach ($mer->getBusiness->getProducts as $item) {
                    $item->update(['status' => 0]);
                }
                return redirect()->back()->with('success', __('message.Status of :user disabled', ['user' => $mer->user_name]));
            }
        } else {
            if ($mer->update(['status' => 1])) {
                return redirect()->back()->with('success', __('message.Status of :user enabled', ['user' => $mer->user_name]));
            }
        }
        return redirect()->back()->with('fail', __('message.Failed to change status'));
    }


    function submitProfileEdit(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required',
            'surname' => 'required',
//            'user_name' => 'required|unique:merchants,user_name,' . $id,
            'email' => 'required|email|unique:merchants,email,' . $id,
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
            'business_name' => 'required',
//            'merchant_share' => 'required|numeric|min:0|max:98',
//            'admin_share' => 'required|numeric|min:2|max:100',
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
            'logo' => 'required',
            'banner' => 'required',
        ];
        $mer = Merchant::find($id);
        $merchant_business = MerchantBusiness::where('merchant_id', $id)->first();
        if (!$mer)
            return redirect()->back()->with('fail', __('message.Something went wrong'));

        if (!$merchant_business) {

            $uniq_slug = false;
            $i = 1;
            $slug = str_slug($request->business_name);
            do {
                $check = MerchantBusiness::where('slug', $slug)->first();
                if (!$check)
                    $uniq_slug = true;
                else
                    $slug = str_slug($request->business_name) . '-' . $i;
                $i++;
            } while ($uniq_slug !== true);

            $business_input = [
                'merchant_id' => $id,
                'name' => $request->business_name,
                'slug' => $slug,
                'country_id' => $request->country_id,
                'city' => $request->city,
                'address' => $request->address,
                'contact_number' => $request->contact_number,
                'registration_number' => $request->registration_number ?? null,
                'pan' => $request->pan,
                'vat' => $request->vat,
            ];
            MerchantBusiness::create($business_input);
//            $shopping = ShoppingMerchant::where('merchant_id', $id)->first();
//            $shopping->update(['merchant_rate' => $request->merchant_share, 'admin_rate' => $request->admin_share]);
            return redirect()->back()->with('success', __('message.Merchant profile updated successfully'));

        } else {
            if ($mer->update($input)) {
                $business_input = [
                    'name' => $request->business_name,
                    'country_id' => $request->country_id,
                    'city' => $request->city,
                    'address' => $request->address,
                    'contact_number' => $request->contact_number,
                    'registration_number' => $request->registration_number ?? null,
                    'pan' => $request->pan,
                    'vat' => $request->vat,
                ];
                $merchant_business->update($business_input);

//                $shopping = ShoppingMerchant::where('merchant_id', $id)->first();
//                $shopping->update(['merchant_rate' => $request->merchant_share, 'admin_rate' => $request->admin_share]);
                return redirect()->back()->with('success', __('message.Merchant profile updated successfully'));
            }
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function submitPasswordEdit(Request $request, $id)
    {
        session()->flash('edit-profile-merchant', 'pass');
        $mer = Merchant::find($id);
//        if (!(Hash::check($request->old_password, $mer->password))) {
//            // The passwords does not matches
//            return redirect()->back()->with('old_password', __('message.Invalid previous password'));
//        }
//
//        if (strcmp($request->new_password, $request->old_password) == 0) {
//            //Current password and new password are same
//            return redirect()->back()->with("new_password", __('message.New Password cannot be same as your current password'));
//        }
        $request->validate([
//            'old_password' => 'required',
            'new_password' => 'required|same:retype_password|min:6',
            'retype_password' => 'required|min:6',
        ]);

        if ($mer->update(['password' => bcrypt($request->new_password)]))
            return redirect()->back()->with("success", __('message.Password changed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function submitTrannsactionPasswordEdit(Request $request, $id)
    {
        session()->flash('edit-profile-merchant', 'pass');
        $mer = Merchant::find($id);
//        if (!(Hash::check($request->old_transaction_password, $mer->transaction_password))) {
//            // The transaction_passwords does not matches
//            return redirect()->back()->with('old_transaction_password', __('message.Invalid previous transaction password'));
//        }
//
//        if (strcmp($request->new_transaction_password, $request->old_transaction_password) == 0) {
//            //Current transaction_password and new transaction_password are same
//            return redirect()->back()->with("new_transaction_password", __('message.New Password cannot be same as your current password'));
//        }
        $request->validate([
//            'old_transaction_password' => 'required',
            'new_transaction_password' => 'required|same:retype_transaction_password|min:6',
            'retype_transaction_password' => 'required|min:6',
        ]);

        if ($mer->update(['transaction_password' => bcrypt($request->new_transaction_password)]))
            return redirect()->back()->with("success", __('message.Transaction password changed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function searchMerchant(Request $request)
    {
        $data['merchants'] = Merchant::where('name', 'like', '%' . $request->term . '%')->orWhere('surname', 'like', '%' . $request->term . '%')->get() ?? [];
        return response()->json($data);
    }

    public function uploadImage($id, Request $request)
    {
        session()->flash('edit-profile-merchant', 'image');

        $validated = $request->validate([
            'logo' => 'required',
        ]);
        $merchant = Merchant::find($id);
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

    public function uploadSignatureImage($id, Request $request)
    {
        session()->flash('edit-profile-merchant', 'image');

        $validated = $request->validate([
            'signature' => 'required',
        ]);
        $merchant = Merchant::find($id);
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

    function merchantDoc($id, Request $request)
    {
        session()->flash('edit-profile-merchant', 'documents');
        $validated = $request->validate([
            'file' => 'required',
        ]);
        $merchant = Merchant::find($id);
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
                'merchant_id' => $id,
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



