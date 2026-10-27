<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\withSession;

/**
 * Корректно заполненная форма оформления заказа
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function validCheckoutData(array $overrides = []): array
{
    return [
        'customer_name' => 'Асель Иванова',
        'phone' => '+7 701 555 12 34',
        'email' => 'asel@example.com',
        'address' => 'Алматы, ул. Абая 10',
        'comment' => 'Позвоните за час',
        ...$overrides,
    ];
}

test('checkout page redirects to the cart when the cart is empty', function () {
    get(route('checkout.create'))
        ->assertRedirect(route('cart.index'));
});

test('checkout page shows products from the cart', function () {
    $product = Product::factory()->create(['is_active' => true, 'stock' => 10]);

    withSession(['cart' => [$product->id => 2]])
        ->get(route('checkout.create'))
        ->assertOk()
        ->assertSee($product->name);
});

test('placing an order saves it with items, reduces stock and clears the cart', function () {
    $product = Product::factory()->create(['is_active' => true, 'stock' => 10, 'price' => 1500]);

    $response = withSession(['cart' => [$product->id => 3]])
        ->post(route('checkout.store'), validCheckoutData());

    $order = Order::sole();

    $response->assertRedirect(route('orders.show', $order))
        ->assertSessionHas('orders', [$order->id])
        ->assertSessionMissing('cart');

    expect($order)
        ->customer_name->toBe('Асель Иванова')
        ->status->toBe(OrderStatus::New)
        ->total->toBe('4500.00');

    expect($order->items->sole())
        ->product_id->toBe($product->id)
        ->product_name->toBe($product->name)
        ->price->toBe('1500.00')
        ->quantity->toBe(3);

    expect($product->fresh()->stock)->toBe(7);
});

test('order total and status are set by the server, not by the form', function () {
    $product = Product::factory()->create(['is_active' => true, 'stock' => 10, 'price' => 1500]);

    withSession(['cart' => [$product->id => 2]])
        ->post(route('checkout.store'), validCheckoutData(['total' => 1, 'status' => 'completed']));

    expect(Order::sole())
        ->total->toBe('3000.00')
        ->status->toBe(OrderStatus::New);
});

test('placing an order requires name, phone and address', function () {
    $product = Product::factory()->create(['is_active' => true, 'stock' => 10]);

    withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), [])
        ->assertSessionHasErrors(['customer_name', 'phone', 'address']);

    expect(Order::count())->toBe(0);
});

test('placing an order is rejected when there is not enough stock left', function () {
    $product = Product::factory()->create(['is_active' => true, 'stock' => 2]);

    withSession(['cart' => [$product->id => 5]])
        ->from(route('checkout.create'))
        ->post(route('checkout.store'), validCheckoutData())
        ->assertRedirect(route('checkout.create'))
        ->assertSessionHasErrors(['cart' => "Товара «{$product->name}» осталось только 2 шт."])
        ->assertSessionHas('cart', [$product->id => 5]);

    expect(Order::count())->toBe(0)
        ->and($product->fresh()->stock)->toBe(2);
});

test('placing an order with an empty cart redirects to the cart', function () {
    post(route('checkout.store'), validCheckoutData())
        ->assertRedirect(route('cart.index'));

    expect(Order::count())->toBe(0);
});

test('checkout page redirects to the cart when no product in it is sold anymore', function () {
    $product = Product::factory()->create(['is_active' => false, 'stock' => 10]);

    withSession(['cart' => [$product->id => 1]])
        ->get(route('checkout.create'))
        ->assertRedirect(route('cart.index'));
});

test('placing an order is rejected when no product in the cart is sold anymore', function () {
    $product = Product::factory()->create(['is_active' => false, 'stock' => 10]);

    withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), validCheckoutData())
        ->assertRedirect(route('cart.index'));

    expect(Order::count())->toBe(0);
});

test('order placed by a logged-in user is linked to their account', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['is_active' => true, 'stock' => 10]);

    actingAs($user)
        ->withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), validCheckoutData());

    expect(Order::sole()->user_id)->toBe($user->id);
});

test('order placed by a guest is not linked to any account', function () {
    $product = Product::factory()->create(['is_active' => true, 'stock' => 10]);

    withSession(['cart' => [$product->id => 1]])
        ->post(route('checkout.store'), validCheckoutData());

    expect(Order::sole()->user_id)->toBeNull();
});
