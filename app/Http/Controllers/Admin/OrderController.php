<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Список заказов с фильтром по статусу и поиском по имени или телефону
     */
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', ['orders' => $orders]);
    }

    /**
     * Страница заказа
     */
    public function show(Order $order): View
    {
        $order->load(['items', 'user']);

        return view('admin.orders.show', ['order' => $order]);
    }

    /**
     * Смена статуса заказа
     */
    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)],
        ]);

        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('status', 'Статус заказа обновлён');
    }
}
