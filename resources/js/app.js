// cookie
if (document.querySelector('#cookie-banner')) {

    document.addEventListener('DOMContentLoaded', function () {
        const banner = document.getElementById('cookie-banner');
        setTimeout(() => {
            banner.classList.add('bottom-0%', 'sm:bottom-[5%]');
        }, 100)
        const acceptBtn = document.getElementById('accept-cookies');

        // Проверяем, есть ли кука "cookies_accepted"
        function getCookie(name) {
            const value = "; " + document.cookie;
            const parts = value.split("; " + name + "=");
            if (parts.length === 2) return parts.pop().split(";").shift();
        }

        if (getCookie('cookies_accepted')) {
            // Кука есть — скрываем баннер
            banner.style.display = 'none';
        }

        acceptBtn.addEventListener('click', function () {
            // Устанавливаем куку на год
            const expires = new Date();
            expires.setFullYear(expires.getFullYear() + 1);
            document.cookie = "cookies_accepted=true; expires=" + expires.toUTCString() + "; path=/";

            // Прячем баннер
            banner.classList.remove('bottom-0%', 'sm:bottom-[5%]');
            setTimeout(() => {
                banner.style.display = 'none';
            }, 600)
        });
    });
}


// loader btn
document.addEventListener('DOMContentLoaded', function () {
    // вставим CSS спиннера один раз
    const style = document.createElement('style');
    style.innerHTML = `
  @keyframes spin { 0% { transform: rotate(0deg);} 100% { transform: rotate(360deg);} }
  .btn-loader-spinner {
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    width: 14px;
    height: 14px;
    display: inline-block;
    animation: spin 0.8s linear infinite;
    vertical-align: middle;
    margin-right: 6px;
  }`;
    document.head.appendChild(style);

    // helper: показать лоадер на кнопке
    function showLoaderOnButton(btn) {
        if (!btn) return;
        if (btn.dataset._locked) return; // уже заблокирована
        btn.dataset._locked = '1';
        // сохраняем оригинал для возможного восстановления
        if (!btn.dataset._origHtml) btn.dataset._origHtml = btn.innerHTML;

        btn.disabled = true;
        btn.style.pointerEvents = 'none';
        btn.style.cursor = 'wait';
        btn.style.opacity = '0.6';

        btn.innerHTML = `<span class="btn-loader-spinner" aria-hidden="true"></span> Подождите...`;
    }

    // На всякий случай: откат (если нужен)
    function restoreButton(btn) {
        if (!btn || !btn.dataset._origHtml) return;
        btn.innerHTML = btn.dataset._origHtml;
        btn.disabled = false;
        btn.style.pointerEvents = 'auto';
        btn.style.cursor = 'pointer';
        btn.style.opacity = '1';
        delete btn.dataset._locked;
        // не удаляем origHtml — можно сохранить
    }

    // Привяжем к формам: обработчик submit (надёжнее)
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (e) {
            // e.submitter — кнопка, вызвавшая submit (современные браузеры)
            // fallback: найдём первую .waiting не disabled внутри формы
            const submitBtn = e.submitter || form.querySelector('.waiting:not([disabled])') || form.querySelector('button[type="submit"]');

            // Добавляем небольшой таймаут, чтобы не мешать нативной отправке в редких случаях
            // (обычно setTimeout(0) достаточно)
            setTimeout(() => showLoaderOnButton(submitBtn), 0);
            // НЕ вызывать e.preventDefault() — форма должна уйти
        });
    });

    // На случай, если где-то форма не срабатывает на submit (редкие edge cases),
    // также добавим делегированный click для .waiting, но с отложенным выполнением:
    document.querySelectorAll('.waiting').forEach(btn => {
        btn.addEventListener('click', function (e) {
            // Если кнопка уже заблокирована — ничего не делаем
            if (btn.dataset._locked) return;
            // Отложим UI-изменения, чтобы нативная отправка начала выполняться
            setTimeout(() => {
                // Если элемент всё ещё в DOM — покажем лоадер
                if (document.body.contains(btn)) showLoaderOnButton(btn);
            }, 10); // 0-10ms — безопасно, 10ms для стабильности
        });
    });

});












