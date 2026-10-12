<?php

use App\Models\Product;

test('adding a product stores it in the session cart and redirects back', function () {
    $product = Product::factory()->create(['is_active' => true, 'stock' => 10]);

    $this->from(route('products.show', $product))
        ->post(route('cart.store', $product), ['quantity' => 2])
        ->assertRedirect(route('products.show', $product))
        ->assertSessionHas('status')
        ->assertSessionHas('cart', [$product->id => 2]);
});

test('cart quantity never exceeds product stock', function () {
    $product = Product::factory()->create(['is_active' => true, 'stock' => 3]);

    $this->withSession(['cart' => [$product->id => 2]])
        ->post(route('cart.store', $product), ['quantity' => 3])
        ->assertSessionHas('cart', [$product->id => 3]);
});

test('inactive products cannot be added to the cart', function () {
    $product = Product::factory()->create(['is_active' => false, 'stock' => 10]);

    $this->post(route('cart.store', $product), ['quantity' => 1])
        ->assertNotFound();
});

test('empty cart page renders', function () {
    $this->get(route('cart.index'))
        ->assertOk()
        ->assertSee('В корзине пока пусто');
});
