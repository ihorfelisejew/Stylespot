/*------modules---------*/
import "./modules/swiper/doctors-swiper.js";
import "./modules/open-modal.js";
import "./modules/reviews/review-stars.js";
import "./modules/swiper/reviews-swiper.js";
import "./modules/swiper/services-swiper.js";
import "./modules/course-list.js";
import "./modules/openPhoneMenu.js";

/*-----functions-------*/
import { addReview } from "./modules/reviews/add-review.js";
import { dateInputLogic } from "./modules/order-modal-data/date-input.js";

/*-----objects---------*/
import { calendar } from "./modules/order-modal-data/calendar-logic.js";
import { orderController } from "./modules/orders/order.js";
/*------main-code-------*/
document.addEventListener("DOMContentLoaded", function () {
    var metaTag = document.querySelector('meta[name="csrf-token"]');
    var csrfToken = metaTag.getAttribute("content");
    metaTag.parentNode.removeChild(metaTag);

    addReview(csrfToken);
    calendar.renderCalendar();

    orderController.setToken(csrfToken);
    orderController.saveOrder();

    dateInputLogic();
});
