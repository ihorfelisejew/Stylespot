import { doctorModal } from "./doctor-modal.js";
import { orderController } from "../orders/order.js";

class TimeInput {
    constructor() {
        this.timeInput = document.getElementById("order-time_input");
        this.timeButtons = document.querySelectorAll(".order-time__item");
        this.selectedTime;
        this.init();
    }

    init() {
        this.timeButtons.forEach((button) => {
            button.addEventListener("click", () =>
                this.handleTimeButtonClick(button)
            );
        });
    }

    getSelectedTime() {
        return this.selectedTime;
    }

    handleTimeButtonClick(button) {
        if (button.classList.contains("nataliia")) {
            doctorModal.updateDoctor(1, "Наталія Лисюк");
        } else if (button.classList.contains("inna")) {
            doctorModal.updateDoctor(2, "Інна Клонюк");
        }

        // Remove the selected class from all buttons
        this.timeButtons.forEach((anotherButton) => {
            anotherButton.classList.remove("selected");
        });

        // Add the selected class to the clicked button
        button.classList.add("selected");

        this.selectedTime = button.textContent.trim();

        // Set the value of the input to the clicked button's text
        this.timeInput.value = this.selectedTime;

        if (orderController.submitStatus) {
            orderController.validateFormData();
        }
    }

    removeTimeButtons(doctor) {
        this.timeButtons.forEach((button) => {
            if (
                (doctor == 1 && button.classList.contains("inna")) ||
                (doctor == 2 && button.classList.contains("nataliia"))
            ) {
                button.classList.add("hidden");
                button.classList.remove("selected");
            } else {
                button.classList.remove("hidden");
            }
        });
    }

    async changeTimesByDate(date) {
        const currentTime = new Date();
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset()); // коригуємо час на часову зону
        const formattedDate = date.toISOString().split("T")[0];
        const orders = await orderController.getOrdersByDate(formattedDate);

        this.timeButtons.forEach((button) => {
            const buttonTimeText = button.textContent;
            const [buttonHours, buttonMinutes] = buttonTimeText
                .split(":")
                .map(Number);
            const buttonTime = new Date();
            buttonTime.setHours(buttonHours);
            buttonTime.setMinutes(buttonMinutes);
            buttonTime.setSeconds(0);

            let isTimePassed = false;

            if (buttonTime < currentTime) {
                if (date.toDateString() === currentTime.toDateString()) {
                    isTimePassed = true;
                } else {
                    isTimePassed = false;
                }
            }

            let isTimeTaken = false;

            orders.forEach((order) => {
                const orderTime = new Date(order.date);
                orderTime.setSeconds(0);
                orderTime.setMilliseconds(0);

                if (
                    orderTime.getHours() === buttonHours &&
                    orderTime.getMinutes() === buttonMinutes
                ) {
                    isTimeTaken = true;
                }
            });

            if (isTimeTaken || isTimePassed) {
                button.classList.add("inactive");
            } else {
                button.classList.remove("inactive");
            }
        });
    }

    clearTimes() {
        this.timeButtons.forEach((button) => {
            button.classList.remove("inactive", "hidden", "selected");
        });
    }
}

export const timeInput = new TimeInput();
