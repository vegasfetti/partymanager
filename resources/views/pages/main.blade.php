@extends('layouts.app')

@section('title', 'Главная | ПАТИМЕНЕДЖЕР')




@section('content')

    @push('app-links')
        <!-- lordicon -->
        <script src="https://cdn.lordicon.com/lordicon.js"></script>
        <!-- aos -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    @endpush

    <!-- header -->
    <header class="header w-full absolute z-50 pt-2.5">
        <div class="container flex items-center justify-between header-container">
            <div class="flex items-center gap-20 header__nav">
                <a data-aos="fade-down" data-aos-delay="0" href="{{ route('main') }}" class="header__logo el-hover">
                    <img src="{{ asset('img/white-logo.png') }}" alt="ПАТИМЕНЕДЖЕР">
                </a>
                <div class="header__menu flex gap-10 text-[#F5F9FF]">
                    <a data-aos="fade-down" data-aos-delay="100" class="header__link text-[18px] el-hover"
                        href="{{ route('specialists') }}">Найти специалиста</a>
                    <a data-aos="fade-down" data-aos-delay="200" class="el-hover header__link text-[18px] opacity-50 "
                        href="{{ route('main') }}">Стать специалистом</a>
                </div>
            </div>
            <div class="flex items-center gap-10 header__btns">
                @guest
                    <a data-aos="fade-down" data-aos-delay="400"
                        class="header__btn flex items-center gap-2 bg-[#F5F9FF] pl-5 pr-5 pt-2 pb-2 rounded-xl"
                        href="{{ route('signin') }}">
                        <p class="font-medium">войти</p>
                        <img class="p-2 rounded-lg bg-[#FFD700]" src="{{ asset('ico/arrow_link.svg') }}" alt="войти">
                    </a>
                @endguest
                @auth
                    <a data-aos="fade-down" data-aos-delay="400" class="smart-order-btn hover:opacity-70"
                        href="{{route('cart.index')}}">
                        <img src="{{asset('ico/booking-white.png')}}" alt="">
                    </a>
                    <a data-aos="fade-down" data-aos-delay="500" class="hover:opacity-70" href="{{ route('profile') }}">
                        <img class="w-10 h-10 border border-[#0000001a] object-cover object-center rounded-full"
                            src="{{ asset('storage/' . Auth::user()->image) }}" alt="">
                    </a>
                @endauth
            </div>
            <div data-aos="fade-down" data-aos-delay="100" class="burger-btn">
                <img onclick="openMenu()" class="cursor-pointer" src="{{ asset('ico/burger-white.png') }}" alt="">
            </div>
        </div>
    </header>


    <!-- Бургер-меню фон -->
    <div id="burger-overlay" class="fixed inset-0 bg-[#00000071] bg-opacity-50 z-900 hidden"></div>

    <!-- Бургер-меню сам -->
    <div id="burger-menu"
        class="burger-menu border-l border-[#0000001a] fixed top-0 right-0 w-[300px] max-w-full h-full bg-[#F5F9FF] z-1000 transform translate-x-full transition-transform duration-300 p-5 flex flex-col">
        <!-- Логотип -->
        <div class="logo-burger flex w-full justify-between items-center mb-10">
            <a href="{{ route('main') }}" class="flex items-center justify-between w-fit">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="w-[70px]">
            </a>
            <button onclick="closeMenu()" class="text-black text-2xl font-bold opacity-50 hover:opacity-100 w-fit"><img
                    src="{{ asset('ico/close.png') }}" alt=""></button>
        </div>
        <!-- Меню -->
        <nav class="flex flex-col gap-5 text-lg">
            <a href="{{ route('specialists') }}" class="hover:opacity-70">Найти специалиста</a>
            <a href="{{ route('specialists.create') }}" class="hover:opacity-70 mb-5">Стать
                специалистом</a>

            <a class="hover:opacity-70" href="{{route('categories.index')}}">Все категории</a>
            <a class="hover:opacity-70  mb-5" href="{{route('blog')}}">Наш блог</a>
            @guest
                <a href="{{ route('signin') }}"
                    class="bg-[#1B1E4A] text-[#F5F9FF] px-5 py-2 rounded-lg flex items-center gap-2 w-fit">
                    Войти
                    <img class="p-2 rounded-lg bg-[#FFD700]" src="{{ asset('ico/arrow_link.svg') }}" alt="войти">
                </a>
            @endguest
            @auth
                <a href="{{route('cart.index')}}" class="flex items-center gap-2 hover:opacity-70">
                    <img src="{{asset('ico/booking.png')}}" alt=""> Умное бронирование
                </a>
                <a href="{{ route('profile') }}" class="flex items-center gap-2 hover:opacity-70">
                    <img class="w-7 h-7 border border-[#0000001a] object-cover object-center rounded-full"
                        src="{{ asset('storage/' . Auth::user()->image) }}" alt="">
                    <p class="line-clamp-1 break-words">{{ Auth::user()->name }}</p>
                </a>
            @endauth
        </nav>
    </div>




    <!-- slider -->
    <div class="swiper mySwiper w-full h-[90vh]">

        <div class="h-full swiper-wrapper">


            @foreach ($banners as $banner)

                <!-- slide -->
                <div style="background-image: url('{{ asset('storage/' . $banner->image) }}')"
                    class="relative h-full bg-center bg-cover swiper-slide banner-slide">
                    <div class="absolute inset-0 bg-black opacity-50 slide__promo"></div>

                    <div class="container relative w-full h-full">
                        <div data-aos="fade-left" data-aos-delay="600"
                            class="slide-text text-right absolute bottom-[10%] right-0 text-[#F5F9FF] px-4 sm:px-8 md:px-16 w-full sm:w-auto">
                            @if($banner->is_promo == 1)
                                <div
                                    class="slide-text__promo text-xs opacity-30 hover:opacity-100 pt-1 pb-1 pl-2 pr-2 rounded-md bg-[#F5F9FF] text-black inline-block mb-2.5 border border-[#0000001a]">
                                    промо</div>
                            @endif
                            <div class="text-lg font-bold slide-text__title sm:text-xl md:text-2xl lg:text-3xl">
                                {{ $banner->title }}
                            </div>
                            <div class="text-base font-light slide-text__subtitle sm:text-lg">{{ $banner->subtitle }}
                            </div>
                            @if ($banner->link)

                                <a href="{{ $banner->link }}"
                                    class="slide-text__btn pt-2 pb-2 pl-4 pr-4 rounded-xl bg-[#F5F9FF] text-black inline-block mt-5 font-semibold border border-[#0000001a]">перейти
                                </a>
                            @endif
                        </div>
                    </div>

                </div>

            @endforeach



        </div>

        <!-- search -->
        <div data-aos="fade-right" data-aos-delay="500"
            class="absolute z-10 w-full px-2 -translate-x-1/2 -translate-y-1/2 swiper-search top-1/2 left-1/2">
            <div class="container">
                <h1 class="text-2xl font-bold text-center text-white search__title xs:text-3xl sm:text-4xl">ПАТИМЕНЕДЖЕР
                </h1>
                <div class="text-base font-light text-center text-white search__subtitle xs:text-lg">Ваши задачи — наши
                    специалисты.</div>
                <form
                    class="banner-search flex flex-col sm:flex-row mt-5 bg-[#F5F9FF] p-2.5 rounded-2xl w-full sm:w-2/3 md:w-1/2 mx-auto justify-between gap-2.5"
                    action="{{ route('specialists')}}">
                    <div class="flex items-center w-full gap-2">
                        <img class="px-2.5" src="{{ asset('ico/search.png') }}" alt="поиск" class="w-5 h-5">
                        <input id="search" name="search" class="w-full text-sm bg-transparent outline-none" type="text"
                            placeholder="Поиск специалистов">
                    </div>
                    <button
                        class="bg-[#FFD700] hover:brightness-110 px-4 py-2 rounded-xl cursor-pointer font-medium border border-[#0000001a]">поиск</button>
                </form>
                {{-- <div class="search__text flex gap-1 text-[#F5F9FF] opacity-50 mt-4 justify-center items-center">
                    <p>Ищут чаще всего: </p><a class="underline" href="">Ведущий</a>
                </div> --}}
            </div>
        </div>

        <div class="swiper-pagination"></div>
    </div>























    <main class="bg-[#F5F9FF] mt-[-50px] z-100 relative rounded-t-3xl pt-20 overflow-hidden pb-40">

        {{-- <h1>Вы выбрали город: {{ $current_city->id }}</h1>

        <form>
            <select onchange="location.href='/set-city/' + this.value">
                @foreach(App\Models\City::all() as $city)
                <option value="{{ $city->slug }}" {{ $current_city && $current_city->id === $city->id ? 'selected' : '' }}>
                    {{ $city->name }}
                </option>
                @endforeach
            </select>
        </form> --}}




        <!-- circles -->
        <div
            class="hidden xl:block circle pointer-events-none w-[1000px] h-[1000px] absolute bg-[#2C3E50] rounded-full left-0 top-[10%] -translate-x-1/2 -translate-y-1/2 opacity-15 blur-3xl z-1">
        </div>
        <div
            class="hidden xl:block circle pointer-events-none w-[1000px] h-[1000px] absolute bg-[#1B1E4A] rounded-full right-0 top-[30%] translate-x-1/2 -translate-y-1/2 opacity-15 blur-3xl z-1">
        </div>

        <div
            class="hidden xl:block circle pointer-events-none w-[1000px] h-[1000px] absolute bg-[#FFD700] rounded-full left-0 top-[60%] -translate-x-1/2 -translate-y-1/2 opacity-15 blur-3xl z-1">
        </div>

        <div
            class="hidden xl:block circle pointer-events-none w-[1000px] h-[1000px] absolute bg-[#D4AF37] rounded-full right-0 top-[90%] translate-x-1/2 -translate-y-1/2 opacity-15 blur-3xl z-1">
        </div>



        <div class="container pb-20">
            <!-- title -->
            <div data-aos="fade-down" class="pb-10 text-center big-title sm:text-left">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Популярные категории</h2>
                <p class="text-sm opacity-50 sm:text-base">Найдите своего специалиста, они все уже готовы вам помочь</p>
            </div>

            <!-- cats -->
            <div data-aos="fade-down" class="relative z-10 grid grid-cols-1 gap-5 pb-8 cats sm:grid-cols-2 lg:grid-cols-3">
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
            <a data-aos="fade-down"
                class="header__btn flex items-center w-fit mx-auto gap-2 bg-[#FFD700] px-5 py-2 rounded-xl border border-[#0000001a] hover:brightness-110 transition"
                href="{{ route('categories.index') }}">
                <p class="text-sm font-medium sm:text-base">Показать все</p>
                <img class="p-2 rounded-lg bg-[#1B1E4A] w-6 h-6" src="{{ asset('ico/arrow_link_white.svg') }}"
                    alt="показать все">
            </a>
        </div>





        <div
            class="hdiw mx-auto w-[97.5%] pb-14 bg-gradient-to-br from-[#1C1F4C] to-[#2C3E50] rounded-3xl z-10 relative border border-[#0000001a]">

            <div class="container px-4 md:px-6">
                <!-- title -->
                <div data-aos="fade-down" class="py-10 big-title md:py-13 lg:text-center 2xl:text-left">
                    <h2 class="font-semibold text-3xl md:text-4xl text-[#F5F9FF]">Как это работает</h2>
                    <p class="text-[#F5F9FF] opacity-50 text-base md:text-lg">Удобно, быстро и просто</p>
                </div>

                <div class="grid grid-cols-1 gap-5 hdiw__items xl:grid-cols-2">
                    <!-- item -->
                    <div data-aos="fade-down"
                        class="hdiw__item bg-[#1d2445] w-full py-6 px-6 md:px-10 rounded-2xl flex items-start gap-5 md:gap-7 text-[#F5F9FF]">
                        <h3 class="text-5xl font-bold opacity-50 md:text-8xl">01</h3>
                        <div>
                            <h3 class="text-lg sm:text-xl font-semibold md:text-2xl">Зарегистрируйтесь</h3>
                            <p class="text-sm opacity-50 md:text-base">Нужно для того, чтобы видеть свои брони. Это займёт
                                всего пару минут.</p>
                        </div>
                    </div>

                    <div data-aos="fade-down"
                        class="hdiw__item bg-[#1d2445] w-full py-6 px-6 md:px-10 rounded-2xl flex items-start gap-5 md:gap-7 text-[#F5F9FF]">
                        <h3 class="text-5xl font-bold opacity-50 md:text-8xl">02</h3>
                        <div>
                            <h3 class="text-lg sm:text-xl font-semibold md:text-2xl">Выберите специалиста</h3>
                            <p class="text-sm opacity-50 md:text-base">Выберите нужного исполнителя — они все готовы помочь
                                вам.</p>
                        </div>
                    </div>

                    <div data-aos="fade-down"
                        class="hdiw__item bg-[#1d2445] w-full py-6 px-6 md:px-10 rounded-2xl flex items-start gap-5 md:gap-7 text-[#F5F9FF]">
                        <h3 class="text-5xl font-bold opacity-50 md:text-8xl">03</h3>
                        <div>
                            <h3 class="text-lg sm:text-xl font-semibold md:text-2xl">Оставьте заявку</h3>
                            <p class="text-sm opacity-50 md:text-base">Заполните и отправьте форму, если специалист свободен
                                — он
                                примет заказ.</p>
                        </div>
                    </div>

                    <div data-aos="fade-down"
                        class="hdiw__item bg-[#1d2445] w-full py-6 px-6 md:px-10 rounded-2xl flex items-start gap-5 md:gap-7 text-[#F5F9FF]">
                        <h3 class="text-5xl font-bold opacity-50 md:text-8xl">04</h3>
                        <div>
                            <h3 class="text-lg sm:text-xl font-semibold md:text-2xl">Свяжитесь</h3>
                            <p class="text-sm opacity-50 md:text-base">После подтверждения брони специалист свяжется с вами
                                и уточнит детали.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="container">
            <!-- title -->
            <div data-aos="fade-down" class="pt-20 pb-10 text-center big-title md:text-left">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Договоримся обо всём за вас</h2>
                <p class="mt-2 opacity-50">Наслаждайтесь праздником, организацию возьмём на себя</p>
            </div>

            <!-- agree -->
            <div class="z-10 grid items-center grid-cols-1 gap-10 agree lg:grid-cols-2 lg:gap-16">
                <!-- Текст -->
                <div data-aos="fade-down" class="flex flex-col gap-8 agree__info md:gap-10">
                    <!-- Элемент -->
                    <div class="agree__info__el flex gap-2.5 items-start">
                        <img src="{{ asset('ico/Frame-2.png') }}" alt="Выберите исполнителей"
                            class="object-contain w-10 h-10 agree__info__el--ico">
                        <div class="agree__info__el--text flex flex-col gap-1.5 w-full sm:w-3/4">
                            <p class="text-base font-semibold sm:text-lg">Выберите исполнителей</p>
                            <span class="text-sm opacity-50 sm:text-base">Выберите исполнителей и нужную дату. Заполните и
                                отправьте форму</span>
                        </div>
                    </div>

                    <div class="agree__info__el flex gap-2.5 items-start">
                        <img src="{{ asset('ico/Frame-1.png') }}" alt="Свяжемся со всеми за вас"
                            class="object-contain w-10 h-10 agree__info__el--ico">
                        <div class="agree__info__el--text flex flex-col gap-1.5 w-full sm:w-3/4">
                            <p class="text-base font-semibold sm:text-lg">Свяжемся со всеми за вас</p>
                            <span class="text-sm opacity-50 sm:text-base">Свяжемся с каждым исполнителем. Забронируем их в
                                нужную дату</span>
                        </div>
                    </div>

                    <div class="agree__info__el flex gap-2.5 items-start">
                        <img src="{{ asset('ico/Frame.png') }}" alt="Связываемся с вами"
                            class="object-contain w-10 h-10 agree__info__el--ico">
                        <div class="agree__info__el--text flex flex-col gap-1.5 w-full sm:w-3/4">
                            <p class="text-base font-semibold sm:text-lg">Связываемся с вами</p>
                            <span class="text-sm opacity-50 sm:text-base">Свяжемся с вами уже с готовым результатом. Поможем
                                подобрать варианты, если кто-то занят</span>
                        </div>
                    </div>

                    <!-- Кнопки -->
                    <div class="flex flex-col items-start gap-4 pt-6 btns sm:flex-row sm:gap-5 sm:items-center sm:pt-10">
                        <a class="header__btn flex items-center gap-2 bg-[#FFD700] px-5 py-2 rounded-xl border border-[#0000001a] hover:brightness-110"
                            href="{{ route('specialists') }}">
                            <p class="font-medium">К исполнителям</p>
                            <img class="p-2 rounded-lg bg-[#1B1E4A]" src="{{ asset('ico/arrow_link_white.svg') }}"
                                alt="К исполнителям">
                        </a>
                        <a class="header__btn flex items-center gap-2 bg-[#1B1E4A] px-5 py-2 rounded-xl border border-[#0000001a]"
                            href="{{ route('cart.index') }}">
                            <p class="text-[#F5F9FF] font-medium">Умное бронирование</p>
                            <img class="p-2 rounded-lg bg-[#FFD700]" src="{{ asset('ico/arrow_link.svg') }}"
                                alt="В корзину">
                        </a>
                    </div>
                </div>
                <!-- Изображения -->
                <div data-aos="fade-left" class="agree__imgs grid grid-cols-2 gap-4 h-auto max-h-[500px] hidden lg:grid">
                    <div class="agree__imgs--img bg-[#dbeafe] rounded-2xl h-84">
                        <img alt="" src="{{asset('img/agree1.webp')}}" class="w-full h-full object-cover rounded-2xl">
                    </div>
                    <div class="grid grid-rows-2 gap-4 agree__imgs--imgs">
                        <div class="agree__imgs--img bg-[#bfdbfe] rounded-2xl h-40">
                            <img alt="" src="{{asset('img/agree2.webp')}}" class="w-full h-full object-cover rounded-2xl">
                        </div>
                        <div class="agree__imgs--img bg-[#93c5fd] rounded-2xl h-40">
                            <img alt="" src="{{asset('img/agree3.webp')}}" class="w-full h-full object-cover rounded-2xl">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- problems -->
        <div class="container">

            <!-- title -->
            <div data-aos="fade-down" class="pt-20 pb-10 text-center big-title sm:pb-16 sm:pt-32">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Проблемы и Решения</h2>
                <p class="mt-2 text-sm opacity-50 sm:text-base">Для чего это нужно и почему это удобно</p>
            </div>

            <!-- карточки -->
            <div class="z-10 grid grid-cols-1 gap-4 pb-20 problems sm:grid-cols-2 lg:grid-cols-4 sm:gap-5">

                <div data-aos="fade-down" data-aos-delay="0"
                    class="prob relative w-full p-4 border border-[#0000001a] text-center shadow rounded-xl">
                    <div
                        class="prob-ico rounded-full bg-[#1B1E4A] absolute left-1/2 -translate-x-1/2 -top-6 h-[50px] w-[50px] flex justify-center items-center">
                        <lord-icon src="https://cdn.lordicon.com/cniwvohj.json" trigger="hover" colors="primary:#fff"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </div>
                    <p class="pt-6 text-xs line-through opacity-50 sm:text-sm">Долгий поиск в разных местах</p>
                    <span class="text-sm font-semibold sm:text-base">Большой список кандидатов в одном месте</span>
                </div>

                <div data-aos="fade-down" data-aos-delay="100"
                    class="prob relative w-full p-4 border border-[#0000001a] text-center shadow rounded-xl">
                    <div
                        class="prob-ico rounded-full bg-[#1B1E4A] absolute left-1/2 -translate-x-1/2 -top-6 h-[50px] w-[50px] flex justify-center items-center">
                        <lord-icon src="https://cdn.lordicon.com/bpptgtfr.json" trigger="hover" colors="primary:#fff"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </div>
                    <p class="pt-6 text-xs line-through opacity-50 sm:text-sm">Отсутствие отзывов</p>
                    <span class="text-sm font-semibold sm:text-base">Публикуем только реальные отзывы</span>
                </div>

                <div data-aos="fade-down" data-aos-delay="200"
                    class="prob relative w-full p-4 border border-[#0000001a] text-center shadow rounded-xl">
                    <div
                        class="prob-ico rounded-full bg-[#1B1E4A] absolute left-1/2 -translate-x-1/2 -top-6 h-[50px] w-[50px] flex justify-center items-center">
                        <lord-icon src="https://cdn.lordicon.com/uwnsxkfm.json" trigger="hover" colors="primary:#ffffff"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </div>
                    <p class="pt-6 text-xs line-through opacity-50 sm:text-sm">Риск наткнуться на мошенников</p>
                    <span class="text-sm font-semibold sm:text-base">Регулярно проверяем исполнителей</span>
                </div>

                <div data-aos="fade-down" data-aos-delay="300"
                    class="prob relative w-full p-4 border border-[#0000001a] text-center shadow rounded-xl">
                    <div
                        class="prob-ico rounded-full bg-[#1B1E4A] absolute left-1/2 -translate-x-1/2 -top-6 h-[50px] w-[50px] flex justify-center items-center">
                        <lord-icon src="https://cdn.lordicon.com/dnupukmh.json" trigger="hover" colors="primary:#ffffff"
                            style="width:30px;height:30px">
                        </lord-icon>
                    </div>
                    <p class="pt-6 text-xs line-through opacity-50 sm:text-sm">Сложно сравнить цены</p>
                    <span class="text-sm font-semibold sm:text-base">Только прозрачная стоимость в объявлениях</span>
                </div>

            </div>
        </div>




        <!-- reviews -->
        <div class="container pb-10">
            <div data-aos="fade-down" class="pt-20 pb-10 text-center big-title sm:pb-16 sm:pt-16">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Последние отзывы</h2>
                <p class="mt-2 text-sm opacity-50 sm:text-base">Последние отзывы о наших исполнителях</p>
            </div>



            <!-- Swiper -->
            <div class="reviews__slider swiper mySwiper2">
                <div class="pb-10 swiper-wrapper">








                    @foreach ($reviews as $review)
                        <div data-aos="fade-down" class="swiper-slide reviews__slider--item">
                            <div class="review relative w-full h-100 p-5 bg-[#f0f4fa] border border-[#d0d4d8] rounded-2xl">
                                <div class="flex items-center w-full gap-5 review__author">
                                    <img class="w-12 h-12 rounded-full" src="{{ asset('storage/' . $review->user->image) }}"
                                        alt="Автор отзывы" class="rounded-full review__author__img w-18 h-18">
                                    <div class="max-w-full review__author__info">
                                        <p class="opacity-20 w-min">01.07.25</p>
                                        <p class="w-[100%] text-lg font-semibold line-clamp-1">{{ $review->user->name }}</p>
                                        <div class="flex gap-1 review__author__stars w-min">
                                            <img class="{{ $review->rating >= 1 ? 'opacity-100' : 'opacity-20' }}"
                                                src="{{ asset(path: 'ico/star.svg') }}" alt="Рейтинг">
                                            <img class="{{ $review->rating >= 2 ? 'opacity-100' : 'opacity-20' }}"
                                                src="{{ asset(path: 'ico/star.svg') }}" alt="Рейтинг">
                                            <img class="{{ $review->rating >= 3 ? 'opacity-100' : 'opacity-20' }}"
                                                src="{{ asset(path: 'ico/star.svg') }}" alt="Рейтинг">
                                            <img class="{{ $review->rating >= 4 ? 'opacity-100' : 'opacity-20' }}"
                                                src="{{ asset(path: 'ico/star.svg') }}" alt="Рейтинг">
                                            <img class="{{ $review->rating >= 5 ? 'opacity-100' : 'opacity-20' }}"
                                                src="{{ asset(path: 'ico/star.svg') }}" alt="Рейтинг">
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full pt-5 text-lg font-semibold review__title line-clamp-2">
                                    {{ $review->title }}
                                </div>
                                <div class="review__text line-clamp-6">
                                    {{ $review->text }}
                                </div>

                                <div class="absolute opacity-50 review__links bottom-5 left-5">
                                    <p>Услуга: <a href="{{ route('specialists.show', $review->specialist->id) }}"
                                            class="underline">{{ $review->specialist->title }}</a></p>
                                    <p class="flex gap-1.5">Исполнитель: <a
                                            href="{{ route('specialists')}}?search={{ $review->specialist->user->name }}"
                                            class="underline w-[60%] line-clamp-1">{{ $review->specialist->user->name }}</a></p>
                                </div>
                                <img src="{{ asset(path: 'ico/quotes.png') }}" alt="quotes"
                                    class="absolute font-semibold review__quotes bottom-5 right-5 text-9xl">

                            </div>
                        </div>
                    @endforeach


                    <!--  -->
                </div>

                <div data-aos="fade-left"
                    class="review-next cursor-pointer absolute top-1/2 right-0 bg-[#1C1F4C] w-6 h-6 rounded-full flex items-center justify-center z-50 translate-x-[-50%] translate-y-[-50%] opacity-25 hover:opacity-100">
                    <img class="rotate-180" src="{{ asset('ico/line.png') }}" alt="">
                </div>
                <div data-aos="fade-right"
                    class="review-prev cursor-pointer absolute top-1/2 left-0 bg-[#1C1F4C] w-6 h-6 rounded-full flex items-center justify-center z-50 translate-x-[50%] translate-y-[-50%] opacity-25 hover:opacity-100">
                    <img class="" src="{{ asset('ico/line.png') }}" alt="">
                </div>
                <div class="swiper-pagination "></div>
            </div>




        </div>


        <!-- blog -->
        <div class="container">
            <!-- title -->
            <div data-aos="fade-down" class="pt-20 pb-10 text-center big-title sm:pb-16 sm:pt-16">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Наш блог</h2>
                <p class="mt-2 text-sm opacity-50 sm:text-base">Интересно почитать на досуге</p>
            </div>



            <div class=" main-blogs grid grid-cols-1 gap-10 blogs sm:grid-cols-2 lg:grid-cols-3 justify-items-center">

                @foreach ($blogs as $blog)

                    <a data-aos="fade-down" href="{{ route('blog.show', $blog->slug) }}"
                        class="blog__item w-full max-w-[320px] flex flex-col gap-2.5">
                        <img src="{{ asset("storage/" . $blog->image) }}" alt="{{ $blog->title }}"
                            class="blog__item--img w-full max-h-[250px] rounded-2xl shadow-2xl object-cover">
                        <p class="text-lg font-semibold blog__item--title">{{ $blog->title }}
                        </p>
                        <p class="text-sm opacity-50 blog__item--subtitle">{{ $blog->subtitle }}</p>
                    </a>
                @endforeach

            </div>

            <a data-aos="fade-down"
                class="header__btn flex items-center w-fit mx-auto gap-2 bg-[#FFD700] hover:brightness-110 pl-5 pr-5 pt-2 pb-2 rounded-xl mt-10 border border-[#0000001a]"
                href="{{ route('blog') }}">
                <p class="font-medium">Все новости</p>
                <img class="p-2 rounded-lg bg-[#1B1E4A]" src="{{ asset('ico/arrow_link_white.svg') }}" alt="показать все">
            </a>
        </div>





        <!-- send email -->
        <div class="sendemail w-full bg-gradient-to-br from-[#D4AF37] to-[#FFD700] py-10 my-20 z-50">
            <div class="container">
                <!-- title -->
                <div data-aos="fade-down" class="big-title">
                    <h2 class="font-semibold text-4xl text-[#F5F9FF]">Хотите попасть в промо баннер?</h2>
                    <p class="text-[#F5F9FF] opacity-70">Заполняйте форму и мы с вами свяжемся</p>
                </div>

                <div class="sendmail-btns flex flex-col gap-2.5 mt-5">
                    <a class="opacity-100 hover:opacity-100 flex items-center w-fit bg-[#F5F9FF] font-semibold hover:brightness-110 pl-5 pr-5 pt-2 pb-2 rounded-xl border border-[#0000001a]"
                        href="#footer">Оставить заявку</a>
                    <p class="text-[#F5F9FF] font-semibold opacity-70">Или напишите нам на почту <a class="underline"
                            href="milto:partymanager@yandex.ru">partymanager@yandex.ru</a></p>
                </div>
                {{-- <form data-aos="fade-down"
                    class="flex flex-col sm:flex-row mt-5 bg-[#F5F9FF] p-2.5 rounded-xl sm:w-3/4 lg:w-1/2 justify-between gap-5"
                    action="">
                    <div class="flex items-center w-full gap-2">
                        <img class="px-2.5" src="{{ asset('ico/mail.svg') }}" alt="почта">
                        <input class="w-full bg-transparent outline-none" type="email" placeholder="Ваша почта:">
                    </div>
                    <button
                        class="bg-[#FFD700] hover:brightness-110 px-4 py-2 rounded-xl cursor-pointer font-medium border border-[#0000001a] whitespace-nowrap">
                        отправить
                    </button>
                </form>

                <p data-aos="fade-down" class="text-[#F5F9FF] opacity-75 pt-5 sm:w-3/4 lg:w-1/2 text-sm leading-snug">
                    Нажимая на кнопку, вы соглашаетесь на обработку персональных данных в соответствии с
                    <a class="underline hover:brightness-110" href="{{ route('privacy') }}">политикой
                        конфиденциальности</a>
                </p> --}}
            </div>
        </div>



        <div class="container px-4 mx-auto text-base">
            <!-- title -->
            <div data-aos="fade-down" class="pt-20 pb-10 text-center big-title sm:pb-16 sm:pt-16">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">FAQs</h2>
                <p class="mt-2 text-sm opacity-50 sm:text-base">Часто задаваемые вопросы</p>
            </div>

            <!-- адаптивная сетка: 1 колонка на мобильных, 2 — с md и выше -->
            <div class="grid grid-cols-1 gap-5 faqs md:grid-cols-2 ">
                <!-- блок FAQ -->
                <div data-aos="fade-down" z-1000"
                    class="faq__blok w-full bg-[#f5f9ff] border border-[#d0d4d8] rounded-2xl shadow h-min">

                    <!-- элемент аккордеона -->
                    <div class="cursor-pointer accardion ">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Как сделать заказ?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Чтобы сделать заказ, просто выберите понравившийся товар, добавьте его в корзину и перейдите
                                к оформлению. Вам потребуется указать контактные данные, адрес доставки и выбрать способ
                                оплаты. После подтверждения заказа, вы получите письмо с деталями.
                            </p>
                        </div>
                    </div>

                    <hr class="border-[#d0d4d8]">

                    <!-- скопировать этот блок столько раз, сколько нужно -->
                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Где забрать свой заказ?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Вы можете забрать заказ в одном из наших пунктов самовывоза. Адреса и график работы доступны
                                в разделе «Контакты» на сайте.
                            </p>
                        </div>
                    </div>

                    <hr class="border-[#d0d4d8]">

                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Как оформить заказ?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Вы можете забрать заказ в одном из наших пунктов самовывоза. Адреса и график работы доступны
                                в разделе «Контакты» на сайте.
                            </p>
                        </div>
                    </div>

                    <hr class="border-[#d0d4d8]">

                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Какие способы оплаты вы принимаете?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Вы можете забрать заказ в одном из наших пунктов самовывоза. Адреса и график работы доступны
                                в разделе «Контакты» на сайте.
                            </p>
                        </div>
                    </div>

                    <hr class="border-[#d0d4d8]">

                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Сколько стоит доставка и как долго ждать заказ?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Вы можете забрать заказ в одном из наших пунктов самовывоза. Адреса и график работы доступны
                                в разделе «Контакты» на сайте.
                            </p>
                        </div>
                    </div>

                    <!-- ... ещё элементы ... -->

                </div>

                <!-- Второй столбец FAQ -->
                <div data-aos="fade-down"
                    class="faq__blok w-full bg-[#f5f9ff] border border-[#d0d4d8] rounded-2xl shadow z-1000 h-min">

                    <!-- аналогично первая колонка -->
                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Как вернуть товар, если он не подошел?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Для отмены заказа свяжитесь с нашей службой поддержки в течение часа после оформления. Мы
                                постараемся помочь вам как можно быстрее.
                            </p>
                        </div>
                    </div>

                    <hr class="border-[#d0d4d8]">

                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Вы храните данные моей карты?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Для отмены заказа свяжитесь с нашей службой поддержки в течение часа после оформления. Мы
                                постараемся помочь вам как можно быстрее.
                            </p>
                        </div>
                    </div>

                    <hr class="border-[#d0d4d8]">

                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Как срочно связаться с вами в нерабочее время?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Для отмены заказа свяжитесь с нашей службой поддержки в течение часа после оформления. Мы
                                постараемся помочь вам как можно быстрее.
                            </p>
                        </div>
                    </div>

                    <hr class="border-[#d0d4d8]">

                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Как отменить заказ?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Для отмены заказа свяжитесь с нашей службой поддержки в течение часа после оформления. Мы
                                постараемся помочь вам как можно быстрее.
                            </p>
                        </div>
                    </div>

                    <hr class="border-[#d0d4d8]">

                    <div class="cursor-pointer accardion">
                        <div class="flex items-center justify-between p-6 accardion-btn">
                            <p class="sm:text-ld text-base font-medium">Что делать, если товар пришел с дефектом?</p>
                            <img class="transition-transform duration-200 opacity-50 w-4 h-4" src="{{ asset('ico/accardion.svg') }}"
                                alt="toggle">
                        </div>
                        <div class="overflow-hidden transition-all duration-300 accardion-content max-h-0">
                            <hr class="mb-5 border-[#d0d4d8] w-[90%] mx-auto">
                            <p class="p-6 pt-0 opacity-50">
                                Для отмены заказа свяжитесь с нашей службой поддержки в течение часа после оформления. Мы
                                постараемся помочь вам как можно быстрее.
                            </p>
                        </div>
                    </div>

                    <!-- ... ещё элементы ... -->

                </div>
            </div>
        </div>


    </main>

    @include('particals.footer')


    @push('app-scripts')
        <script>
            //banner
            if (document.querySelector('.mySwiper')) {
                var swiper = new Swiper(".mySwiper", {
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    simulateTouch: true,
                    keyboard: {
                        enabled: true,
                        onlyInViewport: true,
                    },


                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                        dynamicBullets: true,

                    },
                });
            }

            //review
            if (document.querySelector('.mySwiper2')) {
                var swiper = new Swiper(".mySwiper2", {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    loop: true,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    simulateTouch: true,
                    keyboard: {
                        enabled: true,
                        onlyInViewport: true,
                    },
                    navigation: {
                        nextEl: ".review-next",
                        prevEl: ".review-prev",
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                        dynamicBullets: true,
                    },

                    breakpoints: {
                        0: {
                            slidesPerView: 1,
                            spaceBetween: 12,
                        },
                        640: {
                            slidesPerView: 1.2,
                            spaceBetween: 15,
                        },
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 20,
                        }
                    }
                });
            }

            // accardion
            if (document.querySelector('.accardion')) {
                let accardions = document.querySelectorAll('.accardion');

                accardions.forEach(el => {
                    el.addEventListener('click', () => {
                        let isActive = el.classList.contains('accardion-active');

                        accardions.forEach(item => {
                            item.classList.remove('accardion-active');
                        });

                        if (!isActive) {
                            el.classList.add('accardion-active');
                        }
                    });
                });
            }

            //aos
            AOS.init();
        </script>
    @endpush
@endsection