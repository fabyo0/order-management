<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Categories\CategoriesList;
use App\Livewire\Order\OrderForm;
use App\Livewire\Order\OrderIndex;
use App\Livewire\Products\ProductForm;
use App\Livewire\Products\ProductsLists;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Categories
    Route::get('/categories', CategoriesList::class)->name('categories.index');

    // Products
    Route::get('/products', ProductsLists::class)->name('products.index');
    Route::get('products/create', ProductForm::class)->name('products.create');
    Route::get('products/{product}', ProductForm::class)->name('products.edit');

    // Orders
    Route::get('/orders', OrderIndex::class)->name('orders.index');
    Route::get('orders/create', OrderForm::class)->name('orders.create');
    Route::get('orders/{order}', OrderForm::class)->name('orders.edit');
});

require __DIR__.'/auth.php';
