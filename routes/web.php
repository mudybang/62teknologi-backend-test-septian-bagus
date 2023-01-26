<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('business/search', [App\Http\Controllers\BusinessController::class, 'get_data'])->name('business.search');
Route::resource('business', App\Http\Controllers\BusinessController::class)->names([
    'index' => 'business'
]);;
Route::post('business/get_data', [App\Http\Controllers\BusinessController::class, 'get_data'])->name('business.data');
Route::get('bulk', [App\Http\Controllers\BusinessController::class, 'bulk_insert'])->name('business.bulk_insert');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});
