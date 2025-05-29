class ScrollFunctions {
    #isScrollBlocked;

    constructor() {
        this.#isScrollBlocked = false;
    }

    addScrollFunction(scrollableObj) {
        scrollableObj.addEventListener("wheel", this.#handleScrollable, {
            passive: false,
        });
        scrollableObj.addEventListener("touchmove", this.#handleScrollable, {
            passive: false,
        });
    }

    removeScrollFunction(scrollableObj) {
        scrollableObj.removeEventListener("wheel", this.#handleScrollable, {
            passive: false,
        });
        scrollableObj.removeEventListener("touchmove", this.#handleScrollable, {
            passive: false,
        });
    }

    toggleWindowScroll() {
        if (this.#isScrollBlocked) {
            window.removeEventListener("wheel", this.#preventScroll, {
                passive: false,
            });
            window.removeEventListener("touchmove", this.#preventScroll, {
                passive: false,
            });
            window.removeEventListener("keydown", this.#preventKeyScroll);
        } else {
            window.addEventListener("wheel", this.#preventScroll, {
                passive: false,
            });
            window.addEventListener("touchmove", this.#preventScroll, {
                passive: false,
            });
            window.addEventListener("keydown", this.#preventKeyScroll);
        }

        this.#isScrollBlocked = !this.#isScrollBlocked;
    }

    #handleScrollable(e) {
        const target = e.currentTarget;
        const scrollTop = target.scrollTop;
        const scrollHeight = target.scrollHeight;
        const clientHeight = target.clientHeight;

        const tolerance = 1;
        const isAtTop = scrollTop <= 0 && e.deltaY < 0;
        const isAtBottom =
            scrollTop + clientHeight >= scrollHeight - tolerance &&
            e.deltaY > 0;

        if (isAtTop || isAtBottom) {
            e.preventDefault();
        }

        e.stopPropagation();
    }

    #preventScroll(e) {
        e.preventDefault();
    }

    #preventKeyScroll(e) {
        const keys = [33, 34, 35, 36, 37, 38, 39, 40];
        if (keys.includes(e.keyCode)) {
            e.preventDefault();
        }
    }
}

export const scrollControl = new ScrollFunctions();
