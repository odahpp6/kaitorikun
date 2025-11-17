<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Estimate;
use App\Models\EstimateItem;
use Illuminate\Support\Facades\DB; // ★追加：トランザクション用
use Illuminate\Http\RedirectResponse; // ★追加：戻り値の型用



class EstimateController extends Controller
{

//見積登録表示    
public function create()
    {
    require_once public_path('simple_html_dom.php');

    $html = file_get_html('https://gold.tanaka.co.jp/retanaka/price/');

    $prices = [
        'k24tokutei' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[0]->plaintext),
        'k24' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[1]->plaintext),
        'k22' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[2]->plaintext),
        'k20' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[3]->plaintext),
        'k18' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[4]->plaintext),
        'k16' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[5]->plaintext),
        'k14' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[6]->plaintext),
        'k12' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[7]->plaintext),
        'k10' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[8]->plaintext),
        'k9'  => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[9]->plaintext),
        'pttokutei' => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[0]->plaintext),
        'pt1000'    => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[1]->plaintext),
        'pt950'     => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[2]->plaintext),
        'pt900'     => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[3]->plaintext),
        'pt850'     => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[4]->plaintext),
        'pt800'     => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[5]->plaintext),
        'pt750'     => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[6]->plaintext),
        'silver'    => $this->cleanPrice($html->find('#ag_price table tbody tr td')[0]->plaintext),

        // 追加の価格もここに
    ];

    return view('estimate.register', compact('prices'));
    }//create

    private function cleanPrice($text)
    {
    return intval(preg_replace('/[^\d]/u', '', $text));
    }

    public function store(Request $request): RedirectResponse // 戻り値の型を修正
    {
        // 1. 認証ユーザーなどからDBに必要な情報を取得（仮の値として設定）
        $storeId = 1; // ログインユーザーの店舗IDなどを設定
        $role = 'user'; // デフォルトの権限を設定

        // 2. トランザクションで処理をラップし、データの整合性を保証
        $estimateId = DB::transaction(function () use ($request, $storeId, $role) {
            
            // 2-1. 見積登録テーブル (estimates) への登録
            $estimate = Estimate::create([
                'title' => $request->title,
                'adjustment' => $request->adjustment,
                'store_id' => $storeId, // ★必須：store_idを設定
                'role' => $role,         // ★必須：roleを設定
            ]);

            // 2-2. 見積行テーブル (estimate_items) への登録
            if ($request->has('text') && is_array($request->input('text'))) {
                foreach ($request->input('text') as $index => $text) {
                    if (empty($text) && empty($request->input('num1')[$index])) {
                        continue;
                    }
                    
                    EstimateItem::create([
                        'estimate_no' => $estimate->id, // 親のIDを外部キーとして設定
                        'text' => $text,
                        'num1' => $request->input('num1')[$index] ?? 0,
                        'num2' => $request->input('num2')[$index] ?? 1,
                        'remarks' => $request->input('remarks')[$index],
                    ]);
                }
            }
            return $estimate->id;
        });
        
        // 3. 処理後のリダイレクト
        return redirect()->route('estimate.register', ['id' => $estimateId])
                         ->with('success', '見積登録が完了しました。');
    }
    //一覧表示
    public function list(){
        $Estimates=Estimate::all();
        // ★修正★ キー名を 'Estimates' (複数形) に変更
    return view('estimate.list', ['Estimates' => $Estimates]);
    //'Estimates'はキーである。bladeには変数で渡る
    }



}










 

