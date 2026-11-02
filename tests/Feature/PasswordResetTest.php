<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

beforeEach(function () {
    Notification::fake();
});

test('login page links to the forgot password page', function () {
    get(route('login'))
        ->assertOk()
        ->assertSee(route('password.request'));
});

test('reset link is sent to a registered email', function () {
    $user = User::factory()->create();

    post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()
        ->assertSessionHas('status');

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset link request for an unregistered email shows an error', function () {
    post(route('password.email'), ['email' => 'nobody@example.com'])
        ->assertSessionHasErrors(['email' => 'Такой email не зарегистрирован']);

    Notification::assertNothingSent();
});

test('reset link cannot be requested again within a minute', function () {
    $user = User::factory()->create();

    post(route('password.email'), ['email' => $user->email]);
    post(route('password.email'), ['email' => $user->email])
        ->assertSessionHasErrors(['email']);

    Notification::assertSentToTimes($user, ResetPassword::class, 1);
});

test('reset password page prefills the email from the link', function () {
    $user = User::factory()->create();

    get(route('password.reset', ['token' => 'some-token', 'email' => $user->email]))
        ->assertOk()
        ->assertSee($user->email);
});

test('user can set a new password with a valid token', function () {
    $user = User::factory()->create();

    post(route('password.update'), [
        'token' => Password::createToken($user),
        'email' => $user->email,
        'password' => 'new-secret-password',
        'password_confirmation' => 'new-secret-password',
    ])
        ->assertRedirect(route('login'))
        ->assertSessionHas('status');

    expect(Hash::check('new-secret-password', $user->fresh()->password))->toBeTrue();
});

test('password is not changed with an invalid token', function () {
    $user = User::factory()->create();

    post(route('password.update'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'new-secret-password',
        'password_confirmation' => 'new-secret-password',
    ])->assertSessionHasErrors(['email']);

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('new password must be confirmed and at least 8 characters', function () {
    $user = User::factory()->create();

    post(route('password.update'), [
        'token' => Password::createToken($user),
        'email' => $user->email,
        'password' => 'short',
        'password_confirmation' => 'other',
    ])->assertSessionHasErrors(['password']);

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
});

test('logged in user cannot open the forgot password page', function () {
    actingAs(User::factory()->create())
        ->get(route('password.request'))
        ->assertRedirect();
});
