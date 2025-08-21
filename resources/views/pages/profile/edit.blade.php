@extends('layouts.app')

@section('title', 'Редактировать профиль | ПАТИМЕНЕДЖЕР')


@section('content')

    @include('particals.header')

    @push('app-links')
        <link href="https://unpkg.com/filepond/dist/filepond.min.css" rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css"
            rel="stylesheet" />
        <link href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.min.css" rel="stylesheet" />

        <script
            src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
        <script
            src="https://unpkg.com/filepond-plugin-image-validate-size/dist/filepond-plugin-image-validate-size.min.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.min.js"></script>

        <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>

        <style>

        </style>
    @endpush

    <main class="container min-h-[100vh]">


        <!-- title -->
        <div class="pt-10 pb-10 text-center big-title sm:text-left">
            <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Редактировать профиль</h2>
            <p class="text-sm opacity-50 sm:text-base">Редактируйте свою профиль</p>
        </div>

        <form method="post" enctype="multipart/form-data"
            class="w-full bg-[#F0F4FA] p-6 sm:p-8 md:p-10 border border-[#0000001a] rounded-2xl"
            action="{{ route('profile.update') }}">
            @method('PUT')
            @csrf

            {{-- Фото профиля --}}
            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-10">
                <div class="flex flex-col md:col-span-2 gap-2.5 justify-center items-center text-center">
                    <p class="text-xl sm:text-2xl font-semibold">Загрузите фото профиля</p>
                    <span class="opacity-50 text-sm sm:text-base">Размер файла не более 1 Мб</span>
                </div>
                <div class="flex flex-col md:col-span-2 gap-5">
                    <div class="filepond-wrapper w-full">
                        <input type="file" name="preview_image" class="filepond filepond-input" />
                    </div>
                    @if ($errors->has('avatar'))
                        <p class="text-sm text-red-500">{{ $errors->first('avatar') }}</p>
                    @endif
                </div>
            </div>

            <hr class="my-8 md:my-10 border-[#0000001a]">

            {{-- Имя --}}
            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-10">
                <div class="flex flex-col md:col-span-2 gap-2.5 justify-center items-center text-center">
                    <p class="text-xl sm:text-2xl font-semibold">Введите новое имя</p>
                    <span class="opacity-50 text-sm sm:text-base">Ваше имя или название</span>
                </div>
                <div class="flex flex-col md:col-span-2 gap-5">
                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                        class="w-full border border-[#0000001a] rounded-xl px-4 py-3 bg-[#F5F9FF]">
                    @if ($errors->has('name'))
                        <p class="text-sm text-red-500">{{ $errors->first('name') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-btn flex justify-end mt-6">
                <button
                    class="bg-[#FFD700] text-[#1C1F4C] px-4 py-2 cursor-pointer border border-[#0000001a] rounded-xl font-semibold">
                    Сохранить
                </button>
            </div>
        </form>

        <hr class="my-8 md:my-10 border-[#0000001a]">

        {{-- Смена пароля --}}
        <form method="post" class="w-full bg-[#F0F4FA] p-6 sm:p-8 md:p-10 border border-[#0000001a] rounded-2xl"
            action="{{ route('profile.changePassword') }}">
            @method('PUT')
            @csrf

            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-10">
                <div class="flex flex-col md:col-span-2 gap-2.5 justify-center items-center text-center">
                    <p class="text-xl sm:text-2xl font-semibold">Сменить пароль</p>
                    <span class="opacity-50 text-sm sm:text-base">Минимум 8 символов, включая цифры и буквы</span>
                </div>
                <div class="flex flex-col md:col-span-2 gap-5">

                    {{-- Старый пароль --}}
                    <div
                        class="password_input auth_input px-5 py-3.5 bg-[#F5F9FF] border border-[#0000001a] rounded-xl relative">
                        <input name="old_password" id="password1" type="password" placeholder="Старый пароль"
                            class="w-[90%] outline-0 bg-transparent">
                        <img id="show_password1"
                            class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                            src="{{ asset('ico/eye.png') }}" alt="Показать пароль">
                        <img style="display: none;" id="hide_password1"
                            class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                            src="{{ asset('ico/eye-closed.png') }}" alt="Скрыть пароль">
                    </div>
                    @if ($errors->has('old_password'))
                        <p class="text-sm text-red-500">{{ $errors->first('old_password') }}</p>
                    @endif

                    {{-- Новый пароль --}}
                    <div
                        class="password_input auth_input px-5 py-3.5 bg-[#F5F9FF] border border-[#0000001a] rounded-xl relative">
                        <input name="password" id="password2" type="password" placeholder="Новый пароль"
                            class="w-[90%] outline-0 bg-transparent">
                        <img id="show_password2"
                            class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                            src="{{ asset('ico/eye.png') }}" alt="Показать пароль">
                        <img style="display: none;" id="hide_password2"
                            class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                            src="{{ asset('ico/eye-closed.png') }}" alt="Скрыть пароль">
                    </div>
                    @if ($errors->has('password'))
                        <p class="text-sm text-red-500">{{ $errors->first('password') }}</p>
                    @endif

                    {{-- Подтверждение --}}
                    <div
                        class="password_input auth_input px-5 py-3.5 bg-[#F5F9FF] border border-[#0000001a] rounded-xl relative">
                        <input name="password_confirmation" id="password3" type="password" placeholder="Повторите пароль"
                            class="w-[90%] outline-0 bg-transparent">
                        <img id="show_password3"
                            class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                            src="{{ asset('ico/eye.png') }}" alt="Показать пароль">
                        <img style="display: none;" id="hide_password3"
                            class="absolute right-5 top-1/2 -translate-y-1/2 cursor-pointer opacity-50 hover:opacity-100"
                            src="{{ asset('ico/eye-closed.png') }}" alt="Скрыть пароль">
                    </div>
                    @if ($errors->has('password_confirmation'))
                        <p class="text-sm text-red-500">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-btn flex justify-end mt-6">
                <button
                    class="bg-[#FFD700] text-[#1C1F4C] px-4 py-2 cursor-pointer border border-[#0000001a] rounded-xl font-semibold">
                    Сменить пароль
                </button>
            </div>
        </form>

        <hr class="my-8 md:my-10 border-[#0000001a]">

        {{-- Удаление аккаунта --}}
        <form method="post" class="w-full bg-[#F0F4FA] p-6 sm:p-8 md:p-10 border border-[#0000001a] rounded-2xl mb-20"
            action="{{ route('profile.delete') }}">
            @method('DELETE')
            @csrf

            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-10">
                <div class="flex flex-col md:col-span-2 gap-2.5 justify-center items-center text-center">
                    <p class="text-xl sm:text-2xl font-semibold">Удалить аккаунт</p>
                    <span class="opacity-50 text-sm sm:text-base">
                        Введите <strong class="text-red-900">удалить мой аккаунт навсегда</strong> чтобы подтвердить
                    </span>
                </div>
                <div class="flex flex-col md:col-span-2 gap-5">
                    <input type="text" name="confirm_text" id="confirm_text" placeholder="Введите фразу для удаления"
                        class="w-full px-5 py-3.5 bg-[#F5F9FF] border border-[#0000001a] rounded-xl" autocomplete="off" />
                    @if ($errors->has('confirm_text'))
                        <p class="text-sm text-red-500">{{ $errors->first('confirm_text') }}</p>
                    @endif
                </div>
            </div>

            <div class="form-btn flex justify-end mt-6">
                <button type="submit" id="delete_account_btn"
                    class="opacity-50 bg-red-900 text-white px-4 py-2 cursor-not-allowed border border-[#0000001a] rounded-xl font-semibold"
                    disabled>
                    Удалить аккаунт
                </button>
            </div>
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
                labelIdle: 'Перетащите файл или <span class="filepond--label-action">выберите</span>',
                storeAsFile: true,
                imagePreviewFit: 'cover',
            });

            const pond = FilePond.create(document.querySelector('.filepond'), {
                allowMultiple: false,
                maxFiles: 1,
                instantUpload: false,
                minFileSize: '1KB',
                maxFileSize: '1MB',
                imageValidateSizeMinWidth: 100,
                imageValidateSizeMinHeight: 100,
                
            });





            @if(!empty(Auth::user()->image))
                pond.addFile("{{ asset('storage/' . Auth::user()->image) }}").catch(() => {
                    console.warn('Не удалось загрузить текущий аватар в превью');
                });
            @endif


        //confirm text
                    const confirmInput = document.getElementById('confirm_text');
            const deleteBtn = document.getElementById('delete_account_btn');
            const requiredPhrase = 'удалить мой аккаунт навсегда';

            confirmInput.addEventListener('paste', (e) => {
                e.preventDefault();
            });

            confirmInput.addEventListener('input', () => {
                if (confirmInput.value.trim().toLowerCase() === requiredPhrase) {
                    deleteBtn.disabled = false;
                    deleteBtn.classList.remove('cursor-not-allowed', 'bg-red-600', 'opcacity-50');
                    deleteBtn.classList.add('cursor-pointer', 'bg-red-700', 'opacity-100');
                } else {
                    deleteBtn.disabled = true;
                    deleteBtn.classList.add('cursor-not-allowed', 'bg-red-600', 'opacity-50');
                    deleteBtn.classList.remove('cursor-pointer', 'bg-red-700', 'opacity-100');
                }
            });
        </script>
    @endpush

    @include('particals.footer')

@endsection