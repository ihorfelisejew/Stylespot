<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $gender = $request->input('gender');

        $categorySlugs = array_filter(explode(',', $request->input('category', '')));

        // Отримуємо масив розмірів (як і раніше)
        $selectedSizes = array_filter(explode(',', $request->input('size', '')));

        // 1. Запит для фільтрації товарів
        $productQuery = Product::query()
            ->when($gender, fn($q) => $q->where('gender', $gender))
            ->when(!empty($categorySlugs), function ($q) use ($categorySlugs) {
                $q->whereHas('category', fn($subQ) => $subQ->whereIn('slug', $categorySlugs));
            })
            ->when(!empty($selectedSizes), function ($q) use ($selectedSizes) {
                $q->whereHas('sizes', fn($sq) => $sq->whereIn('size', $selectedSizes));
            })
            ->with(['sizes', 'category']);

        $products = $productQuery->get();

        // 2. Отримуємо фільтровані розміри (відповідно до вибраних категорій)
        $sizeQuery = Product::query()
            ->when($gender, fn($q) => $q->where('gender', $gender))
            ->when(!empty($categorySlugs), function ($q) use ($categorySlugs) {
                $q->whereHas('category', fn($subQ) => $subQ->whereIn('slug', $categorySlugs));
            })
            ->with('sizes');

        $availableSizes = $sizeQuery->get()
            ->flatMap(fn($product) => $product->sizes->pluck('size'))
            ->unique()
            ->sort()
            ->values();

        // 3. Отримуємо фільтровані категорії (відповідно до вибраних розмірів)
        $categoryQuery = Product::query()
            ->when($gender, fn($q) => $q->where('gender', $gender))
            ->when(!empty($selectedSizes), function ($q) use ($selectedSizes) {
                $q->whereHas('sizes', fn($sq) => $sq->whereIn('size', $selectedSizes));
            })
            ->with('category');

        $availableCategories = $categoryQuery->get()
            ->pluck('category')
            ->unique('id')
            ->values();

        return view('pages.catalog', [
            'products' => $products,
            'productsSizes' => $availableSizes,
            'availableCategories' => $availableCategories,
        ]);
    }



    public function productPage($slug, $sku)
    {
        $product = Product::where('slug', $slug)
            ->where('sku', $sku)
            ->with(['sizes', 'images', 'reviews'])
            ->firstOrFail();

        return view('pages.product-page', [
            'product' => $product,
        ]);
    }
}

