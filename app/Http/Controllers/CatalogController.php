<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Список активных товаров
     */
    public function index(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with('category')
            ->latest()
            ->paginate(12);

        return view('catalog.index', compact('products'));
    }
}
