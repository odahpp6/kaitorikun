<?php
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EstimateController;
// ★★★ この行を追加 ★★★
use App\Http\Controllers\MasterCampaignController;
use App\Http\Controllers\MasterWholesaleController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\SaleController;
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

Route::get('/', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

//認証済みユーザーのみアクセス可能
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    //見積システム
    Route::get('/estimate', [EstimateController::class, 'create'])->name('estimate.register'); 
    Route::post('/estimate', [EstimateController::class, 'store']); 

    //見積一覧
    Route::get('/estimate/list', [EstimateController::class, 'list'])->name('estimate.list');
    //見積詳細
    Route::get('/estimate/{id}/detail', [EstimateController::class, 'detail'])->name('estimate.detail');
    //見積印刷（PDF）
    Route::get('/estimate/{id}/print', [EstimateController::class, 'print'])->name('estimate.print');
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
     //折り込みマスター削除確認
    Route::get('/master/{id}/dlete_campaign',[MasterCampaignController::class, 'delete_campaign'])->name('master.delete_campaign');
   
   
    //折り込みマスター削除実行
    Route::delete('/master/{id}/dlete_campaign',[MasterCampaignController::class, 'delete_campaign_excecute'])->name('master.delete_campaign_excecute');



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

// 買取契約登録
Route::get('/purchase',[BuyController::class, 'purchase'])->name('purchase.register');
Route::post('/purchase',[BuyController::class, 'store'])->name('purchase.store');

// 買取契約登録一覧
Route::get('/purchase/list',[BuyController::class, 'purchase_list'])->name('purchase.list');

// 買取契約検索
Route::get('/purchase/list',[BuyController::class, 'index'])->name('purchase.search');

// 買取登録詳細
Route::get('/purchase/{id}/detail',[BuyController::class, 'purchase_detail'])->name('purchase.detail');

// 買取登録契約書印刷
Route::get('/purchase/{id}/print',[BuyController::class, 'purchase_print'])->name('purchase.print');


// 買取登録修正画面表示
Route::get('/purchase/{id}/edit',[BuyController::class, 'purchase_edit'])->name('purchase.edit');
// 買取登録更新処理
Route::put('/purchase/{id}/edit',[BuyController::class, 'purchase_update'])->name('purchase.update');
// 買取登録削除確認
Route::get('/purchase/{id}/delete_confirm',[BuyController::class, 'purchase_delete_confirm'])->name('purchase.delete_confirm');
// 買取登録削除実行
Route::delete('/purchase/{id}/delete',[BuyController::class, 'purchase_delete'])->name('purchase.delete');

// 販売登録
Route::get('/sale', [SaleController::class, 'register_view'])->name('sale.register');




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
