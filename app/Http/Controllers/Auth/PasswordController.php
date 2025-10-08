<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'captcha' => 'required|captcha',
            ],
            [
                'captcha.captcha' => 'Kode captcha yang Anda masukkan salah.',
                'captcha.required' => 'Captcha harus diisi.',
            ],
        );
        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' => 'Email tidak ditemukan dalam sistem kami.',
                ])
                ->withInput();
        }
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT ? back()->with('status', __($status)) : back()->withErrors(['email' => __($status)]);
    }
    public function showResetForm($token, Request $request)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
            ],
            [
                'password.regex' => 'Password harus mengandung minimal satu huruf besar, satu huruf kecil, dan satu angka.',
                'password.min' => 'Password minimal 8 karakter.',
            ],
        );

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user
                ->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])
                ->save();
        });
        if ($status === Password::PASSWORD_RESET) {
            // Hapus token dari tabel password_resets
            DB::table('password_resets')->where('email', $request->email)->delete();

        }
        return $status === Password::PASSWORD_RESET ? redirect()->route('login')->with('status', __($status)) : back()->withErrors(['email' => [__($status)]]);
    }
}
