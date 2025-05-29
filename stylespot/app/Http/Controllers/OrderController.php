<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.create-order');
    }
    public function store(Request $request)
    {
        $cart = session('cart');

        if (!$cart || count($cart) === 0) {
            return redirect()->back()->withErrors(['cart' => 'Кошик порожній']);
        }

        $validated = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_email' => 'nullable|email',
            'shipping_method' => 'required|string',
            'shipping_city' => 'required|string',
            'post_office_number' => 'nullable|string',
            'shipping_address' => 'nullable|string',
            'shipping_postal_code' => 'nullable|string',
        ]);

        $total = 0;

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return redirect()->back()->withErrors(['product' => 'Один із товарів більше недоступний.']);
            }

            $total += $product->price * $item['count'];
        }

        $order = Order::create([
            ...$validated,
            'total_price' => $total,
            'status' => 'not_processed',
        ]);

        foreach ($cart as $item) {
            $product = Product::find($item['product_id']);
            $productSize = ProductSize::where('product_id', $item['product_id'])
                ->where('size', $item['size'])
                ->first();

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_size_id' => $productSize?->id,
                'quantity' => $item['count'],
                'price' => $product->price,
            ]);
        }

        session()->forget('cart');

        return back()->with(['success' => 'Замовлення оформлено!', 'order_id' => $order->id]);
    }
}
