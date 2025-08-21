<footer id="footer" class="footer bg-gradient-to-br from-[#1C1F4C] to-[#2C3E50] py-10 relative overflow-hidden z-100">
    <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- Колонка 1 -->
        <div class="footer-col flex flex-col gap-5 text-[#F5F9FF] lg:order-none">
            <a class="w-fit" href="">
                <img src="{{ asset('img/white-logo.png') }}" alt="logo">
            </a>
            <a class="w-fit opacity-70 hover:opacity-100" href="{{ route('main') }}">Главная</a>
            <a class="w-fit opacity-70 hover:opacity-100" href="{{ route('specialists') }}">Специалисты</a>
            <a class="w-fit opacity-70 hover:opacity-100" href="{{ route('specialists.create') }}">Стать специалистом</a>
            <a class="w-fit opacity-70 hover:opacity-100" href="{{ route('cart.index') }}">Умное бронирование</a>
            <a class="w-fit opacity-70 hover:opacity-100" href="{{ route('blog') }}">Наш блог</a>
            <a class="w-fit opacity-70 hover:opacity-100" href="https://3solnca.ru/">Сделано 3СОЛНЦА</a>
        </div>

        <!-- Колонка 2 -->
        <div class="footer-col flex flex-col gap-5 text-[#F5F9FF] lg:order-none">
            <a class="w-fit opacity-70 hover:opacity-100" href="{{ route('privacy') }}">Политика конфиденциальности</a>
            <a class="w-fit opacity-70 hover:opacity-100" href="{{ route('offer') }}">Публичная оферта</a>
            <a class="w-fit opacity-70 hover:opacity-100" href="{{ route('cookie') }}">Политика cookie-файлов</a>
            <hr class="opacity-50 w-2/3">
            <a class="w-fit opacity-70 hover:opacity-100">Степанов Василий Геннадиевич</a>
            <a class="w-fit opacity-70 hover:opacity-100">ИНН 1234123451212</a>
            <a class="w-fit opacity-70 hover:opacity-100" href="mailto:partymanager@gmail">partymanager@gmail.com</a>
            <a class="w-fit opacity-70 hover:opacity-100">ПАТИМЕНЕДЖЕР {{ date('Y') }} ©</a>
        </div>

        <!-- Колонка 3 (форма) -->
        <div class="footer-col flex flex-col gap-5 text-[#F5F9FF]
                order-first md:col-span-2 lg:col-span-1 lg:order-none">
            <div class="big-title">
                <h2 class="font-semibold text-4xl">Обратная связь</h2>
                <p class="opacity-50">Форма для связи с нами</p>
            </div>

            <form method="post" action="{{ route('form.feedback') }}" class="flex flex-col gap-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" value="{{ old('name') }}" name="name" placeholder="Ваше имя" class="footer_input">
                    <input type="text" value="{{ old('email') }}" name="email" placeholder="Ваша почта" class="footer_input">
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <input type="text" value="{{ old('phone') }}" name="phone" placeholder="Телефон" class="footer_input">
                    <input type="text" value="{{ old('telegram') }}" name="telegram" placeholder="Телеграм" class="footer_input">
                </div>
                <textarea class="footer_input resize-none" name="message" placeholder="Сообщение" rows="2">{{ old('message') }}</textarea>
                <p class="text-sm text-[#d0d4d8]">
                    Нажимая на кнопку, вы даёте согласие на обработку персональных данных в соответствии с
                    <a class="text-[#FFD700] underline" href="{{ route('privacy') }}">Политикой конфиденциальности</a>
                </p>
                <button class="bg-[#FFD700] text-[#1C1F4C] px-4 py-2 font-semibold border border-[#0000001a] rounded-xl">
                    Отправить
                </button>
            </form>
        </div>

    </div>
</footer>