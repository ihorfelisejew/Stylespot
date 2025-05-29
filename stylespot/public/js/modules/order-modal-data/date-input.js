import { orderController } from "../orders/order.js";

export function dateInputLogic() {
    const dateInput = document.getElementById("order-date");

    // Обробка введення дати
    dateInput.addEventListener("input", handleDateInput);
    dateInput.addEventListener("keydown", handleBackspace);

    // Форматування дати
    function handleDateInput() {
        let value = dateInput.value.replace(/\D/g, "").slice(0, 8); // Обрізаємо зайві символи

        // Форматуємо у вигляді дд/мм/рррр
        if (value.length >= 2) value = `${value.slice(0, 2)}/${value.slice(2)}`;
        if (value.length >= 5) value = `${value.slice(0, 5)}/${value.slice(5)}`;

        // Перевірка та корекція введених значень
        const day = parseInt(value.slice(0, 2), 10);
        const month = parseInt(value.slice(3, 5), 10);
        const year = value.slice(6, 10);

        const currentYear = new Date().getFullYear();

        // Корекція дня, місяця, року
        if (day > 31) value = `${value.slice(0, 2)}31${value.slice(4)}`;
        if (month > 12) value = `${value.slice(0, 3)}12${value.slice(5)}`;
        if (year.length === 4 && parseInt(year, 10) < currentYear) {
            value = `${value.slice(0, 6)}${currentYear}`;
        }

        if (orderController.submitStatus) {
            orderController.validateFormData();
        }

        dateInput.value = value;
        toggleMaxLength(value);
    }

    // Обробка клавіші Backspace
    function handleBackspace(e) {
        if (e.key === "Backspace") {
            const cursorPosition = dateInput.selectionStart;

            if (cursorPosition === 3 || cursorPosition === 6) {
                dateInput.value =
                    dateInput.value.slice(0, cursorPosition - 1) +
                    dateInput.value.slice(cursorPosition);
                dateInput.setSelectionRange(
                    cursorPosition - 1,
                    cursorPosition - 1
                );
            }
        }
    }

    // Встановлення maxlength в залежності від довжини введеного значення
    function toggleMaxLength(value) {
        if (value.length === 10) {
            dateInput.setAttribute("maxlength", "10");
        } else {
            dateInput.removeAttribute("maxlength");
        }
    }
}
