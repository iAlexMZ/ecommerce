<?php
namespace App;

use App\Http\Livewire\Admin\ShowCity;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\EditProduct;
use App\Http\Livewire\Admin\ShowCategory;
use App\Http\Livewire\Admin\ShowProducts;
use App\Http\Livewire\Admin\CreateProduct;
use App\Http\Livewire\Admin\ShowProducts2;
use App\Http\Livewire\Admin\UserComponent;
use App\Http\Livewire\Admin\BrandComponent;
use App\Http\Livewire\Admin\ShowDepartment;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Livewire\Admin\DepartmentComponent;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;


Route::get('/', ShowProducts::class)->name('admin.index');
Route::get('details', ShowProducts2::class)->name('admin.details.index');

Route::get('products/{product}/edit', EditProduct::class)->name('admin.products.edit');

Route::get('products/create', CreateProduct::class)->name('admin.products.create');

Route::post('product/{product}/files', [ProductController::class, 'files'])->name('admin.products.files');

Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories.index');

Route::get('categories/{category}', ShowCategory::class)->name('admin.categories.show');

Route::get('brands', BrandComponent::class)->name('admin.brands.index');

Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');

Route::get('departments', DepartmentComponent::class)->name('admin.departments.index');
Route::get('departments/{department}', ShowDepartment::class)->name('admin.departments.show');

Route::get('cities/{city}', ShowCity::class)->name('admin.cities.show');

Route::get('users', UserComponent::class)->name('admin.users.index');
