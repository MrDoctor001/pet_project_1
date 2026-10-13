<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Добавляет продукт в корзину
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_if(! $product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->stock],
        ]);

        $cart = $request->session()->get('cart', []);

        $cart[$product->id] = ($cart[$product->id] ?? 0) + (int) $validated['quantity'];

        $cart[$product->id] = min($cart[$product->id], $product->stock);

        $request->session()->put('cart', $cart);

        return back()->with('status', 'Товар добавлен в корзину');
    }

    /**
     * Отображает страницу корзины
     */
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);

        $products = Product::query()
            ->whereIn('id', array_keys($cart))
            ->where('is_active', true)
            ->with('category')
            ->get();

        $total = 0;

        foreach ($products as $product) {
            $total += $product->price * $cart[$product->id];
        }

        return view('cart.index', compact('cart', 'products', 'total'));
    }

    /**
     * Обновляет количество продукта в корзине
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->stock],
        ]);

        $cart = $request->session()->get('cart', []);

        $cart[$product->id] = (int) $validated['quantity'];

        $request->session()->put('cart', $cart);

        return back()->with('status', 'Количество товара в корзине обновлено');
    }

    /**
     * Удаляет продукт из корзины
     */
    public function destroy(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);

        unset($cart[$product->id]);

        $request->session()->put('cart', $cart);

        return back()->with('status', 'Товар удалён из корзины');
    }
}
