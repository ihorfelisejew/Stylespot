export function initializeSwiper(selector, customOptions = {}) {
    return new Swiper(selector, {
        loop: true, // стандартне налаштування для всіх Swiper
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },
        ...customOptions, // зливаємо додаткові налаштування, які передаємо через параметри
    });
}
