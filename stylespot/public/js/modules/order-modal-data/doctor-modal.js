import { orderController } from "../orders/order.js";
import { timeInput } from "./time-input.js";

class DoctorModal {
    constructor(modalDoctorsBlock) {
        this.modalDoctorsBlock = modalDoctorsBlock;
        this.openDoctorsButton = modalDoctorsBlock.querySelector("button");
        this.doctorsModal = modalDoctorsBlock.querySelector(".modal-doctors");
        this.doctorInput = document.getElementById("order-doctor");
        this.doctorName = document.querySelector(".doctor-name");
        this.doctorButtons = document.querySelectorAll(
            ".modal-doctors__button"
        );

        // Ініціалізуємо події
        this.init();
    }

    init() {
        // Відкриваємо модалку при натисканні на кнопку
        this.openDoctorsButton.addEventListener("click", () => {
            this.toggleModal();
        });

        // Обробляємо вибір лікаря
        this.doctorButtons.forEach((button) => {
            button.addEventListener("click", () => {
                this.handleDoctorSelection(button);
            });
        });

        // Закриваємо модалку при кліку поза її межами
        document.addEventListener("click", (event) => {
            if (
                !this.modalDoctorsBlock.contains(event.target) &&
                this.doctorsModal.classList.contains("active")
            ) {
                this.closeModal();
            }
        });
    }

    getSelectedDoctor() {
        return this.doctorInput.value;
    }

    handleDoctorSelection(button) {
        return new Promise((resolve) => {
            this.doctorInput.value = button.getAttribute("data-value");
            this.doctorName.textContent = button.textContent;
            console.log(this.doctorInput.value);

            timeInput.removeTimeButtons(button.getAttribute("data-value"));

            if (orderController.submitStatus) {
                orderController.validateFormData();
            }

            this.closeModal();
            resolve();
        });
    }

    toggleModal() {
        this.doctorsModal.classList.toggle("active");
        this.openDoctorsButton.classList.toggle("active");
    }

    closeModal() {
        this.doctorsModal.classList.remove("active");
        this.openDoctorsButton.classList.remove("active");
    }

    updateDoctor(doctorId, doctorName) {
        this.doctorInput.value = doctorId;
        this.doctorName.textContent = doctorName;

        this.doctorButtons.forEach((button) => {
            if (button.getAttribute("data-value") === doctorId) {
                this.handleDoctorSelection(button);
            }
        });
    }

    clearDoctor() {
        this.doctorInput.value = "";
        this.doctorName.textContent = "";
    }
}

const generalModal = document.querySelector(".order-modal");
const modalDoctorsBlock = generalModal.querySelector(".get-doctors__block");
export const doctorModal = new DoctorModal(modalDoctorsBlock);
