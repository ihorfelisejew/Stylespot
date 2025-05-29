export function addReview(csrfToken) {
    document
        .querySelector(".review-form")
        .addEventListener("submit", function (event) {
            event.preventDefault(); // Зупиняємо перезавантаження сторінки

            const formData = new FormData(this);

            fetch("/reviews/store", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: formData,
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Помилка при відправці форми");
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log(data.message);
                    // Очистити форму або відобразити повідомлення про успіх
                    this.reset();
                    alert(data.message);
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert("Сталася помилка, спробуйте ще раз.");
                });
        });
}
