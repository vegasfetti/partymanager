@extends('layouts.app')

@section('title', 'Все исполнители | ПАТИМЕНЕДЖЕР')
@section('description', 'Найдите лучших специалистов для вашего праздника в одном месте. Удобный поиск, фильтры по категориям, быстрый просмотр портфолио и отзывов. ПАТИМЕНЕДЖЕР — ваш надежный помощник в поиске исполнителей.')



@section('content')

    @include('particals.header')

    @push('app-links')
        <!-- nouislider -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    @endpush


    <main class="container">

        <!-- breadcrumbs -->
        <div class="breadcrumbs flex flex-wrap gap-3.5 pt-10 pb-5">
            <a class="opacity-50" href="{{ route('main') }}">Главная</a>
            <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="">

            <a class="{{ isset($activeCategory) ? 'opacity-50' : '' }}" href="{{ route('specialists') }}">Специалисты</a>

            @if (isset($activeCategory))
                <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="">
                <a class="{{ isset($activeSubcategory) ? 'opacity-50' : '' }}"
                    href="{{ route('specialists.byCategory', $activeCategory->slug) }}">
                    {{ $activeCategory->name }}
                </a>
            @endif

            @if (isset($activeSubcategory))
                <img src="{{ asset('ico/breadcrumbs.svg') }}" alt="">
                <a href="{{ route('specialists.bySubcategory', [$activeCategory->slug, $activeSubcategory->slug]) }}">
                    {{ $activeSubcategory->name }}
                </a>
            @endif
        </div>




        <button id="openFilter"
            class="bg-[#FFD700] fixed top-1/2 left-0 transform translate-y-1/2 rounded-[0px_10px_10px_0px] flex items-center gap-5 px-4 py-2 border border-[#0000001a] font-semibold text-[#1B1E4A] cursor-pointer">
            <p>Открыть фильтр</p>
            <img src="{{ asset('ico/filter.png') }}" alt="">
        </button>


        <div id="filterOverlay" class="filter-overlay hidden"></div>
        {{-- adaptve filtreation --}}
        <div id="filterModal" class="specialists__filtration__adaptive">
            <button id="closeFilter" class=" opacity-50 hover:opacity-100 cursor-pointer absolute top-5 right-5">
                <img class="p-3" alt="" src="{{ asset('ico/close.png') }}">
            </button>
            <div class="flex flex-col col-span-2 gap-5 mb-5 specialists__filtration__adaptive--container pt-2 relative">

                @foreach ($categories as $category)
                    <div class="specialists__filtration--el flex flex-col gap-2.5">

                        {{-- Название категории --}}
                        <a class="text-lg font-medium {{ isset($activeCategory) && $activeCategory->id == $category->id ? 'opacity-100' : 'opacity-50 hover:opacity-100' }}"
                            href="{{ route('specialists.byCategory', ['category' => $category->slug]) }}">
                            {{ $category->name }}
                        </a>

                        {{-- Подкатегории показываем только если категория активная --}}
                        @if (isset($activeCategory) && $activeCategory->id == $category->id)
                            @foreach ($category->subcategories as $subcategory)
                                <a class="opacity-50 hover:opacity-100 w-fit
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {{ isset($activeSubcategory) && $activeSubcategory->id == $subcategory->id ? 'opacity-100' : '' }}"
                                    href="{{ route('specialists.bySubcategory', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}">
                                    – {{ $subcategory->name }}
                                </a>
                            @endforeach
                        @endif

                    </div>
                @endforeach

                <div class="w-full h-[1px] bg-[#0000001a]"></div>

                <form class="flex flex-col gap-5" method="get" class="w-full" action="{{ url()->current() }}">


                    <!-- select -->
                    <div class="relative w-full flex flex-col gap-2.5">
                        <label class="font-semibold text-gray-400 text-md" for="sort">Сортировать:</label>
                        <div class="relative flex items-center">
                            <select name="sort" id="sort"
                                class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] text-gray-500 focus:outline-[#FFD700]">
                                <option class="text-lg" value="default" {{ !$sort ? 'selected' : '' }}>По умолчанию</option>
                                <option class="text-lg" value="popularity" {{ $sort == 'popularity' ? 'selected' : '' }}>По
                                    популярности</option>
                                <option class="text-lg" value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>По рейтингу
                                </option>
                                <option class="text-lg" value="price" {{ $sort == 'price' ? 'selected' : '' }}>По цене
                                </option>
                            </select>


                            <!-- Иконка стрелки, центрируемая через top-1/2 и -translate-y-1/2 -->
                            <div
                                class="absolute flex items-center transform -translate-y-1/2 pointer-events-none top-1/2 right-3">
                                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>


                    <!-- range -->
                    <div class="w-full nouislider-container">
                        <div class="mb-5" id="nouislider"></div>

                        <div class="grid grid-cols-2 gap-4">






                            <div class="w-full flex flex-col gap-2.5">
                                <label class="font-semibold text-gray-400 text-md" for="priceFromFormatted">Цена
                                    от:</label>
                                <input type="text" id="priceFromFormatted" placeholder="Цена от:"
                                    value="{{ number_format($minPrice, 0, '.', ' ') }}"
                                    class="text-[#1B1E4A] font-medium w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] focus:!border-gray-300">
                                <input type="hidden" id="priceFrom" name="min_price" value="{{ $minPrice }}">
                            </div>
                            <div class="w-full flex flex-col gap-2.5">
                                <label class="font-semibold text-gray-400 text-md" for="priceToFormatted">Цена
                                    до:</label>
                                <input type="text" id="priceToFormatted" placeholder="Цена до:"
                                    value="{{ number_format($maxPrice, 0, '.', ' ') }}"
                                    class="text-[#1B1E4A] font-medium w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] focus:!border-gray-300">
                                <input type="hidden" id="priceTo" name="max_price" value="{{ $maxPrice }}">
                            </div>
                        </div>

                    </div>

                    <!-- select -->
                    <div class="relative w-full flex flex-col gap-2.5">
                        <label class="font-semibold text-gray-400 text-md" for="experience">Опыт работы:</label>
                        <div class="relative flex items-center">
                            <select id="experience" name="experience"
                                class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] text-gray-500 focus:outline-[#FFD700]">
                                <option class="text-lg" value="default" class="" {{ !$exp ? 'selected disabled' : '' }}>Не
                                    важно</option>
                                <option class="text-lg" value="less_than_1" {{ $exp == 'less_than_1' ? 'selected' : '' }}
                                    class="">Менее
                                    1
                                    года
                                </option>
                                <option class="text-lg" value="1_3_years" {{ $exp == '1_3_years' ? 'selected' : '' }}
                                    class="">От
                                    1 до 3
                                    лет
                                </option>
                                <option class="text-lg" value="3_5_years" {{ $exp == '3_5_years' ? 'selected' : '' }}
                                    class="">От
                                    3 до 5
                                    лет
                                </option>
                                <option class="text-lg" value="more_than_5" {{ $exp == 'more_than_5' ? 'selected' : '' }}
                                    class="">Более
                                    5 лет
                                </option>
                            </select>
                            <!-- Иконка стрелки, центрируемая через top-1/2 и -translate-y-1/2 -->
                            <div
                                class="absolute flex items-center transform -translate-y-1/2 pointer-events-none top-1/2 right-3">
                                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>


                    <!-- select -->
                    <div class="relative w-full flex flex-col gap-2.5">
                        <label class="font-semibold text-gray-400 text-md" for="subject_type">Вид занятости</label>
                        <div class="relative flex items-center">
                            <select name="subject_type" id="subject_type"
                                class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] text-gray-500 focus:outline-[#FFD700]">
                                <option class="text-lg" value="default" {{ !$subject_type ? 'selected disabled' : '' }}>
                                    Любой</option>
                                <option class="text-lg" value="individual" {{ $subject_type == 'individual' ? 'selected' : '' }}>
                                    Частное лицо
                                </option>
                                <option class="text-lg" value="company" {{ $subject_type == 'company' ? 'selected' : '' }}>
                                    Компания
                                </option>
                            </select>
                            <!-- Иконка стрелки, центрируемая через top-1/2 и -translate-y-1/2 -->
                            <div
                                class="absolute flex items-center transform -translate-y-1/2 pointer-events-none top-1/2 right-3">
                                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input {{ request()->has('contract') ? 'checked' : '' }} type="checkbox" class="hidden peer"
                            name="contract" />

                        <div
                            class="w-5 h-5 rounded-sm border border-gray-300 bg-[#f0f4fa] peer-checked:bg-[#FFD700] peer-checked:border-[#0000001a] transition-all duration-200">
                        </div>

                        <span class="text-md font-medium text-[#1B1E4A] ">Только по договору</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input {{ request()->has('verified') ? 'checked' : '' }} type="checkbox" class="hidden peer"
                            name="verified" />

                        <div
                            class="w-5 h-5 rounded-sm border border-gray-300 bg-[#f0f4fa] peer-checked:bg-[#FFD700] peer-checked:border-[#0000001a] transition-all duration-200">
                        </div>

                        <span class="text-md font-medium text-[#1B1E4A]">Исполнитель проверен</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input {{ request()->has('filled') ? 'checked' : '' }} type="checkbox" class="hidden peer"
                            name="filled" />

                        <div
                            class="w-5 h-5 rounded-sm border border-gray-300 bg-[#f0f4fa] peer-checked:bg-[#FFD700] peer-checked:border-[#0000001a] transition-all duration-200">
                        </div>

                        <span class="text-md font-medium text-[#1B1E4A]">Заполненный профиль</span>
                    </label>

                    <button
                        class="bg-[#FFD700] px-4 py-3 rounded-xl border border-[#0000001a] font-semibold text-[#1B1E4A] cursor-pointer">Применить</button>
                </form>

            </div>
        </div>





        <!-- specialists -->

        <div class="grid grid-cols-4 gap-5 specialists">
            <div class="flex flex-col col-span-1 gap-5 mb-20 specialists__filtration">
                @foreach ($categories as $category)
                    <div class="specialists__filtration--el flex flex-col gap-2.5">

                        {{-- Название категории --}}
                        <a class="text-lg font-medium {{ isset($activeCategory) && $activeCategory->id == $category->id ? 'opacity-100' : 'opacity-50 hover:opacity-100' }}"
                            href="{{ route('specialists.byCategory', ['category' => $category->slug]) }}">
                            {{ $category->name }}
                        </a>

                        {{-- Подкатегории показываем только если категория активная --}}
                        @if (isset($activeCategory) && $activeCategory->id == $category->id)
                            @foreach ($category->subcategories as $subcategory)
                                <a class="opacity-50 hover:opacity-100 w-fit
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            {{ isset($activeSubcategory) && $activeSubcategory->id == $subcategory->id ? 'opacity-100' : '' }}"
                                    href="{{ route('specialists.bySubcategory', ['category' => $category->slug, 'subcategory' => $subcategory->slug]) }}">
                                    – {{ $subcategory->name }}
                                </a>
                            @endforeach
                        @endif

                    </div>
                @endforeach

                <div class="w-full h-[1px] bg-[#0000001a]"></div>

                <form class="flex flex-col gap-5" method="get" class="w-full" action="{{ url()->current() }}">


                    <!-- select -->
                    <div class="relative w-full flex flex-col gap-2.5">
                        <label class="font-semibold text-gray-400 text-md" for="sort">Сортировать:</label>
                        <div class="relative flex items-center">
                            <select name="sort" id="sort"
                                class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] text-gray-500 focus:outline-[#FFD700]">
                                <option class="text-lg" value="default" {{ !$sort ? 'selected' : '' }}>По умолчанию</option>
                                <option class="text-lg" value="popularity" {{ $sort == 'popularity' ? 'selected' : '' }}>По
                                    популярности</option>
                                <option class="text-lg" value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>По рейтингу
                                </option>
                                <option class="text-lg" value="price" {{ $sort == 'price' ? 'selected' : '' }}>По цене
                                </option>
                            </select>

                            <!-- Иконка стрелки, центрируемая через top-1/2 и -translate-y-1/2 -->
                            <div
                                class="absolute flex items-center transform -translate-y-1/2 pointer-events-none top-1/2 right-3">
                                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>


                    <!-- range -->
                    <div class="w-full nouislider-container">
                        <div class="mb-5" id="nouislider"></div>

                        <div class="grid grid-cols-2 gap-4">






                            <div class="w-full flex flex-col gap-2.5">
                                <label class="font-semibold text-gray-400 text-md" for="priceFromFormatted">Цена
                                    от:</label>
                                <input type="text" id="priceFromFormatted" placeholder="Цена от:"
                                    value="{{ number_format($minPrice, 0, '.', ' ') }}"
                                    class="text-[#1B1E4A] font-medium w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] focus:!border-gray-300">
                                <input type="hidden" id="priceFrom" name="min_price" value="{{ $minPrice }}">
                            </div>
                            <div class="w-full flex flex-col gap-2.5">
                                <label class="font-semibold text-gray-400 text-md" for="priceToFormatted">Цена
                                    до:</label>
                                <input type="text" id="priceToFormatted" placeholder="Цена до:"
                                    value="{{ number_format($maxPrice, 0, '.', ' ') }}"
                                    class="text-[#1B1E4A] font-medium w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] focus:!border-gray-300">
                                <input type="hidden" id="priceTo" name="max_price" value="{{ $maxPrice }}">
                            </div>
                        </div>

                    </div>



                    <!-- select -->
                    <div class="relative w-full flex flex-col gap-2.5">
                        <label class="font-semibold text-gray-400 text-md" for="experience">Опыт работы:</label>
                        <div class="relative flex items-center">
                            <select id="experience" name="experience"
                                class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] text-gray-500 focus:outline-[#FFD700]">
                                <option class="text-lg" value="default" class="" {{ !$exp ? 'selected disabled' : '' }}>Не
                                    важно</option>
                                <option class="text-lg" value="less_than_1" {{ $exp == 'less_than_1' ? 'selected' : '' }}
                                    class="">Менее
                                    1
                                    года
                                </option>
                                <option class="text-lg" value="1_3_years" {{ $exp == '1_3_years' ? 'selected' : '' }}
                                    class="">От 1 до 3
                                    лет
                                </option>
                                <option class="text-lg" value="3_5_years" {{ $exp == '3_5_years' ? 'selected' : '' }}
                                    class="">От 3 до 5
                                    лет
                                </option>
                                <option class="text-lg" value="more_than_5" {{ $exp == 'more_than_5' ? 'selected' : '' }}
                                    class="">Более
                                    5 лет
                                </option>
                            </select>
                            <!-- Иконка стрелки, центрируемая через top-1/2 и -translate-y-1/2 -->
                            <div
                                class="absolute flex items-center transform -translate-y-1/2 pointer-events-none top-1/2 right-3">
                                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>


                    <!-- select -->
                    <div class="relative w-full flex flex-col gap-2.5">
                        <label class="font-semibold text-gray-400 text-md" for="subject_type">Вид занятости</label>
                        <div class="relative flex items-center">
                            <select name="subject_type" id="subject_type"
                                class="appearance-none w-full px-4 py-3 border border-gray-300 rounded-xl bg-[#f0f4fa] text-gray-500 focus:outline-[#FFD700]">
                                <option class="text-lg" value="default" class="" {{ !$subject_type ? 'selected disabled' : '' }}>Любой
                                </option>
                                <option class="text-lg" value="individual" {{ $subject_type == 'individual' ? 'selected' : '' }} class="">
                                    Частное
                                    лицо</option>
                                <option class="text-lg" value=" company" {{ $subject_type == 'company' ? 'selected' : '' }}
                                    class="">
                                    Компания
                                </option>

                            </select>
                            <!-- Иконка стрелки, центрируемая через top-1/2 и -translate-y-1/2 -->
                            <div
                                class="absolute flex items-center transform -translate-y-1/2 pointer-events-none top-1/2 right-3">
                                <svg class="w-5 h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input {{ request()->has('contract') ? 'checked' : '' }} type="checkbox" class="hidden peer"
                            name="contract" />

                        <div
                            class="w-5 h-5 rounded-sm border border-gray-300 bg-[#f0f4fa] peer-checked:bg-[#FFD700] peer-checked:border-[#0000001a] transition-all duration-200">
                        </div>

                        <span class="text-md font-medium text-[#1B1E4A] ">Только по договору</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input {{ request()->has('verified') ? 'checked' : '' }} type="checkbox" class="hidden peer"
                            name="verified" />

                        <div
                            class="w-5 h-5 rounded-sm border border-gray-300 bg-[#f0f4fa] peer-checked:bg-[#FFD700] peer-checked:border-[#0000001a] transition-all duration-200">
                        </div>

                        <span class="text-md font-medium text-[#1B1E4A]">Исполнитель проверен</span>
                    </label>

                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input {{ request()->has('filled') ? 'checked' : '' }} type="checkbox" class="hidden peer"
                            name="filled" />

                        <div
                            class="w-5 h-5 rounded-sm border border-gray-300 bg-[#f0f4fa] peer-checked:bg-[#FFD700] peer-checked:border-[#0000001a] transition-all duration-200">
                        </div>

                        <span class="text-md font-medium text-[#1B1E4A]">Заполненный профиль</span>
                    </label>

                    <button
                        class="bg-[#FFD700] px-4 py-3 rounded-xl border border-[#0000001a] font-semibold text-[#1B1E4A] cursor-pointer">Применить</button>
                </form>

            </div>




            <div class="w-full col-span-3 specialists__list">

                <!-- swiper -->
                <div class="swiper specSwiper h-[300px] rounded-2xl">
                    <div class="swiper-wrapper ">
                        <!-- slider -->

                        @foreach ($banners as $banner)
                            <div class="relative swiper-slide">
                                <div class="absolute inset-0 bg-black opacity-50 slide__promo rounded-2xl"></div>

                                <img class="object-cover object-center w-full h-full rounded-2xl"
                                    src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}">
                                <div class="slide-text text-right absolute bottom-[25px] right-[25px] text-[#F5F9FF]">
                                    @if ($banner->is_promo == 1)
                                        <div
                                            class="slide-text__promo text-xs opacity-30 hover:opacity-100 pt-1 pb-1 pl-2 pr-2 rounded-sm bg-[#F5F9FF] text-black inline-block mb-2.5">
                                            промо</div>
                                    @endif
                                    <div class="text-2xl font-bold slide-text__title">{{ $banner->title }}</div>
                                    <div class="text-lg font-light slide-text__subtitle">{{ $banner->subtitle }}</div>
                                    <a href="{{ $banner->link }}"
                                        class="slide-text__btn pt-2 pb-2 pl-4 pr-4 rounded bg-[#F5F9FF] text-black inline-block mt-5 font-medium">перейти</a>
                                </div>
                            </div>
                        @endforeach




                    </div>

                    <div class="swiper-scrollbar"></div>

                </div>

                <!-- search -->
                <form
                    class="banner-search flex my-5 bg-[#F5F9FF] p-2.5 rounded-xl w-full justify-between gap-2.5 border border-[#0000001a]"
                    action="{{ url()->current() }}" method="get">
                    <div class="flex items-center w-full gap-2">
                        <img class="pl-2.5 pr-2.5" src="{{ asset('ico/search.png') }}" alt="поиск">
                        <input id="search" class="w-full outline-none" type="text" name="search"
                            value="{{ old('search', $search) }}" placeholder="Поиск специалистов">
                    </div>
                    <button
                        class="bg-[#FFD700] px-4 py-2 rounded-lg cursor-pointer font-medium border border-[#0000001a]">поиск</button>
                </form>



                @if (count($specialists) == 0)
                    <!-- title -->
                    <div class="mt-20 mb-8 big-title">
                        <h2 class="font-semibold text-4xl text-[#000]">На данный момент нет подходящих специалистов</h2>
                        <p class="text-[#000] opacity-50">Попробуйте поменять фильтрацию или попробуйте позже</p>
                    </div>

                    <a class="text-[#000] font-medium px-4 py-2 rounded-lg border border-[#0000001a]"
                        href="{{ route('specialists') }}">Сбросить</a>
                @endif






                <!-- cards -->
                <div class="flex flex-col gap-5 specialists__list__cards mb-15">
                    @foreach ($specialists as $specialist)
                        @php
