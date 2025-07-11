<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function sendOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    $user = User::where('email', $request->email)->first();

    $otp = rand(100000, 999999); // 6-digit OTP

    $user->otp = $otp;
    $user->otp_expires_at = Carbon::now()->addMinutes(10);
    $user->save();

    // Send email (use Mailtrap for testing)
    Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Password Reset OTP');
    });

    return response()->json([
        'status' => true,
        'message' => 'OTP sent to your email',
    ], 200);
}

public function verifyOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'otp' => 'required',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (
        $user->otp !== $request->otp ||
        Carbon::parse($user->otp_expires_at)->isPast()
    ) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid or expired OTP',
        ], 400);
    }

    $user->password = $request->password;
    $user->otp = null;
    $user->otp_expires_at = null;
    $user->save();

    return response()->json([
        'status' => true,
        'message' => 'Password has been reset successfully',
    ], 200);
}

}
