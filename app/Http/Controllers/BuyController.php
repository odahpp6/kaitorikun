<?php

namespace App\Http\Controllers;

use App\Models\BuyItem;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\MasterCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;

class BuyController extends Controller
{
    public function purchase()
    {
        $storeId = Auth::id();
        $mastercampaigns = MasterCampaign::where('store_id', $storeId)->get();
        return view('purchase.register', compact('mastercampaigns'));
    }

    public function store(Request $request)
    {
       
        // 1. バリデーション（主要項目のみ抜粋。適宜追加してください）
        $request->validate([
             'name' => 'required|max:50',
             'phone_number' => 'required',
             'email' => 'nullable|email',
             'proof_img_1' => 'required|image|max:10240', // 10MB
             'items.*.product' => 'required',
             'items.*.quantity' => 'required|integer|min:1',
             'items.*.buy_price' => 'required|numeric',
             'signature_image_data' => 'required', // 署名は必須
             'payment_method' => 'required|string', // arrayではなくstringにする
        ]);

        DB::beginTransaction();
        try {
            $storeId = Auth::id(); // ログイン中の店舗ID

            // 2. 顧客情報の保存（Customersテーブル）
            $customer = new Customer();
            $customer->store_id = $storeId;
            $customer->name = $request->name;
            $customer->furigana = $request->furigana;
            $customer->birth_y = $request->birth_y;
            $customer->birth_m = $request->birth_m;
            $customer->birth_d = $request->birth_d;
            $customer->gender = $request->gender;
            $customer->occupation = $request->occupation;
            $customer->postal_code = $request->postal_code;
            $customer->prefecture = $request->prefecture;
            $customer->city = $request->city;
            $customer->address_detail = $request->address_detail;
            $customer->address_building = $request->address_building;
            $customer->phone_number = $request->phone_number;
            $customer->email = $request->email;
            $customer->proof_type = $request->proof_type;
            $customer->proof_num = $request->proof_num;

            // 本人確認画像の保存
            if ($request->hasFile('proof_img_1')) {
                $customer->proof_img_1 = $request->file('proof_img_1')->store('proofs', 'public');
            }
            if ($request->hasFile('proof_img_2')) {
                $customer->proof_img_2 = $request->file('proof_img_2')->store('proofs', 'public');
            }
            $customer->save();

            // 3. 取引情報の保存（Dealsテーブル）
            $deal = new Deal();
            $deal->store_id = $storeId;
            $deal->customer_id = $customer->id;
            // 伝票番号の生成 (日付-ランダム文字列)
            $deal->slip_number = date('Ymd') . '-' . strtoupper(Str::random(4));
            $deal->buy_type = $request->buy_type;
            $deal->arrival_type = $request->arrival_type;
            $deal->campaign_id = $request->campaign_id;
            $deal->payment_method = $request->payment_method;
            $deal->invoice_issuer = $request->invoice_issuer;
            
            // 同意フラグ
            $deal->agree_received_amount = $request->has('agree_received_amount');
            $deal->agree_no_return = $request->has('agree_no_return');
            $deal->agree_privacy = $request->has('agree_privacy');

            // 署名画像（Base64）の保存
            if ($request->signature_image_data) {
                $sigData = $request->signature_image_data;
                $sigData = str_replace('data:image/png;base64,', '', $sigData);
                $sigData = str_replace(' ', '+', $sigData);
                $sigImageName = 'sig_' . time() . '_' . Str::random(10) . '.png';
                Storage::disk('public')->put('signatures/' . $sigImageName, base64_decode($sigData));
                $deal->signature_image_data = 'signatures/' . $sigImageName;
            }

            // 合計金額の計算
            $totalPrice = collect($request->items)->sum(function ($item) {
                $quantity = isset($item['quantity']) ? (int) $item['quantity'] : 1;
                $price = isset($item['buy_price']) ? (float) $item['buy_price'] : 0;
                return $quantity * $price;
            });
            $deal->total_price = $request->filled('total_price') ? $request->total_price : $totalPrice;
            $deal->save();

            // 4. 商品情報の保存（BuyItemsテーブル）
            if ($request->items) {
                foreach ($request->items as $itemData) {
                    $item = new BuyItem();
                    $item->store_id = $storeId;
                    $item->deal_id = $deal->id;
                    $item->product = $itemData['product'];
                    $item->classification = $itemData['classification'];
                    $item->remarks_2 = $itemData['remarks_2'] ?? null;
                    $item->quantity = isset($itemData['quantity']) ? (int) $itemData['quantity'] : 1;
                    $item->buy_price = $itemData['buy_price'];

                    // 商品画像の保存
                    if (isset($itemData['product_img']) && $itemData['product_img'] instanceof \Illuminate\Http\UploadedFile) {
                        $item->product_img = $itemData['product_img']->store('products', 'public');
                    }
                    $item->save();
                }
            }

            DB::commit();
            return redirect()->route('purchase.list')->with('success', '契約を完了しました。伝票番号: ' . $deal->slip_number);

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', '保存に失敗しました。' . $e->getMessage());
        }
    }
    