// search
if (document.querySelector('#search')) {

    (function () {
        const input = document.getElementById('search');
        const phrases = [
            'Ведущий', 'Видеограф', 'Диджей', 'Фамилия специалиста', 'Фотограф', 'Аренда помещения', 'Что-то из описания'
        ];

        const typingSpeed = 100;   // скорость печати (мс на символ)
        const erasingSpeed = 50;   // скорость удаления (мс на символ)
        const pauseBetween = 1500; // пауза после полной фразы (мс)

        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;

        function type() {
            const current = phrases[phraseIndex];
            if (!isDeleting) {
                // печатаем
                input.placeholder = current.slice(0, ++charIndex);

                if (charIndex === current.length) {
                    // вся фраза набрана — ждем и начинаем удалять
                    isDeleting = true;
                    setTimeout(type, pauseBetween);
                } else {
                    setTimeout(type, typingSpeed);
                }
            } else {
                // удаляем
                input.placeholder = current.slice(0, --charIndex);

                if (charIndex === 0) {
                    // удалили — переходим к следующей фразе
                    isDeleting = false;
                    phraseIndex = (phraseIndex + 1) % phrases.length;
                    setTimeout(type, typingSpeed);
                } else {
                    setTimeout(type, erasingSpeed);
                }
            }
        }

        // стартуем
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(type, pauseBetween);
        });
    })();
}


if (document.querySelector('#h_top')) {
    const header = document.getElementById('h_top');
    const headerHeight = header.offsetHeight;
    let lastScroll = 0;

    document.body.style.paddingTop = headerHeight + 'px'; // чтобы контент не прыгал

    window.addEventListener('scroll', () => {
        const currentScroll = window.scrollY;

        if (currentScroll > 200 && currentScroll > lastScroll) {
            // Скролл вниз — скрываем
            header.classList.remove('visible');
        } else if (currentScroll > 200) {
            // Скролл вверх — показываем
            header.classList.add('visible');
        }

        lastScroll = currentScroll;
    });
}








// passwrod toggle
document.addEventListener('DOMContentLoaded', function () {
    function setupPasswordToggle(inputId, showId, hideId) {
        const input = document.getElementById(inputId);
        const show = document.getElementById(showId);
        const hide = document.getElementById(hideId);
        show.addEventListener('click', function () {
            input.type = 'text';
            show.style.display = 'none';
            hide.style.display = 'block';
        });
        hide.addEventListener('click', function () {
            input.type = 'password';
            hide.style.display = 'none';
            show.style.display = 'block';
        });
    }
    if (document.getElementById('password1') && document.getElementById('show_password1') && document.getElementById('hide_password1')) {
        setupPasswordToggle('password1', 'show_password1', 'hide_password1');
    }
    if (document.getElementById('password2') && document.getElementById('show_password2') && document.getElementById('hide_password2')) {
        setupPasswordToggle('password2', 'show_password2', 'hide_password2');
    }
    if (document.getElementById('password3') && document.getElementById('show_password3') && document.getElementById('hide_password2')) {
        setupPasswordToggle('password3', 'show_password3', 'hide_password3');
    }

});









