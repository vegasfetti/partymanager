@php
    // session()->flash('alerts', [
    // ['type' => 'success', 'message' => 'Данные успешно обновлены'],
    // ['type' => 'info', 'message' => 'Следующее обновление доступно через 24ч'],
    // ['type' => 'error', 'message' => 'Нельзя поменять поля в форме'],
    // ]);
@endphp
@if(session()->has('alerts'))
    <div class="fixed top-0 left-0 right-0 sm:top-5 sm:right-5 sm:left-auto z-[5000] flex flex-col gap-5">
        @foreach(session('alerts') as $index => $alert)
            <div
                class="alert-item transform opacity-0 -translate-y-5 transition-all duration-300 w-full bg-[#F5F9FF] p-4 sm:p-5 flex items-center gap-4 sm:gap-5 shadow rounded-none sm:rounded-2xl relative">

                <!-- Иконка -->
                <div
                    class="flex-shrink-0 flex items-center justify-center bg-gray-100 border border-[#0000001a] h-12 w-12 sm:h-15 sm:w-15 rounded-full">
                    <img class="w-6 h-6 sm:w-8 sm:h-8" src="{{ asset('ico/' . $alert['type'] . '-alert.png') }}" alt="">
                </div>

                <!-- Текст -->
                <div class="flex-1 flex flex-col gap-1 alert-message">
                    <p class="text-lg sm:text-2xl font-semibold">
                        @if ($alert['type'] == 'success') Успех
                        @elseif ($alert['type'] == 'info') Информация
                        @elseif ($alert['type'] == 'error') Ошибка
                        @endif
                    </p>
                    <span class="text-sm sm:text-lg font-medium opacity-50 break-words">
                        {{ $alert['message'] }}
                    </span>
                </div>

                <!-- Кнопка закрытия -->
                <img src="{{ asset('ico/close.png') }}" alt=""
                    class="close-alert absolute top-3 sm:top-[15px] right-3 sm:right-[15px] cursor-pointer w-4 h-4 sm:w-5 sm:h-5">
            </div>
        @endforeach
    </div>
@endif