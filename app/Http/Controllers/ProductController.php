<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    /**
     * Показывает страницу продукта
     */
    public function show(Product $product): View
    {
        abort_if(! $product->is_active, 404);

        return view('products.show', compact('product'));
    }
}
