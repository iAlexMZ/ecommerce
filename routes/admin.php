<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\EditProduct;
use App\Http\Livewire\Admin\ShowProducts;
use App\Http\Livewire\Admin\CreateProduct;
use App\Http\Controllers\Admin\ProductController;


Route::get('/', ShowProducts::class)->name('admin.index');

Route::get('products/{product}/edit', EditProduct::class)->name('admin.products.edit');

Route::get('products/create', CreateProduct::class)->name('admin.products.create');

Route::post('product/{product}/files', [ProductController::class, 'files'])->name('admin.products.files');

