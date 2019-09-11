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


            auth()->login($user);
            return redirect()->to('/');
        } catch (Exception $e) {
            return redirect()->to(url('login'));
        }
    }
}
