<?php

use App\Models\Order;

use function Pest\Laravel\get;
use function Pest\Laravel\withSession;

test('customer can see their own order', function () {
    $order = Order::factory()->create();

    withSession(['orders' => [$order->id]])
        ->get(route('orders.show', $order))
        ->assertOk()
        ->assertSee($order->customer_name);
});

test('order page returns 404 for an order from another session', function () {
    $order = Order::factory()->create();
    
    get(route('orders.show', $order))->assertNotFound();
});
