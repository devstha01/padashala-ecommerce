<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('adminLogout');

    }

    public function showAdminLoginForm()
    {
        return view('backend.auth.login', ['url' => 'admin'])->with('title',__('message.Admin Login'));
    }

    public function adminLogin(Request $request)
    {
        if (Auth::guard('admin')->attempt(['user_name' => $request->user_name, 'password' => $request->password])) {

            return redirect('/admin/dashboard');
        }
        return back()
            ->withInput($request->only('user_name'))
            ->withErrors([
                'no-match-error' => __('message.Invalid Login name or Password')
            ]);
    }

    public function adminLogout(Request $request){

        Auth::guard('admin')->logout();

//        $request->session()->flush();
//        $request->session()->regenerate();

        return redirect('admin/login');
    }

}
