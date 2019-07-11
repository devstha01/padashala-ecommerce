<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Merchant;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class MerchantLoginController extends Controller
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
    protected $redirectTo = '/merchant/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:merchant')->except('merchantLogout');

    }

    public function showMerchantLoginForm()
    {
        return view('backend.auth.login', ['url' => 'merchant'])->with('title', __('message.Merchant Login'));
    }

    public function merchantLogin(Request $request)
    {
        $check = Merchant::where('user_name', $request->user_name)->first();
        if ($check) {
            if (!$check->status) {
                return back()
                    ->withInput($request->only('user_name'))
                    ->withErrors([
                        'no-match-error' => __('Email verification required')
                    ]);
            }
        }

        if (Auth::guard('merchant')->attempt(['user_name' => $request->user_name, 'password' => $request->password])) {

            return redirect('/merchant/dashboard');
        }
        return back()
            ->withInput($request->only('user_name'))
            ->withErrors([
                'no-match-error' => __('message.Invalid Login name or Password')
            ]);
    }

    public function merchantLogout(Request $request)
    {

        Auth::guard('merchant')->logout();

//        $request->session()->flush();
//        $request->session()->regenerate();

        return redirect('merchant/login');
    }

}
