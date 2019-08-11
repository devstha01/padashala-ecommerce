<?php

namespace App\Http\Controllers\backend\Admin\Aboutus;

use App\Models\Country;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\About;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AboutController extends Controller
{
    private $_path = 'backend.admin.aboutus.';
    private $_data = [];

    public function __construct()
    {
        $this->middleware('admin');

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function index()
    {
        $this->_data['about'] = About::first();
        if ($this->_data['about'] === null)
            $this->_data['url'] = route('admin-save-about-content');
        else
            $this->_data['url'] = route('admin-update-about-content', $this->_data['about']->id);

        return view('backend.admin.aboutus.edit', $this->_data);

//        $this->_data['abouts'] = About::all();
//        return view('backend.admin.aboutus.index', $this->_data);
    }

    public function createContent()
    {
        $this->_data['abouts'] = About::all();
        return view('backend.admin.aboutus.create', $this->_data);
    }

    public function saveContent(Request $request)
    {


        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',

        ]);

        $validated['slug'] = str_slug($request->name) . '-' . strtotime(Carbon::now());
        $validated['title'] = $request->title;
        $validated['description'] = $request->description;


        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validated['image'] = time() . '.' . $image->getClientOriginalName();
            $destinationPath = public_path('image/about');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($image->getRealPath());
            $img->resize(385, 264, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $validated['image']);
        }


        if (About::create($validated))
            return redirect()->to(route('admin-about'))->with('success', __('message.Content added successfully'));
        return redirect()->back()->with('fail', __('message.Failed to add contents'));
    }

    public function editContent($id)
    {
        $this->_data['about'] = About::find($id);
        return view('backend.admin.aboutus.edit', $this->_data);
    }

    public function updateContent(Request $request, $id)
    {

        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);
        $validated['slug'] = str_slug($request->name) . '-' . strtotime(Carbon::now());
        $validated['description'] = $request->description;
        $validated['title'] = $request->title;


        $about = About::find($id);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $validated['image'] = md5(time() . $image->getClientOriginalName()) . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('image/about');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $img = Image::make($image->getRealPath());
            $img->resize(385, 264, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $validated['image']);
            $old_img = public_path('image/about/' . $about->image);
            if (File::exists($old_img)) {
                File::delete($old_img);
            }
        }

        if ($about->update($validated)) {
            return redirect()->to(route('admin-about'))->with('success', __('message.Content updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Failed to update content'));
    }

    function destroy($id)
    {
        if (About::find($id)->delete())
            return redirect()->back()->with('success', __('message.Content removed successfully'));
        return redirect()->back()->with('fail', __('message.Something went wrong'));
    }

    function subscribe()
    {
        $this->_data['lists'] = Subscriber::all();
        return view('backend.admin.aboutus.subscribe-list', $this->_data);
    }

    function subscribeStatus($id)
    {
        if ($sub = Subscriber::find($id)) {
            $sub->update(['status' => ($sub->status ? 0 : 1)]);
            return redirect()->back()->with('success', __('message.Status updated successfully'));
        }
        return redirect()->back()->with('fail', __('message.Something went wrong'));

    }

    function customerList()
    {
        $this->_data['users'] = User::where('is_member', 0)->get();
        return view('backend.admin.aboutus.customer-list', $this->_data);
    }

    function customerDetail($id)
    {
        $this->_data['user'] = User::find($id);
        return view('backend.admin.aboutus.profile', $this->_data);
    }

    function editCustomer($id)
    {
        $this->_data['user'] = User::find($id);
        $this->_data['countries'] = Country::all()->pluck('name', 'id');
        $this->_data['identificationType'] = array(
            'citizenship' => 'Citizenship',
            'passport' => 'Passport',
        );

        return view('backend.admin.aboutus.edit-profile', $this->_data);

    }

    function updateProfileCustomer($id, Request $request)
    {
        $valid = $request->validate([
            'name' => 'required',
            'surname' => 'required',
//            'user_name' => 'required|unique:users,user_name',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'country' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'contact_number' => 'required|numeric',
        ]);
        $valid['country_id'] = $request->country;
        $valid['dob_date'] = $request->dob;
        if (User::find($id)->update($valid))
            return redirect()->back()->with('success', 'Profile updated!');
        return redirect()->back()->with('fail', 'Profile updated!');
    }

    function editMemberPassword($id)
    {
        $data['user'] = User::find($id);
        if (!$data['user'])
            return redirect()->back();
        return view('backend.admin.aboutus.password', $data);
    }

    function updateMemberPassword($id, Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|same:password_confirmation',
            'password_confirmation' => 'required'
        ]);

        $user = User::find($id);
        if ($user->update(['password' => bcrypt($request->password)]))
            return redirect()->back()->with('success', 'Password updated');
        return redirect()->back()->with('fail', 'Something went wrong');
    }
//
//    function updateMemberTransaction($id, Request $request)
//    {
//        $request->validate([
//            'transaction_password' => 'required|min:6|same:transaction_password_confirmation',
//            'transaction_password_confirmation' => 'required'
//        ]);
//
//        $user = User::find($id);
//        if ($user->update(['transaction_password' => bcrypt($request->transaction_password)]))
//            return redirect()->back()->with('success', 'Transaction Password updated');
//        return redirect()->back()->with('fail', 'Something went wrong');
//    }

    function customerChangeStatus($id)
    {
        $user = User::find($id);
        if ($user->update(['status' => $user->status ? 0 : 1]))
            return redirect()->back()->with('success', 'Status changed successfully');
    }

}
