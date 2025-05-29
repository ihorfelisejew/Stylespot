import { orderController } from "../orders/order.js";
import { scrollControl } from "../utils/scrollFunctions.js";

class ServiceModal {
    constructor() {
        this.modalServicesBlock = document.querySelector(
            ".get-services__block"
        );
        this.openServicesButton =
            this.modalServicesBlock.querySelector("button");
        this.servicesModal =
            this.modalServicesBlock.querySelector(".modal-services");

        this.servicesInput = document.getElementById("order-services");
        this.serviceButtons = document.querySelectorAll(
            ".modal-service__button"
        );
        this.serviceNames = document.querySelector(".services-names");

        this.selectedServices = [];
        this.selectedServicesNames = [];

        this.init();
    }

    init() {
        this.openServicesButton.addEventListener(
            "click",
            this.toggleModal.bind(this)
        );
        this.serviceButtons.forEach((button) => {
            button.addEventListener(
                "click",
                this.handleServiceSelection.bind(this, button)
            );
        });

        document.addEventListener(
            "click",
            this.closeModalOnClickOutside.bind(this)
        );

        //Додаємо скрол по модалці послуг
        scrollControl.addScrollFunction(this.servicesModal);
    }

    // Відкриває/закриває модальне вікно
    toggleModal() {
        this.servicesModal.classList.toggle("active");
        this.openServicesButton.classList.toggle("active");
    }

    // Оновлення введеного значення послуг в форму
    updateServicesInput() {
        this.servicesInput.value = this.selectedServices.join(",");
        this.serviceNames.textContent = this.selectedServicesNames.join(", ");
    }

    // Обробка вибору послуги
    handleServiceSelection(button) {
        const serviceId = button.getAttribute("data-value");
        button.querySelector(".select").classList.toggle("selected");
        const serviceName = button.querySelector(".service-name").textContent;

        if (this.selectedServices.includes(serviceId)) {
            this.selectedServices = this.selectedServices.filter(
                (id) => id !== serviceId
            );
            this.selectedServicesNames = this.selectedServicesNames.filter(
                (name) => name !== serviceName
            );
        } else {
            this.selectedServices.push(serviceId);
            this.selectedServicesNames.push(serviceName);
        }

        this.updateServicesInput();

        if (orderController.submitStatus) {
            orderController.validateFormData();
        }
    }

    updateServiceDroplist(serviceName, serviceId) {
        this.serviceButtons.forEach((button) => {
            const buttonText = button.querySelector("p").textContent;
            button
                .querySelector("span")
                .classList.toggle("selected", buttonText === serviceName);
        });
        this.selectedServices.push(serviceId);
        this.selectedServicesNames.push(serviceName);
        this.servicesInput.value = serviceId;
        this.serviceNames.textContent = serviceName;
    }

    // Закриття модалки при кліку за її межами
    closeModalOnClickOutside(event) {
        if (
            !this.modalServicesBlock.contains(event.target) &&
            this.servicesModal.classList.contains("active")
        ) {
            this.servicesModal.classList.remove("active");
            this.openServicesButton.classList.remove("active");
        }
    }

    clearServices() {
        this.selectedServices = [];
        this.selectedServicesNames = [];
        this.serviceNames.textContent = "";
        this.serviceButtons.forEach((button) => {
            button.querySelector("span").classList.remove("selected");
        });
    }
}

export const serviceModal = new ServiceModal();