    // 買取契約登録一覧
    public function purchase_list()
    {
    $storeId = Auth::id();
    
    $deals = Deal::where('store_id', $storeId)
                  ->with(['customer', 'buyItems']) // ★ buyItemsを追加　customerやbuyItemsはmodelで定義したリレーション名　
                  ->orderBy('created_at', 'desc')
                  ->get();
                  
    return view('purchase.list', compact('deals'));
    }
    public function purchase_detail($id)
    {
        $storeId = Auth::id();

        $deal = Deal::where('id', $id)
                    ->where('store_id', $storeId)
                    ->with(['customer', 'buyItems'])
                    ->firstOrFail();

        return view('purchase.detail', compact('deal'));
    }

    
    // 買取契約修正画面表示
    public function purchase_edit($id)
    {
        $storeId = Auth::id(); // ログイン中の店舗ID
        $deal = Deal::where('id', $id)
                    ->where('store_id', $storeId)
                    ->with(['customer', 'buyItems'])
                    ->firstOrFail();
        $mastercampaigns = MasterCampaign::where('store_id', $storeId)->get(); 
        return view('purchase.edit', compact('deal', 'mastercampaigns'));
    }

    public function purchase_update(Request $request, $id) // IDを受け取る
{
    // 1. バリデーション（画像などは更新時のみ任意にすることが多い）
    $request->validate([
        'name' => 'required|max:50',
        'phone_number' => 'required',
        'email' => 'nullable|email',
        // 更新時は画像が必須でない場合が多いので nullable や required_without などにする
        'items.*.product' => 'required',
        'items.*.buy_price' => 'required|numeric',
        'payment_method' => 'required|string',
    ]);

    DB::beginTransaction();
    try {
        $storeId = Auth::id();

        // 2. 既存の取引を取得
        $deal = Deal::where('id', $id)->where('store_id', $storeId)->firstOrFail();
        
        // 3. 顧客情報の更新
        $customer = Customer::findOrFail($deal->customer_id);
        $customer->fill($request->only([
            'name', 'furigana', 'birth_y', 'birth_m', 'birth_d', 'gender', 
            'occupation', 'postal_code', 'prefecture', 'city', 
            'address_detail', 'address_building', 'phone_number', 'email', 'proof_type', 'proof_num'
        ]));

        // 画像が新しくアップロードされた場合のみ上書き
        if ($request->hasFile('proof_img_1')) {
            $customer->proof_img_1 = $request->file('proof_img_1')->store('proofs', 'public');
        }
        $customer->save();

        // 4. 取引情報の更新
        $deal->fill($request->only([
            'buy_type', 'arrival_type', 'campaign_id', 'payment_method', 'invoice_issuer'
        ]));
        $deal->agree_received_amount = $request->has('agree_received_amount');
        $deal->agree_no_return = $request->has('agree_no_return');
        $deal->agree_privacy = $request->has('agree_privacy');

        // 署名が新しく送られてきた場合のみ上書き
        if ($request->signature_image_data && str_contains($request->signature_image_data, 'base64')) {
            $sigData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $request->signature_image_data);
            $sigImageName = 'sig_' . time() . '_' . Str::random(10) . '.png';
            Storage::disk('public')->put('signatures/' . $sigImageName, base64_decode($sigData));
            $deal->signature_image_data = 'signatures/' . $sigImageName;
        }
        $deal->save();

        // 5. 商品情報の更新（一度消して作り直すのが確実）
        // ※ 古い画像の削除処理を入れるとなお良い
        $deal->buyItems()->delete(); 

        if ($request->items) {
            foreach ($request->items as $itemData) {
                $item = new BuyItem();
                $item->store_id = $storeId;
                $item->deal_id = $deal->id;
                $item->product = $itemData['product'];
                $item->classification = $itemData['classification'] ?? '未分類';
                $item->buy_price = $itemData['buy_price'];
                
                // 画像が新しい場合は保存、ない場合は以前のパスを引き継ぐロジックが必要（今回は新規のみ想定）
                if (isset($itemData['product_img']) && $itemData['product_img'] instanceof \Illuminate\Http\UploadedFile) {
                    $item->product_img = $itemData['product_img']->store('products', 'public');
                }
                $item->save();
            }
        }

        DB::commit();
        return redirect()->route('purchase.list')->with('success', '契約を更新しました。');

        } catch (Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', '更新に失敗しました。' . $e->getMessage());
        }
    }
    // 買取契約削除確認
    public function purchase_delete_confirm($id)
    {
        $storeId = Auth::id(); // ログイン中の店舗ID
        $deal = Deal::where('id', $id)
                    ->where('store_id', $storeId)
                    ->with(['customer', 'buyItems'])
                    ->firstOrFail();        

        return view('purchase.delete_confirm', compact('deal'));
    }

    // 買取契約削除実行
    public function purchase_delete($id)
    {
        $storeId = Auth::id();

        DB::beginTransaction();
        try {
            $deal = Deal::where('id', $id)
                        ->where('store_id', $storeId)
                        ->firstOrFail();

            $deal->buyItems()->delete();
            $deal->delete();

            DB::commit();
            return redirect()->route('purchase.list')->with('success', '契約を削除しました。');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('purchase.list')->with('error', '削除に失敗しました。' . $e->getMessage());
        }
    }


}
