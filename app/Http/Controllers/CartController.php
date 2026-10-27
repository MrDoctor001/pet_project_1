<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    /**
     * Добавляет товар в корзину
     */
    public function store(Request $request, Product $product, Cart $cart): RedirectResponse
    {
        abort_if(! $product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->stock],
        ]);

        $cart->add($product, (int) $validated['quantity']);

        return back()->with('status', 'Товар добавлен в корзину');
    }

    /**
     * Отображает страницу корзины
     */
    public function index(Cart $cart): View
    {
        return view('cart.index', [
            'cart' => $cart->quantities(),
            'products' => $cart->products(),
            'total' => $cart->total(),
        ]);
    }

    /**
     * Обновляет количество товара в корзине
     */
    public function update(Request $request, Product $product, Cart $cart): RedirectResponse
    {
        abort_if(! $product->is_active, 404);

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$product->stock],
        ]);

        $cart->update($product, (int) $validated['quantity']);

        return back()->with('status', 'Количество товара обновлено');
    }

    /**
     * Удаляет товар из корзины
     */
    public function destroy(Product $product, Cart $cart): RedirectResponse
    {
        $cart->remove($product);

        return back()->with('status', 'Товар удалён из корзины');
    }
}
