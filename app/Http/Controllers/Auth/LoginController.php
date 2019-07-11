<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('guest:admin')->except('adminLogout');
//        $this->middleware('guest:member')->except('memberLogout');
//        $this->middleware('guest:merchant')->except('merchantLogout');
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

    public function showMemberLoginForm()
    {
        return view('backend.auth.login', ['url' => 'member'])->with('title',__('message.Member Login'));
    }

    public function memberLogin(Request $request)
    {

        if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password,'is_member' =>1])) {
            return redirect('/member/dashboard');
            }
            return back()
                ->withInput($request->only('user_name'))
                ->withErrors([
                    'no-match-error' => __('message.Invalid Login name or Password')
                ]);

    }

    public function memberLogout(Request $request){

        Auth::guard('web')->logout();

//        $request->session()->flush();
//        $request->session()->regenerate();
        
        return redirect('login');
    }

    public function showMerchantLoginForm()
    {
        return view('backend.auth.login', ['url' => 'merchant'])->with('title',__('message.Merchant Login'));
    }

    public function merchantLogin(Request $request)
    {
        if (Auth::guard('merchant')->attempt(['user_name' => $request->user_name, 'password' => $request->password])) {

            return redirect('/merchant/dashboard');
        }
        return back()
            ->withInput($request->only('user_name'))
            ->withErrors([
                'no-match-error' => __('message.Invalid Login name or Password')
            ]);
    }

    public function merchantLogout(Request $request){

        Auth::guard('merchant')->logout();

//        $request->session()->flush();
//        $request->session()->regenerate();
        
        return redirect('merchant/login');
    }

    public function showCustomerLoginForm()
    {
        return view('frontend.auth.login', ['url' => 'customer'])->with('title',__('message.Customer Login'));
    }

    public function customerLogin(Request $request)
    {
        if (Auth::attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
            
            $checkCustomer = User::where('user_name',$request->user_name)->first();
           
            if($checkCustomer->is_member == 0){
                return redirect('/');
            }

            Auth::guard('web')::logout();

//            $request->session()->flush();
//            $request->session()->regenerate();

            return back()
                ->withInput($request->only('user_name'))
                ->withErrors([
                    'no-match-error' => __('message.Invalid Login name or Password')
                ]);

        }
        return back()
            ->withInput($request->only('user_name'))
            ->withErrors([
                'no-match-error' => __('message.Invalid Login name or Password')
            ]);
    }

    public function customerLogout(Request $request)
    {

        Auth::guard('web')::logout();

//        $request->session()->flush();
//        $request->session()->regenerate();
        
        return redirect('login');
    }
}
