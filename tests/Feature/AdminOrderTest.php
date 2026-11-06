<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

test('regular user cannot manage orders', function () {
    $order = Order::factory()->create();
    $user = User::factory()->create();

    actingAs($user)->get(route('admin.orders.index'))->assertForbidden();
    actingAs($user)->get(route('admin.orders.show', $order))->assertForbidden();
    actingAs($user)->patch(route('admin.orders.update', $order), ['status' => 'completed'])->assertForbidden();

    expect($order->fresh()->status)->toBe(OrderStatus::New);
});

test('admin sees all orders, newest first', function () {
    Order::factory()->create(['customer_name' => 'Старый Заказчик', 'created_at' => now()->subDay()]);
    Order::factory()->create(['customer_name' => 'Новый Заказчик']);

    actingAs($this->admin)
        ->get(route('admin.orders.index'))
        ->assertOk()
        ->assertSeeInOrder(['Новый Заказчик', 'Старый Заказчик']);
});

test('admin can filter orders by status', function () {
    Order::factory()->create(['customer_name' => 'Ждёт отправки', 'status' => OrderStatus::Processing]);
    Order::factory()->create(['customer_name' => 'Уже получил', 'status' => OrderStatus::Completed]);

    actingAs($this->admin)
        ->get(route('admin.orders.index', ['status' => 'processing']))
        ->assertOk()
        ->assertSee('Ждёт отправки')
        ->assertDontSee('Уже получил');
});

test('admin can search orders by customer name or phone', function () {
    Order::factory()->create(['customer_name' => 'Асель Иванова', 'phone' => '+7 701 111 11 11']);
    Order::factory()->create(['customer_name' => 'Бауыржан Ким', 'phone' => '+7 777 222 22 22']);

    actingAs($this->admin)
        ->get(route('admin.orders.index', ['search' => 'Асель']))
        ->assertSee('Асель Иванова')
        ->assertDontSee('Бауыржан Ким');

    actingAs($this->admin)
        ->get(route('admin.orders.index', ['search' => '777 222']))
        ->assertSee('Бауыржан Ким')
        ->assertDontSee('Асель Иванова');
});

test('admin sees order details with items and customer data', function () {
    $customer = User::factory()->create(['name' => 'Диар Сарбай']);
    $order = Order::factory()->for($customer)->create([
        'customer_name' => 'Асель Иванова',
        'address' => 'Алматы, ул. Абая 10',
        'comment' => 'Позвоните за час',
        'total' => 4500,
    ]);
    $order->items()->create(['product_name' => 'Кружка с котом', 'price' => 1500, 'quantity' => 3]);

    actingAs($this->admin)
        ->get(route('admin.orders.show', $order))
        ->assertOk()
        ->assertSee('Заказ №'.$order->id)
        ->assertSee('Кружка с котом')
        ->assertSee('Асель Иванова')
        ->assertSee('Алматы, ул. Абая 10')
        ->assertSee('Позвоните за час')
        ->assertSee('Диар Сарбай');
});

test('admin can change order status', function () {
    $order = Order::factory()->create();

    actingAs($this->admin)
        ->patch(route('admin.orders.update', $order), ['status' => 'shipped'])
        ->assertRedirect(route('admin.orders.show', $order))
        ->assertSessionHas('status', 'Статус заказа обновлён');

    expect($order->fresh()->status)->toBe(OrderStatus::Shipped);
});

test('order status must be one of the known statuses', function () {
    $order = Order::factory()->create();

    actingAs($this->admin)
        ->from(route('admin.orders.show', $order))
        ->patch(route('admin.orders.update', $order), ['status' => 'lost'])
        ->assertRedirect(route('admin.orders.show', $order))
        ->assertSessionHasErrors('status');

    expect($order->fresh()->status)->toBe(OrderStatus::New);
});

test('changing status does not touch other order fields', function () {
    $order = Order::factory()->create(['customer_name' => 'Асель Иванова', 'total' => 4500]);

    actingAs($this->admin)->patch(route('admin.orders.update', $order), [
        'status' => 'processing',
        'customer_name' => 'Взломщик',
        'total' => 1,
    ]);

    expect($order->fresh())
        ->status->toBe(OrderStatus::Processing)
        ->customer_name->toBe('Асель Иванова')
        ->total->toBe('4500.00');
});

test('customer sees the new status in their profile', function () {
    $customer = User::factory()->create();
    $order = Order::factory()->for($customer)->create();

    actingAs($this->admin)->patch(route('admin.orders.update', $order), ['status' => 'shipped']);

    actingAs($customer)
        ->get(route('profile.edit'))
        ->assertSee('Отправлен');
});
