const addReviewButton = document.querySelector(".add-review");
const orderButtons = document.querySelectorAll(".open-order-modal__button");
const closeModalButtons = document.querySelectorAll(".close-modal");
const reviewModal = document.querySelector(".review-modal");
const orderModal = document.querySelector(".order-modal");

import { calendar } from "./order-modal-data/calendar-logic.js";
import { scrollControl } from "./utils/scrollFunctions.js";
import { doctorModal } from "./order-modal-data/doctor-modal.js";
import { serviceModal } from "./order-modal-data/services-modal.js";
import { timeInput } from "./order-modal-data/time-input.js";
import { orderController } from "./orders/order.js";
import { removeError } from "./utils/removeError.js";

scrollControl.addScrollFunction(document.querySelector(".form-content"));

function openModal(modal) {
    modal.classList.add("active");
    scrollControl.toggleWindowScroll();
    setTimeout(() => {
        document.addEventListener("click", closeModalOnClickOutside);
    }, 10);
}

export function clearModal(modal) {
    modal.querySelectorAll("input").forEach((input) => (input.value = ""));
    if (modal == orderModal) {
        serviceModal.clearServices();
        calendar.clearCalendar();
        timeInput.clearTimes();
        doctorModal.clearDoctor();
        orderController.submitStatus = false;
        removeError();
    }
}

function closeModal() {
    document.querySelectorAll(".modal-view.active").forEach((modal) => {
        modal.classList.remove("active");
        clearModal(modal);
    });
    document.querySelector(".calendar-container").classList.remove("active");
    scrollControl.toggleWindowScroll();
    document.removeEventListener("click", closeModalOnClickOutside);
}

function closeModalOnClickOutside(event) {
    if (
        (orderModal.classList.contains("active") &&
            !orderModal
                .querySelector(".modal__content")
                .contains(event.target)) ||
        (reviewModal.classList.contains("active") &&
            !reviewModal
                .querySelector(".modal__content")
                .contains(event.target))
    ) {
        closeModal();
    }
}

closeModalButtons.forEach((button) =>
    button.addEventListener("click", closeModal)
);
addReviewButton.addEventListener("click", () => openModal(reviewModal));

orderButtons.forEach((orderButton) => {
    orderButton.addEventListener("click", () => {
        const doctorName = orderButton.getAttribute("data-doctor");
        const serviceName = orderButton.getAttribute("data-service");
        const serviceId = orderButton.getAttribute("data-service-number");
        const doctorId = orderButton.getAttribute("data-doctor-number");

        if (doctorName) {
            doctorModal.updateDoctor(doctorId, doctorName);
        }

        if (serviceName) {
            serviceModal.updateServiceDroplist(serviceName, serviceId);
        }

        openModal(orderModal);
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const inputs = [];
    inputs.push(orderModal.querySelector("input[name='name']"));
    inputs.push(orderModal.querySelector("input[name='last_name']"));
    inputs.push(orderModal.querySelector("input[name='phone_number']"));
    inputs.push(orderModal.querySelector("input[name='email']"));

    inputs.forEach((input) => {
        input.addEventListener("input", () => {
            if (orderController.submitStatus) {
                orderController.validateFormData();
            }
        });
    });
});
