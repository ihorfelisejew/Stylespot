import { initializeSwiper } from "./initialize-swiper.js";

initializeSwiper(".services-container", {
    slidesPerView: 1.2,
    spaceBetween: 20,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
        renderBullet: (index, className) =>
            `<span class="${className}"></span>`, // Генеруємо всі крапки спочатку
    },
    breakpoints: {
        990: {
            slidesPerView: 3,
        },

        640: {
            slidesPerView: 2,
        },

        540: {
            slidesPerView: 1.6,
        },

        490: {
            slidesPerView: 1.4,
        },
    },
    on: {
        init: function () {
            updatePagination(this);
        },
        slideChange: function () {
            updatePagination(this);
        },
    },
});

function updatePagination(swiper) {
    var bullets = document.querySelectorAll(".swiper-pagination-bullet");
    var currentSlide = swiper.realIndex;

    bullets.forEach((bullet, index) => {
        // Показуємо лише 3 крапки: поточна + 2 сусідні
        if (index >= currentSlide - 1 && index <= currentSlide + 1) {
            bullet.style.display = "block";
        } else {
            bullet.style.display = "none";
        }

        // Активна крапка більша і змінює колір
        if (index === currentSlide) {
            bullet.classList.add("swiper-pagination-bullet-active");
        } else {
            bullet.classList.remove("swiper-pagination-bullet-active");
        }
    });
}
