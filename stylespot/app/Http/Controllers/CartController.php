<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSize;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $size = $request->input('size');
        $count = $request->input('count', 1);

        if (!$productId || !$size) {
            return response()->json(['error' => 'Invalid data'], 422);
        }

        $cart = session()->get('cart', []);

        // Перевірка чи такий товар уже є
        $found = false;
        foreach ($cart as &$item) {
            if ($item['product_id'] == $productId && $item['size'] == $size) {
                $item['count'] += $count;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $cart[] = [
                'product_id' => $productId,
                'size' => $size,
                'count' => $count,
            ];
        }

        session()->put('cart', $cart);

        return $this->getCartHtml(); // повертаємо HTML списку
    }

    public function subtract(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'size' => 'required|string',
        ]);

        $product = Product::findOrFail($data['product_id']);
        $price = $product->price;

        $cart = session()->get('cart', []);

        foreach ($cart as $index => $item) {
            if ($item['product_id'] == $data['product_id'] && $item['size'] == $data['size']) {
                if ($cart[$index]['count'] > 1) {
                    $cart[$index]['count'] -= 1;
                    $cart[$index]['total'] = $price * $cart[$index]['count'];
                } else {
                    unset($cart[$index]); // видалити, якщо кількість = 1
                }
                break;
            }
        }

        session()->put('cart', array_values($cart)); // перевпорядковуємо індекси

        return $this->getCartHtml();
    }


    public function getCartHtml()
    {
        $cart = session()->get('cart', []);
        $products = [];

        foreach ($cart as $item) {
            $product = Product::with('sizes')->find($item['product_id']);
            if (!$product)
                continue;

            $products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'main_image' => $product->main_image,
                'size' => $item['size'],
                'count' => $item['count'],
                'total' => $product->price * $item['count'],
            ];
        }

        return view('components.cart-list', compact('products'))->render();
    }

    public function update(Request $request)
    {
        $id = $request->input('product_id');
        $size = $request->input('size');
        $action = $request->input('action');

        $cart = session('cart', []);
        $updatedCart = [];

        foreach ($cart as $item) {
            if ($item['product_id'] == $id && $item['size'] == $size) {
                if ($action === 'increase') {
                    $item['count'] += 1;
                } elseif ($action === 'decrease') {
                    $item['count'] -= 1;
                }

                if ($item['count'] > 0) {
                    $updatedCart[] = $item;
                }
                // Якщо count <= 0 — не додаємо в новий кошик (видалення)
            } else {
                $updatedCart[] = $item;
            }
        }

        session(['cart' => $updatedCart]);

        $products = [];

        foreach ($updatedCart as $item) {
            $product = Product::with('sizes')->find($item['product_id']);
            if (!$product)
                continue;

            $products[] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'main_image' => $product->main_image,
                'size' => $item['size'],
                'count' => $item['count'],
                'total' => $product->price * $item['count'],
            ];
        }

        return response()->json([
            'success' => true,
            'cart' => $products,
        ]);
    }

}

