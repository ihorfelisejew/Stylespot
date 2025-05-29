<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StyleSpot')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Flex:opsz,wght@8..144,500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('css/client/app.css') }}">
    <link rel="icon" href="{{ asset('assets/logo.png') }}" media="(prefers-color-scheme: light)">
    <link rel="icon" href="{{ asset('assets/logo_white.png') }}" media="(prefers-color-scheme: dark)">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head-tags')
    <script type="module" src="{{ asset('js/main.js') }}"></script>
</head>

<body>
    <div class="wrapper">
        <!--header-->
        @include('components.header')
        <!--content-->
        @yield('content')
        <!--footer-->
        @include('components.footer')
        <!--modals-->
        @yield('modals')
    </div>
    <!--scripts-->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            const cartButton = document.querySelector('.header__forms-block .cart-button');
            const cartDropdown = document.querySelector('.header__forms-block .cart-dropdown');
            const cartContent = document.querySelector('.cart-dropdown__content');
            const sizeModal = document.querySelector('.select-size__modal');
            let sizeListContainer = null;

            if (sizeModal) {
                sizeListContainer = sizeModal.querySelector('.sizes-list__modal');
            }

            const genericModal = document.querySelector('.modal-block');

            // --- Закриття загальної модалки ---
            if (genericModal) {
                const closeButton = genericModal.querySelector('.modal__close-button');
                const modalContent = genericModal.querySelector('.modal__content');

                closeButton?.addEventListener('click', () => genericModal.classList.remove('active'));

                genericModal.addEventListener('click', event => {
                    if (!modalContent.contains(event.target)) {
                        genericModal.classList.remove('active');
                    }
                });
            }

            // --- Керування дропдауном кошика ---
            if (cartButton && cartDropdown && cartContent) {
                cartButton.addEventListener('click', () => {
                    const isOpened = cartDropdown.classList.contains('opened');
                    if (!isOpened) {
                        openCartDropdown();
                    } else {
                        closeCartDropdown();
                    }
                });

                document.addEventListener('click', event => {
                    if (!cartDropdown.contains(event.target) && !cartButton.contains(event.target)) {
                        closeCartDropdown();
                    }
                });
            }

            function openCartDropdown() {
                if (!cartDropdown) return;
                cartDropdown.classList.add('opened');
                cartDropdown.style.maxHeight = (cartDropdown.scrollHeight + 48) + 'px';
            }

            function closeCartDropdown() {
                if (!cartDropdown) return;
                cartDropdown.classList.remove('opened');
                cartDropdown.style.maxHeight = 0;
            }

            // --- AJAX оновлення кошика ---
            async function updateCart(url, data) {
                if (!cartContent) return;
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify(data)
                    });

                    const html = await response.text();
                    cartContent.innerHTML = html;

                    if (!html.includes('cart-item')) {
                        cartContent.innerHTML = `<p class="cart-dropdown__empty">Кошик порожній</p>`;
                    }

                    openCartDropdown();
                } catch (error) {
                    console.error('Помилка запиту:', error);
                }
            }

            // --- Додавання товару з вибором розміру ---
            document.querySelectorAll('.add-product-to-cart').forEach(button => {
                button.addEventListener('click', () => {
                    const productId = button.dataset.id;
                    const sizesData = button.dataset.sizes;

                    if (!productId || !sizesData || !sizeListContainer || !sizeModal) return;

                    sizeListContainer.innerHTML = '';

                    sizesData.split(',').forEach(size => {
                        const sizeBtn = document.createElement('button');
                        sizeBtn.classList.add('size-item');
                        sizeBtn.dataset.size = size;
                        sizeBtn.innerText = size;

                        sizeBtn.addEventListener('click', () => {
                            sizeModal.classList.remove('active');

                            updateCart('/cart/add', {
                                product_id: productId,
                                size: size
                            });
                        });

                        sizeListContainer.appendChild(sizeBtn);
                    });

                    sizeModal.classList.add('active');
                });
            });

            // --- Зміна кількості товару (+ / -) ---
            if (cartDropdown) {
                cartDropdown.addEventListener('click', event => {
                    const target = event.target;

                    if (target.classList.contains('increase') || target.classList.contains('decrease')) {
                        const productId = target.dataset.id;
                        const size = target.dataset.size;
                        const isIncrease = target.classList.contains('increase');

                        if (!productId || !size) return;

                        updateCart(`/cart/${isIncrease ? 'add' : 'subtract'}`, {
                            product_id: productId,
                            size: size
                        });
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>



</html>
