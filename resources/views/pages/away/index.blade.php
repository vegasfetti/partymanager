@extends('layouts.app')

@section('title', 'Переход | ПАТИМЕНЕДЖЕР')


@section('content')

    @include('particals.header')



    @php
        $rawUrl = request()->get('to');
        $url = preg_match('/^https?:\/\//', $rawUrl) ? $rawUrl : 'https://' . $rawUrl;

        $parsed = parse_url($url);
        $isValidUrl = $parsed && isset($parsed['host']);
    @endphp




    <main class="container min-h-[100vh] relative px-4 sm:px-6 lg:px-8">

        <!-- декоративные круги -->
        <div
            class="circle pointer-events-none w-[600px] sm:w-[800px] lg:w-[1000px] h-[600px] sm:h-[800px] lg:h-[1000px] absolute bg-[#2C3E50] rounded-full right-0 top-[10%] translate-x-3/4 -translate-y-1/2 opacity-5 z-10">
        </div>
        <div
            class="circle pointer-events-none w-[600px] sm:w-[800px] lg:w-[1000px] h-[600px] sm:h-[800px] lg:h-[1000px] absolute bg-[#D4AF37] rounded-full left-0 top-[90%] -translate-x-3/4 -translate-y-1/2 opacity-7.5 z-10">
        </div>



        @if($isValidUrl)
            <div class="away max-w-2xl mt-20 text-left relative z-20">
                <div class="big-title pb-6 sm:pb-10">
                    <h2 class="font-semibold text-2xl sm:text-3xl lg:text-4xl">Переход на внешнюю ссылку</h2>
                    <p class="opacity-50 mt-1 sm:mt-2.5 text-sm sm:text-base">Кажется вы хотите перейти на другой ресурс</p>
                </div>

                <p class="text-base sm:text-lg lg:text-xl mt-4 sm:mt-6 mb-6 sm:mb-8">
                    Пожалуйста, будьте бдительны, вы собираетесь перейти на внешний ресурс, никак НЕ связан с Патименеджер
                </p>

                <div class="away_btns flex flex-col sm:flex-row gap-3 sm:gap-5 mb-4">
                    <a href="{{ url()->previous() }}"
                        class="btn bg-[#FFD700] border border-[#0000001a] px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold text-center w-full sm:w-auto">Вернуться</a>
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                        class="btn btn-secondary bg-[#F5F9FF] border border-[#0000001a] px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold text-center w-full sm:w-auto">Перейти</a>
                </div>

                <p class="pt-2.5 opacity-50 text-sm sm:text-base break-words">Ссылка: {{ $url }}</p>
            </div>
        @else
            <div class="away max-w-2xl  mt-20 text-left relative z-20">
                <div class="big-title pb-6 sm:pb-10">
                    <h2 class="font-semibold text-2xl sm:text-3xl lg:text-4xl">Переход на внешнюю ссылку</h2>
                    <p class="opacity-50 mt-1 sm:mt-2.5 text-sm sm:text-base">Кажется вы хотите перейти на другой ресурс</p>
                </div>

                <p class="text-base sm:text-lg lg:text-xl mt-4 sm:mt-6 mb-6 sm:mb-8">Кажется, что ссылка указана не корректно
                </p>

                <div class="away_btns flex flex-col sm:flex-row gap-3 sm:gap-5 mb-4">
                    <a href="/{{ url()->previous() }}"
                        class="btn bg-[#FFD700] border border-[#0000001a] px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl font-semibold text-center w-full sm:w-auto">Вернуться</a>
                </div>

                <p class="pt-2.5 opacity-50 text-sm sm:text-base break-words">Некорректная ссылка</p>
            </div>
        @endif

    </main>


    @include('particals.footer')

@endsection