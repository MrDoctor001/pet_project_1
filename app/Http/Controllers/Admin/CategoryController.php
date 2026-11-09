<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Список категорий
     */
    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('name')->get();

        return view('admin.categories.index', ['categories' => $categories]);
    }

    /**
     * Добавление категории
     */
    public function store(CategoryRequest $request): RedirectResponse
    {
        Category::create($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Категория добавлена');
    }

    /**
     * Страница редактирования
     */
    public function edit(Category $category): View
    {
        $category->loadCount('products');

        return view('admin.categories.edit', ['category' => $category]);
    }

    /**
     * Сохранение категории
     */
    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()->route('admin.categories.index')->with('status', 'Категория сохранена');
    }

    /**
     * Удаление категории
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->withErrors(['category' => 'Нельзя удалить категорию, в которой есть товары']);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Категория удалена');
    }
}
