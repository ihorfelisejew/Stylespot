document.addEventListener("DOMContentLoaded", () => {
    transformMenu();

    const menuButton = document.getElementById("phone-menu-button");
    const menuList = document.querySelector(".header__content .menu__block");
    menuButton.addEventListener("click", () => {
        menuButton.classList.toggle("active");
        menuList.classList.toggle("opened");
    });
});

function transformMenu() {
    const fixedBlock = document.querySelector(".header__content .menu__block");
    const outerContainer = document.querySelector(".header__container");
    const paddingLeft = parseFloat(
        window.getComputedStyle(outerContainer).paddingLeft
    );
    // fixedBlock.style.left = `-${paddingLeft}px`;
}