$view = App\Models\Visit::where('specialist_id', $specialist->id)
        ->where('user_id', auth()->id())
        ->where('updated_at', '>=', now()->subDay())
        ->first();                        
        @endphp
                        <div
                            class="relative specialists__card p-4 sm:p-5 lg:p-6 rounded-2xl border {{ $specialist->promoted_until >= \Carbon\Carbon::now() ? 'border-[#ffd9006e]' : 'border-[#0000001a]' }}  grid grid-cols-1 sm:grid-cols-5 lg:grid-cols-4 gap-5 relative">


                            @if($view)
                                <p class="absolute opacity-50 text-sm top-2 right-2 py-1 px-2.5 rounded-lg bg-[#f0f4fa] border border-[#0000001a] z-50">просмотренно</p>
                            @endif
                            <!-- Фото -->
                            <div class="w-full h-full sm:col-span-2 lg:col-span-1 specialists__card__img order-1 lg:order-none">
                                <div class="mb-4 lg:mb-5 card-slider-container">
                                    <a href="{{ route('specialists.show', $specialist->id) }}"
                                        style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                                        class="relative swiper card-swiper2 rounded-xl">
                                        <div class="swiper-wrapper">
                                            @foreach ($specialist->specImages as $img)
                                                <div class="swiper-slide">
                                                    <img class="w-full aspect-square object-cover object-center rounded-2xl"
                                                        src="{{ asset('storage/' . $img->image) }}" alt="Фото" />
                                                </div>
                                            @endforeach
                                        </div>
                                    </a>

                                    <div thumbsSlider="" class="swiper card-swiper mt-2.5">
                                        <div class="rounded swiper-wrapper">
                                            @foreach ($specialist->specImages as $img)
                                                <div class="swiper-slide">
                                                    <img class="w-full aspect-square object-cover object-center rounded-2xl"
                                                        src="{{ asset('storage/' . $img->image) }}" alt="Фото" />
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- Автор -->
                                <a href="{{ route('specialists') . '?search=' . $specialist->user->name }}"
                                    class="specialists__card__author flex gap-2 items-center">
                                    <img src="{{ asset('storage/' . $specialist->user->image) }}" alt=""
                                        class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover">
                                    <div>
                                        <p class="text-sm sm:text-md font-semibold max-w-[150px] truncate">
                                            {{ $specialist->user->name }}
                                        </p>
                                        @if ($specialist->reviews_avg_rating)
                                            <div class="flex gap-1.5 items-center text-sm sm:text-sm opacity-70">
                                                <img src="{{ asset('ico/star.svg') }}" alt="" class="w-3.5 h-3.5 sm:w-4 sm:h-4">
                                                <p>{{ number_format($specialist->reviews_avg_rating ?? 0, 1) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            </div>

                            <!-- Информация -->
                            <div class="sm:col-span-3 lg:col-span-3 specialists__card__info order-2 lg:order-none">
                                <div class="font-semibold text-base sm:text-lg xl:text-xl mb-2 line-clamp-1">
                                    <a href="{{ route('specialists.show', $specialist->id) }}">{{ $specialist->title }}</a>
                                </div>

                                <div class="mb-4 text-sm sm:text-base opacity-75 line-clamp-3">
                                    <a
                                        href="{{ route('specialists.show', $specialist->id) }}">{!! nl2br(e($specialist->description)) !!}</a>
                                </div>

                                <div class="flex flex-wrap gap-x-2 gap-y-1 text-sm sm:text-sm lg:text-base opacity-50">
                                    <p>{{ $specialist->getSubjectTypeLabel() }}</p>
                                    <p>•</p>
                                    <p>Работа по договору: {{ $specialist->getIsContractLabel() }}</p>
                                    <p>•</p>
                                    <p>Опыт: {{ $specialist->getExperienceLabel() }}</p>
                                </div>

                                <!-- Контакты -->
                                <a href="{{ route('specialists.show', $specialist->id) }}"
                                    class="flex flex-wrap gap-y-2.5 my-4">
                                    <div class="flex gap-2 items-center opacity-50 w-full sm:w-1/2">
                                        <img src="{{ asset('ico/location.png') }}" alt="" class="w-4 h-4 object-contain">
                                        <p class="truncate">{{ $specialist->city->name }}</p>
                                    </div>
                                    @if ($specialist->website)
                                        <div class="flex gap-2 items-center opacity-50 w-full sm:w-1/2">
                                            <img src="{{ asset('ico/browser.png') }}" alt="" class="w-4 h-4 object-contain">
                                            <p class="truncate">{{ $specialist->website }}</p>
                                        </div>
                                    @endif
                                    @if ($specialist->phone)
                                        <div class="flex gap-2 items-center opacity-50 w-full sm:w-1/2">
                                            <img src="{{ asset('ico/phone.png') }}" alt="" class="w-4 h-4 object-contain">
                                            <p class="truncate">{{ $specialist->phone }}</p>
                                        </div>
                                    @endif
                                    @if ($specialist->email)
                                        <div class="flex gap-2 items-center opacity-50 w-full sm:w-1/2">
                                            <img src="{{ asset('ico/mail.png') }}" alt="" class="w-4 h-4 object-contain">
                                            <p class="truncate">{{ $specialist->email }}</p>
                                        </div>
                                    @endif
                                    @if ($specialist->telegram)
                                        <div class="flex gap-2 items-center opacity-50 w-full sm:w-1/2">
                                            <img src="{{ asset('ico/tg.png') }}" alt="" class="w-4 h-4 object-contain">
                                            <p class="truncate">{{ $specialist->telegram }}</p>
                                        </div>
                                    @endif
                                    @if ($specialist->vkontacte)
                                        <div class="flex gap-2 items-center opacity-50 w-full sm:w-1/2">
                                            <img src="{{ asset('ico/vk.png') }}" alt="" class="w-4 h-4 object-contain">
                                            <p class="truncate">{{ $specialist->vkontacte }}</p>
                                        </div>
                                    @endif
                                </a>

                                <!-- Кнопки -->
                                <div class="flex flex-col sm:flex-row gap-2.5 mt-4">
                                    <a href="{{ route('specialists.show', $specialist->id) }}"
                                        class="bg-[#1B1E4A] px-5 py-3 rounded-xl border border-[#0000001a] font-semibold text-[#F5F9FF] text-center whitespace-nowrap">
                                        {{ number_format($specialist->price, 0, '', ' ') }}
                                        ₽/{{ $specialist->getPriceTypeLabel() }}
                                    </a>
                                    <form method="post" action="{{ route('cart.add') }}">
                                        @csrf
                                        <input type="hidden" name="specialist_id" value="{{ $specialist->id }}">
                                        <button
                                            class="bg-[#F5F9FF] cursor-pointer whitespace-nowrap px-5 py-3 rounded-xl border border-[#0000001a] font-semibold text-[#1B1E4A] w-full sm:w-auto">Добавить
                                            в бронь</button>
                                    </form>
                                </div>

                                @if ($specialist->documents_verified_at)
                                    <div
                                        class="flex items-center gap-2 mt-4 md:absolute md:right-5 md:bottom-5 opacity-50 hover:opacity-100">
                                        <img src="{{ asset('ico/shild.png') }}" alt="" class="w-5 h-5">
                                        <p class="text-[#1B1E4A] text-sm sm:text-base">Исполнитель проверен</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>


                {{ $specialists->links('vendor.pagination.tailwind') }}


                <!-- ./pagitantion -->
            </div>
        </div>
        </div>


        <div class="specialists__text mt-20">
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

    @include('particals.footer')


    @push('app-scripts')
        <script>
            // spec slider
            if (document.querySelector('.specSwiper')) {
                var swiper = new Swiper(".specSwiper", {
                    scrollbar: {
                        el: ".swiper-scrollbar",
                        hide: true,
                    },
                    loop: true,
                    spaceBetween: 20,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false,
                    },
                    simulateTouch: true,
                    keyboard: {
                        enabled: true,
                        onlyInViewport: true,
                    },


                });
            }




            // card slider
            document.querySelectorAll('.card-slider-container').forEach(function (container) {
                var thumbSwiper = new Swiper(container.querySelector('.card-swiper'), {
                    direction: 'horizontal',
                    loop: false,
                    spaceBetween: 5,
                    slidesPerView: 5,
                    freeMode: true,
                    watchSlidesProgress: true,
                });

                var mainSwiper = new Swiper(container.querySelector('.card-swiper2'), {
                    loop: false,
                    spaceBetween: 10,
                    navigation: {
                        nextEl: container.querySelector('.card-swiper-button-next'),
                        prevEl: container.querySelector('.card-swiper-button-prev'),
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


                const mainEl = container.querySelector('.card-swiper2');
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


            });



            // range
            if (document.querySelector('#nouislider')) {
                document.querySelectorAll('#nouislider').forEach(slider => {

                    const container = slider.closest('.nouislider-container');
                    const inputFromFormatted = container.querySelector('#priceFromFormatted');
                    const inputToFormatted = container.querySelector('#priceToFormatted');
                    const inputFrom = container.querySelector('#priceFrom');
                    const inputTo = container.querySelector('#priceTo');

                    function formatNumberWithSpaces(num) {
                        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
                    }

                    noUiSlider.create(slider, {
                        start: [{{ $minPrice ? $minPrice : 0 }}, {{ $maxPrice ? $maxPrice : $globalMax }}],
                        connect: true,
                        range: {
                            min: {{ $globalMin }},
                            max: {{ $globalMax }}
                                                                                                                                },
                        step: 1,
                        tooltips: [true, true],
                        format: {
                            to: value => Math.round(value),
                            from: value => Number(value)
                        }
                    });

                    slider.noUiSlider.on('update', (values, handle) => {
                        const raw = slider.noUiSlider.get(true)[handle];
                        let cleanValue = handle === 0 ? Math.floor(raw) : Math.ceil(raw);

                        if (handle === 0) {
                            inputFrom.value = cleanValue;
                            inputFromFormatted.value = formatNumberWithSpaces(cleanValue);
                        } else {
                            inputTo.value = cleanValue;
                            inputToFormatted.value = formatNumberWithSpaces(cleanValue);
                        }
                    });

                    function onFormattedInputChange(inputFormatted, inputHidden, min, max, otherValue, isFrom) {
                        let cleanValue = parseInt(inputFormatted.value.replace(/\D/g, '')) || min;
                        if (isFrom) {
                            cleanValue = Math.min(Math.max(cleanValue, min), otherValue - 100);
                        } else {
                            cleanValue = Math.max(Math.min(cleanValue, max), otherValue + 100);
                        }
                        inputHidden.value = cleanValue;
                        inputFormatted.value = formatNumberWithSpaces(cleanValue);
                        if (isFrom) {
                            slider.noUiSlider.set([cleanValue, null]);
                        } else {
                            slider.noUiSlider.set([null, cleanValue]);
                        }
                    }

                    inputFromFormatted.addEventListener('change', () => {
                        onFormattedInputChange(inputFromFormatted, inputFrom, {{ $globalMin }}, {{ $globalMax }}, slider.noUiSlider.get()[1], true);
                    });

                    inputToFormatted.addEventListener('change', () => {
                        onFormattedInputChange(inputToFormatted, inputTo, {{ $globalMin }}, {{ $globalMax }}, slider.noUiSlider.get()[0], false);
                    });

                });
            }
        </script>
    @endpush
@endsection