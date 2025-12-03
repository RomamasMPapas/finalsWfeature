<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the OAuth Provider.
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     */
    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->user();
        // Find or create a user
        $user = User::firstOrCreate(
            [
                $provider . '_id' => $socialUser->getId(),
            ],
            [
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? $socialUser->getEmail(),
                'email' => $socialUser->getEmail(),
                // password is not needed for social login; set a random one
                'password' => bcrypt(str_random(16)),
            ]
        );
        Auth::login($user);
        return redirect('/');
    }

    // Specific methods for Facebook
    public function redirectToFacebook()
    {
        return $this->redirect('facebook');
    }

    public function handleFacebookCallback()
    {
        return $this->callback('facebook');
    }

    // Specific methods for Google
    public function redirectToGoogle()
    {
        return $this->redirect('google');
    }

    public function handleGoogleCallback()
    {
        return $this->callback('google');
    }
}

?>
