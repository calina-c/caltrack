<?php

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

Route::get('/', [\App\Http\Controllers\Controller::class, 'getFoodEntries'])->name('food-entries.index');
Route::get('/food-items', [\App\Http\Controllers\Controller::class, 'getFoodItems'])->name('food-items.index');
Route::post('/food-items', [\App\Http\Controllers\Controller::class, 'addFoodItem'])->name('food-items.store');
Route::delete('/food-items/{id}', [\App\Http\Controllers\Controller::class, 'deleteFoodItem'])->name('food-items.destroy');
