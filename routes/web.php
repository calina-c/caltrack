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

# Authentication Routes
Route::get('login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');

# Food Entry Routes
Route::get('/', [\App\Http\Controllers\FoodEntriesController::class, 'getFoodEntries'])->name('food-entries.index')->middleware('auth');
Route::post('/food-entries', [\App\Http\Controllers\FoodEntriesController::class, 'addFoodEntry'])->name('food-entries.store')->middleware('auth');
Route::delete('/food-entries/{id}', [\App\Http\Controllers\FoodEntriesController::class, 'deleteFoodEntry'])->name('food-entries.destroy')->middleware('auth');

# Catalogue Routes
## retrieval
Route::get('/food-items', [
    \App\Http\Controllers\FoodItemsController::class, 'getFoodItems'])->name('food-items.index');
Route::get('/api/food-item-format/{id}', [\App\Http\Controllers\FoodItemsController::class, 'getFoodItemFormat'])->name('api.food-item-format')->middleware('auth');
##  require auth
Route::post('/food-items', [\App\Http\Controllers\FoodItemsController::class, 'addFoodItem'])->name('food-items.store')->middleware('auth');
Route::delete('/food-items/{id}', [\App\Http\Controllers\FoodItemsController::class, 'deleteFoodItem'])->name('food-items.destroy')->middleware('auth');

# Day Management Routes
Route::post('/days', [\App\Http\Controllers\DayController::class, 'addDay'])->name('days.store')->middleware('auth');
Route::post('/days/{id}', [\App\Http\Controllers\DayController::class, 'editDay'])->name('days.update')->middleware('auth');

# Goal Management Routes
Route::post('/goals/{goal_type_id}', [\App\Http\Controllers\GoalController::class, 'update'])->name('goals.update')->middleware('auth');
