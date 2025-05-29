@extends('layouts.client-layout')

@section('title', 'StyleSpot | Замовлення')

@section('head-tags')
    <link rel="stylesheet" href="{{ asset('css/client/pages/order-page.css') }}">
@endsection


@section('content')
    <div class="create-order-page">
        <div class="create-order__container">
            <h2 class="create-order__title title">
                Оформлення замовлення
            </h2>
            <section class="create-order__block">
                @if (session('cart') && count(session('cart')) > 0)
                    <form action="{{ route('order.store') }}" method="POST" class="create-order__form">
                        {{-- 🔹 CSRF токен --}}
                        @csrf
                        <div class="client-info__block">
                            <div class="client-info__row">
                                <div class="client-info__item">
                                    <label for="customer_name">Ім’я</label>
                                    <input type="text" name="customer_name" required>
                                </div>
                                <div class="client-info__item">
                                    <label for="customer_phone">Телефон</label>
                                    <input type="text" name="customer_phone" required>
                                </div>
                                <div class="client-info__item">
                                    <label for="customer_email">Email</label>
                                    <input type="email" name="customer_email">
                                </div>
                            </div>
                            <div class="client-info__row">
                                <div class="client-info__item">
                                    <label for="shipping_city">Місто</label>
                                    <input type="text" name="shipping_city" required>
                                </div>
                            </div>
                            <div class="shipping-method">
                                <h3 class="shipping-method__title">
                                    Спосіб доставки
                                </h3>
                                <input type="hidden" name="shipping_method" id="shipping_method" required>
                                <div class="shipping-method__list">
                                    <div class="shipping-method__item">
                                        <button type="button" class="shipping-method__open-button"
                                            data-value="new_post_self">
                                            <div class="radio">
                                                <div class="checked-radio"></div>
                                            </div>
                                            <span class="shipping-method__name">Нова Пошта
                                                (Самовивіз)</span>
                                        </button>
                                        <div class="shipping-method__info">
                                            <div class="inputs-row">
                                                <div class="input-item">
                                                    <label for="post_office_number">Номер відділення</label>
                                                    <input type="text" name="post_office_number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shipping-method__item">
                                        <button type="button" class="shipping-method__open-button"
                                            data-value="new_post_courier">
                                            <div class="radio">
                                                <div class="checked-radio"></div>
                                            </div>
                                            <span class="shipping-method__name">Нова Пошта
                                                (Кур'єрська доставка)</span>
                                        </button>
                                        <div class="shipping-method__info">
                                            <div class="inputs-row">
                                                <div class="input-item">
                                                    <label for="post_office_number">Вулиця</label>
                                                    <input type="text" name="street">
                                                </div>
                                                <div class="input-item">
                                                    <label for="post_office_number">Номер будинку</label>
                                                    <input type="text" name="house_number">
                                                </div>
                                                <div class="input-item">
                                                    <label for="post_office_number">Номер квартири</label>
                                                    <input type="text" name="house_number">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shipping-method__item">
                                        <button type="button" class="shipping-method__open-button"
                                            data-value="urkposhta_self">
                                            <div class="radio">
                                                <div class="checked-radio"></div>
                                            </div>
                                            <span class="shipping-method__name">Укрпошта
                                                (Самовивіз)</span>
                                        </button>
                                        <div class="shipping-method__info">
                                            <div class="inputs-row">
                                                <div class="input-item">
                                                    <label for="post_office_number">Поштовий індекс відділення</label>
                                                    <input type="text" name="shipping_postal_code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="shipping-method__item">
                                        <button type="button" class="shipping-method__open-button"
                                            data-value="urkposhta_courier">
                                            <div class="radio">
                                                <div class="checked-radio"></div>
                                            </div>
                                            <span class="shipping-method__name">Укрпошта (Кур'єрська доставка)</span>
                                        </button>
                                        <div class="shipping-method__info">
                                            <div class="inputs-row">
                                                <div class="input-item">
                                                    <label for="post_office_number">Вулиця</label>
                                                    <input type="text" name="street">
                                                </div>
                                                <div class="input-item">
                                                    <label for="post_office_number">Номер будинку</label>
                                                    <input type="text" name="house_number">
                                                </div>
                                                <div class="input-item">
                                                    <label for="post_office_number">Номер квартири</label>
                                                    <input type="text" name="house_number">
                                                </div>
                                            </div>
                                            <div class="inputs-row">
                                                <div class="input-item">
                                                    <label for="post_office_number">Поштовий індекс</label>
                                                    <input type="text" name="shipping_postal_code">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cart-list">
                            @foreach ($sessionCartProducts as $product)
                                <div class="cart-item">
                                    <div class="image-block">
                                        <img src="{{ asset('assets/products/' . $product['main_image']) }}"
                                            alt="{{ $product['name'] }}">
                                    </div>
                                    <div class="cart-item__info">
                                        <p class="product-name">{{ $product['name'] }} ({{ $product['size'] }})</p>
                                        <p class="product-price">{{ $product['price'] * $product['count'] }} грн</p>
                                        <div class="product-count">
                                            <button type="button" class="sub-product-count decrease"
                                                data-id="{{ $product['id'] }}" data-size="{{ $product['size'] }}">
                                                -
                                            </button>
                                            <p>{{ $product['count'] }}</p>
                                            <button type="button" class="sub-product-count increase"
                                                data-id="{{ $product['id'] }}" data-size="{{ $product['size'] }}">
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <button type="submit" class="button submit-order__button">Підтвердити замовлення</button>
                    </form>
                @else
                    <p>Ваш кошик порожній.</p>
                @endif
            </section>
        </div>
    </div>
@endsection

@if (session('success'))
    @section('modals')
        <div class="order-success__modal modal-block active">
            <div class="modal__content">
                <div class="modal__header">
                    <h2 class="modal__title">Оформлення замовлення</h2>
                    <button class="modal__close-button">
                        <svg width="27" height="25" viewBox="0 0 27 25" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M4.02814 19.7376C3.83368 20.1677 3.90431 20.745 4.21312 21.2777C4.47102 21.7182 5.00916 22.2151 5.43415 22.4044C5.8179 22.5761 6.19031 22.6244 6.55263 22.5566L6.82699 22.5011L14.9137 14.4144L23.0003 6.32772L23.0589 5.9915C23.1292 5.58457 23.0392 5.15284 22.7952 4.73207C22.5373 4.29152 22.0033 3.79882 21.5741 3.6053C21.1904 3.43366 20.818 3.38531 20.4557 3.4531L20.1813 3.50868L12.1451 11.528C5.84245 17.8223 4.09212 19.5894 4.02814 19.7376Z"
                                fill="#000" />
                            <path
                                d="M6.51566 3.78174C6.08558 3.58728 5.50825 3.65791 4.97555 3.96671C4.535 4.22461 4.0381 4.76276 3.84879 5.18775C3.67714 5.5715 3.6288 5.9439 3.69659 6.30622L3.75217 6.58059L11.8388 14.6673L19.9255 22.7539L20.2617 22.8125C20.6687 22.8828 21.1004 22.7928 21.5212 22.5488C21.9617 22.2909 22.4544 21.7569 22.6479 21.3277C22.8196 20.944 22.8679 20.5716 22.8001 20.2093L22.7445 19.9349L14.7252 11.8987C8.43095 5.59605 6.66379 3.84572 6.51566 3.78174Z"
                                fill="#000" />
                        </svg>
                    </button>
                </div>
                <div class="order-success__content">
                    <p class="order-success__text">Ваше замовлення успішно оформлено!</p>
                    <p class="order-success__text">Номер замовлення: <span>{{ session('order_id') }}</span></p>
                    <p class="order-success__text">Очікуйте на дзвінок від нашого менеджера для підтвердження замовлення.
                    </p>
                </div>
            </div>
        </div>
    @endsection
@endif

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // SHIPPING METHOD логіка — залишаємо як є
            const hiddenInput = document.getElementById('shipping_method');
            const buttons = document.querySelectorAll('.shipping-method__open-button');
            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const infoBlock = button.nextElementSibling;
                    if (!infoBlock) return;

                    hiddenInput.value = button.dataset.value;

                    buttons.forEach(btn => {
                        btn.classList.remove('checked');
                        btn.nextElementSibling.classList.remove('active');
                        btn.nextElementSibling.style.maxHeight = 0 + "px";
                    });

                    button.classList.add('checked');
                    infoBlock.classList.add('active');
                    infoBlock.style.maxHeight = infoBlock.scrollHeight + 20 + "px";
                });
            });

            // ОБРОБКА ЗМІНИ КІЛЬКОСТІ ТОВАРІВ
            document.querySelectorAll('.sub-product-count').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const productId = btn.dataset.id;
                    const size = btn.dataset.size;
                    const action = btn.classList.contains('increase') ? 'increase' : 'decrease';

                    const response = await fetch(`{{ route('cart.update') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            product_id: productId,
                            size: size,
                            action: action
                        }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Оновлення DOM
                        if (data.cart.length === 0) {
                            document.querySelector('.create-order__form').remove();
                            document.querySelector('.create-order__block').innerHTML =
                                '<p>Ваш кошик порожній.</p>';
                        } else {
                            location
                                .reload();
                        }
                    }
                });
            });

            // DISABLE INPUTS НЕАКТИВНИХ СПОСОБІВ ДОСТАВКИ
            const form = document.querySelector('.create-order__form');
            form.addEventListener('submit', (e) => {
                const allInfoBlocks = document.querySelectorAll('.shipping-method__info');
                const selectedInfoBlock = document.querySelector('.shipping-method__info.active');

                allInfoBlocks.forEach(info => {
                    if (info !== selectedInfoBlock) {
                        const inputs = info.querySelectorAll('input');
                        inputs.forEach(input => input.disabled = true);
                    }
                });
            });
        });
    </script>
@endsection
