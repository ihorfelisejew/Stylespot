import { clearModal } from "../open-modal.js";
import { calendar } from "../order-modal-data/calendar-logic.js";
import { timeInput } from "../order-modal-data/time-input.js";
import { addError } from "../utils/addError.js";
import { removeError } from "../utils/removeError.js";

const orderModal = document.querySelector(".order-modal");

class Order {
    #csrfToken;

    constructor() {
        this.tokenLeft = false;
        this.submitStatus = false;
    }

    setToken(token) {
        if (!this.tokenLeft) {
            this.#csrfToken = token;
            this.tokenLeft = true;
        } else return false;
    }

    async getOrdersByDate(date) {
        let orders = [];

        try {
            const response = await fetch(
                `/orders/by-date?date=${encodeURIComponent(date)}`,
                {
                    method: "GET",
                    headers: {
                        "X-CSRF-TOKEN": this.#csrfToken, // передаємо csrfToken для безпеки
                    },
                }
            );

            if (!response.ok) {
                throw new Error("Помилка при відправці запиту");
            }

            const data = await response.json();
            orders = data;
        } catch (error) {
            console.error("Error:", error);
            alert("Сталася помилка, спробуйте ще раз.");
        }

        return orders;
    }

    saveOrder() {
        const form = document.getElementById("order-form");

        form.addEventListener("submit", (e) => {
            e.preventDefault();
            this.submitStatus = true;

            if (!this.validateFormData()) {
                return false;
            }

            const time = timeInput.getSelectedTime();
            const date = calendar.getSelectedDate();

            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, "0"); // getMonth() повертає від 0 до 11
            const day = String(date.getDate()).padStart(2, "0");

            const dateTime = `${year}-${month}-${day}T${time}:00`;

            const formData = new FormData(form);
            formData.append("order_datetime", dateTime);

            fetch("/order/store", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": this.#csrfToken,
                },
            })
                .then((response) => {
                    console.log(response);
                    // Перевіряємо, чи відповідь успішна
                    if (!response.ok) {
                        // Якщо відповідь не успішна, виводимо повідомлення та виходимо
                        return response.text().then((text) => {
                            throw new Error(`Помилка сервера: ${text}`);
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Відповідь від сервера:", data); // Виводимо всю відповідь для налагодження

                    if (data.success) {
                        alert("Замовлення успішно надіслано!");
                        clearModal(orderModal);
                    } else {
                        alert("Сталася помилка при відправці замовлення!");
                    }
                })
                .catch((error) => {
                    // Виводимо подробиці помилки
                    console.error("Помилка:", error.message);
                });
        });
    }

    validateFormData() {
        const form = document.getElementById("order-form");

        const nameInput = form.querySelector("input[name='name']");
        const lastNameInput = form.querySelector("input[name='last_name']");
        const emailInput = form.querySelector("input[name='email']");
        const phoneInput = form.querySelector("input[name='phone_number']");

        const name = nameInput.value.trim();
        const lastName = lastNameInput.value.trim();
        const email = emailInput.value.trim();
        const phone = phoneInput.value.trim();

        const errorBlocks = [];

        let message = "";
        // Валідація імені та прізвища
        if (!name & !lastName) {
            message = "Будь ласка, введіть ім'я та прізвище";
            errorBlocks.push(nameInput, lastNameInput);
        } else if (!lastName) {
            message = "Будь ласка, введіть прізвище";
            errorBlocks.push(lastNameInput);
        } else if (!name) {
            message = "Будь ласка, введіть ім'я";
            errorBlocks.push(nameInput);
        }

        // Валідація телефону
        const phonePattern = /^(?:\+380|0)\d{9}$/;
        if (!phonePattern.test(phone)) {
            if (message.length == 0) {
                message = "Будь ласка, введіть коректний номер телефону.";
            }
            errorBlocks.push(phoneInput);
        }

        //валідація послуг
        const serviceInput = document.getElementById("order-services");
        if (!serviceInput.value.trim()) {
            if (message.length == 0) {
                message = "Будь ласка, оберіть необхідні послуги.";
            }
            const serviceButton = document.querySelector(
                ".get-services__button"
            );

            errorBlocks.push(serviceButton);
        }

        // Валідація email
        const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        if (!emailPattern.test(email)) {
            if (message.length == 0) {
                message = "Будь ласка, введіть коректний email";
            }
            errorBlocks.push(emailInput);
        }

        //валідація лікаря
        const doctorInput = document.getElementById("order-doctor");
        if (!doctorInput.value.trim()) {
            if (message.length == 0) {
                message = "Будь ласка, оберіть лікаря.";
            }
            const doctorButton = document.querySelector(".get-doctor__button");
            errorBlocks.push(doctorButton);
        }

        //валідація дати
        const dateInput = document.getElementById("order-date");
        console.log(dateInput);
        if (!dateInput.value.trim() || dateInput.value.length < 10) {
            if (message.length == 0) {
                message = "Будь ласка, оберіть дату прийому.";
            }
            errorBlocks.push(dateInput.parentNode);
        }

        //валідація часу
        const timeInput = document.getElementById("order-time_input");
        if (!timeInput.value.trim()) {
            if (message.length == 0) {
                message = "Будь ласка, оберіть час прийому.";
            }
            const buttonsBlock = document.querySelector(".order-time__block");
            errorBlocks.push(buttonsBlock);
        }

        if (errorBlocks.length != 0) {
            this.#writeError(errorBlocks, message);
            return false;
        } else {
            return true;
        }
    }

    #writeError(inputBlocks, message) {
        removeError();
        const errorField = document.querySelector(
            ".modal-error__field.order-modal__error"
        );

        errorField.classList.add("active");
        errorField.textContent = message;

        inputBlocks.forEach((block) => {
            addError(block);
        });
    }
}

export const orderController = new Order();
