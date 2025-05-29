@extends('layouts.client-layout')

@section('title', 'StyleSpot | Каталог')

@section('head-tags')
    <link rel="stylesheet" href="{{ asset('css/client/pages/product-page.css') }}">
@endsection

@section('content')
    <div class="product-page-content">
        <div class="product-page__container">
            <div class="product-page__pages-history pages-history">
                <a href="{{ route('home') }}" class="pages-history__link">Головна</a>
                <span class="pages-history__arrow">></span>
                <a href="{{ route('catalog.index') }}" class="pages-history__link">Каталог</a>
                <span class="pages-history__arrow">></span>
                <p class="pages-history__current-page">{{ $product->name }}</p>
            </div>
            <div class="product-card">
                <div class="image-slider">
                    <img src="{{ asset('assets/products/' . $product->main_image) }}" alt="{{ $product->name }}"
                        class="image-slider__main-image">
                </div>
                <div class="product-info">
                    <h1 class="product-name">
                        {{ $product->name }}
                    </h1>
                    <p class="product-price">
                        {{ number_format($product->price, 0, '.', ' ') }} грн
                    </p>
                    <div class="product-size">
                        <p class="product-size__note">Розміри:</p>
                        <div class="product-size__list">
                            @foreach ($product->sizes as $size)
                                <p class="product-size__list-item{{ $size->quantity == 0 ? ' not-active' : '' }}">
                                    {{ $size->size }}
                                </p>
                            @endforeach
                        </div>
                    </div>
                    <p class="product-color">
                        Колір: <span>{{ $product->color }}</span>
                    </p>
                    <div class="product-info__buttons-block">
                        @php
                            $sizesString = $product->sizes->pluck('size')->implode(',');
                        @endphp
                        <button class="add-product-to-cart add-to-curt__button button" data-id="{{ $product->id }}"
                            data-sizes="{{ $sizesString }}">
                            Додати у кошик
                        </button>
                        <a href="#" class="fit-cloth-modal__button button light-button">
                            Приміряти одяг
                        </a>
                    </div>
                    <div class="delivery-methods">
                        <h3 class="delivery-methods__title">
                            Способи доставки
                        </h3>
                        <div class="delivery-methods__item">
                            <img src="{{ asset('assets/product-page/Nova_Poshta_2014_logo.png') }}">
                            <p>Самовивіз з відділень Нової Пошти</p>
                        </div>
                        <div class="delivery-methods__item">
                            <img src="{{ asset('assets/product-page/Nova_Poshta_2014_logo.png') }}">
                            <p>Кур'єрська доставка від Нової Пошти</p>
                        </div>
                        <div class="delivery-methods__item">
                            <img src="{{ asset('assets/product-page/Ukrposhta-ua.png') }}">
                            <p>Самовивіз з відділень Укрпошти</p>
                        </div>
                        <div class="delivery-methods__item">
                            <img src="{{ asset('assets/product-page/Ukrposhta-ua.png') }}">
                            <p>Кур'єрська доставка від Укрпошти</p>
                        </div>
                    </div>
                    <div class="return-goods">
                        <h3 class="return-goods__title">
                            Повернення товару
                        </h3>
                        <div class="return-goods__text">
                            <img src="{{ asset('assets/product-page/product-return.png') }}">
                            <p>Повернення товару можливе протягом 14 днів з моменту його отримання</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="product-description">
                <h3 class="product-description__title">Опис товару</h3>
                @php

                    $descriptionItems = array_filter(array_map('trim', explode('\b', $product->description)));
                @endphp
                <ul class="product-description__list">
                    @foreach ($descriptionItems as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="select-size__modal modal-block">
        <div class="modal__content">
            <div class="modal__header">
                <h2 class="modal__title">Оберіть розмір</h2>
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
            <div class="sizes-list__modal">
            </div>
        </div>
    </div>
    <div class="fit-cloth__modal modal-block">
        <div class="modal__content">
            <div class="modal__header">
                <h2 class="modal__title">Приміряти одяг</h2>
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
            <form class="fit-cloth__form">
                <input type="file" name="client-photo" id="fit-cloth__client-photo" class="fit-cloth__input">
                <button type="button" class="fit-cloth__button button light-button">
                    Завантажити своє фото
                </button>
                <div class="fit-cloth__preview">
                    <p>Ось ваше фото:</p>
                    <img class="photo-preview" src="#" alt="Попередній перегляд">
                </div>
                <p class="fit-cloth__note-text">
                    Завантажте своє фото (або фото людини для якої ви хочете приміряти цей одяг), на якому чітко видно
                    контури тіла. Це необхідно для отримання коректного результату. Далі система опрацює ваш запит і
                    ви побачите отриманий результат у цьому вікні.
                </p>
                <button type="submit" class="fit-cloth__submit button">Приміряти одяг</button>
            </form>
            <div class="fit-cloth__result">
                <h3 class="fit-cloth__result-title">Результат примірки</h3>
                <img src="{{ asset('assets/products/ukr_pl_Графітовий-чоловічий-спортивний-костюм-Bolf-27C6505-95953_8.jpg') }}"
                    alt="fit-cloth-result" class="fit-cloth__result-image">
                <p class="fit-cloth__result-text" class="fit-cloth__result-note">
                    На зображенні ви можете побачити, як виглядає одяг на вашій фігурі. Якщо результат вас не
                    влаштовує, ви можете повторити примірку, завантаживши інше фото, попередньо перезавантаживши сторінку.
                </p>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.querySelector('.fit-cloth__modal');
            const openButton = document.querySelector('.fit-cloth-modal__button');

            openButton.addEventListener('click', function() {
                modal.classList.add('active');
            });

            /*---------upload photo---------*/
            const fileInput = modal.querySelector('#fit-cloth__client-photo');
            const uploadButton = modal.querySelector('.fit-cloth__button');
            const previewBlock = document.querySelector('.fit-cloth__preview');
            const previewImg = previewBlock.querySelector('img');
            uploadButton.addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function() {
                if (fileInput.files.length > 0) {
                    const file = fileInput.files[0];
                    const fileName = file.name;
                    uploadButton.textContent = `Вибрано: ${fileName}`;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        previewBlock.classList.add('active');
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
