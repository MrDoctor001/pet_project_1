<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function show(Request $request, Order $order): View
    {
        abort_unless(in_array($order->id, $request->session()->get('orders', [])), 404);

        $order->load('items');

        return view('orders.show', compact('order'));
    }
}
