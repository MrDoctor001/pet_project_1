<?php

use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/categories/{category:slug}', [CatalogController::class, 'category'])->name('catalog.category');
