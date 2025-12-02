<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstimateController;
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

Route::get('/', function () {
    return view('welcome');
});


//見積システム
Route::get('/estimate', [EstimateController::class, 'create'])->name('estimate.register'); 
Route::post('/estimate', [EstimateController::class, 'store']); 


//見積一覧
Route::get('/estimate/list', [EstimateController::class, 'list'])->name('estimate.list');
//見積詳細
Route::get('/estimate/{id}/detail', [EstimateController::class, 'detail'])->name('estimate.detail');
//見積更新
// web.php
// 見積更新 (GET)
Route::get('/estimate/{id}/edit', [EstimateController::class, 'edit'])->name('estimate.edit'); 

// 見積更新処理 (PATCH)
Route::patch('/estimate/{id}/edit', [EstimateController::class, 'update'])->name('estimate.update');

// 見積削除処理 確認
Route::delete('/estimate/{id}/delete', [EstimateController::class, 'delete'])->name('estimate.delete');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
