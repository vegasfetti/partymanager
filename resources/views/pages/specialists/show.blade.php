@extends('layouts.app')

@section('title', \Illuminate\Support\Str::limit($specialist->title, 47) . '... | ПАТИМЕНЕДЖЕР')
@section('description', \Illuminate\Support\Str::limit($specialist->description, 150, '...'))

@section('content')

    @include('particals.header')

    <!-- Masonry -->
    @push('app-links')
        <script src="https://unpkg.com/imagesloaded@4/imagesloaded.pkgd.min.js"></script>
        <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
    @endpush


    <main class="container">
        <!-- breadcrumbs -->
        <div class="breadcrumbs hidden md:flex gap-3.5 pt-10 pb-5 flex-wrap">
            <a class="opacity-50" href="{{ route('main') }}">Главная</a>
            <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="">
            <a class="opacity-50" href="{{ route('specialists') }}">Специалисты</a>
            <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="">
            <a class="opacity-50" href="{{ route('specialists.byCategory', $specialist->category->slug) }}">{{
        $specialist->category->name }}</a>
            <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="">
            <a class="opacity-50"
                href="{{ route('specialists.bySubcategory', [$specialist->category->slug, $specialist->subcategory->slug]) }}">{{
        $specialist->subcategory->name }}</a>
            <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="">
            <a class="truncate w-sm">{{ $specialist->title }}</a>
        </div>
















        <!-- specialists -->
        <div class="grid grid-cols-1 sm:grid-cols-[45%_55%] lg:grid-cols-2 gap-5 specialist pt-5 md:pt-0">

            <!-- Swiper + фото -->
            <div class="specialist-swiper-container order-1 sm:order-none">
                <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                    class="relative shadow swiper one-card-swiper2 rounded-2xl">

                    @if ($specialist->video_link)
                        <a href="{{ route('away') }}?to={{ $specialist->video_link }}"
                            class="absolute bottom-2.5 right-2.5 z-10 w-8 h-8 sm:w-10 sm:h-10 bg-[#F5F9FF] rounded-full flex items-center justify-center">
                            <img src="{{ asset('ico/play-dark.png') }}" alt="" class="w-3.5 sm:w-4">
                        </a>
                    @endif

                    @if($specialist->status == 'on_moderation')
                        <p
                            class="absolute top-2 left-0 z-10 bg-[#ffffffa6] text-gray-600 font-semibold text-lg sm:text-xl lg:text-2xl rounded-[0px_10px_10px_0px] px-3 sm:px-5 py-1 sm:py-2">
                            Проверяется</p>
                    @endif

                    <div class="swiper-wrapper">
                        @foreach ($specialist->specImages as $image)
                            <div class="swiper-slide">
                                <img class="object-cover object-center w-full aspect-square {{ $specialist->status == 'on_moderation' ? 'opacity-40' : '' }}"
                                    src="{{ asset('storage/' . $image->image) }}" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Превью -->
                <div thumbsSlider="" class="swiper one-card-swiper mt-2.5">
                    <div class="swiper-wrapper">
                        @foreach ($specialist->specImages as $image)
                            <div class="swiper-slide">
                                <img class="object-cover object-center w-full aspect-square {{ $specialist->status == 'on_moderation' ? 'opacity-40' : '' }}"
                                    src="{{ asset('storage/' . $image->image) }}" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Информация -->
            <div
                class="specialist__info {{ $specialist->status == 'on_moderation' ? 'opacity-40' : '' }} order-2 sm:order-none">
                <!-- Категории -->
                <div class="specialist__info--cats mb-4 sm:mb-5 flex gap-2.5 flex-wrap">
                    <a class="bg-[#F5F9FF] px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl border border-[#0000001a] opacity-50 hover:opacity-100 text-sm sm:text-sm"
                        href="{{ route('specialists.byCategory', $specialist->category->slug) }}">
                        {{ $specialist->category->name }}
                    </a>
                    <a class="bg-[#F5F9FF] px-4 sm:px-5 py-2 sm:py-2.5 rounded-xl border border-[#0000001a] opacity-50 hover:opacity-100 text-sm sm:text-sm"
                        href="{{ route('specialists.bySubcategory', [$specialist->category->slug, $specialist->subcategory->slug]) }}">
                        {{ $specialist->subcategory->name }}
                    </a>
                </div>

                <!-- Заголовок -->

                <h1
                    class="text-lg sm:text-xl relative lg:text-2xl font-semibold overflow-hidden w-full break-words line-clamp-3">
                    {{ $specialist->title }}
                </h1>


                <!-- Цена -->
                <p class="font-bold text-2xl sm:text-3xl lg:text-4xl pt-2.5">
                    {{ number_format($specialist->price, 0, '', ' ') }} ₽/{{ $specialist->getPriceTypeLabel() }}
                </p>

                <!-- Кнопки -->
                @if($specialist->status == 'verify')
                    <div class="specialist__btns flex flex-col sm:flex-row gap-3.5 pt-2.5">
                        <form method="post" action="{{ route('cart.add') }}" class="w-full sm:w-auto">
                            @csrf
                            <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">
                            <button
                                class="bg-[#FFD700] w-full sm:w-auto px-5 sm:px-6 py-3 rounded-xl border border-[#0000001a] font-semibold flex gap-2.5 items-center justify-center cursor-pointer">
                                <p>Добавить в бронь</p>
                            </button>
                        </form>
                        <p onclick="openOrderModal()"
                            class="bg-[#F5F9FF] w-full sm:w-auto px-5 sm:px-6 py-3 rounded-xl border border-[#0000001a] font-semibold cursor-pointer text-center">
                            Связаться со мной
                        </p>
                    </div>
                @endif

                <!-- Аккордеоны -->
                <div class="specialist__accardeons mt-4 sm:mt-5 flex flex-col gap-2.5 text-sm sm:text-base">
                    <!-- Описание -->
                    <div
                        class="spec__accardeon relative px-4 sm:px-5 py-3 sm:py-4 border border-[#0000001a] rounded-xl cursor-pointer spec__accardeon--active">
                        <div class="flex items-center justify-between spec__speaccardeon--btn">
                            <p class="text-lg sm:text-xl font-semibold opacity-50">Описание</p>
                            <img class="p-1.5 sm:p-2 bg-black rounded-full opacity-50"
                                src="{{ asset('ico/plus-white.png') }}" alt="">
                        </div>
                        <div class="spec__speaccardeon--content mt-2.5 hidden">
                            <p class="opacity-75 break-words line-clamp-6" id="description-text">
                                {!! nl2br(e($specialist->description)) !!}
                            </p>
                            <button id="read-more-btn"
                                class="mt-2 text-[#FFD700] hover:underline text-sm font-medium hidden cursor-pointer">
                                Читать дальше
                            </button>
                        </div>
                    </div>

                    <!-- Контакты -->
                    <div
                        class="spec__accardeon px-4 sm:px-5 py-3 sm:py-4 border border-[#0000001a] rounded-xl cursor-pointer">
                        <div class="flex items-center justify-between spec__speaccardeon--btn">
                            <p class="text-lg sm:text-xl font-semibold opacity-50">Контакты</p>
                            <img class="p-1.5 sm:p-2 bg-black rounded-full opacity-50"
                                src="{{ asset('ico/plus-white.png') }}" alt="">
                        </div>
                        <div class="spec__speaccardeon--content mt-2.5 hidden">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mt-3 sm:mt-5">
                                <div class="flex flex-col gap-3 sm:gap-5">
                                    @if ($specialist->phone)
                                        <a class="flex items-center gap-2.5 opacity-70 hover:opacity-100"
                                            href="tel:{{ $specialist->phone }}">
                                            <img src="{{ asset('ico/phone.png') }}" alt="">
                                            <p class="line-clamp-1">{{ $specialist->phone }}</p>
                                        </a>
                                    @endif
                                    @if ($specialist->email)
                                        <a class="flex items-center gap-2.5 opacity-70 hover:opacity-100"
                                            href="mailto:{{ $specialist->email }}">
                                            <img src="{{ asset('ico/mail.png') }}" alt="">
                                            <p class="line-clamp-1">{{ $specialist->email }}</p>
                                        </a>
                                    @endif
                                    <a class="flex items-center gap-2.5 opacity-70 hover:opacity-100"
                                        href="{{ route('set.city', ['slug' => $specialist->city->slug]) }}">
                                        <img src="{{ asset('ico/location.png') }}" alt="">
                                        <p class="line-clamp-1">г. {{ $specialist->city->name }}</p>
                                    </a>
                                </div>
                                <div class="flex flex-col gap-3 sm:gap-5">
                                    @if ($specialist->vkontacte)
                                        <a class="flex items-center gap-2.5 opacity-70 hover:opacity-100"
                                            href="{{ route('away') . '?to=' . $specialist->vkontacte }}">
                                            <img src="{{ asset('ico/vk.png') }}" alt="">
                                            <p class="line-clamp-1">{{ $specialist->vkontacte }}</p>
                                        </a>
                                    @endif
                                    @if ($specialist->telegram)
                                        <a class="flex items-center gap-2.5 opacity-70 hover:opacity-100"
                                            href="{{ route('away') . '?to=' . $specialist->telegram }}">
                                            <img src="{{ asset('ico/tg.png') }}" alt="">
                                            <p class="line-clamp-1">{{ $specialist->telegram }}</p>
                                        </a>
                                    @endif
                                    @if ($specialist->website)
                                        <a class="flex items-center gap-2.5 opacity-70 hover:opacity-100"
                                            href="{{ route('away') . '?to=' . $specialist->website }}">
                                            <img src="{{ asset('ico/browser.png') }}" alt="">
                                            <p class="line-clamp-1">{{ $specialist->website }}</p>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div
                                class="contact_alert flex items-center gap-2.5 mt-3 sm:mt-5 text-sm bg-[#F0F4FA] py-2 px-4 border border-[#0000000a] rounded-xl">
                                <div
                                    class="flex items-center justify-center bg-[#FFD700] min-w-7 min-h-7 sm:min-w-8 sm:min-h-8 rounded-full border border-[#0000001a]">
                                    <p class="text-[#000] font-bold">!</p>
                                </div>
                                <p>Самостоятельно связываясь со специалистом, не забудьте сказать, что нашли его на
                                    ПАТИМЕНЕДЖЕР</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ценообразование -->
                    <div
                        class="spec__accardeon px-4 sm:px-5 py-3 sm:py-4 border border-[#0000001a] rounded-xl cursor-pointer">
                        <div class="flex items-center justify-between spec__speaccardeon--btn">
                            <p class="text-lg sm:text-xl font-semibold opacity-50">Ценообразование</p>
                            <img class="p-1.5 sm:p-2 bg-black rounded-full opacity-50"
                                src="{{ asset('ico/plus-white.png') }}" alt="">
                        </div>
                        <div class="spec__speaccardeon--content mt-2.5 hidden">
                            <p class="opacity-75 break-words line-clamp-6" data-description>
                                {!! nl2br(e($specialist->price_text)) !!}
                            </p>
                            <button data-readmore
                                class="mt-2 cursoe-pointer text-[#FFD700] hover:underline text-sm font-medium hidden cursor-pointer">
                                Читать дальше
                            </button>
                        </div>
                    </div>

                    <!-- Инфо -->
                    <div
                        class="spec__accardeon px-4 sm:px-5 py-3 sm:py-4 border border-[#0000001a] rounded-xl cursor-pointer">
                        <div class="flex items-center justify-between spec__speaccardeon--btn">
                            <p class="text-lg sm:text-xl font-semibold opacity-50">Инфо</p>
                            <img class="p-1.5 sm:p-2 bg-black rounded-full opacity-50"
                                src="{{ asset('ico/plus-white.png') }}" alt="">
                        </div>
                        <div class="spec__speaccardeon--content mt-2.5 hidden">
                            <div class="flex flex-wrap gap-1 sm:gap-2.5 text-sm sm:text-sm opacity-75">
                                <p>Работа по договору: {{ $specialist->getIsContractLabel() }}</p>
                                <p>•</p>
                                <p>Тип исполнителя: {{ $specialist->getSubjectTypeLabel() }}</p>
                                <p>•</p>
                                <p>Опыт работы: {{ $specialist->getExperienceLabel() }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>




        @if ($specialist->services->count() > 0)

            <!-- title -->
            <div class="{{ $specialist->status == 'on_moderation' ? 'opacity-40' : '' }}">
                <div class="pb-10 big-title sm:pb-16 sm:pt-16 mt-20">
                    <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Доп. услуги</h2>
                    <p class="mt-2 text-sm opacity-50 sm:text-base">Дополнительные услуги исполнителя</p>
                </div>
            </div>


            <div class="services-container relative w-full {{ $specialist->status == 'on_moderation' ? 'opacity-40' : '' }}">
                @if ($specialist->services->count() > 1)

                    <!-- Кнопка "Назад" -->
                    <button
                        class="cursor-pointer scroll-btn absolute -left-5 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-md transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                @endif

                <!-- Область с карточками -->
                <div class="services w-full overflow-x-auto scroll-smooth snap-x snap-mandatory no-scrollbar py-2">
                    <div class="inline-flex gap-2.5 pl-4 pr-4">
                        <!-- Карточка 1 -->
                        @foreach ($specialist->services as $service)
                            <div class="service w-[300px] flex-shrink-0 aspect-square border border-[#0000001a] rounded-xl relative overflow-hidden snap-start"
                                data-title="{{ $service->title }}"
                                data-price="от {{ number_format($service->price, 0, ' ', ' ') }} ₽"
                                data-description="{{ $service->description }}"
                                data-image="{{ asset('storage/' . $service->image) }}">
                                <img class="w-full h-full object-cover object-center filter brightness-50"
                                    src="{{ asset('storage/' . $service->image) }}" alt="Фото"
                                    srcset="{{ asset('storage/' . $service->image) }}') }}" alt="">
                                <div class="service-text absolute flex gap-1.5 flex-col p-3.5 bottom-0 left-0 w-full">
                                    <span class="font-normal text-xl text-[#f5f9ff]">от
                                        {{ number_format($service->price, 0, ' ', ' ') }} ₽</span>
                                    <p class="font-semibold text-xl text-[#f5f9ff] break-words">{{ $service->title }}</p>
                                </div>
                                @if(Auth::check() && $service->specialist_id == $specialist->id && $specialist->user_id == Auth::id())
                                    <form
                                        class="absolute top-2.5 right-2.5 bg-[#0000004d] p-1 rounded-full flex items-center justify-center"
                                        action="{{ route('services.destroy', $service->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="cursor-pointer" onclick="return confirm('Удалить эту услугу?')">
                                            <img src="{{ asset('ico/trash-white.png') }}" alt="">
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>

                @if ($specialist->services->count() > 1)
                        <!-- Кнопка "Вперед" -->
                        <button
                            class="cursor-pointer scroll-btn absolute -right-5 top-1/2 -translate-y-1/2 z-10 bg-white/80 hover:bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow-md transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                @endif



            <!-- Модальное окно -->
            <div class="modal-overlay" id="modalOverlay">
                <div class="modal-window">
                    <span
                        class="modal-close  opacity-90 hover:opacity-100 px-3.5 py- bg-[#f5f9ff] border border-[#0000001a] rounded-full"
                        id="modalClose">&times;</span>
                    <img id="modalImage" src="" alt="" class="modal-image w-full aspect-square object-cover object-center">
                    <h2 class="font-semibold text:xl sm:text-2xl text-[#000]" id="modalTitle"></h2>
                    <p id="modalPrice" class="modal-price"></p>
                    <p id="modalDescription" class="modal-description text-sm smtext-base"></p>
                </div>
            </div>


        @endif




        @if ($specialist->skills || $specialist->equipment || (($specialist->languages !== 'Русский') && $specialist->languages !== null))

            <!-- title -->
            <div
                class="pt-20 pb-10 big-title sm:pb-16 sm:pt-16 {{ $specialist->status == 'on_moderation' ? 'opacity-40' : '' }}">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Дополнительно</h2>
                <p class="mt-2 text-sm opacity-50 sm:text-base">Поможет определится в выбором</p>
            </div>
        @endif





        <div class="dop-info flex flex-col gap-5 mb-10 {{ $specialist->status == 'on_moderation' ? 'opacity-40' : '' }}">

            <!-- Навыки -->
            @if ($specialist->skills)
                <div class="flex flex-col gap-2.5">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">Навыки или преимущества:</p>
                    <div class="flex flex-wrap gap-2 mt-1 tag-list opacity-90">
                        @php
                            $skills = explode('|', $specialist->skills);
                        @endphp
                        @foreach ($skills as $skill)
                            <div
                                class="bg-[#f0f4fa] px-3 sm:px-5 py-1 sm:py-1.5 rounded-xl flex items-center gap-1 sm:gap-2 text-sm sm:text-md border border-[#0000001a]">
                                <span class="font-medium text-gray-700">{{ $skill }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Оборудование -->
            @if ($specialist->equipment)
                <div class="flex flex-col gap-2.5">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">Оборудование:</p>
                    <div class="flex flex-wrap gap-2 mt-1 tag-list opacity-90">
                        @php
                            $equipment = explode('|', $specialist->equipment);
                        @endphp
                        @foreach ($equipment as $el)
                            <div
                                class="bg-[#f0f4fa] px-3 sm:px-5 py-1 sm:py-1.5 rounded-xl flex items-center gap-1 sm:gap-2 text-sm sm:text-md border border-[#0000001a]">
                                <span class="font-medium text-gray-700">{{ $el }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Языки -->
            @if ($specialist->languages && $specialist->languages !== 'Русский')
                <div class="flex flex-col gap-2.5">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">Языки:</p>
                    <div class="flex flex-wrap gap-2 mt-1 tag-list opacity-90">
                        @php
                            $languages = explode('|', $specialist->languages);
                        @endphp
                        @foreach ($languages as $language)
                            <div
                                class="bg-[#f0f4fa] px-3 sm:px-5 py-1 sm:py-1.5 rounded-xl flex items-center gap-1 sm:gap-2 text-sm sm:text-md border border-[#0000001a]">
                                <span class="font-medium text-gray-700">{{ $language }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
































        @php
            $hasReviews = $specialist->reviews->count() > 0;
            $isAuth = auth()->check();
            $on_moderation = $specialist->status == 'on_moderation';
        @endphp
        @if(($hasReviews || $isAuth) && !$on_moderation)
            <!-- title -->
            <div class="pb-8 pt-20 big-title sm:pb-16 sm:pt-16">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Рейтиг и Отзывы</h2>
                <p class="mt-2 text-sm opacity-50 sm:text-base">Рейтинг и отзывы исполнителя</p>
            </div>



            <!-- Rating and Reviews -->
            <div class="grid w-full gap-6 grid-cols-1 md:grid-cols-2 items-center">
                @if ($specialist->reviews->count() > 0)
                    @php
                        $reviews = $specialist->reviews;
                        $total = $reviews->count();

                        $average = $total > 0 ? number_format($reviews->avg('rating'), 1) : '0.0';

                        $ratingsCount = [
                            5 => $reviews->where('rating', 5)->count(),
                            4 => $reviews->where('rating', 4)->count(),
                            3 => $reviews->where('rating', 3)->count(),
                            2 => $reviews->where('rating', 2)->count(),
                            1 => $reviews->where('rating', 1)->count(),
                        ];
                    @endphp

                    <!-- Rating -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start justify-center sm:justify-center gap-5">
                        <h3 class="flex items-end gap-1">
                            <p class="text-[#000] text-6xl sm:text-7xl md:text-8xl lg:text-9xl font-semibold leading-none">
                                {{ $average }}
                            </p>
                            <p class="text-xl sm:text-2xl md:text-3xl font-semibold opacity-50 whitespace-nowrap">/ 5</p>
                        </h3>

                        <!-- rating stat -->
                        <div class="rating__stat w-full sm:w-1/2 flex flex-col gap-2 sm:gap-3.5">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php
                                    $count = $ratingsCount[$star];
                                    $percent = $total > 0 ? ($count / $total) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <img class="w-4 h-4 sm:w-5 sm:h-5" src="{{ asset('ico/star.svg') }}" alt="">
                                    <p class="font-semibold text-sm sm:text-base">{{ $star }}</p>
                                    <div class="bg-[#0000001a] rounded-full w-full h-2 sm:h-3">
                                        <div class="h-full bg-[#FFD700] border border-[#0000001a] rounded-full"
                                            style="width: {{ $percent == 0 ? 3 : $percent }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                @else
                    <div class="flex flex-col items-center justify-center h-full opacity-40 text-center">
                        <p class="text-2xl sm:text-3xl font-semibold">Отзывов нет</p>
                        <span class="text-sm sm:text-base text-[#000]">Будьте первым кто его оставит!</span>
                    </div>
                @endif

                <!-- Reviews -->
                <div class="relative">
                    <div class="swiper reviews-swiper">
                        <div class="swiper-wrapper">
                            <!-- form -->
                            @if(Auth::check())
                                <form method="post" action="{{ route('reviews.create') }}"
                                    class="swiper-slide send_review p-4 sm:p-5 border border-[#0000001a] bg-[#F0F4FA] rounded-xl">
                                    <div class="review_author flex items-center gap-2.5">
                                        <img class="w-10 h-10 object-cover rounded-full border border-[#0000001a]"
                                            src="{{ asset('storage/' . Auth::user()->image) }}" alt="">
                                        <div>
                                            <p class="font-semibold text-sm sm:text-base line-clamp-1 break-all">
                                                {{ Auth::user()->name }}
                                            </p>
                                            <div class="flex gap-1 stars-form">
                                                @for($i = 0; $i < 5; $i++)
                                                    <img class="cursor-pointer w-4 h-4 sm:w-5 sm:h-5" src="{{ asset('ico/star.svg') }}"
                                                        alt="">
                                                @endfor
                                            </div>
                                            <input type="hidden" name="rating" value="5">
                                        </div>
                                    </div>
                                    <div class="mt-4 flex flex-col gap-2.5">
                                        <input
                                            class="w-full border border-[#0000001a] rounded-xl p-2 bg-[#F5F9FF] outline-0 text-sm sm:text-base"
                                            placeholder="Заголовок" type="text" name="title" value="{{ old('title') }}">
                                        @error('title')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                        <textarea
                                            class="w-full border border-[#0000001a] rounded-xl p-2 bg-[#F5F9FF] resize-none outline-0 text-sm sm:text-base"
                                            placeholder="Расскажите, как всё прошло" name="text">{{ old('text') }}</textarea>
                                        @error('text')
                                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                        @enderror
                                        <button
                                            class="bg-[#FFD700] px-3 sm:px-4 py-2 rounded-xl cursor-pointer font-medium border border-[#0000001a] text-sm sm:text-base">Отправить</button>
                                    </div>
                                    @csrf
                                    <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">
                                </form>
                            @endif

                            <!-- reviews_card -->
                            @foreach ($specialist->reviews as $review)
                                <div class="swiper-slide p-4 sm:p-5 border border-[#0000001a] bg-[#F0F4FA] rounded-xl">
                                    <div class="review_author flex items-center gap-2.5">
                                        <img class="w-10 h-10 rounded-full object-cover"
                                            src="{{ asset('storage/' . $review->user->image) }}" alt="">
                                        <div>
                                            <p class="font-semibold text-sm sm:text-base">{{ $review->user->name }}</p>
                                            <div class="flex gap-1 stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <img class="w-4 h-4 sm:w-5 sm:h-5 {{ $review->rating >= $i ? 'opacity-100' : 'opacity-20' }}"
                                                        src="{{ asset('ico/star.svg') }}" alt="">
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-4 font-semibold line-clamp-1 text-sm sm:text-base">{{ $review->title }}</p>
                                    <p class="line-clamp-5 mt-2.5 opacity-70 text-sm sm:text-base">{{ $review->text }}</p>
                                    <p class="opacity-50 mt-2 text-sm sm:text-sm">
                                        {{ date('d.m.Y', strtotime($review->created_at)) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- nav buttons -->
                    <div class="rew_swp_btns absolute top-2 sm:top-5 right-2 sm:right-5 flex gap-2.5 z-10">
                        <div
                            class="reviews-swiper-button-prev cursor-pointer flex w-6 h-6 sm:w-6 sm:h-6 items-center justify-center bg-[#1C1F4C] rounded-full">
                            <img class="w-1 sm:w-2 object-cover" src="{{ asset('ico/line.png') }}" alt="">
                        </div>
                        <div
                            class="reviews-swiper-button-next cursor-pointer flex w-6 h-6 sm:w-6 sm:h-6 items-center justify-center bg-[#1C1F4C] rounded-full">
                            <img class="rotate-180 w-1 sm:w-2" src="{{ asset('ico/line.png') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>

        @endif





























        @if($specialist->portfolios->count() > 0)
            {!! $specialist->status == 'on_moderation' ? '<div class="opacity-40">' : '' !!}
            <!-- title -->
            <div class="pb-8 pt-20 big-title sm:pb-16 sm:pt-16">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Портфолио</h2>
                <p class="mt-2 text-sm opacity-50 sm:text-base">Портфолио исполнителя</p>
            </div>
            {!! $specialist->status == 'on_moderation' ? '</div>' : '' !!}
            <!-- portfolio -->
            <div class="portfolio-grid {{ $specialist->status == 'on_moderation' ? 'opacity-40' : '' }}">
                <!-- Sizer для расчёта ширины колонки Masonry -->
                <div
                    class="grid-sizer
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         w-full
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         sm:w-[calc((100%_-_20px)/2)]
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         md:w-[calc((100%_-_40px)/3)]
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         lg:w-[calc((100%_-_60px)/4)]
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                         xl:w-[calc((100%_-_100px)/6)]">
                </div>

                @foreach ($specialist->portfolios as $p)
                    <div
                        class="grid-item
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       w-full
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       sm:w-[calc((100%_-_20px)/2)]
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       md:w-[calc((100%_-_40px)/3)]
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       lg:w-[calc((100%_-_60px)/4)]
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       xl:w-[calc((100%_-_100px)/6)]
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       mb-5">
                        <img src="{{ asset('storage/' . $p->image) }}" alt="Portfolio image"
                            class="block w-full h-auto rounded-xl portfolio-img">
                    </div>
                @endforeach
            </div>



        @endif













        @auth
            @if (auth()->id() === $specialist->user_id)
                <div class="flex flex-col gap-2.5 my-5">

                    <div class="flex gap-2.5 flex-wrap">
                        @if($specialist->promoted_until < \Carbon\Carbon::now())
                            @if ($specialist->status == 'verify')

                                <form method="post" action="{{route('request.promo', $specialist->id)}}">
                                    @csrf
                                    <button
                                        class="cursor-pointer px-4 py-2 bg-yellow-400 border border-[#0000001a] text-black rounded-xl font-semibold">👑
                                        Выдвенуть в топ</button>
                                </form>
                            @endif
                        @endif

                        <a href="{{ route('specialists.edit', $specialist->id) }}"
                            class="px-4 py-2 bg-[#f0f4fb] border border-[#0000001a] text-black rounded-xl font-semibold">
                            ✏️ Редактировать карточку
                        </a>
                    </div>

                    <div class="flex gap-2.5 flex-wrap">
                        <a class="px-4 py-2 bg-[#f0f4fb] border border-[#0000001a] text-black rounded-xl font-semibold"
                            href="{{route('service.create', $specialist->id)}}">➕
                            Добавить услугу</a>
                        @if ($specialist->documents_verified_at == null)

                            @if ($specialist->status == 'verify')
                                <form method="post" action="{{route('request.verification', $specialist->id)}}">
                                    @csrf
                                    <button
                                        class="cursor-pointer px-4 py-2 bg-[#f0f4fb] border border-[#0000001a] text-black rounded-xl font-semibold">🔎
                                        Запросить проверку</button>
                                </form>
                            @endif
                        @endif

                    </div>



                </div>
            @endif
        @endauth



        <!-- spec info -->
        <div class="flex flex-col gap-5 mb-20 spec_info">
            @if($specialist->documents_verified_at != null)
                <div class="shild flex gap-2.5 items-center opacity-50">
                    <img class="w-5 h-5 object-contain opacity-90" src="{{ asset('ico/shild.png') }}"
                        alt="Исполнитель проверен">
                    <p class="text-sm font-semibold">Исполнитель проверен</p>
                </div>
            @endif
            <div class=" flex gap-2.5 items-center opacity-50">
                <img class="w-5" src="{{ asset('ico/clock.png') }}" alt="">
                <p class="text-sm font-semibold">Обновлено: {{ date('d.m.Y', strtotime($specialist->updated_at)) }}</p>
            </div>
            <div class=" flex gap-2.5 items-center opacity-50">
                <img class="w-5" src="{{ asset('ico/eye.png') }}" alt="">
                <p class="text-sm font-semibold">Просмотров: {{ count($specialist->visits) }}</p>
            </div>

        </div>



















        <!-- Модальное окно для просмотра -->
        <div id="portfolio-modal"
            class="fixed inset-0 bg-[#00000080] items-center justify-center z-1000 hidden cursor-pointer">
            <img id="portfolio-modal-img" src="" alt="" class="max-w-[90vw] max-h-[90vh] rounded-2xl shadow-2xl">
        </div>


        {{-- order modal --}}
        <div class="order-modal hidden fixed inset-0 bg-[#00000080] z-1000 items-center justify-center">
            <form
                class="order-modal-form max-w-[99vw] max-h-[95vh] bg-[#f0f4fa] border border-[#0000001a] rounded-2xl absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 p-10"
                action="{{route('sendOrder')}}" method="post">
                @csrf
                <img onclick="closeOrderModal()" class="absolute top-5 right-5 cursor-pointer opacity-50 hover:opacity-100"
                    src="{{ asset('ico/close.png') }}" alt="" id="order-modal__close">

                <h1 class="font-semibold text-4xl">Связаться со мной</h1>
                <p class="font-light text-lg opacity-50 pt-2.5 pb-7.5">Попросить исполнения связаться со мной</p>

                <div class="flex flex-col gap-2.5">
                    <p class="opacity-50 font-semibold"><span class="text-[#c2a500] font-semibold">*</span> Номер:</p>
                    <input name="phone" id="phone" placeholder="Номер телефона для связи"
                        class="w-full bg-[#f5f9ff] border border-[#0000001a] rounded-xl px-4 py-3" type="text">
                    @if (isset($errors) && $errors->has('phone'))
                        <p class="order-error text-red-500 text-sm">{{ $errors->first('phone') }}</p>
                    @endif
                </div>
                <div class="flex flex-col gap-2.5 mt-5">
                    <p class="opacity-50 font-semibold">Комментарий:</p>
                    <textarea name="comment" placeholder="Комментарий для исполнения"
                        class="w-full bg-[#f5f9ff] border border-[#0000001a] rounded-xl px-4 py-3 resize-none"
                        type="text"></textarea>
                    @if (isset($errors) && $errors->has('comment'))
                        <p class="order-error text-red-500 text-sm">{{ $errors->first('comment') }}</p>
                    @endif
                </div>
                <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">

                <button
                    class="waiting mt-5 bg-[#FFD700] px-4 py-2 rounded-xl cursor-pointer font-semibold w-full">Отправить</button>
            </form>

        </div>


























        <div class="specialists__text">
            <!-- title -->
            <div class="pb-10 big-title">
                <h1 class="text-4xl font-semibold">Как выбрать исполнителя?</h1>
                <p class="opacity-50">Вот несколько простых правил, которые помогут выбрать исполнителя </p>
            </div>

            <p>Выбор подходящего исполнителя — ключ к успешному мероприятию. На платформе Party Manager вы найдёте множество
                профессионалов, готовых воплотить ваши идеи в жизнь. Чтобы сделать правильный выбор, рекомендуем обратить
                внимание на несколько важных моментов:</p>

            <div class="flex flex-col gap-5 mt-8 specialists__text__rules mb-36">
                <div class="specialists__text__rule">
                    <h3 class="text-lg font-semibold"> Читайте отзывы</h3>
                    <p>Изучите впечатления других клиентов. Честные отзывы помогут понять, как исполнитель работает,
                        соблюдает ли сроки и насколько клиенты довольны результатом.</p>
                </div>
                <div class="specialists__text__rule">
                    <h3 class="text-lg font-semibold"> Смотрите портфолио</h3>
                    <p>Обязательно посмотрите фотографии или видео работ исполнителя. Так вы оцените стиль, качество
                        исполнения и убедитесь, подходит ли он именно вам.</p>
                </div>
                <div class="specialists__text__rule">
                    <h3 class="text-lg font-semibold">Общайтесь напрямую</h3>
                    <p>Не стесняйтесь задавать вопросы: о стоимости услуг, сроках выполнения, деталях организации. Хороший
                        исполнитель всегда открыт к диалогу и готов обсудить все нюансы.</p>
                </div>
                <div class="specialists__text__rule">
                    <h3 class="text-lg font-semibold">Сравнивайте предложения</h3>
                    <p>На платформе большое количество исполнителей. Вы можете сравнить их условия, цены и предложения. Так
                        вы найдёте лучшее соотношение цены и качества.</p>
                </div>
                <div class="specialists__text__rule">
                    <h3 class="text-lg font-semibold">Обратите внимание на рейтинг</h3>
                    <p>Рейтинг исполнителя показывает его надёжность и профессионализм. Высокий рейтинг — хороший знак, что
                        люди доверяют этому специалисту.</p>
                </div>
                <div class="specialists__text__rule">
                    <h3 class="text-lg font-semibold"> Исполнитель проверен</h3>
                    <p>Если вы видите плажку "Исполнитель проверен", то это означает, что мы проверили исполнения.</p>
                </div>
            </div>
        </div>
    </main>



    @push('app-scripts')
        <script>


            // one card
            if (document.querySelector('.one-card-swiper')) {
                var thumbSwiper = new Swiper(".one-card-swiper", {
                    direction: 'horizontal',
                    loop: false,
                    spaceBetween: 5,
                    slidesPerView: 9,
                    freeMode: true,
                    watchSlidesProgress: true,
                });

                var mainSwiper = new Swiper(".one-card-swiper2", {
                    loop: false,
                    spaceBetween: 10,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    thumbs: {
                        swiper: thumbSwiper,
                    },
                });

                mainSwiper.on('slideChange', () => {
                    const centerPos = Math.floor(thumbSwiper.params.slidesPerView / 2);
                    const targetThumbIndex = mainSwiper.activeIndex - centerPos;
                    thumbSwiper.slideTo(Math.max(targetThumbIndex, 0));
                });

                const mainEl = document.querySelector('.one-card-swiper2');
                const realSlidesCount = mainSwiper.slides.length;

                mainEl.addEventListener('mousemove', (e) => {
                    if (realSlidesCount <= 1) return;

                    const rect = mainEl.getBoundingClientRect();
                    let x = e.clientX - rect.left;
                    x = Math.min(Math.max(x, 0), rect.width);
                    const percent = x / rect.width;

                    let targetIndex = Math.floor(percent * realSlidesCount);
                    if (targetIndex >= realSlidesCount) targetIndex = realSlidesCount - 1;

                    mainSwiper.slideTo(targetIndex);
                });
            }



            // spec__accardeon
            if (document.querySelector('.spec__accardeon')) {
                let accardeons = document.querySelectorAll('.spec__accardeon');

                accardeons.forEach(el => {
                    el.addEventListener('click', () => {
                        accardeons.forEach(item => {
                            item.classList.remove('spec__accardeon--active');
                        });
                        el.classList.add('spec__accardeon--active');
                    });
                });
            }



            // read more

            const text = document.getElementById("description-text");
            const btn = document.getElementById("read-more-btn");
            const LINES = 6;

            // Проверяем существование элементов БЕЗ return
            if (!text || !btn) {
                // Просто выходим из выполнения, но без оператора return
                console.log("Элементы не найдены");
            } else {
                // Весь остальной код помещаем внутрь else
                function checkLines() {
                    // убираем line-clamp для замера
                    text.classList.remove("line-clamp-6");

                    const cs = getComputedStyle(text);
                    let lh = parseFloat(cs.lineHeight);
                    if (isNaN(lh)) {
                        lh = parseFloat(cs.fontSize) * 1.2; // запасной вариант
                    }

                    const realHeight = text.scrollHeight;
                    const sixLinesHeight = lh * LINES;

                    // возвращаем clamp
                    text.classList.add("line-clamp-6");

                    if (realHeight > sixLinesHeight + 1) {
                        btn.classList.remove("hidden");
                    } else {
                        btn.classList.add("hidden");
                    }
                }

                // первый замер
                checkLines();

                // переключение
                btn.addEventListener("click", function (e) {
                    e.stopPropagation();
                    text.classList.toggle("line-clamp-6");
                    btn.textContent = text.classList.contains("line-clamp-6")
                        ? "Читать дальше"
                        : "Скрыть";
                });

                // пересчёт при ресайзе
                window.addEventListener("resize", checkLines);
            }

            // Второй блок кода (для множественных элементов)
            const LINES_SECOND = 6; // Переименовываем константу

            document.querySelectorAll("[data-description]").forEach(desc => {
                const btn = desc.parentElement.querySelector("[data-readmore]");
                const content = desc.closest(".spec__speaccardeon--content");

                // Проверяем наличие элементов
                if (!btn || !content) return; // Здесь return уже внутри функции forEach - это допустимо

                function measureAndToggleBtn() {
                    const wasHidden = content.classList.contains("hidden");

                    // временно раскрываем невидимо, чтобы получить реальные размеры
                    if (wasHidden) {
                        content.classList.remove("hidden");
                        content.style.visibility = "hidden";
                        content.style.position = "absolute";
                        content.style.zIndex = "-1";
                        content.style.display = "block";
                        content.style.width = content.parentElement.clientWidth + "px";
                    }

                    const hadClamp = desc.classList.contains("line-clamp-6");
                    if (hadClamp) desc.classList.remove("line-clamp-6");

                    const cs = getComputedStyle(desc);
                    let lh = parseFloat(cs.lineHeight);
                    if (isNaN(lh)) {
                        const fs = parseFloat(cs.fontSize) || 16;
                        lh = fs * 1.2; // запасной расчёт
                    }

                    const realHeight = desc.scrollHeight;
                    const sixLinesHeight = lh * LINES_SECOND;

                    // вернуть clamp
                    if (hadClamp) desc.classList.add("line-clamp-6");

                    // вернуть скрытие и стили
                    if (wasHidden) {
                        content.classList.add("hidden");
                        content.style.visibility = "";
                        content.style.position = "";
                        content.style.zIndex = "";
                        content.style.display = "";
                        content.style.width = "";
                    }

                    // показать кнопку только если текста реально больше 6 строк
                    if (realHeight > sixLinesHeight + 1) {
                        btn.classList.remove("hidden");
                    } else {
                        btn.classList.add("hidden");
                    }
                }

                // первичный расчёт
                measureAndToggleBtn();

                // переключатель
                btn.addEventListener("click", function (e) {
                    e.stopPropagation();
                    desc.classList.toggle("line-clamp-6");
                    this.textContent = desc.classList.contains("line-clamp-6") ? "Читать дальше" : "Скрыть";
                });

                // пересчёт при изменении ширины
                window.addEventListener("resize", measureAndToggleBtn);
            });


            // rewive slider
            if (document.querySelector('.reviews-swiper')) {
                var swiper = new Swiper(".reviews-swiper", {
                    navigation: {
                        nextEl: ".reviews-swiper-button-next",
                        prevEl: ".reviews-swiper-button-prev",
                    },
                    loop: true,
                    spaceBetween: 20,

                    simulateTouch: true,
                    keyboard: {
                        enabled: true,
                        onlyInViewport: true,
                    },
                });
            }

            //stars
            if (document.querySelector('.stars-form')) {
                document.querySelectorAll('.stars-form').forEach(function (starsContainer) {
                    const stars = starsContainer.querySelectorAll('img');
                    const input = starsContainer.parentElement.querySelector('input[name="rating"]');

                    stars.forEach((star, idx) => {
                        star.addEventListener('click', function () {
                            // Обновить value
                            input.value = idx + 1;

                            // Визуально подсветить выбранные звезды
                            stars.forEach((s, i) => {
                                s.style.opacity = i <= idx ? '1' : '0.2';
                            });
                        });
                    });
                });
            }

            //services
            if (document.querySelector('.services')) {

                const container = document.querySelector('.services');
                const prevBtn = document.querySelector('.scroll-btn:first-child');
                const nextBtn = document.querySelector('.scroll-btn:last-child');

                if (document.querySelector('.scroll-btn')) {

                    nextBtn.addEventListener('click', () => {
                        container.scrollBy({
                            left: 320, // Ширина карточки + gap
                            behavior: 'smooth'
                        });
                    });

                    prevBtn.addEventListener('click', () => {
                        container.scrollBy({
                            left: -320,
                            behavior: 'smooth'
                        });
                    });
                }
            }

            // modal
            if (document.querySelector('#modalOverlay')) {

                const modalOverlay = document.getElementById("modalOverlay");
                const modalClose = document.getElementById("modalClose");

                const modalImage = document.getElementById("modalImage");
                const modalTitle = document.getElementById("modalTitle");
                const modalPrice = document.getElementById("modalPrice");
                const modalDescription = document.getElementById("modalDescription");

                document.querySelectorAll(".service").forEach(card => {
                    card.addEventListener("click", (e) => {
                        if (e.target.closest("form")) {
                            return;
                        }

                        modalTitle.textContent = card.dataset.title;
                        modalPrice.textContent = card.dataset.price;
                        modalDescription.textContent = card.dataset.description;
                        modalImage.src = card.dataset.image;
                        modalImage.alt = card.dataset.title;

                        modalOverlay.style.display = "flex";
                    });
                });

                modalClose.addEventListener("click", () => {
                    modalOverlay.style.display = "none";
                });

                modalOverlay.addEventListener("click", (e) => {
                    if (e.target === modalOverlay) {
                        modalOverlay.style.display = "none";
                    }
                });

                document.addEventListener("keydown", (e) => {
                    if (e.key === "Escape") {
                        modalOverlay.style.display = "none";
                    }
                });
            }



            //portfolio
            if (document.querySelector('.portfolio-grid')) {
                const grid = document.querySelector('.portfolio-grid');

                // Подождать загрузки всех картинок
                imagesLoaded(grid, () => {
                    // Инициализация Masonry
                    const msnry = new Masonry(grid, {
                        itemSelector: '.grid-item',
                        columnWidth: '.grid-sizer',
                        gutter: 20,
                        percentPosition: true
                    });
                });

            }
            if (document.querySelector('#portfolio-modal')) {
                document.querySelectorAll('.portfolio-img').forEach(img => {
                    img.addEventListener('click', function () {
                        const modal = document.getElementById('portfolio-modal');
                        const modalImg = document.getElementById('portfolio-modal-img');
                        modalImg.src = this.src;
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');

                    });
                });

                document.getElementById('portfolio-modal').addEventListener('click', function (e) {
                    if (e.target === this) {
                        this.classList.add('hidden');
                        this.classList.remove('flex');
                        document.getElementById('portfolio-modal-img').src = '';
                    }
                });
            }





            //order
            if (document.querySelector('.order-modal')) {
                function openOrderModal() {
                    document.querySelector('.order-modal').classList.remove('hidden');
                    document.querySelector('.order-modal').classList.add('block');
                }

                function closeOrderModal() {
                    document.querySelector('.order-modal').classList.remove('block');
                    document.querySelector('.order-modal').classList.add('hidden');
                }

                let ordermodal = document.querySelector('.order-modal');
                let orderform = document.querySelector('.order-modal-form');
                ordermodal.addEventListener('click', function (e) {
                    if (e.target === ordermodal) {
                        closeOrderModal();
                    }
                });

                orderform.addEventListener('click', function (e) {
                    e.stopPropagation();
                });
            }

            if (document.querySelector('.order-error')) {
                openOrderModal();
            }
        </script>
    @endpush
    @include('particals.footer')

@endsection