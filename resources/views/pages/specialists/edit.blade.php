@extends('layouts.app')

@section('title', 'Редактирование | ПАТИМЕНЕДЖЕР')

@push('app-links')
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
    {{-- <style>
        .filepond--file-action-button[data-align="left"] {
            display: none !important;
        }
    </style> --}}
@endpush


@section('content')

    @include('particals.header')





    <main class="container relative min-h-screen">

        <!-- title -->
        <div class="pt-10 pb-10 text-center big-title sm:text-left">
            <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Редактировать объявление</h2>
            <p class="text-sm opacity-50 sm:text-base">Редактируйте свою карточку исполнителя</p>
        </div>




        <form class="bg-[#F0F4FA] w-full p-4 sm:p-6 md:p-8 lg:p-10 border border-[#0000001a] rounded-2xl mb-5" method="POST"
            action="{{ route('specialists.update', ['specialist' => $specialist->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Фото и видео -->
            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-5 md:gap-10">
                <div class="flex flex-col col-span-1 md:col-span-2 gap-2.5 justify-center items-center">
                    <p class="text-lg sm:text-xl md:text-2xl font-semibold text-center"><span
                            class="text-[#c2a500]">*</span> Загрузите до 10 фотографии</p>
                    <span class="opacity-50 text-sm sm:text-base">Размер файла не болле 1 Мб</span>
                </div>
                <div class="flex flex-col col-span-1 md:col-span-2 gap-3 sm:gap-5">
                    <div class="filepond-wrapper">
                        <input type="file" class="filepond filepond-input" name="preview_images[]" multiple accept="image/*"
                            data-max-files="10" id="previewImages" data-files='@json($existingPreviewImages)'>
                    </div>
                    @if (isset($errors) && $errors->has('preview_images'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('preview_images') }}</p>
                    @endif

                    <div class="flex flex-col gap-2">
                        <p class="font-semibold opacity-50 text-sm sm:text-base">Ссылка на видео: </p>
                        <input id="video" placeholder="https://example.com" value="{{ $specialist->video_link }}"
                            class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                            type="text" name="video">
                        @if(isset($errors) && $errors->has('video'))
                            <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('video') }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            <!-- Заголовок и описание -->
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Заполните заголовок и описание</p>
                <span class="opacity-50 text-sm sm:text-base">Заполните поля инфомрации</span>
            </div>

            <div class="flex flex-col gap-3 sm:gap-5">
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span>
                        Заголовок: </p>
                    <input placeholder="Професиональный..." name="title" value="{{$specialist->title}}"
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
                        class="w-full h-[120px] sm:h-[150px] border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF] resize-none">{{$specialist->description}}</textarea>
                    @if (isset($errors) && $errors->has('description'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('description') }}</p>
                    @endif
                </div>
            </div>

            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            <!-- Категория и подкатегория -->
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
                            @foreach ($categories as $category)
                                <option {{$specialist->category_id == $category->id ? 'selected' : ''}}
                                    value="{{ $category->id }}">{{ $category->name }}</option>
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
                            <option value="" disabled selected>Выберите подкатегорию</option>
                            @foreach ($subcategories as $subcategory)
                                <option {{$specialist->subcategory_id == $subcategory->id ? 'selected' : ''}}
                                    data-category-id="{{ $subcategory->category_id }}" value="{{ $subcategory->id }}">
                                    {{ $subcategory->name }}
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
                    @if (isset($errors) && $errors->has('subcategory_id'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('subcategory_id') }}</p>
                    @endif
                </div>
            </div>

            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            <!-- Стоимость -->
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Заполните стоимость</p>
                <span class="opacity-50 text-sm sm:text-base">Заполните поля инфомрации</span>
            </div>

            <div class="flex flex-col gap-3 sm:gap-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                    <div class="flex flex-col gap-2">
                        <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span>
                            Стоимость: </p>
                        <input value="{{ $specialist->price }}" type="number" placeholder="10000" name="price"
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
                                <option {{ $specialist->price_type == 'per_hour' ? 'selected' : '' }} value="per_hour">За час
                                </option>
                                <option {{ $specialist->price_type == 'per_day' ? 'selected' : '' }} value="per_day">За день
                                </option>
                                <option {{ $specialist->price_type == 'per_service' ? 'selected' : '' }} value="per_service">
                                    За услугу</option>
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
                        class="w-full h-[120px] sm:h-[150px] border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF] resize-none">{{$specialist->price_text}}</textarea>
                    @if (isset($errors) && $errors->has('price_text'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('price_text') }}</p>
                    @endif
                </div>
            </div>

            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            <!-- Контакты -->
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Контактная информация</p>
                <span class="opacity-50 text-sm sm:text-base">Заполните ваши контакты</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-5">
                <!-- Город -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Город:
                    </p>
                    <div class="relative flex items-center">
                        <select name="city_id"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option value="" selected>Чебоксары</option>
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

                <!-- Телефон -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Телефон:
                    </p>
                    <input value="{{ $specialist->phone }}" id="phone" placeholder="+7XXXXXXXXXX"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text" name="phone">
                    @if (isset($errors) && $errors->has('phone'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('phone') }}</p>
                    @endif
                </div>

                <!-- Почта -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Почта:
                    </p>
                    <input value="{{ $specialist->email }}" id="email" placeholder="example@ex.com"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="email" name="email">
                    @if (isset($errors) && $errors->has('email'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- ВКонтакте -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">ВКонтакте: </p>
                    <input value="{{ $specialist->vkontacte }}" id="vk" placeholder="https://vk.com/example"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text" name="vkontacte">
                    @if (isset($errors) && $errors->has('vkontacte'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('vkontacte') }}</p>
                    @endif
                </div>

                <!-- Telegram -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">Telegram: </p>
                    <input value="{{ $specialist->telegram }}" id="telegram" placeholder="https://t.me/example"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text" name="telegram">
                    @if (isset($errors) && $errors->has('telegram'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('telegram') }}</p>
                    @endif
                </div>

                <!-- Веб-сайт -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base">Веб-сайт: </p>
                    <input value="{{ $specialist->website }}" id="website" placeholder="https://example.com"
                        class="w-full border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        type="text" name="website">
                    @if (isset($errors) && $errors->has('website'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('website') }}</p>
                    @endif
                </div>
            </div>

            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            <!-- Опыт работы -->
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Ещё немного о вас</p>
                <span class="opacity-50 text-sm sm:text-base">Поможет найти вас при фильтрации</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-5">
                <!-- Опыт работы -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Опыт
                        работы: </p>
                    <div class="relative flex items-center">
                        <select name="experience"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option {{ $specialist->experience == 'less_than_1' ? 'selected' : '' }} value="less_than_1">Менее
                                1 года</option>
                            <option {{ $specialist->experience == '1_3_years' ? 'selected' : '' }} value="1_3_years">От 1 до 3
                                лет</option>
                            <option {{ $specialist->experience == '3_5_years' ? 'selected' : '' }} value="3_5_years">От 3 до 5
                                лет</option>
                            <option {{ $specialist->experience == 'more_than_5' ? 'selected' : '' }} value="more_than_5">Более
                                5 лет</option>
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

                <!-- Вид деятельности -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Ваш вид
                        деятельности: </p>
                    <div class="relative flex items-center">
                        <select name="subject_type"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option {{ $specialist->subject_type == 'individual' ? 'selected' : '' }} value="individual">
                                Частное лицо</option>
                            <option {{ $specialist->subject_type == 'company' ? 'selected' : '' }} value="company">Компания
                            </option>
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

                <!-- Работа по договору -->
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> Работаю
                        по договору:</p>
                    <div class="relative flex items-center">
                        <select name="is_contract"
                            class="appearance-none w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-300 rounded-xl !bg-[#f5f9ff] text-gray-500 focus:outline-[#FFD700] text-sm sm:text-base">
                            <option {{ $specialist->is_contract == true ? 'selected' : '' }} value="true">Да, работаю</option>
                            <option {{ $specialist->is_contract == false ? 'selected' : '' }} value="false">Нет, не работаю
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

            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            <!-- Дополнительные поля -->
            <div class="flex flex-col mini-title gap-2 pb-3 sm:gap-2.5 sm:pb-5">
                <p class="text-xl sm:text-2xl font-semibold">Дополнительные поля</p>
                <span class="opacity-50 text-sm sm:text-base">Необязательно, но поможет выделить вас</span>
            </div>

            <!-- Навыки -->
            <div class="flex flex-col gap-2 tag-wrapper" data-name="skills">
                <p class="font-semibold opacity-50 text-sm sm:text-base">Навыки или приемущества:</p>
                <div class="flex gap-2 flex-wrap">
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
                <input value="{{ $specialist->skills }}" type="hidden" name="skills" class="tag-hidden">
            </div>

            <!-- Оборудование -->
            <div class="flex flex-col gap-2 tag-wrapper" data-name="equipment">
                <p class="font-semibold opacity-50 text-sm sm:text-base">Дополнительное оборудование:</p>
                <div class="flex gap-2 flex-wrap">
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
                <input value="{{ $specialist->equipment }}" type="hidden" name="equipment" class="tag-hidden">
            </div>

            <!-- Языки -->
            <div class="flex flex-col gap-2 tag-wrapper" data-name="languages">
                <p class="font-semibold opacity-50 text-sm sm:text-base">Владение языками:</p>
                <div class="flex gap-2 flex-wrap">
                    <input type="text"
                        class="tag-input w-[50px] md:w-fit  flex-grow border border-[#0000001a] rounded-xl px-3 sm:px-4 py-2 sm:py-3 bg-[#F5F9FF]"
                        placeholder="Введите язык и нажмите Enter или +" />
                    <button type="button"
                        class="px-3 sm:px-5 py-1 sm:py-2 text-lg sm:text-xl font-semibold text-black bg-yellow-400 cursor-pointer add-tag rounded-xl">+</button>
                </div>
                @if (isset($errors) && $errors->has('languages'))
                    <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('languages') }}</p>
                @endif
                <div class="flex flex-wrap gap-2 mt-1 mb-4 tag-list opacity-90"></div>
                <input type="hidden" name="languages" class="tag-hidden" data-default="Русский"
                    value="{{ $specialist->languages }}">
            </div>

            <div class="w-full h-[1px] bg-[#0000001a] my-5 sm:my-8 md:my-10"></div>

            <!-- Портфолио -->
            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-5 md:gap-10">
                <div class="flex flex-col col-span-1 md:col-span-2 gap-2.5 justify-center items-center">
                    <p class="text-lg sm:text-xl md:text-2xl font-semibold text-center">Загрузите до 30 фотографий для
                        порфтолио</p>
                    <span class="opacity-50 text-sm sm:text-base">Размер файла не болле 1 Мб</span>
                </div>
                <div class="flex flex-col col-span-1 md:col-span-2 gap-3 sm:gap-5">
                    <div class="filepond-wrapper">
                        <input type="file" class="filepond-portfoilo filepond-input" name="portfolio_images[]" multiple
                            accept="image/*" data-max-files="30" id="portfolioImages"
                            data-files='@json($existingPortfolioImages)' />
                    </div>
                    @if (isset($errors) && $errors->has('portfolio_images'))
                        <p class="text-xs sm:text-sm text-red-500">{{ $errors->first('portfolio_images') }}</p>
                    @endif
                </div>
            </div>

            <input type="hidden" name="preview_meta" id="previewMeta">
            <input type="hidden" name="portfolio_meta" id="portfolioMeta">

            <!-- Кнопки -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-5 items-center mt-5 sm:mt-10 w-full">
                <a class="text-gray-500 opacity-50 px-4 py-2 border border-[#0000001a] rounded-xl hover:opacity-100 font-semibold text-base"
                    href="{{ route('specialists.show', $specialist->id) }}">Назад</a>
                <button
                    class="bg-[#FFD700] text-[#1C1F4C] px-4 py-2 cursor-pointer border border-[#0000001a] rounded-xl font-semibold text-base"
                    type="submit">Отправить на модерацию</button>
            </div>
            <p class="mt-5 sm:mt-10 opacity-50 text-sm sm:text-base"><span class="text-[#c2a500]">*</span> - обязательные
                поля</p>
        </form>

        <!-- Форма удаления -->
        <form action="{{ route('specialists.destroy', $specialist->id) }}" method="POST" class="mb-5 sm:mb-10">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="text-gray-500 opacity-50 px-4 py-2 border border-[#0000001a] rounded-xl hover:opacity-100 font-semibold text-base cursor-pointer"
                onclick="return confirm('Вы действительно хотите удалить карточку?')">
                💣 Удалить карточку
            </button>
        </form>



        @push('app-scripts')
            <script>
                //file
                FilePond.registerPlugin(
                    FilePondPluginFileValidateSize,
                    FilePondPluginImagePreview,
                    FilePondPluginFilePoster,
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
                    storeAsFile: true,
                    allowReorder: false,
                    allowMultiple: true,
                    dragDropReorder: false
                });



                const input = document.getElementById('previewImages');
                const existingFiles = input.dataset.files ? JSON.parse(input.dataset.files) : [];

                const pond = FilePond.create(input, {
                    allowMultiple: true,
                    allowReorder: false,
                    maxFiles: 10,
                    instantUpload: false,
                    minFileSize: '5KB',
                    maxFileSize: '1MB',
                    storeAsFile: true,
                    imageValidateSizeMinWidth: 200,
                    imageValidateSizeMinHeight: 200,
                    imagePreviewFit: 'cover',
                    files: existingFiles,
                    server: {
                        load: (source, load, error, progress) => {
                            fetch(source)
                                .then(response => {
                                    if (!response.ok) throw Error('Ошибка загрузки');
                                    return response.blob();
                                })
                                .then(load)
                                .catch(error);
                        }
                    }
                });

                pond.on('updatefiles', () => {
                    const order = pond.getFiles().map(f => {
                        if (f.origin === FilePond.FileOrigin.LOCAL || f.origin === 'local') {
                            // Для старых файлов - ссылка/ID
                            return { type: 'existing', source: f.serverId || f.source };
                        } else {
                            // Для новых - имя файла
                            return { type: 'new', name: f.file.name };
                        }
                    });

                    document.querySelector('input[name="preview_meta"]').value = JSON.stringify(order);
                });






                const portfolioInput = document.getElementById('portfolioImages');
                const existingPortfolioFiles = portfolioInput.dataset.files ? JSON.parse(portfolioInput.dataset.files) : [];

                const portfolioPond = FilePond.create(portfolioInput, {
                    allowMultiple: true,
                    maxFiles: 30,
                    instantUpload: false,
                    allowReorder: false,
                    minFileSize: '5KB',
                    maxFileSize: '1MB',
                    imageValidateSizeMinWidth: 200,
                    imageValidateSizeMinHeight: 200,
                    imagePreviewFit: 'cover',
                    storeAsFile: true,
                    files: existingPortfolioFiles,
                    server: {
                        load: (source, load, error, progress) => {
                            fetch(source)
                                .then(response => {
                                    if (!response.ok) throw Error('Ошибка загрузки');
                                    return response.blob();
                                })
                                .then(load)
                                .catch(error);
                        }
                    }
                });

                portfolioPond.on('updatefiles', () => {
                    const order = portfolioPond.getFiles().map(f => {
                        if (f.origin === FilePond.FileOrigin.LOCAL || f.origin === 'local') {
                            return { type: 'existing', source: f.serverId || f.source };
                        } else {
                            return { type: 'new', name: f.file.name };
                        }
                    });

                    document.querySelector('input[name="portfolio_meta"]').value = JSON.stringify(order);
                });




                //categories
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

    </main>
    @include('particals.footer')

@endsection