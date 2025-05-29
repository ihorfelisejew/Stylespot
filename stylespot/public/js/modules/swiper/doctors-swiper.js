import { initializeSwiper } from "./initialize-swiper.js";

initializeSwiper(".doctors-container", {
    breakpoints: {
        570: {
            spaceBetween: 0,
        },
        0: {
            spaceBetween: 20,
        },
    },
});
