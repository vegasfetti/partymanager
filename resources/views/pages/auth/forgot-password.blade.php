@extends('layouts.app')

@section('title', 'Сброс пароля | ПАТИМЕНЕДЖЕР')


@section('content')

    @include('particals.header')



    <main class="container min-h-screen relative px-4 sm:px-6 lg:px-8">

        <!-- circles -->
        <div
            class="circle pointer-events-none w-[600px] sm:w-[800px] lg:w-[1000px] h-[600px] sm:h-[800px] lg:h-[1000px] absolute bg-[#2C3E50] rounded-full right-0 top-[10%] translate-x-3/4 -translate-y-1/2 opacity-5 z-10">
        </div>

        <div
            class="circle pointer-events-none w-[600px] sm:w-[800px] lg:w-[1000px] h-[600px] sm:h-[800px] lg:h-[1000px] absolute bg-[#D4AF37] rounded-full left-0 top-[90%] -translate-x-3/4 -translate-y-1/2 opacity-7.5 z-10">
        </div>

        @if (session('success'))
            <p class="text-green-600 text-center mb-4">{{ session('success') }}</p>
        @endif

        <!-- forgot password form -->
        <form method="post" action="{{ route('password.email') }}"
            class="form forgot-form bg-[#f0f4fa] border border-[#0000001a] rounded-4xl p-6 sm:p-8 lg:p-10 absolute z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[95%] sm:max-w-[400px] lg:max-w-[480px]">
            @csrf
            <h1 class="font-semibold text-3xl sm:text-4xl">Сброс пароля</h1>
            <p class="font-light text-base sm:text-lg opacity-50 pt-2.5 pb-6 sm:pb-7.5">Получите ссылку для сброса пароля
            </p>

            <div class="inputs flex flex-col gap-2.5">
                <input type="email" name="email" id="email" placeholder="Почта"
                    class="auth_input px-4 sm:px-5 py-3 sm:py-3.5 outline-0 bg-[#F5F9FF] border border-[#0000001a] rounded-xl text-base sm:text-sm"
                    value="{{ old('email') }}">

                @if ($errors->any())
                    <div class="text-red-600 text-sm">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <button
                    class="waiting bg-[#FFD700] text-[#1C1F4C] mt-5 px-3.5 sm:px-4 py-2.5 sm:py-3 font-semibold border border-[#0000001a] rounded-xl cursor-pointer w-full sm:w-auto text-base sm:text-sm">
                    Отправить
                </button>

                <p class="text-sm sm:text-base text-gray-500 text-center pt-5">
                    Вернуться к входу? <a class="text-[#FFD700] underline" href="{{ route('signin') }}">Войти</a>
                </p>
            </div>
        </form>

    </main>


    @include('particals.footer')

@endsection