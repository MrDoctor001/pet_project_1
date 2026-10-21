<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Заполнение таблицы продуктов
     */
    public function run(): void
    {
        $catalog = File::json(database_path('seeders/data/catalog.json'));

        foreach ($catalog as $categoryData) {
            $category = Category::where('slug', Str::slug($categoryData['name'], '-', 'ru'))->firstOrFail();

            foreach ($categoryData['products'] as $productData) {
                Storage::disk('public')->put('products/'.$productData['image'], File::get(database_path('seeders/images/'.$productData['image'])));

                $category->products()->create([
                    'name' => $productData['name'],
                    'slug' => Str::slug($productData['name'], '-', 'ru'),
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'stock' => $productData['stock'],
                    'image' => 'products/'.$productData['image'],
                ]);
            }
        }
    }
}
