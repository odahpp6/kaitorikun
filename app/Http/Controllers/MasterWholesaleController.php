<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MasterWholesale;
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
        return redirect()->route('master.index')->with('success', '卸売り先マスターが登録されました。');
    }

public function list_wholesale()
    {
        $storeId = Auth::id(); // ログインユーザーのIDを取得
        // 認証ユーザーの store_id に基づいて卸売り先マスターを取得
        $wholesales = MasterWholesale::where('store_id', $storeId)->get();
        return view('master.list_wholesale', ['wholesales' => $wholesales]);
    }    






}
