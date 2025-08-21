@extends('layouts.app')

@section('title', 'Личный кабинет | ПАТИМЕНЕДЖЕР')


@section('content')

    @include('particals.header')

    @push('app-links')
    @endpush



    <main class="container min-h-[100vh]">

        <div class="avatart-profile mt-10 md:mt-20 w-fit mx-auto flex flex-col gap-2.5 items-center text-center">
            <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 avatar relative">
                <img class="w-full h-full object-cover object-center rounded-full border border-[#0000001a]"
                    src="{{asset('storage/' . Auth::user()->image)}}" alt="">
                <a href="{{route('profile.edit')}}"
                    class="absolute top-0 right-0 cursor-pointer opacity-50 hover:opacity-100">
                    <img class="w-5 h-5 sm:w-6 sm:h-6" src="{{asset('ico/edit-profile.png')}}" alt="">
                </a>
            </div>
            <p class="font-semibold text-2xl sm:text-3xl md:text-4xl break-words max-w-[300px] sm:max-w-full">
                {{ Auth::user()->name }}
            </p>
            <div class="avatart-profile__info flex flex-col gap-1 justify-center items-center">
                <p class="opacity-50 text-sm sm:text-base">{{ Auth::user()->email }}</p>
            </div>
        </div>

        <div class="profile-tabs__btns flex flex-wrap justify-center gap-3 sm:gap-5 items-center my-6 sm:my-10">
            <p
                class="profile-tabs__btns__item w-full sm:w-auto text-center px-4 py-2 rounded-xl cursor-pointer font-semibold border border-[#0000001a] bg-[#FFD700]">
                Мои карточки
            </p>
            <p
                class="profile-tabs__btns__item w-full sm:w-auto text-center px-4 py-2 rounded-xl cursor-pointer font-semibold border border-[#0000001a] transition-all transition-[.3s] hover:border-[#ffd900] bg-[#F5F9FF]">
                Мои брони
            </p>
            <p
                class="profile-tabs__btns__item relative w-full sm:w-auto text-center px-4 py-2 rounded-xl cursor-pointer font-semibold border border-[#0000001a] transition-all transition-[.3s] hover:border-[#ffd900] bg-[#F5F9FF]">
                Заявки на бронь
                @if($orders->where('status', 'waiting')->count() > 0)
                    <span
                        class="absolute top-0 right-0 w-5 h-5 sm:w-6 sm:h-6 transform translate-x-1/2 -translate-y-1/2 flex justify-center items-center text-xs sm:text-sm bg-[#FFD700] rounded-full">
                        {{$orders->where('status', 'waiting')->count() > 9 ? '9+' : $orders->where('status', 'waiting')->count()}}
                    </span>
                @endif
            </p>
            <p
                class="profile-tabs__btns__item w-full sm:w-auto text-center px-4 py-2 rounded-xl cursor-pointer font-semibold border transition-all transition-[.3s] border-[#ffd900] bg-[#F5F9FF]">
                Умное бронирование
            </p>
        </div>










        <div class="profile-tabs">



            {{-- карточки --}}
            @if($specialists->count() > 0)

                <div class="profile-tab grid grid-cols-1 sm:grid-cols-2 gap-5">
