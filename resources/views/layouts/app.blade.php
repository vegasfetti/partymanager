<!DOCTYPE html>
<html lang="ru">

<head>
    <!-- meta -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description"
        content="@yield('description', 'Удобный сервис для поиска и бронирования специалистов. Реальные отзывы, портфолио и заявки онлайн — всё для быстрого выбора.')">

    {{-- Open Graph для соцсетей --}}
    <meta property="og:title" content="ПАТИМЕНЕДЖЕР" />
    <meta property="og:description"
        content="Удобный сервис для поиска и бронирования специалистов. Реальные отзывы, портфолио и заявки онлайн — всё для быстрого выбора." />
    {{--
    <meta property="og:image" content="@yield('og_image', asset('img/default.png'))" /> --}}
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="ПАТИМЕНЕДЖЕР">
    <meta name="twitter:description"
        content="Удобный сервис для поиска и бронирования специалистов. Реальные отзывы, портфолио и заявки онлайн — всё для быстрого выбора.">
    {{--
    <meta name="twitter:image" content="@yield('twitter_image', asset('img/default.png'))"> --}}

    <!-- links -->
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    {{-- favicon --}}
    <link rel="icon" type="image/png" href="/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon/favicon.svg" />
    <link rel="shortcut icon" href="/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="ПАТИМЕНЕДЖЕР" />
    <link rel="manifest" href="/favicon/site.webmanifest" />
    <!-- stack -->
    @stack('app-links')
    <!-- vite -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')

    <!-- title -->
    <title>@yield('title')</title>
</head>

<body class="max-w-screen overflow-x-hidden">


    @include('particals.cookie')






    @yield('content')




    @include('particals.alert')


    @stack('app-scripts')

</body>

</html>