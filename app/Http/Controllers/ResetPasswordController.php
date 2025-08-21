<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        return view('pages.auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^[A-Za-z\d!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]+$/', // Только латиница + цифры + спецсимволы
                    'regex:/[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]/'              // Хотя бы один спецсимвол
                ],
            ],
            [
                'password.regex' => 'Пароль должен содержать только латинские буквы И хотя бы один спецсимвол',
            ]
        );

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->password = Hash::make($request->password);
                $user->save();
                Auth::login($user);
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('profile')->with('alerts', [
                ['type' => 'success', 'message' => 'Пароль успешно изменен!'],
            ])
            : back()->withErrors(['email' => [__($status)]]);
    }
}
