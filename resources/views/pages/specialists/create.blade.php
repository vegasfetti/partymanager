@extends('layouts.app')

@section('title', 'Разместить объявление | ПАТИМЕНЕДЖЕР')
@section('description', 'Разместите свое объявление и найдите клиентов быстро и удобно. ПАТИМЕНЕДЖЕР — платформа для поиска исполнителей.')




@push('app-links')
    {{-- <!-- Подключаем FilePond и плагины -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">

    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>

    <!-- Основной FilePond -->
    <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

    <!-- Плагин File Poster -->
    <link href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.min.css" rel="stylesheet" />
    <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.min.js"></script> --}}
        <!-- FilePond -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <!-- Плагины для валидации размера -->
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script
        src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.min.js"></script>

    <!-- Плагин Image Preview -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
        rel="stylesheet" />

    <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>
    <link href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css" rel="stylesheet">
@endpush


@section('content')

    @include('particals.header')





    <main class="container relative min-h-screen">

        <!-- title -->
        <div class="pt-10 pb-10 text-center big-title sm:text-left">
            <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Разместить объявление</h2>
            <p class="text-sm opacity-50 sm:text-base">Создайте новую карточку исполнителя</p>
        </div>



        <form
            class="bg-[#F0F4FA] w-full p-4 sm:p-6 md:p-8 lg:p-10 border border-[#0000001a] rounded-2xl mb-8 sm:mb-10 md:mb-12 lg:mb-30"
            method="POST" action="{{ route('specialists.create.action') }}" enctype="multipart/form-data">
            @csrf

            {{-- фото --}}
            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-5 md:gap-10">
                <div class="flex flex-col col-span-1 md:col-span-2 gap-2.5 justify-center items-center">
                    <p class="text-lg sm:text-xl md:text-2xl font-semibold text-center"><span
                            class="text-[#c2a500]">*</span> Загрузите до 10 фотографии</p>
                    <span class="opacity-50 text-sm sm:text-base">Размер файла не болле 1 Мб</span>
                </div>
                <div class="flex flex-col col-span-1 md:col-span-2 gap-3 sm:gap-5">
                    <div class="filepond-wrapper">
                        <input type="file" class="filepond filepond-input" name="preview_images[]" multiple accept="image/*"
                            data-max-files="10" />
                    </div>
                    @if (isset($errors) && $errors->has('preview_images'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('preview_images') }}</p>
                    @endif

                    {{-- video --}}
                    <div class="flex flex-col gap-2">
                        <p class="font-semibold opacity-50 text-sm sm:text-base">Ссылка на видео: </p>
                        <input id="video" placeholder="https://example.com" value="{{ old('video') }}"
                            class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                            type="text" name="video">
                        @if(isset($errors) && $errors->has('video'))
                            <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('video') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- hr --}}
            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            {{-- заголовок описание --}}
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Заполните заголовок и описание</p>
                <span class="opacity-50 text-sm sm:text-base">Заполните поля инфомрации</span>
            </div>

            <div class="flex flex-col gap-3 sm:gap-5">
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span>
                        Заголовок: </p>
                    <input placeholder="Професиональный..." name="title" value="{{ old('title') }}"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text">
                    @if (isset($errors) && $errors->has('title'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('title') }}</p>
                    @endif
                </div>
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Описание:
                    </p>
                    <textarea placeholder="Расскажите без лишней воды, что вы предлагаете?" name="description"
                        class="w-full h-[120px] sm:h-[150px] border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF] resize-none">{{ old('description') }}</textarea>
                    @if (isset($errors) && $errors->has('description'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('description') }}</p>
                    @endif
                </div>
            </div>

            {{-- hr --}}
            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            {{-- категория --}}
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Выберите категорию</p>
                <span class="opacity-50 text-sm sm:text-base">Выберите категорию и подкатегорию</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span>
                        Категория: </p>
                    <div class="relative flex items-center">
                        <select name="category_id" id="category-select"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option disabled {{ old('category_id') == '' ? 'selected' : '' }} value="">Выберите категорию
                            </option>
                            @foreach ($categories as $category)
                                <option {{ old('category_id') == $category->id ? 'selected' : '' }} value="{{ $category->id }}">
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute right-3 pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @if (isset($errors) && $errors->has('category_id'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('category_id') }}</p>
                    @endif
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span>
                        Подкатегория: </p>
                    <div class="relative flex items-center">
                        <select name="subcategory_id" id="subcategory-select"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option value="" disabled {{ old('subcategory_id') == '' ? 'selected' : '' }}>Выберите
                                подкатегорию</option>
                            @foreach ($subcategories as $subcategory)
                                <option {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}
                                    data-category-id="{{ $subcategory->category_id }}" value="{{ $subcategory->id }}">
                                    {{ $subcategory->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" id="old-category-id" value="{{ old('category_id') }}">
                        <input type="hidden" id="old-subcategory-id" value="{{ old('subcategory_id') }}">
                        <div class="absolute right-3 pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @if (isset($errors) && $errors->has('subcategory_id'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('subcategory_id') }}</p>
                    @endif
                </div>
            </div>

            {{-- hr --}}
            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            {{-- Стоиомость --}}
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Заполните стоимость</p>
                <span class="opacity-50 text-sm sm:text-base">Заполните поля инфомрации</span>
            </div>

            <div class="flex flex-col gap-3 sm:gap-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                    <div class="flex flex-col gap-2">
                        <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span>
                            Стоимость: </p>
                        <input value="{{ old('price') }}" type="number" placeholder="10000" name="price"
                            class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]">
                        @if (isset($errors) && $errors->has('price'))
                            <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('price') }}</p>
                        @endif
                    </div>
                    <div class="flex flex-col gap-2">
                        <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span>
                            Условия: </p>
                        <div class="relative flex items-center">
                            <select name="price_type"
                                class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                                <option {{ old('price_type') == 'per_hour' ? 'selected' : '' }} value="per_hour">За час
                                </option>
                                <option {{ old('price_type') == 'per_day' ? 'selected' : '' }} value="per_day">За день
                                </option>
                                <option {{ old('price_type') == 'per_service' ? 'selected' : '' }} value="per_service">За
                                    услугу</option>
                            </select>
                            <div class="absolute right-3 pointer-events-none">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 opacity-50" fill="none" stroke="currentColor"
                                    stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        @if (isset($errors) && $errors->has('price_type'))
                            <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('price_type') }}</p>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Описание
                        стоимости: </p>
                    <textarea placeholder="Расскажите подробнее как формируется стоимость" name="price_text"
                        class="w-full h-[120px] sm:h-[150px] border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF] resize-none">{{ old('price_text') }}</textarea>
                    @if (isset($errors) && $errors->has('price_text'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('price_text') }}</p>
                    @endif
                </div>
            </div>

            {{-- hr --}}
            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            {{-- Контакты --}}
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Контактная информация</p>
                <span class="opacity-50 text-sm sm:text-base">Заполните ваши контакты</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                {{-- город --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Город:
                    </p>
                    <div class="relative flex items-center">
                        <select name="city_id"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option selected>Чебоксары</option>
                        </select>
                        <div class="absolute right-3 pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @if (isset($errors) && $errors->has('city_id'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('city_id') }}</p>
                    @endif
                </div>

                {{-- телефон --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Телефон:
                    </p>
                    <input value="{{ old('phone') }}" id="phone" placeholder="+7XXXXXXXXXX"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text" name="phone">
                    @if (isset($errors) && $errors->has('phone'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('phone') }}</p>
                    @endif
                </div>

                {{-- почта --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Почта:
                    </p>
                    <input value="{{ old('email') }}" id="email" placeholder="example@ex.com"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="email" name="email">
                    @if (isset($errors) && $errors->has('email'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                {{-- vk --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">ВКонтакте: </p>
                    <input value="{{ old('vkontacte') }}" id="vk" placeholder="https://vk.com/example"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text" name="vkontacte">
                    @if (isset($errors) && $errors->has('vkontacte'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('vkontacte') }}</p>
                    @endif
                </div>

                {{-- telegram --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">Telegram: </p>
                    <input value="{{ old('telegram') }}" id="telegram" placeholder="https://t.me/example"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text" name="telegram">
                    @if (isset($errors) && $errors->has('telegram'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('telegram') }}</p>
                    @endif
                </div>

                {{-- website --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">Веб-сайт: </p>
                    <input value="{{ old('website') }}" id="website" placeholder="https://example.com"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text" name="website">
                    @if (isset($errors) && $errors->has('website'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('website') }}</p>
                    @endif
                </div>
            </div>

            {{-- hr --}}
            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            {{-- Немного о вас --}}
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Ещё немного о вас</p>
                <span class="opacity-50 text-sm sm:text-base">Поможет найти вас при фильтрации</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
                {{-- Опыт --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Опыт
                        работы: </p>
                    <div class="relative flex items-center">
                        <select name="experience"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option {{ old('experience') == 'less_than_1' ? 'selected' : '' }} value="less_than_1">Менее 1
                                года</option>
                            <option {{ old('experience') == '1_3_years' ? 'selected' : '' }} value="1_3_years">От 1 до 3 лет
                            </option>
                            <option {{ old('experience') == '3_5_years' ? 'selected' : '' }} value="3_5_years">От 3 до 5 лет
                            </option>
                            <option {{ old('experience') == 'more_than_5' ? 'selected' : '' }} value="more_than_5">Более 5 лет
                            </option>
                        </select>
                        <div class="absolute right-3 pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @if (isset($errors) && $errors->has('experience'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('experience') }}</p>
                    @endif
                </div>

                {{-- Дейстельность --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Ваш вид
                        деятельности: </p>
                    <div class="relative flex items-center">
                        <select name="subject_type"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option {{ old('subject_type') == 'individual' ? 'selected' : '' }} value="individual">Частное
                                лицо</option>
                            <option {{ old('subject_type') == 'company' ? 'selected' : '' }} value="company">Компания</option>
                        </select>
                        <div class="absolute right-3 pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @if (isset($errors) && $errors->has('subject_type'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('subject_type') }}</p>
                    @endif
                </div>

                {{-- Работа по догогору --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Работаю
                        по договору:</p>
                    <div class="relative flex items-center">
                        <select name="is_contract"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option {{ old('is_contract') == 'true' ? 'selected' : '' }} value="true">Да, работаю</option>
                            <option {{ old('is_contract') == 'false' ? 'selected' : '' }} value="false">Нет, не работаю
                            </option>
                        </select>
                        <div class="absolute right-3 pointer-events-none">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 opacity-50" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                    @if (isset($errors) && $errors->has('is_contract'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('is_contract') }}</p>
                    @endif
                </div>
            </div>

            {{-- hr --}}
            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            {{-- Дополнительные поля --}}
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Дополнительные поля</p>
                <span class="opacity-50 text-sm sm:text-base">Необязательно, но поможет выделить вас</span>
            </div>

            {{-- Навыки --}}
            <div class="flex flex-col gap-2 tag-wrapper" data-name="skills">
                <p class="font-semibold opacity-50 text-sm sm:text-base">Навыки или приемущества:</p>
                <div class="flex gap-2">
                    <input type="text"
                        class="tag-input w-[50px] md:w-fit flex-grow border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        placeholder="Введите навык и нажмите Enter или +" />
                    <button type="button"
                        class="px-3 sm:px-5 py-1 sm:py-2 text-lg sm:text-xl font-semibold text-black bg-yellow-400 cursor-pointer add-tag rounded-xl">+</button>
                </div>
                @if (isset($errors) && $errors->has('skills'))
                    <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('skills') }}</p>
                @endif
                <div class="flex flex-wrap gap-2 mt-1 mb-4 tag-list opacity-90"></div>
                <input value="{{ old('skills') }}" type="hidden" name="skills" class="tag-hidden">
            </div>

            {{-- Оборудование --}}
            <div class="flex flex-col gap-2 tag-wrapper" data-name="equipment">
                <p class="font-semibold opacity-50 text-sm sm:text-base">Дополнительное оборудование:</p>
                <div class="flex gap-2">
                    <input type="text"
                        class="tag-input w-[50px] md:w-fit flex-grow border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        placeholder="Введите оборудование и нажмите Enter или +" />
                    <button type="button"
                        class="px-3 sm:px-5 py-1 sm:py-2 text-lg sm:text-xl font-semibold text-black bg-yellow-400 cursor-pointer add-tag rounded-xl">+</button>
                </div>
                @if (isset($errors) && $errors->has('equipment'))
                    <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('equipment') }}</p>
                @endif
                <div class="flex flex-wrap gap-2 mt-1 mb-4 tag-list opacity-90"></div>
                <input value="{{ old('equipment') }}" type="hidden" name="equipment" class="tag-hidden">
            </div>

            {{-- Языки --}}
            <div class="flex flex-col gap-2 tag-wrapper" data-name="languages">
                <p class="font-semibold opacity-50 text-sm sm:text-base">Владение языками:</p>
                <div class="flex gap-2">
                    <input type="text"
                        class="tag-input w-[50px] md:w-fit flex-grow border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        placeholder="Введите язык и нажмите Enter или +" />
                    <button type="button"
                        class="px-3 sm:px-5 py-1 sm:py-2 text-lg sm:text-xl font-semibold text-black bg-yellow-400 cursor-pointer add-tag rounded-xl">+</button>
                </div>
                @if (isset($errors) && $errors->has('languages'))
                    <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('languages') }}</p>
                @endif
                <div class="flex flex-wrap gap-2 mt-1 mb-4 tag-list opacity-90"></div>
                <input type="hidden" name="languages" class="tag-hidden" data-default="Русский"
                    value="{{ old('languages', 'Русский') }}">
            </div>

            {{-- hr --}}
            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            {{-- Портфолио --}}
            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-5 md:gap-10">
                <div class="flex flex-col col-span-1 md:col-span-2 gap-2.5 justify-center items-center">
                    <p class="text-lg sm:text-xl md:text-2xl font-semibold text-center">Загрузите до 30 фотографий для
                        порфтолио</p>
                    <span class="opacity-50 text-sm sm:text-base">Размер файла не болле 1 Мб</span>
                </div>
                <div class="flex flex-col col-span-1 md:col-span-2 gap-3 sm:gap-5">
                    <div class="filepond-wrapper">
                        <input type="file" class="filepond-portfoilo filepond-input" name="portfolio_images[]" multiple
                            accept="image/*" data-max-files="30" />
                    </div>
                    @if (isset($errors) && $errors->has('portfolio_images'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('portfolio_images') }}</p>
                    @endif
                </div>
            </div>

            <button
                class="bg-[#FFD700] text-[#1C1F4C] px-4 py-2 sm:px-6 sm:py-3 font-normal border border-[#0000001a] rounded-xl font-semibold mt-5 sm:mt-8 md:mt-10 cursor-pointer w-full sm:w-auto"
                type="submit">Отправить на модерацию</button>

            <p class="mt-5 sm:mt-8 md:mt-10 opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> -
                обязательные поля</p>
        </form>




    </main>
    @push('app-scripts')
        <script>
            //filepound
            FilePond.registerPlugin(
                FilePondPluginFileValidateSize,
                FilePondPluginFilePoster,
                FilePondPluginImagePreview,
                FilePondPluginImageValidateSize
            );

            FilePond.setOptions({
                labelIdle: 'Перетащите файлы или <span class="filepond--label-action">выберите</span>',
                labelInvalidField: 'Поле содержит недопустимые файлы',
                labelFileWaitingForSize: 'Ожидание размера...',
                labelFileSizeNotAvailable: 'Размер недоступен',
                labelFileLoading: 'Загрузка...',
                labelFileLoadError: 'Ошибка при загрузке',
                labelFileProcessing: 'Обработка...',
                labelFileProcessingComplete: 'Готово',
                labelFileProcessingAborted: 'Отменено',
                labelFileProcessingError: 'Ошибка при обработке',
                labelFileProcessingRevertError: 'Ошибка при откате',
                labelFileRemoveError: 'Ошибка при удалении',
                labelTapToCancel: 'Нажмите, чтобы отменить',
                labelTapToRetry: 'Нажмите, чтобы повторить',
                labelTapToUndo: 'Нажмите, чтобы отменить действие',
                labelButtonRemoveItem: 'Удалить',
                labelButtonAbortItemLoad: 'Отменить',
                labelButtonRetryItemLoad: 'Повторить',
                labelButtonAbortItemProcessing: 'Отменить',
                labelButtonUndoItemProcessing: 'Отменить',
                labelButtonRetryItemProcessing: 'Повторить',
                labelButtonProcessItem: 'Загрузить',
                labelMaxFileSizeExceeded: 'Файл слишком большой',
                labelMaxFileSize: 'Максимальный размер: {filesize}',
                labelMinFileSize: 'Минимальный размер: {filesize}',
                labelFileTypeNotAllowed: 'Недопустимый тип файла',
                fileValidateTypeLabelExpectedTypes: 'Ожидается: {allButLastType} или {lastType}',
                imageValidateSizeLabelFormatError: 'Недопустимый формат изображения',
                imageValidateSizeLabelImageSizeTooSmall: 'Изображение слишком маленькое',
                imageValidateSizeLabelImageSizeTooBig: 'Изображение слишком большое',
                imageValidateSizeLabelExpectedMinSize: 'Минимальный размер: {minWidth} × {minHeight}',
                imageValidateSizeLabelExpectedMaxSize: 'Максимальный размер: {maxWidth} × {maxHeight}',
                storeAsFile: true
            });

            FilePond.create(document.querySelector('.filepond'), {
                allowMultiple: true,
                maxFiles: 10,
                instantUpload: false,
                minFileSize: '5KB',
                maxFileSize: '1MB',
                imageValidateSizeMinWidth: 200,
                imageValidateSizeMinHeight: 200,
                imagePreviewFit: 'cover',
                storeAsFile: true
            });


            FilePond.create(document.querySelector('.filepond-portfoilo'), {
                allowMultiple: true,
                maxFiles: 30,
                instantUpload: false,
                minFileSize: '5KB',
                maxFileSize: '1MB',
                imageValidateSizeMinWidth: 200,
                imageValidateSizeMinHeight: 200,
                imagePreviewFit: 'cover',
                storeAsFile: true
            });


            // categories
            const categorySelect = document.getElementById('category-select');
            const subcategorySelect = document.getElementById('subcategory-select');

            const oldCategoryId = document.getElementById('old-category-id')?.value;
            const oldSubcategoryId = document.getElementById('old-subcategory-id')?.value;

            function filterSubcategories() {
                const selectedCategoryId = categorySelect.value;

                Array.from(subcategorySelect.options).forEach(option => {
                    if (!option.dataset.categoryId) return;
                    option.style.display = option.dataset.categoryId === selectedCategoryId ? '' : 'none';
                });

                const selectedOption = subcategorySelect.querySelector(`option[value="${subcategorySelect.value}"]`);
                if (!selectedOption || selectedOption.style.display === 'none') {
                    subcategorySelect.value = '';
                }
            }

            categorySelect.addEventListener('change', filterSubcategories);

            subcategorySelect.addEventListener('change', () => {
                const selectedOption = subcategorySelect.options[subcategorySelect.selectedIndex];
                if (selectedOption && selectedOption.dataset.categoryId) {
                    categorySelect.value = selectedOption.dataset.categoryId;
                    filterSubcategories();
                }
            });

            if (oldSubcategoryId) {
                const selectedSubOption = subcategorySelect.querySelector(`option[value="${oldSubcategoryId}"]`);
                if (selectedSubOption) {
                    const categoryId = selectedSubOption.dataset.categoryId;
                    categorySelect.value = categoryId;
                    filterSubcategories();
                    subcategorySelect.value = oldSubcategoryId;
                }
            } else if (oldCategoryId) {
                categorySelect.value = oldCategoryId;
                filterSubcategories();
            }








            //tags
            document.querySelectorAll('.tag-wrapper').forEach(wrapper => {
                const input = wrapper.querySelector('.tag-input');
                const button = wrapper.querySelector('.add-tag');
                const tagList = wrapper.querySelector('.tag-list');
                const hiddenInput = wrapper.querySelector('.tag-hidden');

                let initial = hiddenInput.value || hiddenInput.dataset.default || '';
                let tags = initial.split('|').filter(tag => tag.trim() !== '');

                const render = () => {
                    tagList.innerHTML = '';
                    tags.forEach((tag, index) => {
                        const el = document.createElement('div');
                        el.className = 'bg-gray-200 px-5 py-1.5 rounded-xl flex items-center gap-2 text-md border border-[#0000001a]';
                        el.innerHTML = `
                                                                                                                                                <span class="font-medium text-gray-700">${tag}</span>
                                                                                                                                                <button type="button" class="text-xl text-gray-500 cursor-pointer hover:text-gray-900" data-index="${index}">&times;</button>
                                                                                                                                            `;
                        tagList.appendChild(el);
                    });
                    hiddenInput.value = tags.join('|');
                };

                const addTag = () => {
                    const val = input.value.trim();

                    if (val.length < 3 || val.length > 30) {
                        alert("Тег должен содержать от 3 до 30 символов");
                        return;
                    }

                    if (val && !tags.includes(val)) {
                        tags.push(val);
                        render();
                    }
                    input.value = '';
                };

                input.addEventListener('keydown', e => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        addTag();
                    }
                });

                button.addEventListener('click', addTag);

                tagList.addEventListener('click', e => {
                    if (e.target.tagName === 'BUTTON') {
                        const index = e.target.dataset.index;
                        tags.splice(index, 1);
                        render();
                    }
                });

                render();
            });


        </script>
    @endpush
    @include('particals.footer')

@endsection