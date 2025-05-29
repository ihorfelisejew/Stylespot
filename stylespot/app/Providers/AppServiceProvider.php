<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cart = session('cart', []);

            $productIds = array_column($cart, 'product_id');
            $products = Product::whereIn('id', $productIds)->get();

            $mappedProducts = [];

            foreach ($cart as $item) {
                $product = $products->firstWhere('id', $item['product_id']);
                if (!$product)
                    continue;

                $mappedProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'main_image' => $product->main_image,
                    'size' => $item['size'],
                    'count' => $item['count'],
                    'total' => $product->price * $item['count'],
                ];
            }

            $view->with('sessionCartProducts', $mappedProducts);
        });
    }
}
