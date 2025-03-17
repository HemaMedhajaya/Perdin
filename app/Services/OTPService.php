<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;

class OTPService
{
    public static function generateOTP(User $user)
    {
        $otp = rand(100000, 999999); // OTP 6 digit
        $expiry = Carbon::now()->addMinutes(60); // OTP expired dalam 5 menit

        $user->update([
            'otp_code' => $otp,
            'otp_expiry' => $expiry
        ]);

        return $otp;
    }
}
