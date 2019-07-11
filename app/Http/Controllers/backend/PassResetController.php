<?php

namespace App\Http\Controllers\backend;

use App\Http\Middleware\Admin;
use App\Mail\PassRecoveryEmail;
use App\Models\Merchant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PassResetController extends Controller
{

    private $_path = 'backend.auth.';
    private $_data = [];


    function passRecoveryForm($type)
    {
        switch (strtolower($type)) {
            case 'merchant':
                $this->_data['url'] = 'merchant';
                break;
            case 'admin':
                $this->_data['url'] = 'admin';
                break;
            default:
                return redirect()->back();
        }
        return view($this->_path . 'pass-recovery', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function passRecoveryPost($type, Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('fail', $validate->errors()->first());
        }

        switch (strtolower($type)) {
            case 'merchant':
                $url = url('b-reset-password/merchant');
                break;
            case 'admin':
                $url = url('b-reset-password/admin');
                break;
            default:
                return redirect()->back();
        }
        $user = Merchant::where('email', $request->email)->first();
        if (!$user)
            return redirect()->back()->with('fail', __('message.Invalid Email'));

        Mail::to($request->email)->send(new PassRecoveryEmail($user, $url));
        return redirect()->back()->with('info', __('message.Recovery request submitted successfully'));
    }

    function resetPasswordForm($type, $token = null)
    {
        if ($token == null) return redirect()->to(route('b-recovery', $type));
        $data = base64_decode($token);
        $arr = explode('&t=', $data);
        if (Carbon::parse($arr[1])->toDateString() < Carbon::now()->toDateString()) return redirect()->to(route('b-recovery', $type))->with('info', __('message.Reset password link expired'));
        $username = str_replace('u=', '', $arr[0]);
        $this->_data['url'] = $type;
        $this->_data['recover'] = $token;
        return view($this->_path . '.form-recovery', $this->_data)->with('title', __('message.Golden Gate'));
    }

    function resetPasswordPost($type, $token = null, Request $request)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'required|confirmed||min:6',
        ]);

        if ($validate->fails()) return redirect()->back()->with('fail', $validate->errors()->first());


        if ($token == null) return redirect()->to(route('b-recovery', $type));
        $data = base64_decode($token);
        $arr = explode('&t=', $data);
        if (Carbon::parse($arr[1])->toDateString() < Carbon::now()->toDateString()) return redirect()->to(route('b-recovery', $type))->with('info', __('message.Reset password link expired'));
        $username = str_replace('u=', '', $arr[0]);

        switch ($type) {
            case 'merchant':
                $user = Merchant::where('user_name', $username)->where('status', 1)->first();
                break;
            case'admin':
                $user = Admin::where('user_name', $username)->where('status', 1)->first();
                break;
            default:
                break;
        }

        if (!$user) return redirect()->to(route('b-recovery', $type))->with('info', __('message.Invalid reset password link'));
        $user->update(['password' => bcrypt($request->password)]);

        return redirect()->to(url($type . '/login'))->with(['title' => 'Golden Gate', 'success' => __('message.Password changed successfully')]);
    }
}
