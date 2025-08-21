@extends('layouts.app')

@section('title', 'Подтвердите почту | ПАТИМЕНЕДЖЕР')


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

        <!-- email verification form -->
        <div
            class="form bg-[#f0f4fa] border border-[#0000001a] rounded-4xl p-6 sm:p-8 lg:p-10 absolute z-50 top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[95%] sm:max-w-[400px] lg:max-w-[480px]">
            <h1 class="font-semibold text-3xl sm:text-4xl">Подтвердите почту</h1>


            <p class="font-light text-base sm:text-lg opacity-50 pt-2.5 pb-6 sm:pb-7.5">
                Пожалуйста, подтвердите вашу почту, перейдя по ссылке в письме
            </p>

            <div class="inputs flex flex-col gap-2.5 w-full">

                @if (session('message'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('message') }}
                    </div>
                @endif

                <p class="pt-2.5 opacity-50">Почта: {{Auth::user()->email}}</p>
                <div class="flex flex-col gap-2.5 mt-5 sm:flex-row">
                    <form class="w-auto" method="post" action="{{ route('verification.send') }}">
                        @csrf
                        <button
                            class="waiting bg-[#FFD700] text-[#1C1F4C] px-3.5 sm:px-4 py-2.5 sm:py-3 font-semibold border border-[#0000001a] rounded-xl cursor-pointer w-full sm:w-auto text-base ">
                            Отправить повторно
                        </button>
                    </form>
                    <form action="{{route('signout')}}">
                        <button
                            class="bg-[#f0f4fa] text-[#1C1F4C] px-3.5 sm:px-4 py-2.5 sm:py-3 font-semibold border border-[#0000001a] rounded-xl cursor-pointer w-full sm:w-auto text-base">Выйти</button>
                    </form>
    
                </div>

            </div>
        </div>

    </main>


    @include('particals.footer')

@endsection