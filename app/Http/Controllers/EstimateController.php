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
        // $request->validate() は第1引数にルール、第2引数にカスタムメッセージ、
        // 第3引数にカスタム属性（表示名）を取ることができます。
        
        $request->validate(
            // 第1引数: バリデーションルール
            [
                'title' => ['required', 'string', 'max:50'], 
                'adjustment' => ['nullable', 'numeric'],
            ], 
            
            // 第2引数: カスタムメッセージ (標準の日本語訳を使う場合は空の配列でOK)
            [], 
            
            // 🚨 第3引数: カスタム属性 (フィールド名を日本語にする) 🚨
            [
                'title' => 'タイトル',
                'adjustment' => '調整金額',
            ]
        );
        
        
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
        return redirect()->route('estimate.list', ['id' => $estimateId])
                         ->with('success', '見積登録が完了しました。');
    }
    //一覧表示
    public function list(){
        $Estimates=Estimate::all();
        // ★修正★ キー名を 'Estimates' (複数形) に変更
    return view('estimate.list', ['Estimates' => $Estimates]);
    //'Estimates'はキーである。bladeには変数で渡る
    }

    //詳細表示
    public function detail($id){
      // ★修正（改善）：Eager Loading（with('items')）を使用
    // Estimate Modelに定義されたitems()リレーションを通じて、明細も同時に取得
    $Estimate = Estimate::with('items')->findOrFail($id); 

    // ビューには $Estimate (親データ) のみを渡す
    // 明細データは $Estimate->items でアクセス可能になる
    return view('estimate.detail', compact('Estimate'));
    }

    //見積更新表示
    public function edit($id){
        // 1. 既存の見積データと明細をEager Loadingで取得
        $Estimate = Estimate::with('items')->findOrFail($id);
        
        // 2. ★価格情報の取得ロジック（ロバストなエラーハンドリングを実装）★
        require_once public_path('simple_html_dom.php'); 
        
        // エラー抑制演算子@を付けることで、file_get_htmlの警告を抑える
        $html = @file_get_html('https://gold.tanaka.co.jp/retanaka/price/');
        
        // 🚨 HTML取得の成否チェックとフォールバック処理 🚨
        if (!$html || !is_object($html)) {
            // 失敗した場合: 全ての価格を0として定義（Bladeエラー回避のため）
            // k9 の定義も忘れずに行う
            $prices = [
                'k24tokutei' => 0, 'k24' => 0, 'k22' => 0, 'k20' => 0,
                'k18' => 0, 'k16' => 0, 'k14' => 0, 'k12' => 0, 'k10' => 0,
                'k9' => 0,
                'pttokutei' => 0, 'pt1000' => 0, 'pt950' => 0, 'pt900' => 0, 
                'pt850' => 0, 'pt800' => 0, 'pt750' => 0, 'silver' => 0,
            ];
            \Log::warning("Failed to scrape Tanaka price data for estimate ID: {$id}. Using default (0) prices.");
            
        } else {
            // 成功した場合: 通常通り価格を取得・整形
            try {
                $prices = [
                    'k24tokutei' => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[0]->plaintext),
                    'k24'        => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[1]->plaintext),
                    'k22'        => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[2]->plaintext),
                    'k20'        => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[3]->plaintext),
                    'k18'        => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[4]->plaintext),
                    'k16'        => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[5]->plaintext),
                    'k14'        => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[6]->plaintext),
                    'k12'        => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[7]->plaintext),
                    'k10'        => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[8]->plaintext),
                    'k9'         => $this->cleanPrice($html->find('table#au_price_table tbody tr td')[9]->plaintext), 
                    
                    'pttokutei'  => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[0]->plaintext),
                    'pt1000'     => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[1]->plaintext),
                    'pt950'      => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[2]->plaintext),
                    'pt900'      => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[3]->plaintext),
                    'pt850'      => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[4]->plaintext),
                    'pt800'      => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[5]->plaintext),
                    'pt750'      => $this->cleanPrice($html->find('#pt_price_table tbody tr td')[6]->plaintext),
                    'silver'     => $this->cleanPrice($html->find('#ag_price table tbody tr td')[0]->plaintext),
                ];
                
                // メモリ解放
                $html->clear();
                unset($html);
            } catch (\Exception $e) {
                 // HTMLは取得したが、要素が見つからないなどでエラーになった場合
                 $prices = [
                    'k24tokutei' => 0, 'k24' => 0, 'k22' => 0, 'k20' => 0,
                    'k18' => 0, 'k16' => 0, 'k14' => 0, 'k12' => 0, 'k10' => 0,
                    'k9' => 0,
                    'pttokutei' => 0, 'pt1000' => 0, 'pt950' => 0, 'pt900' => 0, 
                    'pt850' => 0, 'pt800' => 0, 'pt750' => 0, 'silver' => 0,
                ];
                 \Log::error("Error parsing Tanaka price data: " . $e->getMessage());
            }

        }
        
        // 3. Viewに $Estimate と $prices の両方を渡す
        return view('estimate.edit', compact('Estimate', 'prices'));
    }


    public function update(Request $request): RedirectResponse // 戻り値の型を修正
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
        return redirect()->route('estimate.list', ['id' => $estimateId])
                         ->with('success', '見積登録更新しました。');
    }//edit終了

    //見積削除確認
    public function deleteConfirm($id){
        $Estimate = Estimate::findOrFail($id);
        return view('estimate.delete_confirm', compact('Estimate'));
    }
    
    //見積削除処理
    public function delete($id): RedirectResponse
    {
        // 1. トランザクションで処理をラップし、データの整合性を保証
        DB::transaction(function () use ($id) {
            // 1-1. 見積行テーブル (estimate_items) から関連する明細を削除
            EstimateItem::where('estimate_no', $id)->delete();

            // 1-2. 見積登録テーブル (estimates) から見積自体を削除
            Estimate::where('id', $id)->delete();
        });

        // 2. 処理後のリダイレクト
        return redirect()->route('estimate.list')
                         ->with('success', '見積が削除されました。');
    } 



}










 

