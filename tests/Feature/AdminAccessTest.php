<?php

use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

test('guest is sent to the login page instead of the admin panel', function () {
    get(route('admin.dashboard'))->assertRedirect(route('login'));
});

test('regular user cannot open the admin panel', function () {
    actingAs(User::factory()->create())
        ->get(route('admin.dashboard'))
        ->assertForbidden();
});

test('admin can open the admin panel', function () {
    actingAs(User::factory()->admin()->create(['name' => 'Админ']))
        ->get(route('admin.dashboard'))
        ->assertOk()
        ->assertSee('Здравствуйте, Админ!');
});

test('admin sees a link to the admin panel in the shop header', function () {
    actingAs(User::factory()->admin()->create())
        ->get(route('catalog.index'))
        ->assertSee(route('admin.dashboard'));
});

test('regular user does not see a link to the admin panel', function () {
    actingAs(User::factory()->create())
        ->get(route('catalog.index'))
        ->assertDontSee('Админка');
});
