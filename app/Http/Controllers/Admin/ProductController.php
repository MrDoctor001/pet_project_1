<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Главная страница продуктов для админки
     */
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->when($request->search, fn($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', ['products' => $products]);
    }

    /**
     * Форма добавления товара
     */
    public function create(): View
    {
        return view('admin.products.create', ['categories' => Category::orderBy('name')->get()]);
    }

    /**
     * Добавление товара
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('status', 'Товар добавлен');
    }

    /**
     * Форма редактирования товара
     */
    public function edit(Product $product): View
    {
        return view('admin.products.edit', ['categories' => Category::orderBy('name')->get(), 'product' => $product]);
    }

    /**
     * Обновление товара
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('products', 'public');

            if($product->image){
                Storage::disk('public')->delete($product->image);
            }
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('status', 'Товар сохранён');
    }

    /**
     * Удаляет товар
     */
    public function destroy(Product $product): RedirectResponse
    {
        if($product->image){
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('status', 'Товар удалён');
    }
}
