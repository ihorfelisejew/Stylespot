import { orderController } from "../orders/order.js";
import { timeInput } from "./time-input.js";

class Calendar {
    constructor(
        calendar,
        container,
        monthYearLabel,
        prevButton,
        nextButton,
        dateInputSelector
    ) {
        this.calendar = document.querySelector(calendar);
        this.calendarContainer = document.querySelector(container);
        this.monthYearLabel = document.getElementById(monthYearLabel);
        this.prevMonthButton = document.querySelector(prevButton);
        this.nextMonthButton = document.querySelector(nextButton);
        this.dateInputBlock = document.querySelector(dateInputSelector);
        this.currentDate = new Date();
        this.selectedDate = null;

        // Відкриття/закриття календаря
        var dateInputButton = document.querySelector(".get-order-date__button");
        dateInputButton.addEventListener("click", () => this.toggleCalendar());

        document.addEventListener("click", (event) => {
            if (
                !this.calendar.contains(event.target) &&
                !dateInputButton.contains(event.target) &&
                this.calendar.classList.contains("active")
            ) {
                this.toggleCalendar();
            }
        });

        this.init();
    }

    init() {
        this.renderCalendar();

        this.prevMonthButton.addEventListener("click", () =>
            this.changeMonth(-1)
        );
        this.nextMonthButton.addEventListener("click", () =>
            this.changeMonth(1)
        );
    }

    getSelectedDate() {
        const date = new Date(this.selectedDate);
        date.setHours(12, 0, 0, 0);
        return date;
    }

    renderCalendar(date = this.currentDate) {
        const month = date.getMonth();
        const year = date.getFullYear();
        const firstDayOfMonth = new Date(year, month, 1);
        const lastDateOfMonth = new Date(year, month + 1, 0);
        const firstDayOfWeek =
            firstDayOfMonth.getDay() === 0 ? 6 : firstDayOfMonth.getDay() - 1;
        const lastDate = lastDateOfMonth.getDate();

        this.calendarContainer.innerHTML = "";

        // Попередні дні
        const prevMonthLastDate = new Date(year, month, 0).getDate();
        for (let i = firstDayOfWeek - 1; i >= 0; i--) {
            this.createDayCell(prevMonthLastDate - i, [
                "disabled",
                "not-today-month",
            ]);
        }

        // Поточний місяць
        for (let day = 1; day <= lastDate; day++) {
            const dayCell = this.createDayCell(day);
            if (
                this.selectedDate &&
                this.selectedDate.getDate() === day &&
                this.selectedDate.getMonth() === month &&
                this.selectedDate.getFullYear() === year
            ) {
                dayCell.classList.add("today");
                timeInput.changeTimesByDate(this.selectedDate);
            }

            dayCell.addEventListener("click", () => {
                this.changeDate(day, month, year, dayCell);

                if (orderController.submitStatus) {
                    orderController.validateFormData();
                }
            });

            if (this.isToday(day, month, year) && !this.selectedDate) {
                dayCell.classList.add("today");
            }
            if (
                this.isBeforeToday(day, month, year) &&
                !this.isToday(day, month, year)
            ) {
                dayCell.classList.add("disabled");
            }
        }

        // Наступні дні
        const remainingCells = 42 - this.calendarContainer.children.length;
        for (let i = 1; i <= remainingCells; i++) {
            this.createDayCell(i, ["disabled", "not-today-month"]);
        }

        // Оновлення назви місяця
        const monthName = date.toLocaleString("uk-UA", { month: "long" });
        const capitalizedMonthName =
            monthName.charAt(0).toUpperCase() + monthName.slice(1);
        this.monthYearLabel.textContent = `${capitalizedMonthName} ${year}`;

        // Заборона на попередні місяці
        this.prevMonthButton.disabled =
            date.getFullYear() <= new Date().getFullYear() &&
            date.getMonth() <= new Date().getMonth();
    }

    createDayCell(day, classes = []) {
        const dayCell = document.createElement("div");
        dayCell.textContent = day;
        classes.forEach((cls) => dayCell.classList.add(cls));
        this.calendarContainer.appendChild(dayCell);
        return dayCell;
    }

    isToday(day, month, year) {
        const currentDate = new Date();
        return (
            currentDate.getDate() === day &&
            currentDate.getMonth() === month &&
            currentDate.getFullYear() === year
        );
    }

    isBeforeToday(day, month, year) {
        const currentDate = new Date();
        const selectedDate = new Date(year, month, day);
        return selectedDate < currentDate;
    }

    changeDate(day, month, year, dayCell) {
        this.selectedDate = new Date(year, month, day);

        timeInput.changeTimesByDate(this.selectedDate);

        if (this.dateInputBlock) {
            this.dateInputBlock.value = `${day < 10 ? "0" + day : day}/${
                month + 1 < 10 ? "0" + (month + 1) : month + 1
            }/${year}`;
        }

        this.calendarContainer
            .querySelectorAll("div")
            .forEach((cell) => cell.classList.remove("today"));
        dayCell.classList.add("today");

        this.toggleCalendar();
    }

    changeMonth(delta) {
        this.currentDate.setMonth(this.currentDate.getMonth() + delta);
        this.renderCalendar();
    }

    clearCalendar() {
        this.currentDate = new Date();
        this.selectedDate = null;
        this.toggleCalendar();
        this.renderCalendar();
    }

    toggleCalendar() {
        this.calendarContainer.parentElement.classList.toggle("active");
    }
}

export const calendar = new Calendar(
    ".calendar-container",
    ".calendar-body",
    "month-year",
    ".prev-month",
    ".next-month",
    "#order-date"
);
