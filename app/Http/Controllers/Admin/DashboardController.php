<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Главная админки со статистикой
     */
    public function index(): View
    {
        $ordersCount = Order::count();

        $newOrdersCount = Order::where('status', OrderStatus::New)->count();

        $revenue = Order::where('status', '!=', OrderStatus::Canceled)->sum('total');

        $outOfStockCount = Product::where('stock', 0)->count();

        $latestOrders = Order::latest()->take(5)->get();

        return view('admin.dashboard', [
            'ordersCount' => $ordersCount,
            'newOrdersCount' => $newOrdersCount,
            'revenue' => $revenue,
            'outOfStockCount' => $outOfStockCount,
            'latestOrders' => $latestOrders,
        ]);
    }
}
