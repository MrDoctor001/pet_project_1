<?php

use App\Models\Category;
use App\Models\Product;

test('customer can search products by name', function () {
    Product::factory()->create(['name' => 'Беспроводные наушники', 'is_active' => true]);
    Product::factory()->create(['name' => 'Шерстяной свитер', 'is_active' => true]);

    $this->get(route('catalog.index', ['search' => 'наушники']))
        ->assertOk()
        ->assertSee('Беспроводные наушники')
        ->assertDontSee('Шерстяной свитер');
});

test('search does not show hidden products', function () {
    Product::factory()->create(['name' => 'Скрытые наушники', 'is_active' => false]);

    $this->get(route('catalog.index', ['search' => 'наушники']))
        ->assertOk()
        ->assertDontSee('Скрытые наушники')
        ->assertSee('Ничего не найдено');
});

test('search on a category page looks only inside that category', function () {
    $headphones = Category::factory()->create();
    Product::factory()->for($headphones)->create(['name' => 'Беспроводные наушники Sony', 'is_active' => true]);
    Product::factory()->create(['name' => 'Детские наушники из другой категории', 'is_active' => true]);

    $this->get(route('catalog.category', ['category' => $headphones, 'search' => 'наушники']))
        ->assertOk()
        ->assertSee('Беспроводные наушники Sony')
        ->assertDontSee('Детские наушники из другой категории');
});

test('pagination keeps the search query', function () {
    Product::factory()->count(13)->for(Category::factory())->create(['name' => 'Наушники', 'is_active' => true]);

    $this->get(route('catalog.index', ['search' => 'Наушники']))
        ->assertOk()
        ->assertSee(route('catalog.index', ['search' => 'Наушники', 'page' => 2]));
});
