const element = document.querySelector(".course__list");
let position = element.getBoundingClientRect();

if (position.top < window.innerHeight && position.bottom >= 0) {
    element.classList.add("animate");
}

document.addEventListener("scroll", function () {
    position = element.getBoundingClientRect();
    if (position.top < window.innerHeight && position.bottom >= 0) {
        element.classList.add("animate");
    }
});
