export function removeError() {
    const orderModal = document.querySelector(".order-modal");
    const errorBlocks = orderModal.querySelectorAll(".error");
    errorBlocks.forEach((block) => {
        block.classList.remove("error");
    });
    document.querySelector(".order-modal__error").classList.remove("active");
}
