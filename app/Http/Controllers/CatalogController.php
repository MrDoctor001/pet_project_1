<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    /**
     * Список активных товаров
     */
    public function index(Request $request): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->with('category')
            ->when($request->search, fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()
            ->with('coverProduct')
            ->withCount(['products' => fn (Builder $query) => $query->where('is_active', true)])
            ->orderBy('name')
            ->get();

        return view('catalog.index', compact('products', 'categories'));
    }

    /**
     * Страница категории
     */
    public function category(Request $request, Category $category): View
    {
        $products = $category->products()
            ->where('is_active', true)
            ->with('category')
            ->when($request->search, fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('catalog.index', [
            'products' => $products,
            'categories' => $categories,
            'currentCategory' => $category,
        ]);
    }
}
