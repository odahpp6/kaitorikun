<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\MasterCampaign;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth; // ★Authファサードの追加

class MasterCampaignController extends Controller
      
{

    //キャンペーン一覧表示
    public function index()
    {
      $mastercampaigns = MasterCampaign::where('store_id', Auth::id())->get();
      return view('master.index', ['mastercampaigns' => $mastercampaigns]);
    }




//キャンペーン登録画面表示
  public function create_campaign()
    {
      $storeId = Auth::id(); // ログインユーザーのIDを取得
      $mastercampaigns = MasterCampaign::where('store_id', $storeId)->get(); 
      return view('master.create_campaign',compact('mastercampaigns'));

    }

  public function store_campaign(Request $request)
    {
      //バリデーションルール
      $validated = $request->validate([
        'campaign' => 'required|string|max:255',
        'distribution_date' => 'nullable|date',
         'remarks' => 'nullable|string|min:0|max:255',
      ]);

       // 1. 認証ユーザーからDBに必要な情報を取得（仮の値から認証情報に変更）
       // 🚨 認証ユーザーのIDを store_id として取得 🚨
        $storeId = Auth::id(); // ログインユーザーのIDを取得
       // 🚨 認証ユーザーの role を取得 🚨
        // Userモデルにroleカラムがあることが前提

        $mastercampaign = MasterCampaign::create([
          'store_id' => $storeId,
          'campaign' => $validated['campaign'],
          'distribution_date' => $validated['distribution_date'] ?? null,
          'remarks' => $validated['remarks'] ?? null,
        ]);

        
         return redirect()->route('master.create_campaign')->with('success', '卸売り先マスターが登録されました。');

    }

    //キャンペーン更新画面表示

    public function edit_campaign(Request $request)
    { 
      $storeId = Auth::id(); // ログインユーザーのIDを取得
      $mastercampaign = MasterCampaign::where($request->id)
                                      ->where('store_id', $storeId)
                                      ->firstOrFail();
                
      return view('master.edit_campaign',compact('mastercampaign'));
    }
    
    //キャンペーン更新処理
    public function update_campaign(Request $request)
    {
      $validated = $request->validate([
        'campaign' => 'required|string|max:255',
        'distribution_date' => 'nullable|date',
        'remarks' => 'nullable|string|min:0|max:255',
      ]);
      
      $mastercampaign = MasterCampaign::find($request->id);
      $update = $mastercampaign->update($validated);
      if($update) {
        return redirect('/master/')->with('success', 'キャンペーンマスターが更新されました。');
      } else {
        return back()->with('error', 'キャンペーンマスターの更新に失敗しました。');
      }
    }

    
    //キャンペーン削除確認画面表示

    public function delete_campaign(Request $request)
    { 
      $storeId = Auth::id(); // ログインユーザーのIDを取得
      $mastercampaign = MasterCampaign::where('id', $request->id)
                                      ->where('store_id', $storeId)
                                      ->firstOrFail();
                
      return view('master.delete_campaign',compact('mastercampaign'));
    }
    
     public function delete_campaign_excecute($id)
    {   $storeId = Auth::id(); // ログインユーザーのIDを取得
        $deleted = MasterCampaign::where('id', $id)
                              ->where('store_id', $storeId)
                              ->delete();
       if ($deleted) {
        return redirect()->route('master.create_campaign')->with('success', '卸売り先マスターが削除されました。');
    }                      

    }


}