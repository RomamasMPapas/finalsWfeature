<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class OtpController extends Controller
{
    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        
        // Generate 5-digit OTP
        $otp = str_pad(random_int(0, 99999), 5, '0', STR_PAD_LEFT);
        
        // Store OTP in cache for 10 minutes
        Cache::put('otp_' . $email, $otp, now()->addMinutes(10));
        
        // Send email
        try {
            Mail::raw("Your OTP code is: $otp\n\nThis code expires in 10 minutes.", function ($message) use ($email) {
                $message->to($email)
                        ->subject('Your Payment Verification OTP');
            });
            
            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:5'
        ]);

        $email = $request->email;
        $otp = $request->otp;
        
        // Get stored OTP from cache
        $storedOtp = Cache::get('otp_' . $email);
        
        if (!$storedOtp) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired or not found. Please request a new one.'
            ], 400);
        }
        
        if ($storedOtp === $otp) {
            // Clear the OTP after successful verification
            Cache::forget('otp_' . $email);
            
            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP'
        ], 400);
    }
}
