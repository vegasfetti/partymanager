@extends('layouts.app')

@section('title', 'Умное бронирование | ПАТИМЕНЕДЖЕР')
@section('description', 'Выберите исполнителей, а мы свяжемся и скоординируем всех вместе. ПАТИМЕНЕДЖЕР — ваш надежный помощник в поиске исполнителей.')


@section('content')

    @push('app-links')
        <!-- CSS Flatpickr -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

        <!-- JS Flatpickr -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        <!-- Локализация на русский -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ru.js"></script>
    @endpush


    @include('particals.header')

    <main class="container min-h-[100vh]">

        <div class="smart-booking grid grid-cols-1 lg:grid-cols-2 gap-10 mt-10 mb-10 h-fit">
            <!-- Форма -->
            <div class="smart-booking__form sticky top-20 h-fit order-2 lg:order-1">
                <!-- title -->
                <div class="pb-10 big-title text-left">
                    <h2 class="text-2xl font-semibold hidden lg:block sm:text-3xl md:text-4xl">Умное бронирование</h2>
                    <p class="text-sm opacity-50 hidden lg:block sm:text-base">Выберите исполнителей, а мы свяжемся и
                        скоординируем
                        всех
                        вместе</p>
                    <form
                        class="my-10 flex flex-col gap-5 p-5 md:p-10 bg-[#f0f4fa] rounded-3xl border border-[#0000001a] {{ $cartItems->isEmpty() ? 'opacity-50 pointer-events-none' : '' }}"
                        action="{{ route('smartorder.create') }}" method="POST">
                        @csrf
                        <div class="input flex flex-col gap-2.5">
                            <p class="font-semibold opacity-50 "><span class="text-[#c2a500] font-semibold">*</span> Номер:
                            </p>
                            <input value="{{ old('phone') }}"
                                class="w-full px-4 py-3 bg-[#f5f9ff] border border-[#0000001a] rounded-xl"
                                placeholder="Номер телефона" type="text" name="phone" id="phone">
                            @if(isset($errors) && $errors->has('phone'))
                                <p class="text-sm text-red-500">{{ $errors->first('phone') }}</p>
                            @endif
                        </div>
                        <div class="input flex flex-col gap-2.5">
                            <p class="font-semibold opacity-50 "><span class="text-[#c2a500] font-semibold">*</span> Соц.
                                сеть:</p>
                            <input value="{{ old('network_link') }}"
                                class="w-full px-4 py-3 bg-[#f5f9ff] border border-[#0000001a] rounded-xl"
                                placeholder="Ссылка на соц. сеть для связи" type="text" name="network_link" id="">
                            @if (isset($errors) && $errors->has('network_link'))
                                <p class="text-sm text-red-500">{{ $errors->first('network_link') }}</p>
                            @endif
                        </div>

                        {{-- date --}}
                        <div class="input flex flex-col gap-2.5">
                            <p class="font-semibold opacity-50 "><span class="text-[#c2a500] font-semibold">*</span>
                                Выберите дату:</p>
                            <input value="{{ old('current_date') }}" placeholder="Выберите один или несколько дней"
                                class="w-full px-4 py-3 bg-[#f5f9ff] border border-[#0000001a] rounded-xl"
                                name="current_date" type="text" id="dateRange">
                            @if (isset($errors) && $errors->has('current_date'))
                                <p class="text-sm text-red-500">{{ $errors->first('current_date') }}</p>
                            @endif
                        </div>

                        <div class="input flex flex-col gap-2.5">
                            <p class="font-semibold opacity-50 ">Комментарий:</p>
                            <textarea name="comment" placeholder="Ваши пожелания, мы их учтём"
                                class="w-full bg-[#f5f9ff] border border-[#0000001a] rounded-xl px-4 py-3 resize-none"
                                id="">{{ old('comment') }}</textarea>
                            @if (isset($errors) && $errors->has('comment'))
                                <p class="text-sm text-red-500">{{ $errors->first('comment') }}</p>
                            @endif
                        </div>

                        <div class="checked flex items-start gap-2.5 pt-5">
                            <input name="agreement" class="bg-[#F5F9FF] border border-[#0000001a] accent-[#FFD700]"
                                type="checkbox" class="w-5 h-5 cursor-pointer">
                            <p class="font-light text-sm text-gray-500">Продолжая я соглашаюсь с обработкой
                                персональных
                                данных согласно <a class="text-[#FFD700] underline" href="{{ route('privacy') }}">политике
                                    конфиденциальности</a>, а также с <a class="text-[#FFD700] underline"
                                    href="{{ route('offer') }}">публичной офертой</a> </p>
                        </div>
                        @if (isset($errors) && $errors->has('agreement'))
                            <p class="text-sm text-red-500">{{ $errors->first('agreement') }}</p>
                        @endif

                        <button
                            class="w-full bg-[#FFD700] text-[#1C1F4C] px-4 py-2 cursor-pointer border border-[#0000001a] rounded-xl font-semibold">Оплатить</button>
                    </form>
                </div>
            </div>

            <!-- Список -->
            <div class="smart-booking__list flex flex-col gap-5 order-1 lg:order-2">
                <div class=" lg:hidden pb-10 big-title text-left">
                    <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Умное бронирование</h2>
                    <p class="text-sm opacity-50 sm:text-base">Выберите исполнителей, а мы свяжемся и скоординируем всех
                        вместе</p>
                </div>
                @if ($cartItems->isEmpty())
                    <h3 class="text-2xl font-semibold text-left md:text-right">Список пуст</h3>
                @else
                    @foreach ($cartItems as $item)
                        <a href="{{ route('specialists.show', $item->specialist->id)}}"
                            class="smart-booking__item border border-[#0000001a] p-4 rounded-xl bg-[#f0f4fa] flex gap-5 relative hover:transform hover:scale-101">
                            <img class="h-[80px] aspect-square object-cover rounded-xl"
                                src="{{ asset('storage/' . $item->specialist->specImages->first()->image)}}" alt="фото">
                            <div class="smart-booking__item--text flex flex-col gap-2.5">
                                <div class="smart-booking__item--info">
                                    <p class="font-semibold line-clamp-1">{{$item->specialist->title }}</p>
                                    <p class="text-sm opacity-50">от {{number_format($item->specialist->price, 0, ' ', ' ')}} ₽/
                                        {{$item->specialist->getPriceTypeLabel()}}
                                    </p>
                                </div>
                                <div class="raing flex flex-col sm:flex-row items-start sm:items-center gap-1.25 sm:gap-5 ">
                                    <div class="flex items-center gap-2.5 opacity-50">
                                        <img class="w-4 h-4 rounded-xl" src="{{ asset('ico/location.png') }}" alt="">
                                        <p class="opacity-50 font-semibold text-sm">{{$item->specialist->city->name}}</p>
                                    </div>
                                    <div class="flex items-center gap-2.5">
                                        <img class="w-4 h-4 rounded-xl" src="{{ asset('ico/star.svg') }}" alt="">
                                        <p class="opacity-50 font-semibold text-sm">{{$item->specialist->getRating()}}</p>
                                    </div>
                                </div>
                            </div>

                            <form class="absolute bottom-3 right-3" method="post"
                                action="{{ route('cart.remove', $item->specialist->id) }}">
                                @csrf
                                @method('DELETE')
                                <button
                                    class="p-2 border border-[#0000001a] rounded-full bg-[#f5f9ff] cursor-pointer hover:opacity-70"><img
                                        src="{{ asset('ico/close.png') }}" alt=""></button>
                            </form>
                        </a>
                    @endforeach

                    @if($cartItems->count() > 1)
                        <form class="w-full flex justify-start md:justify-end" method="post" action="{{route('cart.clear')}}">
                            @method('DELETE')
                            @csrf
                            <button
                                class="w-fit bg-[#f5f9ff] text-[#1C1F4C] px-4 py-2 cursor-pointer border border-[#0000001a] rounded-xl font-semibold">Очистить
                                список</button>
                        </form>
                    @endif
                @endif

                <div class="price-text text-left md:text-right flex flex-col items-start md:items-end">
                    <p class="pb-5 mb-5 border-b-[#0000001a] border-b w-fit text-xl"><span class="opacity-50">Выбрано
                            исполнителей:</span>
                        <span class="font-semibold">{{$cartItems->count()}}</span>
                    </p>

                    <p><span class="opacity-50">Стоиость исполнителей:</span> <span
                            class="font-semibold">Иднивидуально</span></p>
                    <p><span class="opacity-50">Стоиомть бронирования:</span> <span class="font-semibold">5000 ₽</span></p>
                </div>

                @if ($cartItems->isEmpty())
                    <div class="w-full flex items-start md:justify-end">
                        <a class="w-fit bg-[#FFD700] text-[#1C1F4C] px-4 py-2 cursor-pointer border border-[#0000001a] rounded-xl font-semibold"
                            href="{{ route('specialists')}}">К специалистам</a>
                    </div>
                @endif
            </div>
        </div>





        <div class="smart-order__text mt-20">
            <!-- title -->
            <div class=" lg:hidden pb-5 big-title text-left">
                <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Как это работает?</h2>
                <p class="text-sm opacity-50 sm:text-base">Наслаждайтесь праздником, а орагнизацию возьмём на себя</p>
            </div>

            {{-- <p>Выбор подходящего исполнителя — ключ к успешному мероприятию. На платформе Party Manager вы найдёте
                множество
                профессионалов, готовых воплотить ваши идеи в жизнь. Чтобы сделать правильный выбор, рекомендуем обратить
                внимание на несколько важных моментов:</p> --}}

            <div class="flex flex-col gap-5 mt-8 smart-order__text__rules mb-36">
                <div class="smart-order__text__rule">
                    <h3 class="text-lg font-semibold"> Выберите исполнителей</h3>
                    <p>Выберите исполнителей которые вам понравились, от площадок до фотографов. Добавьте их в «Умное
                        бронирование».</p>
                </div>
                <div class="smart-order__text__rule">
                    <h3 class="text-lg font-semibold"> Заполните форму</h3>
                    <p>Заполните свои контакты, что бы мы могли с вами связаться. Также напишите ваши пожелания, мы
                        обязательно это учтём.</p>
                </div>
                <div class="smart-order__text__rule">
                    <h3 class="text-lg font-semibold">Связываемся с вами</h3>
                    <p>После оплаты связываемся с вами для уточнениях всех деталей события</p>
                </div>
                <div class="smart-order__text__rule">
                    <h3 class="text-lg font-semibold">Связываемся с исполнителями</h3>
                    <p>Самостоятельно связываемся с исполнителями освобождая вас от лишних звонков и забот. Если выбранный
                        исполнитель не может принять заказ, поможет поободрать альтернативный вариант.</p>
                </div>
                <div class="smart-order__text__rule">
                    <h3 class="text-lg font-semibold">Наслаждайтесь праздником</h3>
                    <p>Наслаждайтесь вашим праздником, а мы соберём всех исполнителей в одном месте.</p>
                </div>

            </div>
        </div>

    </main>


    @push('app-scripts')
        <script>
            flatpickr("#dateRange", {
                mode: "range",
                dateFormat: "d.m.Y",
                locale: "ru",
                minDate: "today" 
            });
        </script>
    @endpush
    @include('particals.footer')

@endsection