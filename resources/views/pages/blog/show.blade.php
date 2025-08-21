@extends('layouts.app')

@php 
                if ($blog->meta_title == null) {
        $title = $blog->title;
    } else {
        $title = $blog->meta_title;
    }
    if ($blog->meta_description == null) {
        $description = $blog->description;
    } else {
        $description = $blog->meta_description;
    }
@endphp
@section('title', \Illuminate\Support\Str::limit($title, 47) . '... | ПАТИМЕНЕДЖЕР')
@section('description', \Illuminate\Support\Str::limit($description, 150, '...'))


@section('content')

    @include('particals.header')








    <!-- blog -->
    <main class="container px-4 sm:px-6 lg:px-8">

        <!-- breadcrumbs -->
        <div class="breadcrumbs flex flex-wrap items-center gap-2 sm:gap-3.5 pt-5 pb-5 text-sm sm:text-base">
            <a class="opacity-50 hover:underline" href="{{ route('main') }}">Главная</a>
            <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="" class="h-3 sm:h-4">
            <a class="opacity-50 hover:underline" href="{{ route('blog') }}">Блог</a>
            <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="" class="h-3 sm:h-4">
            <a class="truncate max-w-full sm:max-w-[70%]">{{ $blog->title }}</a>
        </div>

        <!-- title -->
        <div class="big-title pb-6 sm:pb-10 pt-2 sm:pt-5 text-left">
            <h2 class="font-semibold text-2xl sm:text-3xl lg:text-4xl">{{ $blog->title }}</h2>
            <p class="opacity-50 mt-2 sm:mt-2.5 text-sm sm:text-base">{{ $blog->subtitle }}</p>
        </div>

        <!-- main image -->
        <img class="rounded-2xl sm:rounded-4xl w-full max-h-72 sm:max-h-140 object-cover object-center mb-5 sm:mb-8"
            src="{{ asset("storage/" . $blog->image) }}" alt="{{ $blog->title }}">


        <!-- text -->
        <div class="blog-content">
            <p class="opacity-75 blog-text mt-4 sm:mt-5 mb-10 text-sm sm:text-base leading-relaxed">
                {!!$blog->text!!}
            </p>
        </div>

    

        <!-- publication date -->
        <p class="opacity-50 mb-10 sm:mb-20 text-xs sm:text-sm">
            Дата публикации: {{ \Carbon\Carbon::parse($blog->published_at)->format('d.m.Y') }}
        </p>

    </main>


    @include('particals.footer')

@endsection