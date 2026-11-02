<?php

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guest is sent to the login page instead of the profile', function () {
    get(route('profile.edit'))->assertRedirect(route('login'));
});

test('profile shows the user data and their orders with item counts', function () {
    $user = User::factory()->create(['name' => 'Асель', 'email' => 'asel@example.com']);
    $order = Order::factory()->for($user)->create();
    $order->items()->createMany([
        ['product_name' => 'Наушники', 'price' => 15000, 'quantity' => 1],
        ['product_name' => 'Чехол', 'price' => 3000, 'quantity' => 2],
    ]);

    actingAs($user)
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertSee('Асель')
        ->assertSee('asel@example.com')
        ->assertSee('Заказ №'.$order->id)
        ->assertSee('2 товара')
        ->assertSee(route('orders.show', $order));
});

test('profile does not show orders of other users', function () {
    $otherUsersOrder = Order::factory()->for(User::factory())->create();

    actingAs(User::factory()->create())
        ->get(route('profile.edit'))
        ->assertOk()
        ->assertDontSee(route('orders.show', $otherUsersOrder))
        ->assertSee('Вы ещё ничего не заказывали');
});

test('user can update their name and email', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->from(route('profile.edit'))
        ->patch(route('profile.update'), ['name' => 'Асель', 'email' => 'asel@example.com'])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status');

    expect($user->fresh())
        ->name->toBe('Асель')
        ->email->toBe('asel@example.com');
});

test('user can keep their current email when changing the name', function () {
    $user = User::factory()->create(['email' => 'asel@example.com']);

    actingAs($user)
        ->patch(route('profile.update'), ['name' => 'Асель', 'email' => 'asel@example.com'])
        ->assertSessionHasNoErrors();

    expect($user->fresh()->name)->toBe('Асель');
});

test('user cannot take an email that belongs to another user', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create(['email' => 'asel@example.com']);

    actingAs($user)
        ->patch(route('profile.update'), ['name' => 'Асель', 'email' => 'taken@example.com'])
        ->assertSessionHasErrors(['email']);

    expect($user->fresh()->email)->toBe('asel@example.com');
});

test('name and email are required to update the profile', function () {
    actingAs(User::factory()->create())
        ->patch(route('profile.update'), ['name' => '', 'email' => 'not-an-email'])
        ->assertSessionHasErrors(['name', 'email']);
});

test('user can change the password by confirming the current one', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->from(route('profile.edit'))
        ->put(route('profile.password'), [
            'current_password' => 'password',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])
        ->assertRedirect(route('profile.edit'))
        ->assertSessionHas('status');

    expect(Hash::check('new-secret-password', $user->fresh()->password))->toBeTrue();
});

test('password is not changed when the current password is wrong', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->put(route('profile.password'), [
            'current_password' => 'wrong-password',
            'password' => 'new-secret-password',
            'password_confirmation' => 'new-secret-password',
        ])
        ->assertSessionHasErrors(['current_password']);

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('new password must be confirmed and at least 8 characters long', function () {
    $user = User::factory()->create();

    actingAs($user)
        ->put(route('profile.password'), [
            'current_password' => 'password',
            'password' => 'short',
            'password_confirmation' => 'other',
        ])
        ->assertSessionHasErrors(['password']);

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('header links to the profile for a logged in user', function () {
    actingAs(User::factory()->create())
        ->get(route('catalog.index'))
        ->assertSee(route('profile.edit'));
});
