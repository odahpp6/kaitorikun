<?php

namespace App\Http\Controllers;
use App\Models\MasterStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // ★Authファサードの追加  
class MasterStaffController extends Controller
{
    //　担当者マスター登録画面表示
    public function create_staff()
    {  $storeId = Auth::id(); // ログインユーザーのIDを取得
         // 認証ユーザーの store_id に基づいて担当者マスターを取得
         $staffs = MasterStaff::where('store_id', $storeId)->get();
          return view('master.create_staff',compact('staffs'));
    }
    //　担当者マスター登録DB保存
    public function store_staff(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'staff_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);
         // 🚨 認証ユーザーのIDを store_id として取得 🚨
        $storeId = Auth::id(); // ログインユーザーのIDを取得
       
        $masterStaff = MasterStaff::create([
            'store_id' => $storeId,
            'staff_name' => $validatedData['staff_name'],
            'position' => $validatedData['position'],
            'remarks' => $validatedData['remarks'] ?? null,
        ]);
        // 登録完了後のリダイレクト
        return redirect()->route('master.create_staff')->with('success', '担当者マスターが登録されました。');
        
        
    }
    public function delete_confirm($id)
    {   $storeId = Auth::id(); // ログイン中の店舗ID
        $staff = MasterStaff::where('id', $id)
                            ->where('store_id', $storeId)
                            ->firstOrFail();
        return view('master.delete_confirm_staff', compact('staff'));
    }
    //削除処理
    public function delete($id)
    {
        $storeId = Auth::id(); // ログイン中の店舗ID
        $staff = MasterStaff::where('id', $id)
                            ->where('store_id', $storeId)
                            ->firstOrFail();
        $staff->delete();
        return redirect()->route('master.create_staff')->with('success', '担当者マスターが削除されました。');
    }



}
