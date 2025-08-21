@extends('layouts.app')

@section('title', 'Блог | ПАТИМЕНЕДЖЕР')
@section('description', 'Читайте статьи, новости и полезную информацию на сайте ПАТИМЕНЕДЖЕР.')

@section('content')

    @include('particals.header')










    <!-- blog -->
    <main class="container mx-auto px-4">
        <!-- title -->
        <div class="big-title pb-10 pt-20 text-center">
            <h2 class="font-semibold text-3xl sm:text-4xl">Наш блог</h2>
            <p class="opacity-50 text-sm sm:text-base">Интересно почитать на досуге</p>
        </div>

        <!-- большие блоги -->
        <div class="big_blogs grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6 md:gap-10 mb-20">
            @foreach($blogs->take(2) as $blog)
                <a href="{{ route('blog.show', $blog->slug) }}"
                    class="big_blog__item flex flex-col gap-2.5">
                    <img src="{{ asset("storage/" . $blog->image) }}" alt="{{ $blog->title }}"
                        class="blog__item--img w-full h-[425px] w-full rounded-2xl shadow-md object-cover object-center">
                    <p class="blog__item--title font-semibold text-lg sm:text-xl">{{ $blog->title }}</p>
                    <p class="blog__item--subtitle opacity-50 text-sm sm:text-base">{{ $blog->subtitle }}</p>
                </a>
            @endforeach
        </div>

        <!-- обычные блоги -->
        <div class="blogs grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 md:gap-10 mb-20">
            @foreach($blogs->slice(2) as $blog)
                <a href="{{ route('blog.show', $blog->slug) }}" class="blog__item flex flex-col gap-2.5">
                    <img src="{{ asset("storage/" . $blog->image) }}" alt="{{ $blog->title }}"
                        class="blog__item--img w-full h-[250px] rounded-2xl shadow object-center object-cover">
                    <p class="blog__item--title font-semibold text-lg sm:text-lg">{{ $blog->title }}</p>
                    <p class="blog__item--subtitle opacity-50 text-sm sm:text-sm">{{ $blog->subtitle }}</p>
                </a>
            @endforeach
        </div>
    </main>
    @include('particals.footer')

@endsection