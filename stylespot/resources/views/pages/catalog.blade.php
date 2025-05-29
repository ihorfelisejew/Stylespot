@extends('layouts.client-layout')

@section('title', 'StyleSpot | Каталог')

@section('head-tags')
    <link rel="stylesheet" href="{{ asset('css/client/pages/catalog.css') }}">
@endsection

@section('content')
    <div class="catalog-page-content">
        <section class="catalog-filters">
            <div class="catalog-filters__container">
                <div class="catalog-filters__content">
                    <div class="sizes-filter catalog-filter__item">
                        <button class="sizes-filter__button filter-button">
                            Розмір
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                        <div class="sizes-filter__dropdown catalog-filters__dropdown">
                            @php
                                $selectedSizes = explode(',', request()->get('size', ''));
                            @endphp
                            @foreach ($productsSizes as $productSize)
                                @php
                                    $isActive = in_array($productSize, $selectedSizes);

                                    $newSizes = $selectedSizes;

                                    if ($isActive) {
                                        $newSizes = array_filter($newSizes, fn($size) => $size !== $productSize);
                                    } else {
                                        $newSizes[] = $productSize;
                                    }

                                    // Отримуємо всі поточні параметри, крім 'size'
                                    $params = request()->except('size');

                                    // Якщо розміри не порожні — додаємо новий параметр size
                                    $newSizeString = implode(',', $newSizes);
                                    if ($newSizeString !== '') {
                                        $params['size'] = $newSizeString;
                                    }

                                    $url = route('catalog.index', $params);
                                @endphp

                                <a href="{{ $url }}"
                                    class="filter-dropdown__button sizes-filter__item {{ $isActive ? 'active' : '' }}">
                                    <span>
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    {{ $productSize }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="categories-filter catalog-filter__item">
                        <button class="sizes-filter__button filter-button">
                            Категорії
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 9L12 15L18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div class="categories-filter__dropdown catalog-filters__dropdown">
                            @foreach ($availableCategories as $category)
                                @php
                                    $selectedCategories = array_filter(explode(',', request('category', '')));

                                    $isActive = in_array($category->slug, $selectedCategories);

                                    if ($isActive) {
                                        $updatedCategories = array_filter(
                                            $selectedCategories,
                                            fn($slug) => $slug !== $category->slug,
                                        );
                                    } else {
                                        $updatedCategories = [...$selectedCategories, $category->slug];
                                    }

                                    $params = request()->all();
                                    $params['category'] = implode(',', $updatedCategories); // ✅ через кому

                                    $url = url()->current() . '?' . http_build_query($params);
                                @endphp

                                <a href="{{ $url }}"
                                    class="filter-dropdown__button categories-filter__item {{ $isActive ? 'active' : '' }}">
                                    <span>
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20 6L9 17L4 12" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="catalog-products">
            <div class="catalog-products__container">
                <div class="catalog-products__list {{ $products->isEmpty() ? 'empty' : '' }}">
                    @forelse($products as $product)
                        <div class="product-card" data-slug="{{ $product->slug }}" data-sku="{{ $product->sku }}">
                            <div class="image-block">
                                @if ($product->main_image)
                                    <img src="{{ asset('assets/products/' . $product->main_image) }}"
                                        alt="{{ $product->name }}" class="product-card__image">
                                @endif
                            </div>
                            <h3 class="product-card__title">{{ $product->name }}</h3>
                            <p class="product-card__price">{{ number_format($product->price, 0, '.', ' ') }} грн</p>
                            <div class="product-card__sizes">
                                @foreach ($product->sizes as $size)
                                    <span class="product-size">{{ $size->size }}</span>
                                @endforeach
                            </div>
                            @php
                                $sizesString = $product->sizes->pluck('size')->implode(',');
                            @endphp
                            <button class="button add-to-curt__button add-product-to-cart" data-sizes="{{ $sizesString }}"
                                data-id="{{ $product->id }}">
                                Додати у кошик
                            </button>
                        </div>
                    @empty
                        <p class="no-products__by-filters">
                            На жаль, за обраними параметрами товарів не знайдено.
                            <a href="{{ route('catalog.index', ['gender' => request('gender')]) }}">
                                Скинути усі фільтри
                            </a>
                        </p>
                    @endforelse
                </div>
            </div>
        </section>
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
                {{-- Цей блок буде заповнений через JavaScript --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const filterBlocks = document.querySelectorAll('.catalog-filter__item');
            filterBlocks.forEach((filter) => {
                const filterButton = filter.querySelector('.filter-button');
                const filterDropdown = filter.querySelector('.catalog-filters__dropdown');

                filterButton.addEventListener('click', (e) => {
                    e.stopPropagation(); // щоб не спрацьовував глобальний клік

                    filterBlocks.forEach((otherFilter) => {
                        if (otherFilter !== filter) {
                            const otherButton = otherFilter.querySelector('.filter-button');
                            const otherDropdown = otherFilter.querySelector(
                                '.catalog-filters__dropdown');
                            otherButton.classList.remove('active');
                            otherDropdown.classList.remove('opened');
                            otherDropdown.style.maxHeight = '0';
                            otherDropdown.style.overflowY = "hidden";
                        }
                    });

                    const isActive = filterButton.classList.toggle('active');
                    if (isActive) {
                        filterDropdown.classList.add('opened');
                        filterDropdown.style.maxHeight = '150px';
                        setTimeout(() => {
                            filterDropdown.style.overflowY = "auto";
                        }, 500);
                    } else {
                        filterDropdown.classList.remove('opened');
                        filterDropdown.style.maxHeight = '0';
                        filterDropdown.style.overflowY = "hidden";
                    }
                })

                filterDropdown.addEventListener('click', (e) => {
                    e.stopPropagation();
                });
            });

            document.addEventListener('click', () => {
                const allFilters = document.querySelectorAll('.catalog-filter__item');
                allFilters.forEach((filter) => {
                    const button = filter.querySelector('.filter-button');
                    const dropdown = filter.querySelector('.catalog-filters__dropdown');

                    if (button.classList.contains('active')) {
                        button.classList.remove('active');
                        dropdown.classList.remove('opened');
                        dropdown.style.overflowY = "hidden";
                        dropdown.style.maxHeight = '0';
                    }
                });
            });

            document.querySelectorAll('.product-card').forEach(card => {
                card.addEventListener('click', function(event) {
                    // якщо клік був на кнопці або її нащадку — не переходимо
                    if (event.target.closest('.add-product-to-cart')) {
                        return;
                    }

                    const slug = this.dataset.slug;
                    const sku = this.dataset.sku;

                    const url =
                        "{{ route('catalog.product-page', ['slug' => ':slug', 'sku' => ':sku']) }}"
                        .replace(':slug', slug)
                        .replace(':sku', sku);

                    window.location.href = url;
                });
            });
        });
    </script>
@endsection
