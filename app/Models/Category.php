<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    /**
     * Товары, относящиеся к категории
     *
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Первый активный товар с фото — его картинка служит обложкой категории
     *
     * @return HasOne<Product, $this>
     */
    public function coverProduct(): HasOne
    {
        return $this->hasOne(Product::class)->ofMany(['id' => 'min'], function (Builder $query) {
            $query->where('is_active', true)->whereNotNull('image');
        });
    }
}
