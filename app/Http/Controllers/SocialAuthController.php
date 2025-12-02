<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Facebook OAuth
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * Handle Facebook callback
     */
    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            $user = $this->findOrCreateUser($facebookUser, 'facebook');
            
            Auth::login($user);
            
            return redirect('/')->with('success', 'Successfully logged in with Facebook!');
            
        } catch (Exception $e) {
            return redirect('/login')->with('failure', 'Unable to login with Facebook. Please try again.');
        }
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google callback
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = $this->findOrCreateUser($googleUser, 'google');
            
            Auth::login($user);
            
            return redirect('/')->with('success', 'Successfully logged in with Google!');
            
        } catch (Exception $e) {
            return redirect('/login')->with('failure', 'Unable to login with Google. Please try again.');
        }
    }

    /**
     * Find or create user from social provider
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        // Check if user exists by email
        $user = User::where('email', $socialUser->getEmail())->first();
        
        if ($user) {
            // User exists, just return
            return $user;
        }
        
        // Create new user
        $nameParts = explode(' ', $socialUser->getName(), 2);
        $firstName = $nameParts[0] ?? $socialUser->getName();
        $lastName = $nameParts[1] ?? '';
        
        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(24)), // Random password for social users
            'phone' => '', // Will need to be filled later
            'address' => '', // Will need to be filled later
        ]);
        
        return $user;
    }
}
