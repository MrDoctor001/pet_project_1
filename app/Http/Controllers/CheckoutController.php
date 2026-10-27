<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /**
     * Страница оформления заказа
     */
    public function create(Cart $cart): View|RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        return view('checkout.create', [
            'cart' => $cart->quantities(),
            'products' => $cart->products(),
            'total' => $cart->total(),
        ]);
    }

    /**
     * Создаёт заказ из корзины и списывает товары со склада
     */
    public function store(StoreOrderRequest $request, Cart $cart): RedirectResponse
    {
        if ($cart->isEmpty()) {
            return redirect()->route('cart.index');
        }

        $order = DB::transaction(function () use ($request, $cart) {
            $quantities = $cart->quantities();
            $products = $cart->products(lockForUpdate: true);

            foreach ($products as $product) {
                if ($quantities[$product->id] > $product->stock) {
                    throw ValidationException::withMessages([
                        'cart' => "Товара «{$product->name}» осталось только {$product->stock} шт.",
                    ]);
                }
            }

            $order = Order::create([
                ...$request->validated(),
                'user_id' => $request->user()?->id,
                'total' => $products->sum(fn ($product) => $product->price * $quantities[$product->id]),
            ]);

            foreach ($products as $product) {
                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantities[$product->id],
                ]);

                $product->decrement('stock', $quantities[$product->id]);
            }

            return $order;
        });

        $cart->clear();

        session()->push('orders', $order->id);

        return redirect()->route('orders.show', $order)->with('status', 'Заказ успешно оформлен');
    }
}
