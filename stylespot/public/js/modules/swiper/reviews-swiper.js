import { initializeSwiper } from "./initialize-swiper.js";

initializeSwiper(".reviews-container", {
    slidesPerView: 1.2,
    spaceBetween: 24,
    breakpoints: {
        840: {
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
});
