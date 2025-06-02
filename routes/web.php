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

Route::get('/', [\App\Http\Controllers\Controller::class, 'getFoodEntries'])->name('food-entries.index')->middleware('auth');
;
Route::get('/food-items', [\App\Http\Controllers\Controller::class, 'getFoodItems'])->name('food-items.index')->middleware('auth');
;
Route::post('/food-items', [\App\Http\Controllers\Controller::class, 'addFoodItem'])->name('food-items.store')->middleware('auth');
;
Route::delete('/food-items/{id}', [\App\Http\Controllers\Controller::class, 'deleteFoodItem'])->name('food-items.destroy')->middleware('auth');
;
Route::post('/food-entries', [\App\Http\Controllers\Controller::class, 'addFoodEntry'])->name('food-entries.store')->middleware('auth');
;
Route::delete('/food-entries/{id}', [\App\Http\Controllers\Controller::class, 'deleteFoodEntry'])->name('food-entries.destroy')->middleware('auth');
;

Route::get('login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');
