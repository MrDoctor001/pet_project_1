<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Страница заказа: доступна в сессии, где он оформлен, и его владельцу
     */
    public function show(Request $request, Order $order): View
    {
        $isPlacedInThisSession = in_array($order->id, $request->session()->get('orders', []));
        $isPlacedByCurrentUser = $order->user_id !== null && $order->user_id === $request->user()?->id;

        abort_unless($isPlacedInThisSession || $isPlacedByCurrentUser, 404);

        $order->load('items');

        return view('orders.show', compact('order'));
    }
}
