<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MasterWholesale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; // ★Authファサードの追加  

class MasterWholesaleController extends Controller
{
    //
public function create_wholesale()
    {
        return view('master.create_wholesale');
    }
public function store_wholesale(Request $request)
    {
        // バリデーション
        $validated = $request->validate([
            'wholesale' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:1000',
        ]);

         // 🚨 認証ユーザーのIDを store_id として取得 🚨
        $storeId = Auth::id(); // ログインユーザーのIDを取得

        $masterwholesale = MasterWholesale::create([
            'store_id' => $storeId,
            'wholesale' => $validated['wholesale'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        // データベースへの保存処理などをここに追加

        // 登録完了後のリダイレクト
        return redirect()->route('master.list_wholesale')->with('success', '卸売り先マスターが登録されました。');
    }


    //一覧表示
public function list_wholesale()
    {
        $storeId = Auth::id(); // ログインユーザーのIDを取得
        // 認証ユーザーの store_id に基づいて卸売り先マスターを取得
        $wholesales = MasterWholesale::where('store_id', $storeId)->get();
        return view('master.list_wholesale', ['wholesales' => $wholesales]);
    }

   //削除確認
//削除確認
// ★ ID を受け取る
public function delete_wholesale($id) 
{
    $storeId = Auth::id();
    // ★ 単一の卸売り先を取得
    $wholesale = MasterWholesale::where('id', $id)
                                ->where('store_id', $storeId)
                                ->firstOrFail(); 
                                
    // ★ 変数名は単数形 'wholesale'
    return view('master.delete_wholesale', ['wholesale' => $wholesale]); 
}

//削除実行
// public function delete($id) メソッドは、EstimateController.phpのdeleteメソッドと同じロジック（トランザクションは不要ですが）で単一削除を実行していれば、そのまま利用可能です。
// EstimateController.php の delete($id) と同様の安全な削除方法 (多対多ではないためシンプル)
public function delete($id): RedirectResponse // 必要であれば: RedirectResponse を追加
                             
{
    $storeId = Auth::id();
    
    // IDとstore_idが一致するレコードのみを削除（Estimate::where('id', $id)->delete() と同じ考え方）
    $deleted = MasterWholesale::where('id', $id)
                              ->where('store_id', $storeId)
                              ->delete();
    
    if ($deleted) {
        return redirect()->route('master.list_wholesale')->with('success', '卸売り先マスターが削除されました。');
    }
    
    // 削除失敗または権限なしの場合
    return redirect()->route('master.index')->with('error', '削除対象が見つからないか、権限がありません。');
}


}
