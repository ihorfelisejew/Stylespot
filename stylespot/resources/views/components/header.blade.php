<header>
    <div class="header__container">
        <div class="header__content">
            <a href="{{ route('home') }}" class="header__logo">
                <img src="{{ asset('assets/logo_dark.png') }}" alt="StyleSpot">
            </a>
            <nav class="header-menu">
                <ul class="header-menu__list">
                    <li class="menu__item">
                        <a href="{{ route('catalog.index', ['gender' => 'male']) }}" class="menu__link">
                            Чоловічий одяг
                        </a>
                    </li>
                    <li class="menu__item">
                        <a href="{{ route('catalog.index', ['gender' => 'female']) }}" class="menu__link">
                            Жіночий одяг
                        </a>
                    </li>
                    <li class="menu__item">
                        <a href="#" class="menu__link">Privacy Policy</a>
                    </li>
                    <li class="menu__item">
                        <a href="#" class="menu__link">Контакти</a>
                    </li>
                </ul>
            </nav>
            <div class="header__forms-block">
                <form class="header__search">
                    <input type="text" name="search-value" id="header-search__value">
                    <button type="submit">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.9129 12.1759L16.25 16.5M13.75 7.75C13.75 11.2017 10.9517 14 7.5 14C4.04822 14 1.25 11.2017 1.25 7.75C1.25 4.29822 4.04822 1.5 7.5 1.5C10.9517 1.5 13.75 4.29822 13.75 7.75Z"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>
                </form>

                @if (!Route::is('order.index'))
                    <button class="cart-button">
                        <svg width="31" height="28" viewBox="0 0 31 28" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.8717 26.5C12.0726 26.5 13.0461 25.482 13.0461 24.2262C13.0461 22.9704 12.0726 21.9524 10.8717 21.9524C9.67078 21.9524 8.69727 22.9704 8.69727 24.2262C8.69727 25.482 9.67078 26.5 10.8717 26.5Z"
                                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" />
                            <path
                                d="M20.651 26.5C21.8519 26.5 22.8254 25.482 22.8254 24.2262C22.8254 22.9704 21.8519 21.9524 20.651 21.9524C19.4501 21.9524 18.4766 22.9704 18.4766 24.2262C18.4766 25.482 19.4501 26.5 20.651 26.5Z"
                                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" />
                            <path
                                d="M0 1.50001H3.02823C3.72265 1.49873 4.39939 1.72885 4.96029 2.15697C5.52118 2.5851 5.93704 3.18896 6.14754 3.88096L9.77914 16.2738H9.24408C8.52247 16.2738 7.8304 16.5736 7.32014 17.1072C6.80989 17.6407 6.52322 18.3644 6.52322 19.119C6.52624 19.8716 6.81422 20.5922 7.32416 21.1232C7.83409 21.6543 8.52443 21.9524 9.24408 21.9524H20.6284"
                                stroke="currentColor" stroke-width="2" stroke-miterlimit="10" />
                            <path d="M7.61621 6.04764H25.0001V8.32145L22.8257 16.2738H9.77923" stroke="currentColor"
                                stroke-width="2" stroke-miterlimit="10" />
                        </svg>
                        <span class="cart-button__count">0</span>
                    </button>
                    <div class="cart-dropdown">
                        <div class="cart-dropdown__content">
                            @if (session()->has('cart') && count(session('cart')) > 0)
                                @include('components.cart-list', ['products' => $sessionCartProducts])
                            @else
                                <p class="cart-dropdown__empty">Кошик порожній </p>
                            @endif
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</header>
