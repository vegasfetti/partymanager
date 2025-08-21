<header id="h_top" class="visible header w-full z-100 py-2.5 border-b border-[#0000001a] shadow-sm relative bg-[#F5F9FF]">
    <div class="container flex items-center justify-between header-container">
        <div class="flex items-center gap-20 header__nav">
            <a href="{{ route('main') }}" class="header__logo el-hover">
                <img class="max-w-[70px] md:max-w-[90px]" src="{{ asset('img/logo.png') }}" alt="ПАТИМЕНЕДЖЕР">
            </a>
            <div class="flex gap-10 text-black header__menu">
                <a class="header__link text-[18px] el-hover" href="{{ route('specialists') }}">Найти специалиста</a>
                <a href="{{ route('specialists.create') }}" class="el-hover header__link text-[18px] opacity-50 "
                    href="{{ route('specialists.create') }}">Стать
                    специалистом</a>
            </div>
        </div>
        <div class="flex items-center gap-10 header__btns">
            @guest
                <a class="header__btn flex items-center gap-2 bg-[#1B1E4A] pl-5 pr-5 pt-2 pb-2 rounded-xl"
                    href="{{ route('signin') }}">
                    <p class="font-medium text-[#F5F9FF]">войти</p>
                    <img class="p-2 rounded-lg bg-[#FFD700]" src="{{ asset('ico/arrow_link.svg') }}" alt="войти">
                </a>
            @endguest
            @auth
                <a class="smart-order-btn hover:opacity-70" href="{{route('cart.index')}}">
                    <img src="{{asset('ico/booking.png')}}" alt="">
                </a>
                <a class="hover:opacity-70" href="{{ route('profile') }}">
                    <img class="w-10 h-10 border border-[#0000001a] object-cover object-center rounded-full"
                        src="{{ asset('storage/' . Auth::user()->image) }}" alt="">
                </a>
            @endauth
        </div>
        <div class="burger-btn">
            <img onclick="openMenu()" class="cursor-pointer" src="{{ asset('ico/burger.png') }}" alt="">
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
                class="bg-[#1B1E4A] text-[#F5F9FF] px-5 py-2 rounded-xl flex items-center gap-2 w-fit">
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