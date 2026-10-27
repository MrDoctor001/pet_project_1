<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertAuthenticatedAs;
use function Pest\Laravel\assertGuest;
use function Pest\Laravel\post;

test('guest can register and is sent to the login page', function () {
    post(route('register.store'), [
        'name' => 'Асель',
        'email' => 'asel@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])
        ->assertRedirect(route('login'))
        ->assertSessionHas('status');

    $user = User::sole();

    expect($user)
        ->name->toBe('Асель')
        ->email->toBe('asel@example.com')
        ->is_admin->toBeFalse()
        ->and(Hash::check('secret-password', $user->password))->toBeTrue();
});

test('registration requires a confirmed password of at least 8 characters', function () {
    post(route('register.store'), [
        'name' => 'Асель',
        'email' => 'asel@example.com',
        'password' => 'short',
        'password_confirmation' => 'other',
    ])->assertSessionHasErrors(['password']);

    expect(User::count())->toBe(0);
});

test('registration cannot make the new user an admin', function () {
    post(route('register.store'), [
        'name' => 'Асель',
        'email' => 'asel@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
        'is_admin' => true,
    ]);

    expect(User::sole()->is_admin)->toBeFalse();
});

test('registration rejects an email that is already taken', function () {
    User::factory()->create(['email' => 'asel@example.com']);

    post(route('register.store'), [
        'name' => 'Асель',
        'email' => 'asel@example.com',
        'password' => 'secret-password',
        'password_confirmation' => 'secret-password',
    ])->assertSessionHasErrors(['email']);

    expect(User::count())->toBe(1);
});

test('user can log in with correct credentials', function () {
    $user = User::factory()->create();

    post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect(route('catalog.index'));

    assertAuthenticatedAs($user);
});

test('user cannot log in with a wrong password', function () {
    $user = User::factory()->create();

    post(route('login.store'), ['email' => $user->email, 'password' => 'wrong-password'])
        ->assertSessionHasErrors(['email']);

    assertGuest();
});

test('login is blocked after too many attempts', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $attempt) {
        post(route('login.store'), ['email' => $user->email, 'password' => 'wrong-password']);
    }

    post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
        ->assertTooManyRequests();

    assertGuest();
});

test('user can log out', function () {
    actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('login'));

    assertGuest();
});
