<?php

namespace App\Http\Controllers;
use App\Models\User;

use App\Models\MasterCampaign;
use Illuminate\Http\Request;
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
      return view('master.create_campaign');

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

        return redirect('/master/');

    }


}
