<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/categories/{category:slug}', [CatalogController::class, 'category'])->name('catalog.category');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');