if (document.querySelector('.alert-item')) {
    document.addEventListener("DOMContentLoaded", function () {
        const alerts = document.querySelectorAll(".alert-item");

        alerts.forEach((alert, index) => {
            // Плавное появление
            setTimeout(() => {
                alert.classList.add("opacity-100", "translate-y-0");
            }, index * 200);

            // Закрытие кнопкой
            const closeBtn = alert.querySelector(".close-alert");
            if (closeBtn) {
                closeBtn.addEventListener("click", () => removeAlert(alert));
            }

            // Авто-удаление через 10 секунд
            setTimeout(() => removeAlert(alert), 10000);

            // Свайп
            let startX = 0;
            let startY = 0;
            let currentX = 0;
            let currentY = 0;
            let dragging = false;

            const handleDragStart = (e) => {
                dragging = true;
                startX = e.type.includes("touch") ? e.touches[0].clientX : e.clientX;
                startY = e.type.includes("touch") ? e.touches[0].clientY : e.clientY;
                alert.style.transition = "none";
            };

            const handleDragMove = (e) => {
                if (!dragging) return;
                currentX = e.type.includes("touch") ? e.touches[0].clientX : e.clientX;
                currentY = e.type.includes("touch") ? e.touches[0].clientY : e.clientY;
                const deltaX = currentX - startX;
                const deltaY = currentY - startY;

                // Сдвигаем только вправо или вверх
                const translateX = deltaX > 0 ? deltaX : 0;
                const translateY = deltaY < 0 ? deltaY : 0;

                alert.style.transform = `translate(${translateX}px, ${translateY}px)`;
                const opacity = Math.max(1 - Math.abs(deltaX) / 200 - Math.abs(deltaY) / 200, 0);
                alert.style.opacity = opacity;
            };

            const handleDragEnd = () => {
                if (!dragging) return;
                dragging = false;
                const deltaX = currentX - startX;
                const deltaY = currentY - startY;

                // Если свайп больше 100px по X или Y — удаляем
                if (deltaX > 100 || deltaY < -100) {
                    alert.style.transition = "all 0.3s ease";
                    alert.style.transform = `translate(${deltaX > 100 ? 100 : 0}%, ${deltaY < -100 ? -100 : 0}%)`;
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 300);
                } else {
                    // возвращаем на место
                    alert.style.transition = "all 0.3s ease";
                    alert.style.transform = "";
                    alert.style.opacity = "1";
                }
            };

            alert.addEventListener("mousedown", handleDragStart);
            alert.addEventListener("mousemove", handleDragMove);
            alert.addEventListener("mouseup", handleDragEnd);
            alert.addEventListener("mouseleave", handleDragEnd);

            alert.addEventListener("touchstart", handleDragStart);
            alert.addEventListener("touchmove", handleDragMove);
            alert.addEventListener("touchend", handleDragEnd);
        });

        function removeAlert(el) {
            el.style.transition = "all 0.3s ease";
            el.style.transform = "translate(100%, -100%)";
            el.style.opacity = "0";
            setTimeout(() => el.remove(), 300);
        }
    });
}


// adaptive

if (document.querySelector('#filterModal')) {

    document.addEventListener("DOMContentLoaded", () => {
        const openBtn = document.getElementById("openFilter");
        const closeBtn = document.getElementById("closeFilter");
        const overlay = document.getElementById("filterOverlay");
        const modal = document.getElementById("filterModal");

        function openModal() {
            overlay.classList.remove("hidden");
            modal.classList.add("active");
            modal.classList.remove("hidden");
        }

        function closeModal() {
            overlay.classList.add("hidden");
            modal.classList.remove("active");
            setTimeout(() => modal.classList.add("hidden"), 300);
        }

        openBtn.addEventListener("click", openModal);
        closeBtn.addEventListener("click", closeModal);
        overlay.addEventListener("click", closeModal);
    });
}




// burger menu
if (document.querySelector('#burger-menu')) {
    const burgerMenu = document.getElementById('burger-menu');
    const burgerOverlay = document.getElementById('burger-overlay');

    window.openMenu = function () {
        burgerMenu.classList.remove('translate-x-full');
        burgerOverlay.classList.remove('hidden');
    }

    window.closeMenu = function () {
        burgerMenu.classList.add('translate-x-full');
        burgerOverlay.classList.add('hidden');
    }

    burgerOverlay.addEventListener('click', window.closeMenu);
}


// phone
if (document.querySelector('#phone')) {
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', () => {
        let val = phoneInput.value;

        // Если пустое — ничего не делаем
        if (val.length === 0) return;

        // Если первый символ не "+", добавляем
        if (!val.startsWith('+')) {
            phoneInput.value = '+' + val.replace(/^\+*/, '');
        }
    });
}
