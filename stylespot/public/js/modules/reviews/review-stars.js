const stars = document.querySelectorAll(".review-modal__star");
let ratingValue = 0;
let ratingInput = document.querySelector(".rating-input");

stars.forEach((star, index) => {
    const starValue = parseInt(star.getAttribute("data-value"));
    star.addEventListener("mousemove", function (event) {
        if (ratingValue != 0) {
            clearStars();
        }
        const rect = star.getBoundingClientRect();
        const isLeftHalf = event.clientX < rect.left + rect.width / 2;
        fillStars(isLeftHalf, index);
    });

    star.addEventListener("mouseleave", () => {
        let index = Math.floor(ratingValue);
        let isLeftHalf = ratingValue % 1 !== 0;
        clearStars();
        if (ratingValue != 0) {
            fillStars(isLeftHalf, index);
        }
    });

    star.addEventListener("click", () => {
        const isLeftHalf = star.classList.contains("half-filled");
        ratingValue = isLeftHalf ? starValue - 0.5 : starValue;
        ratingInput.value = ratingValue;
    });
});

function clearStars() {
    stars.forEach((star) => {
        star.classList.remove("filled", "half-filled");
    });
}

function fillStars(isLeftHalf, index) {
    for (let i = 0; i < stars.length; i++) {
        const star = stars[i];
        star.classList.remove("filled", "half-filled");

        if (i < index) {
            // Заповнюємо повну зірку
            star.classList.add("filled");
        } else if (i === index && isLeftHalf) {
            // Напівзаповнена зірка, якщо наведено на ліву половину
            star.classList.add("half-filled");
            break;
        } else if (i === index && !isLeftHalf) {
            // Повна зірка, якщо наведено на праву половину
            star.classList.add("filled");
            break;
        }
    }
}
