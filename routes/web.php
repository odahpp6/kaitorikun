<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstimateController;
// ★★★ この行を追加 ★★★
use App\Http\Controllers\MasterCampaignController;
use App\Http\Controllers\MasterWholesaleController;
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


//認証済みユーザーのみアクセス可能
Route::middleware('auth')->group(function () {

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
    Route::put('/estimate/{id}/edit', [EstimateController::class, 'update'])->name('estimate.update');

    // 見積削除処理 確認
    Route::get('/estimate/{id}/delete_confirm', [EstimateController::class, 'deleteConfirm'])->name('estimate.delete_confirm');

    // 見積削除処理
    Route::delete('/estimate/{id}/delete', [EstimateController::class, 'delete'])->name('estimate.delete');
    //マスター管理

    //折り込みマスター表示
    Route::get('/master/', [MasterCampaignController::class, 'index'])->name('master.index');
    //折り込みマスター登録
    Route::get('/master/create_campaign',[MasterCampaignController::class, 'create_campaign'])->name('master.create_campaign');
    Route::post('/master/create_campaign',[MasterCampaignController::class, 'store_campaign'])->name('master.store_campaign');
    //折り込みマスター更新画面表示
    Route::get('/master/{id}/edit_campaign',[MasterCampaignController::class, 'edit_campaign'])->name('master.edit_campaign');
    //折り込みマスター更新処理
   Route::put('/master/{id}/edit_campaign', [MasterCampaignController::class, 'update_campaign'])->name('master.update_campaign');





    //卸先マスター登録
    Route::get('/master/create_wholesale',[MasterWholesaleController::class, 'create_wholesale'])->name('master.create_wholesale');
    //卸売りマスター登録
    Route::post('/master/create_wholesale',[MasterCampaignController::class, 'store_wholesale'])->name('master.store_wholesale');

    //卸先マスター登録DB保存
    Route::post('/master/create_wholesale',[MasterWholesaleController::class, 'store_wholesale'])->name('master.store_wholesale');

   // web.php (修正後)

// 卸先マスター一覧 (リスト表示に戻るために必要)
Route::get('/master/list_wholesale',[MasterWholesaleController::class, 'list_wholesale'])->name('master.list_wholesale');
   
// 卸先マスター削除確認 (GET)
// ★確認画面用ルート名に戻す (master.delete_wholesale_confirm)
Route::get('/master/list_wholesale/{id}/delete',[MasterWholesaleController::class, 'delete_wholesale'])->name('master.delete_wholesale_confirm');

// 卸先マスター削除実行 (DELETE)
// ★DELETEルートを追加し、フォームが参照する名前を付ける
Route::delete('/master/list_wholesale/{id}/delete',[MasterWholesaleController::class, 'delete'])->name('master.delete_wholesale');

// ★ 以前の提案で追加した未使用のルートは削除またはコメントアウト
// Route::delete('/master/delete_wholesale_execute', [MasterWholesaleController::class, 'deleteExecute'])->name('master.delete_wholesale_execute');


});








Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
