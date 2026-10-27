<?php

use App\Models\Order;
use App\Models\User;

use function Pest\Laravel\actingAs;
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

test('logged-in user can see their order from any session', function () {
    $user = User::factory()->create();
    $order = Order::factory()->for($user)->create();

    actingAs($user)
        ->get(route('orders.show', $order))
        ->assertOk()
        ->assertSee($order->customer_name);
});

test('logged-in user cannot see an order of another user', function () {
    $order = Order::factory()->for(User::factory())->create();

    actingAs(User::factory()->create())
        ->get(route('orders.show', $order))
        ->assertNotFound();
});

test('logged-in user cannot see a guest order from another session', function () {
    $order = Order::factory()->create();

    actingAs(User::factory()->create())
        ->get(route('orders.show', $order))
        ->assertNotFound();
});
