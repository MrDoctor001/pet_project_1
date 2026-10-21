<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Создать категории из файла каталога.
     */
    public function run(): void
    {
        $catalog = File::json(database_path('seeders/data/catalog.json'));

        foreach ($catalog as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name'], '-', 'ru'),
            ]);
        }
    }
}
