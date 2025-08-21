<!-- cookie banner -->
    <div class="bg-[#F5F9FF] fixed px-6 sm:px-7.5 py-4 sm:py-5 w-full sm:w-fit border border-[#0000001a] rounded-none sm:rounded-2xl shadow left-0 sm:left-[5%]  bottom-[-100%]"
        id="cookie-banner">
        <!-- title -->
        <div class="pb-5 text-left">
            <h2 class="text-2xl font-semibold sm:text-3xl md:text-4xl">Мы используем cookie-файлы 🍪</h2>
            <p class="text-sm opacity-50 sm:text-base">Они нужны, что бы вам было удобнее пользоваться сайтом</p>
        </div>
        <div class="cookie-btns flex items-center gap-5">
            <button
                class="bg-[#FFD700] text-black font-semibold cursor-pointer border border-[#0000001a] rounded-xl px-4 py-2"
                id="accept-cookies">Хорошо</button>
            <a class="bg-[#f5f9ff] text-black font-semibold cursor-pointer border border-[#0000001a] rounded-xl px-4 py-2"
                href="{{route('cookie')}}">Подробнее</a>
        </div>
    </div>