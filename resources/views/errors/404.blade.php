@extends('layouts.app')

@section('title', '404 | ПАТИМЕНЕДЖЕР')


@section('content')

    @include('particals.header')

    <main class="container min-h-[90vh] flex justify-center items-center relative">




        <!-- circles -->
        <div
            class="circle pointer-events-none w-[600px] sm:w-[800px] lg:w-[1000px] h-[600px] sm:h-[800px] lg:h-[1000px] absolute bg-[#2C3E50] rounded-full right-0 top-[10%] translate-x-3/4 -translate-y-1/2 opacity-5 z-10">
        </div>

        <div
            class="circle pointer-events-none w-[600px] sm:w-[800px] lg:w-[1000px] h-[600px] sm:h-[800px] lg:h-[1000px] absolute bg-[#D4AF37] rounded-full left-0 top-[90%] -translate-x-3/4 -translate-y-1/2 opacity-7.5 z-10">
        </div>




        <div class="text-center">
            <h1 class="font-semibold text-4xl">404</h1>
            <p class="font-light text-lg opacity-50 pt-2.5 pb-7.5">Кажется, такой страницы не существует</p>
            <a class="px-4 py-2 border font-semibold border-[#0000001a] rounded-xl bg-[#FFD700] " href="{{ route('specialists') }}">К специалистам</a>
        </div>

    </main>

    @include('particals.footer')

@endsection