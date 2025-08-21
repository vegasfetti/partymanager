@extends('layouts.app')

@section('title', 'Регистрация | ПАТИМЕНЕДЖЕР')


@section('content')

    @include('particals.header')



    <main class="container min-h-[90vh] relative px-4 sm:px-6 lg:px-8">

        <!-- circles -->
        <div
            class="circle pointer-events-none w-[600px] sm:w-[800px] lg:w-[1000px] h-[600px] sm:h-[800px] lg:h-[1000px] absolute bg-[#2C3E50] rounded-full right-0 top-[10%] translate-x-3/4 -translate-y-1/2 opacity-5 z-10">
        </div>

        <div
            class="circle pointer-events-none w-[600px] sm:w-[800px] lg:w-[1000px] h-[600px] sm:h-[800px] lg:h-[1000px] absolute bg-[#D4AF37] rounded-full left-0 top-[90%] -translate-x-3/4 -translate-y-1/2 opacity-7.5 z-10">
        </div>

        <!-- registration form -->
        <form method="post" action="{{ route('action.signup') }}"
            class="form bg-[#f0f4fa] border border-[#0000001a] rounded-4xl p-6 sm:p-8 lg:p-10 absolute z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[95%] sm:max-w-[400px] lg:max-w-[480px]">
            @csrf
            <h1 class="font-semibold text-3xl sm:text-4xl">Регистрация</h1>
            <p class="font-light text-base sm:text-lg opacity-50 pt-2.5 pb-6 sm:pb-7.5">Добро пожаловать</p>

            <div class="inputs flex flex-col gap-2.5">
                <input value="{{ old('name') }}" name="name" type="text" placeholder="Имя или компания"
                    class="auth_input px-4 sm:px-5 py-3 sm:py-3.5 outline-0 bg-[#F5F9FF] border border-[#0000001a] rounded-xl text-base sm:text-sm">
                @error('name')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <input value="{{ old('email') }}" name="email" type="text" placeholder="Почта"
                    class="auth_input px-4 sm:px-5 py-3 sm:py-3.5 outline-0 bg-[#F5F9FF] border border-[#0000001a] rounded-xl text-base sm:text-sm">
                @error('email')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <div
                    class="password_input auth_input px-4 sm:px-5 py-3 sm:py-3.5 outline-0 bg-[#F5F9FF] border border-[#0000001a] rounded-xl relative">
                    <input name="password" id="password1" type="password" placeholder="Пароль"
                        class="w-[85%] sm:w-[90%] outline-0 bg-transparent text-base sm:text-sm">
                    <img id="show_password1"
                        class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                        src="{{ asset('ico/eye.png') }}" alt="Показать пароль">
                    <img style="display: none;" id="hide_password1"
                        class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                        src="{{ asset('ico/eye-closed.png') }}" alt="Скрыть пароль">
                </div>
                @error('password')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <div
                    class="password_input auth_input px-4 sm:px-5 py-3 sm:py-3.5 outline-0 bg-[#F5F9FF] border border-[#0000001a] rounded-xl relative">
                    <input name="password_confirmation" id="password2" type="password" placeholder="Повторите пароль"
                        class="w-[85%] sm:w-[90%] outline-0 bg-transparent text-base sm:text-sm">
                    <img id="show_password2"
                        class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                        src="{{ asset('ico/eye.png') }}" alt="Показать пароль">
                    <img style="display: none;" id="hide_password2"
                        class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                        src="{{ asset('ico/eye-closed.png') }}" alt="Скрыть пароль">
                </div>

                <div class="flex items-start gap-2 mt-4">
                    <input type="checkbox" name="agreement" class="accent-[#FFD700] w-5 h-5">
                    <p class="text-gray-500 text-xs">Продолжая регистрацию, я соглашаюсь с <a href="{{ route('privacy') }}"
                            class="text-[#FFD700] underline">политикой конфиденциальности</a></p>
                </div>
                @error('agreement')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <button
                    class="waiting bg-[#FFD700] text-[#1C1F4C] mt-5 px-3.5 sm:px-4 py-2.5 sm:py-3 font-semibold border border-[#0000001a] rounded-xl cursor-pointer w-full sm:w-auto text-base ">
                    Зарегистрироваться
                </button>

                <p class="text-sm sm:text-base text-gray-500 text-center pt-5">
                    Уже есть аккаунт? <a class="text-[#FFD700] underline" href="{{ route('signin') }}">Войти</a>
                </p>
            </div>
        </form>
    </main>


    @include('particals.footer')

@endsection