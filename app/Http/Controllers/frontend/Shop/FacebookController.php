<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class FacebookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        try {
            $user = Socialite::driver('facebook')->user();
            $createName = $user->getName();
            $createFacebook_id = $user->getId();
            $createEmail = $user->getEmail() ?? $user->getId() . '@fb.com';

            $user = User::where('email', $createEmail)->first();
            if (!$user)
                $user = User::create([
                    'name' => $createName,
                    'surname' => $createName,
                    'user_name' => $createFacebook_id,
                    'email' => $createEmail,
                    'password' => bcrypt($createFacebook_id),
                    'country_id' => 1,
                    'provider'=>'facebook',
                    'provider_id'=>$createFacebook_id
                ]);


            if (Auth::attempt(['user_name' => $user->user_name, 'password' => $user->password])) {
                User::find(Auth::id())->update(['jwt_token_handle' => '']);
                $login =new LoginController();
                $login->mergeCartFromDB();
                $login->refreshCart();

                return redirect()->to(url('/'));
            }
            return redirect()->to(url('login'));
        } catch (Exception $e) {
            return redirect()->to(url('login'));
        }
    }
}
