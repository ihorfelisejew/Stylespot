<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*--------home-pages--------*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/men-clothes', [HomeController::class, 'indexMen'])->name('home.men');
Route::get('/women-clothes', [HomeController::class, 'indexWomen'])->name('home.women');
/*--------catalog-pages--------*/
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{slug}/{sku}', [CatalogController::class, 'productPage'])->name('catalog.product-page');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/subtract', [CartController::class, 'subtract']);
/*-------order-pages-------*/
Route::get('/order-products', [OrderController::class, 'index'])->name('order.index');
Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
