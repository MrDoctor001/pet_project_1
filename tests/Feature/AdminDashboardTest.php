<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

test('dashboard counts all orders and new orders', function () {
    Order::factory()->count(2)->create(['status' => OrderStatus::New]);
    Order::factory()->create(['status' => OrderStatus::Completed]);
    Order::factory()->create(['status' => OrderStatus::Canceled]);

    actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertViewHas('ordersCount', 4)
        ->assertViewHas('newOrdersCount', 2);
});

test('revenue does not include canceled orders', function () {
    Order::factory()->create(['status' => OrderStatus::New, 'total' => 1000]);
    Order::factory()->create(['status' => OrderStatus::Completed, 'total' => 3000]);
    Order::factory()->create(['status' => OrderStatus::Canceled, 'total' => 5000]);

    actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertViewHas('revenue', fn ($revenue) => (float) $revenue === 4000.0);
});

test('dashboard counts products that are out of stock', function () {
    Product::factory()->count(2)->create(['stock' => 0]);
    Product::factory()->create(['stock' => 5]);

    actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertViewHas('outOfStockCount', 2)
        ->assertSee('товара закончились');
});

test('dashboard shows the five latest orders, newest first', function () {
    Order::factory()->create(['customer_name' => 'Самый старый', 'created_at' => now()->subDays(10)]);

    foreach (range(1, 4) as $daysAgo) {
        Order::factory()->create(['customer_name' => "Покупатель {$daysAgo}", 'created_at' => now()->subDays($daysAgo)]);
    }

    $newest = Order::factory()->create(['customer_name' => 'Самый новый']);
    Order::factory()->create(['customer_name' => 'Позапрошлый месяц', 'created_at' => now()->subMonths(2)]);

    actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertViewHas('latestOrders', fn ($orders) => $orders->count() === 5 && $orders->first()->is($newest))
        ->assertSeeInOrder(['Самый новый', 'Покупатель 1', 'Покупатель 4'])
        ->assertSee(route('admin.orders.show', $newest))
        ->assertDontSee('Позапрошлый месяц')
        ->assertDontSee('Самый старый');
});

test('dashboard tells when there are no orders yet', function () {
    actingAs($this->admin)
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Заказов пока нет')
        ->assertViewHas('ordersCount', 0)
        ->assertViewHas('revenue', fn ($revenue) => (float) $revenue === 0.0);
});
