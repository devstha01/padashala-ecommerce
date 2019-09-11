<?php

namespace App\Http\Controllers\frontend\Shop;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect('/login');
        }

        $authUser = $this->findOrCreateUser($user, $provider);
        Auth::login($authUser, true);
        return redirect('/');
    }


    public function findOrCreateUser($providerUser, $provider)
    {
        $user = User::where('provider',$provider)
            ->where('provider_id',$providerUser->getId())
            ->first();

        if ($user) {
            return $user;
        } else {
            $user = User::whereEmail($providerUser->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name'  => $providerUser->getName(),
                    'provider_id'   => $providerUser->getId(),
                    'provider_name' => $provider,
                    'country_id' => 1,
                ]);
            }
            return $user;
        }
    }

//    public function handleFacebookCallback()
//    {
//        try {
//            $user = Socialite::driver('facebook')->user();
//            $createName = $user->getName();
//            $createFacebook_id = $user->getId();
//            $createEmail = $user->getEmail() ?? $user->getId() . '@fb.com';
//            $user = User::where('email', $createEmail)->first();
//            if (!$user){
//                $user = User::create([
//                    'name' => $createName,
//                    'surname' => $createName,
//                    'user_name' => $createFacebook_id,
//                    'email' => $createEmail,
//                    'country_id' => 1,
//                    'provider'=>'facebook',
//                    'provider_id'=>$createFacebook_id
//                ]);
//            }
//            Auth::login($user, true);
//            return redirect()->to('/');
//        } catch (Exception $e) {
//            return redirect()->to(url('login'));
//        }
//    }
}
