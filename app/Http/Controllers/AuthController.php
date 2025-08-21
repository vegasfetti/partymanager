<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    // pages
    public function showSignin()
    {
        return view('pages.auth.signin');
    }

    public function showSignup()
    {
        return view('pages.auth.signup');
    }



    // actions
    public function actionSignup(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => [
                    'required',
                    'confirmed',
                    'min:8',
                    'regex:/^[A-Za-z\d!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]+$/', // Только латиница + цифры + спецсимволы
                    'regex:/[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]/'              // Хотя бы один спецсимвол
                ],
                'agreement' => 'required',
            ],
            [
                'password.regex' => 'Пароль должен содержать только латинские буквы И хотя бы один спецсимвол',
            ]
            // , [
            //     // Кастомные сообщения (по желанию)
            //     'name.required' => 'Обязательно для заполнения',
            //     'email.required' => 'Обязательно для заполнения',
            //     'password.required' => 'Обязательно для заполнения',
            //     'agreement.required' => 'Необходимо согласие',
            //     'email.email' => 'Введите корректный адрес электронной почты.',
            //     'password.confirmed' => 'Пароли не совпадают.',
            // ]
        );

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        try{
        $user->sendEmailVerificationNotification();
        }catch(\Exception $e){
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'При отправке письма произошла ошибка, попробуйте позже'],
            ]);
        }

        session()->flash('alerts', [
            ['type' => 'success', 'message' => 'Аккаунт успешно создан!'],
        ]);

        Auth::login($user);



        // email verification redirection
        return redirect()->route('verification.notice');

    }


    public function actionSignin(Request $request)
    {



        $request->validate(
            [
                'email' => 'required',
                'password' => 'required',
            ]
        );

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            session()->flash('alerts', [
                ['type' => 'success', 'message' => 'Добро пожаловать, ' . Auth::user()->name . '!'],
            ]);
            // Authentication passed...
            return redirect()->route('profile'); // Redirect to a profile or dashboard page
        }



        return back()->withErrors([
            'err' => 'Почта или пароль неверны.',
            'forgot' => 'forgot',
        ]);


        // $user = \App\Models\User::where('email', $credentials['email'])->first();

        // if ($user && Hash::check($credentials['password'], $user->password)) {
        //     Auth::login($user);
        //     return back()->withErrors([
        //         'err' => 'Почта или пароль неверны.',
        //     ]);
        // }


    }

    public function actionSignout()
    {
        session()->flash('alerts', [
            ['type' => 'success', 'message' => 'До свидания, ' . Auth::user()->name . '!'],
        ]);
        Auth::logout();

        return redirect()->route('signin');
    }




    // mails
}