@if($specialists->count() < 10)
                    <a class="w-full h-full border border-[#0000001a] bg-[#f0f4fa] flex justify-center items-center rounded-2xl font-semibold hover:bg-[#FFD700] min-h-[100px] transition-all transition-[.3s]"
                        href="{{route('specialists.create')}}">
                        Разместить карточку
                    </a>
                    @endif

                    @foreach ($specialists as $specialist)
                        <div
                            class="profile-spec grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5 p-4 rounded-2xl border border-[#0000001a] w-full relative bg-[#f0f4fa]">

                            <a href="{{route('specialists.show', ['id' => $specialist->id])}}">
                                <img class="profile-spec__img rounded-2xl aspect-square w-full object-center object-cover"
                                    src="{{asset('storage/' . $specialist->specImages->first()->image)}}" alt="">
                            </a>

                            <div class="profile-spec__info sm:col-span-2 flex flex-col justify-between mt-3 sm:mt-0">
                                <div>
                                    <div class="profile-spec__info--breadcrumb flex flex-wrap gap-2.5 text-sm">
                                        <a class="font-medium opacity-50 hover:opacity-100"
                                            href="{{route('specialists.byCategory', ['category' => $specialist->category->slug])}}">
                                            {{$specialist->category->name}}
                                        </a>
                                        <span class="font-medium opacity-50">/</span>
                                        <a class="font-medium opacity-50 hover:opacity-100"
                                            href="{{route('specialists.bySubcategory', ['category' => $specialist->category->slug, 'subcategory' => $specialist->subcategory->slug])}}">
                                            {{$specialist->subcategory->name}}
                                        </a>
                                    </div>

                                    <a href="{{route('specialists.show', ['id' => $specialist->id])}}"
                                        class="font-semibold text-base sm:text-lg mt-1.5 line-clamp-1">
                                        {{$specialist->title}}
                                    </a>

                                    <p class="font-semibold opacity-50 text-sm mt-1.5">
                                        от {{$specialist->price}} ₽ / {{$specialist->getPriceTypeLabel()}}
                                    </p>

                                    <div class="profile-spec__info--stat flex gap-x-2.5 gap-y-1.5 flex-wrap mt-4">
                                        <div
                                            class="profile-spec__info--stat__item flex gap-2.5 items-center opacity-50 text-xs sm:text-sm">
                                            <img class="w-4 h-4" src="{{ asset('ico/clock.png') }}" alt="">
                                            <p>{{ date('d.m.Y', strtotime($specialist->created_at)) }}</p>
                                        </div>
                                        <div
                                            class="profile-spec__info--stat__item flex gap-2.5 items-center opacity-50 text-xs sm:text-sm">
                                            <img class="w-4 h-4" src="{{ asset('ico/star.svg') }}" alt="">
                                            <p>
                                                {{$specialist->reviews->avg('rating') == '' ? ' - ' : $specialist->reviews->avg('rating')}}
                                                ({{$specialist->reviews->count()}} отзывов)
                                            </p>
                                        </div>
                                        <div
                                            class="profile-spec__info--stat__item flex gap-2.5 items-center opacity-50 text-xs sm:text-sm">
                                            <img class="w-4 h-4" src="{{ asset('ico/eye.png') }}" alt="">
                                            <p>{{$specialist->visits->count()}}</p>
                                        </div>
                                    </div>

                                    <div
                                        class="profile-spec__info--stat flex gap-x-2.5 gap-y-1.25 flex-wrap mt-1.5 text-xs sm:text-sm">
                                        <div class="opacity-50">Проверен: Нет</div>
                                        <div class="opacity-50">В топе: {{$specialist->promoted_until != null ? ($specialist->promoted_until >= \Carbon\Carbon::now() ? 'до ' . date('d.m.Y', strtotime($specialist->promoted_until)) : 'Нет') : 'Нет' }}</div>
                                    </div>
                                </div>

                                <div class="profile-spec__info--status mt-2.5">
                                    @if($specialist->status == 'on_moderation')
                                        <p class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit bg-[#FFD700]">На
                                            проверке</p>
                                    @elseif($specialist->status == 'verify')
                                        <p
                                            class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit bg-[#00B400] text-white">
                                            Подтверждено</p>
                                    @else
                                        <p
                                            class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit bg-[#7C0000] text-white">
                                            Отклонено</p>
                                    @endif
                                </div>
                            </div>

                            <a class="absolute bottom-2 right-2 opacity-50 hover:opacity-100"
                                href="{{route('specialists.edit', ['specialist' => $specialist->id])}}">
                                <img class="w-5 h-5 sm:w-6 sm:h-6" src="{{ asset('ico/edit-profile.png') }}" alt="">
                            </a>
                        </div>
                    @endforeach
                </div>

            @else
                <div class="profile-tab grid grid-cols-1 gap-5">
                    <div class="w-full h-full flex flex-col gap-5 items-center justify-center text-center p-6">
                        <p class="font-semibold text-xl sm:text-2xl">Разместите свою первую карточку!</p>
                        <a class="px-4 py-2 rounded-xl w-fit bg-[#FFD700] font-semibold border border-[#0000001a]"
                            href="{{route('specialists.create')}}">Разместить</a>
                    </div>
                </div>
            @endif








            {{-- Мои брони --}}
            @if ($my_orders->count() > 0)
                <div class="profile-tab my_orders grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 hidden">
                    @foreach ($my_orders as $my_order)
                        <div
                            class="w-full h-full flex flex-col justify-between gap-5 border border-[#0000001a] bg-[#f0f4fa] rounded-2xl font-semibold p-4 sm:p-5 lg:p-6">

                            <div class="flex flex-col gap-2.5">
                                <a class="flex flex-col hover:opacity-50"
                                    href="{{route('specialists.show', ['id' => $my_order->specialist->id])}}">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Исполниетль:</p>
                                    <p class="font-medium text-base sm:text-lg line-clamp-4">{{$my_order->specialist->title}}</p>
                                </a>

                                <span class="flex flex-col">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Номер:</p>
                                    <p class="font-medium text-base">{{$my_order->phone}}</p>
                                </span>

                                <span class="flex flex-col gap-0">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Описание:</p>
                                    <p class="font-medium text-base">
                                        {{$my_order->comment != '' ? $my_order->comment : 'Без описания'}}
                                    </p>
                                </span>

                                <span class="flex flex-col gap-0">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Дата отправки:</p>
                                    <p class="font-medium text-base">{{date('d.m.Y', strtotime($my_order->created_at))}}</p>
                                </span>
                            </div>

                            <div
                                class="my_orders__action flex flex-col sm:flex-row sm:justify-between gap-3 sm:gap-0 items-start sm:items-center">
                                @if($my_order->status == 'waiting')
                                    <p class="font-semibold text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#FFD700]">Ожидает
                                        подтверждения</p>
                                @elseif($my_order->status == 'verify')
                                    <p class="font-semibold text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#00B400] text-white">
                                        Ожидайте звонка</p>
                                @else
                                    <p class="font-semibold text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#7C0000] text-white">
                                        Отклонено </p>
                                @endif

                                @if($my_order->status == 'waiting')
                                    <form method="post" action="{{route('orders.destroy', ['id' => $my_order->id])}}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="cursor-pointer opacity-50 hover:opacity-100">
                                            <img src="{{ asset('ico/xmark.png') }}" alt="">
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="profile-tab my_orders grid grid-cols-1 gap-5 hidden">
                    <div class="w-full h-full flex flex-col gap-5 items-center justify-center text-center px-4">
                        <p class="font-semibold text-xl sm:text-2xl">Здесь будут ваши заявки на простое бронирование</p>
                        <a class="px-4 py-2 rounded-xl w-fit bg-[#FFD700] font-semibold border border-[#0000001a]"
                            href="{{route('specialists')}}">К исполнителям</a>
                    </div>
                </div>
            @endif










            {{-- Заявки на бронь --}}
            @if($orders->count() > 0)
                <div class="profile-tab my_orders grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 hidden">
                    @foreach ($orders as $order)
                        <div
                            class="w-full h-full flex flex-col justify-between gap-5 border border-[#0000001a] bg-[#f0f4fa] rounded-2xl font-semibold p-4 sm:p-5 lg:p-6">

                            <div class="flex flex-col gap-2.5">
                                <span class="flex flex-col">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Имя:</p>
                                    <p class="font-medium text-base sm:text-lg">{{$order->user->name}}</p>
                                </span>

                                <span class="flex flex-col">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Номер:</p>
                                    <p class="font-medium text-base">
                                        {{ $order->status == 'verify' ? $order->phone : 'Скрыто до подтверждения'}}
                                    </p>
                                </span>

                                <span class="flex flex-col gap-0">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Описание:</p>
                                    <p class="font-medium text-base">{{ $order->comment != '' ? $order->comment : 'Без описания'}}
                                    </p>
                                </span>

                                <a href="{{route('specialists.show', ['id' => $order->specialist->id])}}"
                                    class="flex flex-col gap-0 hover:opacity-50">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Услуга:</p>
                                    <p class="font-medium text-base">{{$order->specialist->title}}</p>
                                </a>

                                <span class="flex flex-col gap-0">
                                    <p class="font-semibold opacity-50 text-sm sm:text-base">Дата отправки:</p>
                                    <p class="font-medium text-base">{{ date('d.m.Y', strtotime($order->created_at))}}</p>
                                </span>
                            </div>

                            <div
                                class="my_orders__action flex flex-col sm:flex-row sm:justify-between gap-3 sm:gap-0 items-start sm:items-center">
                                @if($order->status == 'waiting')
                                    <p class="font-semibold text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#FFD700]">Ожидает
                                        подтверждения</p>
                                @elseif($order->status == 'verify')
                                    <p class="font-semibold text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#00B400] text-white">Ожидает
                                        звонка</p>
                                @else
                                    <p class="font-semibold text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#7C0000] text-white">
                                        Отклонено</p>
                                @endif

                                @if ($order->status == 'waiting')
                                    <div class="flex gap-2.5 items-center">
                                        <form method="post" action="{{route('orders.cancel', ['id' => $order->id])}}">
                                            @method('PUT')
                                            @csrf
                                            <button class="cursor-pointer opacity-50 hover:opacity-100">
                                                <img src="{{ asset('ico/xmark.png') }}" alt="">
                                            </button>
                                        </form>
                                        <form method="post" action="{{route('orders.confirm', ['id' => $order->id])}}">
                                            @method('PUT')
                                            @csrf
                                            <button class="cursor-pointer opacity-50 hover:opacity-100">
                                                <img src="{{ asset('ico/check-circle.png') }}" alt="">
                                            </button>
                                        </form>
                                    </div>
                                @elseif($order->status == 'verify')
                                    <a href="tel:{{ $order->phone }}">
                                        <img src="{{ asset('ico/call.png') }}" alt="">
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="profile-tab my_orders grid grid-cols-1 gap-5 hidden text-center px-4">
                    <p class="font-semibold text-xl sm:text-2xl max-w-[700px] mx-auto">Здесь будут все ваши активные заявки</p>
                    <span class="opacity-50 font-medium">После подтверждения не забывайте связываться с клиентом</span>
                </div>
            @endif






            {{-- Умное бронирование --}}
            @if($smart_orders->count() > 0)
                <div class="profile-tab tab-statistics grid hidden grid-cols-1 gap-5">
                    @foreach ($smart_orders as $order)

                        <div class="smart-booking flex flex-col gap-5 p-6 bg-[#f0f4fa] border border-[#0000001a] rounded-2xl">
                            {{-- Заголовок --}}
                            <div class="smart-booking--title flex flex-col sm:flex-row sm:items-center gap-2.5 justify-between">
                                <p class="font-semibold text-xl">Умное бронирование №{{$order->id}}</p>
                                <span class="opacity-50">{{ date('d.m.Y', strtotime($order->created_at))}}</span>
                            </div>

                            {{-- Список специалистов --}}
                            <div class="smart-booking__specialists grid grid-cols-1 lg:grid-cols-2 gap-2.5 w-full">
                                @foreach ($order->SmartOrderSpecialists as $orderSpecialist)
                                    @php
                                        $specialist = $orderSpecialist->specialist;
                                        $image = $specialist->specImages->first()->image;
                                        $status = $orderSpecialist->status ?? 'waiting';
                                    @endphp

                                    <a href="{{ route('specialists.show', ['id' => $specialist->id]) }}"
                                        class="smart-booking__specialists__item col-span-1 p-5 bg-[#00000007] hover:bg-[#0000000e] rounded-md border border-[#00000009]  gap-5 flex flex-col sm:grid sm:grid-cols-5">
                                        <img class="rounded-xl sm:col-span-1 w-full h-auto object-cover aspect-square"
                                            src="{{ $image ? asset('storage/' . $image) : asset('ico/default.png') }}"
                                            alt="{{ $specialist->title }}">

                                        <div class="smart-booking__specialists__item--info sm:col-span-4 flex flex-col gap-1">
                                            <p class="font-medium opacity-50 text-sm sm:text-base">{{ $specialist->category->name }} /
                                                {{ $specialist->subcategory->name }}</p>
                                            <h2 class="font-semibold text-lg sm:text-xl line-clamp-2">{{ $specialist->title }}</h2>
                                            <span class="font-semibold opacity-50 text-sm sm:text-base">от
                                                {{ number_format($specialist->price, 0, ' ', ' ') }} ₽/
                                                {{ $specialist->getPriceTypeLabel() }}</span>

                                            @if($status == 'waiting')
                                                <p
                                                    class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#FFD700]">
                                                    В работе</p>
                                            @elseif($status == 'verify')
                                                <p
                                                    class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#00B400] text-white">
                                                    Подтверждено</p>
                                            @else
                                                <p
                                                    class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#7C0000] text-white">
                                                    Отклонено</p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            {{-- Информация о заказе --}}
                            <div class="smart-booking__info grid grid-cols-1 sm:grid-cols-3 gap-5">
                                <div class="flex flex-col gap-1">
                                    <p class="opacity-50 font-semibold text-sm sm:text-base">Выбранная дата</p>
                                    <p class="font-medium text-sm sm:text-base">{{ $order->current_date }}</p>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="opacity-50 font-semibold text-sm sm:text-base">Социальная сеть</p>
                                    <p class="font-medium text-sm sm:text-base line-clamp-1">{{ $order->social_network }}</p>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <p class="opacity-50 font-semibold text-sm sm:text-base">Номер телефона</p>
                                    <p class="font-medium text-sm sm:text-base">{{ $order->phone }}</p>
                                </div>
                            </div>

                            <div class="smart-booking--info w-full mt-2">
                                <p class="opacity-50 font-semibold text-sm sm:text-base">Комментарий</p>
                                <p class="font-medium text-sm sm:text-base">{{ $order->comment ?: 'Без комментария' }}</p>
                            </div>

                            {{-- Статус заказа --}}
                            @php $orderStatus = $order->status ?? 'waiting'; @endphp
                            @if($orderStatus == 'waiting')
                                <p class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#FFD700] mt-2">В
                                    работе</p>
                            @elseif($orderStatus == 'verify')
                                <p
                                    class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#00B400] text-white mt-2">
                                    Подтверждено</p>
                            @else
                                <p
                                    class="font-semibold text-xs sm:text-sm px-2 py-1.25 rounded-lg w-fit h-fit bg-[#7C0000] text-white mt-2">
                                    Отклонено</p>
                            @endif

                        </div>

                    @endforeach
                </div>
            @else
                <div class="profile-tab tab-statistics grid hidden grid-cols-1 gap-5">
                    <p class="font-semibold text-2xl max-w-[700px] mx-auto text-center">
                        Здесь появятся ваши специалисты после умного бронирования
                    </p>
                </div>
            @endif

        </div>


        <form class="mx-auto w-fit my-10" method="get" action="{{route('signout')}}">
            @csrf
            <button
                class="opacity-50 hover:opacity-100 px-4 py-2 rounded-xl cursor-pointer font-semibold border border-[#0000001a] flex gap-2.5 items-center  ">
                <p>выход</p>
                <img src="{{ asset('ico/log-out.png') }}" alt="">
            </button>
        </form>
    </main>





    @include('particals.footer')


    @push('app-scripts')
<script>

// profile tabs
if (document.querySelector('.profile-tabs__btns__item')) {
    document.addEventListener("DOMContentLoaded", () => {
        const tabs = document.querySelectorAll(".profile-tabs__btns__item");
        const tabContents = document.querySelectorAll(".profile-tab");
        const tabAnchors = ["#cards", "#mybooking", "#booking", "#smartbooking"]; // якоря для вкладок

        function activateTabByIndex(index) {
            tabs.forEach(t => {
                t.classList.remove("bg-[#FFD700]");
                t.classList.add("bg-[#F5F9FF]");
            });
            tabContents.forEach(c => c.classList.add("hidden"));

            tabs[index].classList.remove("bg-[#F5F9FF]");
            tabs[index].classList.add("bg-[#FFD700]");
            tabContents[index].classList.remove("hidden");
        }

        tabs.forEach((tab, index) => {
            tab.addEventListener("click", () => {
                window.location.hash = tabAnchors[index];
                activateTabByIndex(index);
            });
        });

        const initialHash = window.location.hash;
        const initialIndex = tabAnchors.indexOf(initialHash);
        if (initialIndex !== -1) {
            activateTabByIndex(initialIndex);
        } else {
            activateTabByIndex(0); 
        }

        window.addEventListener("hashchange", () => {
            const hash = window.location.hash;
            const index = tabAnchors.indexOf(hash);
            if (index !== -1) {
                activateTabByIndex(index);
            }
        });
    });

}

</script>
    @endpush
@endsection