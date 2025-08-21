<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotForm()
    {
        return view('pages.auth.forgot-password'); // своя форма
    }

    public function sendResetLink(Request $request)
    {

        $request->validate(['email' => 'required|email']);
        
        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );


            return $status === Password::RESET_LINK_SENT
                ? back()->with('alerts', [
                    ['type' => 'success', 'message' => 'Отправили письмо на почту']
                ])
                : back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'При отправке письма произошла ошибка']
            ]);
        }
    }
}

