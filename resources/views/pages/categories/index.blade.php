@extends('layouts.app')

@section('title', 'Все категории | ПАТИМЕНЕДЖЕР')
@section('description', 'Просмотрите все категории специалистов на ПАТИМЕНЕДЖЕР и быстро найдите нужных исполнителей для своего праздника.')



@section('content')


    @push('app-links')
    @endpush

    @include('particals.header')


    <main class="container min-h-screen relative">

        <!-- circles -->
        <div
            class="circle pointer-events-none w-[1000px] h-[1000px] absolute bg-[#2C3E50] rounded-full right-0 top-[10%] translate-x-3/4 -translate-y-1/2 opacity-5 z-10">
        </div>


        <div
            class="circle pointer-events-none w-[1000px] h-[1000px] absolute bg-[#D4AF37] rounded-full left-0 top-[90%] -translate-x-3/4 -translate-y-1/2 opacity-7.5 z-10">
        </div>



        <div class="container pb-20">
            <!-- title -->
            <div class="pb-10 pt-10 text-center big-title sm:text-left">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Все категории</h2>
                <p class="text-sm opacity-50 sm:text-base">Найдите своего специалиста, они все уже готовы вам помочь</p>
            </div>

            <!-- cats -->
            <div class="relative z-10 grid grid-cols-1 gap-5 pb-8 cats sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($categories as $category)

                    <a class="relative w-full h-56 sm:h-64 rounded-2xl shadow-black cats__item"
                        href="{{route('specialists.byCategory', ['category' => $category->slug])}}">
                        <div class="absolute inset-0 bg-[#1B1E4A] rounded-2xl z-0 cats__item--bg"></div>
                        <img class="z-0 object-cover object-center w-full h-full rounded-2xl brightness-90"
                            src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                        <p
                            class="absolute bottom-0 left-0 text-[#F5F9FF] py-2.5 px-5 bg-[#1B1E4A] rounded-tr-2xl rounded-bl-2xl z-10 text-sm sm:text-base">
                            {{ $category->name }}
                        </p>
                    </a>
                @endforeach
            </div>

            <!-- кнопка -->
            <a class="header__btn flex items-center w-fit mx-auto gap-2 bg-[#FFD700] px-5 py-2 rounded-xl border border-[#0000001a] hover:brightness-110 transition"
                href="{{ route('specialists') }}">
                <p class="text-sm font-medium sm:text-base">К исполнителям</p>
                <img class="p-2 rounded-lg bg-[#1B1E4A] w-6 h-6" src="{{ asset('ico/arrow_link_white.svg') }}"
                    alt="показать все">
            </a>
        </div>



    </main>

    @include('particals.footer')

@endsection