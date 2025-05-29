@foreach ($products as $product)
    <div class="cart-item">
        <div class="image-block">
            <img src="{{ asset('assets/products/' . $product['main_image']) }}" alt="{{ $product['name'] }}">
        </div>
        <div class="cart-item__info">
            <p class="product-name">{{ $product['name'] }} ({{ $product['size'] }})</p>
            <p class="product-price">{{ $product['price'] * $product['count'] }} грн</p>
            <div class="product-count">
                <button class="sub-product-count decrease" data-id="{{ $product['id'] }}"
                    data-size="{{ $product['size'] }}">
                    -
                </button>
                <p>{{ $product['count'] }}</p>
                <button class="sub-product-count increase" data-id="{{ $product['id'] }}"
                    data-size="{{ $product['size'] }}">
                    +
                </button>
            </div>
        </div>
    </div>
@endforeach
