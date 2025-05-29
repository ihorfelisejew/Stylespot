document.addEventListener("DOMContentLoaded", function () {
    const serviceButtons = document.querySelectorAll(".service-button");
    const hiddenInput = document.getElementById("selected-services");

    serviceButtons.forEach((button) => {
        button.addEventListener("click", function () {
            const serviceId = this.getAttribute("data-service-id");
            let selectedServices = hiddenInput.value
                ? hiddenInput.value.split(",")
                : [];

            if (selectedServices.includes(serviceId)) {
                selectedServices = selectedServices.filter(
                    (id) => id !== serviceId
                );
                this.classList.remove("selected");
            } else {
                selectedServices.push(serviceId);
                this.classList.add("selected");
            }

            hiddenInput.value = selectedServices.join(",");
        });
    });
});
