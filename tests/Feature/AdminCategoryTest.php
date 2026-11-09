<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->admin = User::factory()->admin()->create();
});

test('regular user cannot manage categories', function () {
    $category = Category::factory()->create();
    $user = User::factory()->create();

    actingAs($user)->get(route('admin.categories.index'))->assertForbidden();
    actingAs($user)->post(route('admin.categories.store'), ['name' => 'Спорт'])->assertForbidden();
    actingAs($user)->delete(route('admin.categories.destroy', $category))->assertForbidden();

    expect(Category::count())->toBe(1);
});

test('admin sees categories with the number of products in each', function () {
    $category = Category::factory()->create(['name' => 'Электроника']);
    Product::factory()->count(3)->for($category)->create();

    actingAs($this->admin)
        ->get(route('admin.categories.index'))
        ->assertOk()
        ->assertSee('Электроника')
        ->assertSee('3 товара');
});

test('admin can add a category and its address is made from the name', function () {
    actingAs($this->admin)
        ->post(route('admin.categories.store'), ['name' => 'Спорт и отдых'])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status', 'Категория добавлена');

    expect(Category::sole())
        ->name->toBe('Спорт и отдых')
        ->slug->toBe(Str::slug('Спорт и отдых'));
});

test('category name is required', function () {
    actingAs($this->admin)
        ->post(route('admin.categories.store'), ['name' => ''])
        ->assertSessionHasErrors('name');

    expect(Category::count())->toBe(0);
});

test('two categories cannot have the same name', function () {
    Category::factory()->create(['name' => 'Книги', 'slug' => Str::slug('Книги')]);

    actingAs($this->admin)
        ->post(route('admin.categories.store'), ['name' => 'Книги'])
        ->assertSessionHasErrors('slug');

    expect(Category::count())->toBe(1);
});

test('admin sees the edit page of a category', function () {
    $category = Category::factory()->create(['name' => 'Игрушки']);

    actingAs($this->admin)
        ->get(route('admin.categories.edit', $category))
        ->assertOk()
        ->assertSee('value="Игрушки"', false);
});

test('admin can rename a category and its address changes too', function () {
    $category = Category::factory()->create(['name' => 'Мебель']);

    actingAs($this->admin)
        ->put(route('admin.categories.update', $category), ['name' => 'Мебель для дома'])
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status', 'Категория сохранена');

    expect($category->fresh())
        ->name->toBe('Мебель для дома')
        ->slug->toBe(Str::slug('Мебель для дома'));
});

test('saving a category without changing its name is allowed', function () {
    $category = Category::factory()->create(['name' => 'Книги', 'slug' => Str::slug('Книги')]);

    actingAs($this->admin)
        ->put(route('admin.categories.update', $category), ['name' => 'Книги'])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('admin.categories.index'));
});

test('admin can delete an empty category', function () {
    $category = Category::factory()->create();

    actingAs($this->admin)
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.index'))
        ->assertSessionHas('status', 'Категория удалена');

    expect(Category::count())->toBe(0);
});

test('category with products cannot be deleted', function () {
    $category = Category::factory()->create();
    Product::factory()->for($category)->create();

    actingAs($this->admin)
        ->from(route('admin.categories.edit', $category))
        ->delete(route('admin.categories.destroy', $category))
        ->assertRedirect(route('admin.categories.edit', $category))
        ->assertSessionHasErrors('category');

    expect(Category::count())->toBe(1);
});

test('edit page explains why a category with products cannot be deleted', function () {
    $category = Category::factory()->create();
    Product::factory()->count(2)->for($category)->create();

    actingAs($this->admin)
        ->get(route('admin.categories.edit', $category))
        ->assertOk()
        ->assertSee('Нельзя удалить: в категории 2 товара');
});
