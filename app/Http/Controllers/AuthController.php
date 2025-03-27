<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\OTPService;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showlogin()
    {
        return view('auth.login');
    }

    public function showotp()
    {
        return view('auth.otp');
    }

    public function loginpost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)
            ->where('status_user', 1)
            ->first();

        if ($user) {
            Auth::login($user); 

            // Generate OTP
            $otp = OTPService::generateOTP(user: $user);

            // Kirim OTP ke email
            try {
                Mail::to($user->email)->send(new OtpMail($otp));

                // simpan session role user
                session()->put('role', $user->role);

                return redirect()->route('show.otp')->with('success', 'Kode OTP telah dikirim ke email Anda.');
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal mengirim email. Pastikan Anda terhubung ke internet dan coba lagi.');
            }
        }

        return back()->with(['error' => 'Email tidak terdaftar.']);
    }

        public function verifyOTP(Request $request)
        {
            $user = Auth::user();
            $otp = $request->otp;

            if ($user->otp_code === $otp && Carbon::now()->lt($user->otp_expiry)) {
                $token = JWTAuth::fromUser($user); 
                if($token){
                    $user->update(['last_login' => Carbon::now()]);

                    session()->put('email', $user->email);
                    session()->put('user_id', $user->id);
                    $role = session()->get('role',3);
                    
                    
                    if ($role === User::ROLE_ADMIN || $role == User::ROLE_ADMIN_IT){
                        return redirect()->route('dashboard')->with([
                            'token' => $token,
                            'berhasil' => 'Berhasil Login'
                        ]);
                    } elseif ($role === User::ROLE_ADMIN_APPROVER) {
                        return redirect()->route('dashboardapp')->with([
                            'token' => $token,
                            'berhasil' => 'Berhasil Login'
                        ]);
                    } else {
                        // dd($role);
                        return redirect()->route('index.dashboard')->with([
                            'token' => $token,
                            'berhasil' => 'Berhasil Login'
                        ]);
                    }
                }
            }

            return back()->withErrors(['otp' => 'Kode OTP salah atau sudah expired.']);
        }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('Berhasil logout!');
    }
}
