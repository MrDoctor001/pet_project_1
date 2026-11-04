<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    Storage::fake('public');

    $this->admin = User::factory()->admin()->create();
    $this->category = Category::factory()->create();
});

/**
 * @return array<string, mixed>
 */
function validProductData(array $overrides = []): array
{
    return [
        'name' => 'Наушники Sony WH-1000XM5',
        'category_id' => test()->category->id,
        'price' => '149990',
        'stock' => '12',
        'description' => 'Лучшее шумоподавление',
        'is_active' => '1',
        ...$overrides,
    ];
}

test('regular user cannot manage products', function () {
    $product = Product::factory()->create();

    actingAs(User::factory()->create())->get(route('admin.products.index'))->assertForbidden();
    actingAs(User::factory()->create())->post(route('admin.products.store'), validProductData())->assertForbidden();
    actingAs(User::factory()->create())->delete(route('admin.products.destroy', $product))->assertForbidden();

    expect(Product::count())->toBe(1);
});

test('admin sees all products including hidden ones', function () {
    Product::factory()->create(['name' => 'Видимый товар', 'is_active' => true]);
    Product::factory()->create(['name' => 'Скрытый товар', 'is_active' => false]);

    actingAs($this->admin)
        ->get(route('admin.products.index'))
        ->assertOk()
        ->assertSee('Видимый товар')
        ->assertSee('Скрытый товар');
});

test('admin can search products by name', function () {
    Product::factory()->create(['name' => 'Беспроводные наушники']);
    Product::factory()->create(['name' => 'Шерстяной свитер']);

    actingAs($this->admin)
        ->get(route('admin.products.index', ['search' => 'наушники']))
        ->assertOk()
        ->assertSee('Беспроводные наушники')
        ->assertDontSee('Шерстяной свитер');
});

test('create page lists categories to choose from', function () {
    actingAs($this->admin)
        ->get(route('admin.products.create'))
        ->assertOk()
        ->assertSee($this->category->name);
});

test('admin can add a product with a photo', function () {
    actingAs($this->admin)
        ->post(route('admin.products.store'), validProductData([
            'image' => UploadedFile::fake()->image('headphones.jpg'),
        ]))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('status');

    $product = Product::sole();

    expect($product)
        ->name->toBe('Наушники Sony WH-1000XM5')
        ->slug->toBe(Str::slug('Наушники Sony WH-1000XM5'))
        ->category_id->toBe($this->category->id)
        ->price->toEqual('149990.00')
        ->stock->toBe(12)
        ->is_active->toBeTrue();

    Storage::disk('public')->assertExists($product->image);
});

test('product can be added without a photo', function () {
    actingAs($this->admin)
        ->post(route('admin.products.store'), validProductData())
        ->assertSessionHasNoErrors();

    expect(Product::sole()->image)->toBeNull();
});

test('product is hidden when the checkbox is unchecked', function () {
    actingAs($this->admin)->post(route('admin.products.store'), validProductData(['is_active' => '0']));

    expect(Product::sole()->is_active)->toBeFalse();
});

test('product data is validated', function () {
    actingAs($this->admin)
        ->post(route('admin.products.store'), [
            'name' => '',
            'category_id' => 999,
            'price' => '-5',
            'stock' => '1.5',
            'image' => UploadedFile::fake()->create('virus.exe', 10),
        ])
        ->assertSessionHasErrors(['name', 'category_id', 'price', 'stock', 'image']);

    expect(Product::count())->toBe(0);
});

test('two products cannot get the same address', function () {
    Product::factory()->create(['name' => 'Наушники', 'slug' => Str::slug('Наушники')]);

    actingAs($this->admin)
        ->post(route('admin.products.store'), validProductData(['name' => 'Наушники']))
        ->assertSessionHasErrors(['slug']);

    expect(Product::count())->toBe(1);
});

test('edit page shows the product data', function () {
    $product = Product::factory()->create(['name' => 'Старое название']);

    actingAs($this->admin)
        ->get(route('admin.products.edit', $product))
        ->assertOk()
        ->assertSee('Старое название');
});

test('admin can update a product and keep its photo', function () {
    $product = Product::factory()->create(['image' => 'products/old.jpg']);

    actingAs($this->admin)
        ->put(route('admin.products.update', $product), validProductData(['name' => 'Новое название', 'price' => '999']))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('status');

    expect($product->fresh())
        ->name->toBe('Новое название')
        ->slug->toBe(Str::slug('Новое название'))
        ->price->toEqual('999.00')
        ->image->toBe('products/old.jpg');
});

test('product keeps its name when other fields change', function () {
    $product = Product::factory()->create(['name' => 'Наушники', 'slug' => Str::slug('Наушники')]);

    actingAs($this->admin)
        ->put(route('admin.products.update', $product), validProductData(['name' => 'Наушники', 'stock' => '3']))
        ->assertSessionHasNoErrors();

    expect($product->fresh()->stock)->toBe(3);
});

test('new photo replaces the old one and the old file is deleted', function () {
    Storage::disk('public')->put('products/old.jpg', 'old');
    $product = Product::factory()->create(['image' => 'products/old.jpg']);

    actingAs($this->admin)->put(route('admin.products.update', $product), validProductData([
        'image' => UploadedFile::fake()->image('new.jpg'),
    ]));

    $newImage = $product->fresh()->image;

    expect($newImage)->not->toBe('products/old.jpg');
    Storage::disk('public')->assertExists($newImage);
    Storage::disk('public')->assertMissing('products/old.jpg');
});

test('admin can delete a product and its photo', function () {
    Storage::disk('public')->put('products/old.jpg', 'old');
    $product = Product::factory()->create(['image' => 'products/old.jpg']);

    actingAs($this->admin)
        ->delete(route('admin.products.destroy', $product))
        ->assertRedirect(route('admin.products.index'))
        ->assertSessionHas('status');

    expect(Product::count())->toBe(0);
    Storage::disk('public')->assertMissing('products/old.jpg');
});

test('deleted product stays in old orders under its name', function () {
    $product = Product::factory()->create(['name' => 'Наушники']);
    $order = Order::factory()->create();
    $order->items()->create(['product_id' => $product->id, 'product_name' => 'Наушники', 'price' => 1000, 'quantity' => 1]);

    actingAs($this->admin)->delete(route('admin.products.destroy', $product));

    expect($order->items()->sole())
        ->product_id->toBeNull()
        ->product_name->toBe('Наушники');
});
