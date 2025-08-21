@extends('layouts.app')

@section('title', 'Добавить услугу | ПАТИМЕНЕДЖЕР')





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
@endpush


@section('content')

    @include('particals.header')





    <main class="container relative min-h-screen">

        <!-- title -->
        <div class="pt-10 pb-10 text-center big-title sm:text-left">
            <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Добавить услугу</h2>
            <p class="text-sm opacity-50 sm:text-base">Можно добавить до 10 дополнительных услуг</p>
        </div>



        <form class="bg-[#F0F4FA] w-full p-6 md:p-10 border border-[#0000001a] rounded-2xl mb-30" method="POST"
            action="{{route('service.create.action', $specialist->id)}}" enctype="multipart/form-data">
            @csrf

            {{-- фото --}}
            <div class="relative grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-10">
                <div class="flex flex-col gap-2.5 justify-center items-center md:col-span-2 text-center md:text-left">
                    <p class="text-xl md:text-2xl font-semibold">
                        <span class="text-[#c2a500]">*</span> Загрузите 1 фото
                    </p>
                    <span class="opacity-50 text-sm md:text-base">Размер файла не более 1 МБ, минимум 200×200 px</span>
                </div>

                <div class="flex flex-col gap-5 md:col-span-2">
                    <div class="filepond-wrapper w-full">
                        <input type="file" name="preview_image" class="filepond filepond-input" />
                    </div>
                    @if (isset($errors) && $errors->has('preview_image'))
                        <p class="text-sm text-red-500">{{ $errors->first('preview_image') }}</p>
                    @endif
                </div>
            </div>

            <div class="w-full h-[1px] bg-[#0000001a] my-8 md:my-10"></div>

            <div class="flex flex-col mini-title gap-2.5 pb-5">
                <p class="text-xl md:text-2xl font-semibold">Заголовок, описание и стоимость</p>
                <span class="opacity-50 text-sm md:text-base">Заполните поля информации</span>
            </div>

            <div class="flex flex-col gap-5">
                {{-- Заголовок --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm md:text-base">
                        <span class="text-[#c2a500]">*</span> Заголовок:
                    </p>
                    <input placeholder="Как называется услуга?" name="title" value="{{ old('title') }}"
                        class="w-full border border-[#0000001a] rounded-xl px-4 py-3 bg-[#F5F9FF]" type="text">
                    @if (isset($errors) && $errors->has('title'))
                        <p class="text-sm text-red-500">{{ $errors->first('title') }}</p>
                    @endif
                </div>

                {{-- Описание --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm md:text-base">Описание:</p>
                    <textarea placeholder="Расскажите без лишней воды, что вы предлагаете?" name="description"
                        class="w-full h-[120px] md:h-[150px] border border-[#0000001a] rounded-xl px-4 py-3 bg-[#F5F9FF] resize-none">{{ old('description') }}</textarea>
                    @if (isset($errors) && $errors->has('description'))
                        <p class="text-sm text-red-500">{{ $errors->first('description') }}</p>
                    @endif
                </div>

                {{-- Стоимость --}}
                <div class="flex flex-col gap-2">
                    <p class="font-semibold opacity-50 text-sm md:text-base">
                        <span class="text-[#c2a500]">*</span> Стоимость:
                    </p>
                    <input value="{{ old('price') }}" type="number" placeholder="10000" name="price"
                        class="w-full border border-[#0000001a] rounded-xl px-4 py-3 bg-[#F5F9FF]">
                    @if (isset($errors) && $errors->has('price'))
                        <p class="text-sm text-red-500">{{ $errors->first('price') }}</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row items-center gap-5 mt-10">
                <a class="text-gray-500 opacity-50 px-4 py-2 border border-[#0000001a] rounded-xl hover:opacity-100 font-semibold text-center"
                    href="{{route('specialists.show', $specialist->id)}}">Назад</a>

                <button
                    class="w-full sm:w-fit bg-[#FFD700] text-[#1C1F4C] px-4 py-2 font-normal border border-[#0000001a] rounded-xl font-semibold cursor-pointer"
                    type="submit">Добавить</button>
            </div>

            <p class="mt-6 md:mt-10 opacity-50 text-sm md:text-base">
                <span class="text-[#c2a500]">*</span> - обязательные поля
            </p>
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
                labelIdle: 'Перетащите файл или <span class="filepond--label-action">выберите</span>',
                storeAsFile: true,
                maxFiles: 1,
                minFileSize: '1KB',
                maxFileSize: '1MB',
            });

            FilePond.create(document.querySelector('.filepond'), {
                allowMultiple: false,
                maxFiles: 1,
                instantUpload: false,
                minFileSize: '5KB',
                maxFileSize: '1MB',
                imageValidateSizeMinWidth: 200,
                imageValidateSizeMinHeight: 200,
                imagePreviewFit: 'cover',
            });
        </script>
    @endpush
    @include('particals.footer')

@endsection