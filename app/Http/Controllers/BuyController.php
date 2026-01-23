<?php

namespace App\Http\Controllers;

use App\Models\BuyItem;
use App\Models\Customer;
use App\Models\Deal;
use App\Models\MasterCampaign;
use App\Models\MasterStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseRegistered;
use Illuminate\Validation\Rule;

class BuyController extends Controller
{
    public function purchase()
    {
        $storeId = Auth::id();
        $mastercampaigns = MasterCampaign::where('store_id', $storeId)->get();
        $masterstaffs = MasterStaff::where('store_id', $storeId)->get();
        return view('purchase.register', compact('mastercampaigns', 'masterstaffs'));
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
             'payment_remarks' => 'nullable|string',
             'remarks' => 'nullable|string',
             'staff_id' => [
                 'nullable',
                 'integer',
                 Rule::exists('master_staff', 'id')->where('store_id', Auth::id()),
             ],
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
            $deal->payment_remarks = $request->payment_remarks;
            $deal->invoice_issuer = $request->invoice_issuer;
            $deal->remarks = $request->remarks;
            $deal->staff_id = $request->staff_id;
            
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

            // Mail sending disabled temporarily.

            return redirect('/purchase/list')->with('success', '契約を完了しました。伝票番号: ' . $deal->slip_number);

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

    // 買取契約書印刷
    public function purchase_print($id)
    {
        $storeId = Auth::id();

        $deal = Deal::where('id', $id)
                    ->where('store_id', $storeId)
                    ->with(['customer', 'buyItems'])
                    ->firstOrFail();

        $store = User::find($storeId);

        $pdf = Pdf::loadView('purchase.print_pdf', compact('deal', 'store'))
            ->setPaper('A4')
            ->setOption('defaultFont', 'ipaexg')
            ->setOption('fontDir', storage_path('fonts'))
            ->setOption('fontCache', storage_path('fonts'))
            ->setOption('chroot', realpath(base_path()));

        return $pdf->stream('purchase_' . ($deal->slip_number ?? $deal->id) . '.pdf');
    }

// 買取契約検索機能

public function index(Request $request)
{
    $query = $this->buildPurchaseListQuery($request);
    $deals = $query->orderBy('created_at', 'desc')->paginate(100);

    return view('purchase.list', compact('deals'));
}

public function exportCsv(Request $request)
{
    $query = $this->buildPurchaseListQuery($request);
    $filename = 'purchase_list_' . now()->format('Ymd_His') . '.csv';

    $headers = [
        '身分証明書種類',
        '顧客名',
        'フリガナ',
        '電話番号',
        'Email',
        '生年月日(西暦)',
        '性別',
        '職業',
        '郵便番号',
        '都道府県',
        '市区町村',
        '番地以降',
        '建物名',
        '登録日時',
        '伝票番号',
        '合計金額',
        '買取区分',
        '来店区分',
        'お支払い方法',
        '担当者',
        '備考',
        '取引備考',
        '適格請求書発行事業者',
        '提示金額受領',
        '返品不可同意',
        '個人情報同意',
        '同意・署名',
        '消費税額(10%)',
        '個数合計',
        '商品番号',
        '商品画像',
        '商品名',
        '買取分類',
        '商品備考',
        '個数',
        '買取金額',
    ];

    $genderLabelMap = [
        'male' => '男性',
        'female' => '女性',
        'other' => 'その他',
    ];

    return response()->streamDownload(function () use ($query, $headers, $genderLabelMap) {
        $output = fopen('php://output', 'w');
        fwrite($output, "\xEF\xBB\xBF");
        fputcsv($output, $headers);

        $deals = $query->orderBy('created_at', 'desc')->get();
        foreach ($deals as $deal) {
            $customer = $deal->customer;
            $items = $deal->buyItems ?? collect();
            $itemCount = $items->sum('quantity');

            $formatItemLines = function ($items, callable $valueForItem) {
                $lines = [];
                foreach ($items as $index => $item) {
                    $value = $valueForItem($item);
                    $lines[] = ($index + 1) . ':' . ($value ?? '');
                }
                return implode("\n", $lines);
            };

            $itemNumbers = $items->map(function ($item, $index) {
                return (string) ($index + 1);
            })->implode("\n");

            $itemImages = $formatItemLines($items, function ($item) {
                if (!$item->product_img) {
                    return '';
                }
                return Storage::disk('public')->url($item->product_img);
            });
            $itemProducts = $formatItemLines($items, function ($item) {
                return $item->product;
            });
            $itemClassifications = $formatItemLines($items, function ($item) {
                return $item->classification;
            });
            $itemRemarks = $formatItemLines($items, function ($item) {
                return $item->remarks_2;
            });
            $itemQuantities = $formatItemLines($items, function ($item) {
                return $item->quantity;
            });
            $itemPrices = $formatItemLines($items, function ($item) {
                return $item->buy_price;
            });

            $birthDate = $customer
                ? sprintf('%04d-%02d-%02d', $customer->birth_y, $customer->birth_m, $customer->birth_d)
                : '';
            $gender = $customer ? ($genderLabelMap[$customer->gender] ?? $customer->gender) : '';
            $staffName = $deal->staff ? $deal->staff->staff_name : '';
            $signatureStatus = $deal->signature_image_data ? '有' : '無';
            $taxAmount = $deal->total_price ? round($deal->total_price / 11) : 0;

            fputcsv($output, [
                $customer->proof_type ?? '',
                $customer->name ?? '',
                $customer->furigana ?? '',
                $customer->phone_number ?? '',
                $customer->email ?? '',
                $birthDate,
                $gender,
                $customer->occupation ?? '',
                $customer->postal_code ?? '',
                $customer->prefecture ?? '',
                $customer->city ?? '',
                $customer->address_detail ?? '',
                $customer->address_building ?? '',
                $deal->created_at ? $deal->created_at->format('Y-m-d H:i:s') : '',
                $deal->slip_number ?? '',
                $deal->total_price ?? 0,
                $deal->buy_type ?? '',
                $deal->arrival_type ?? '',
                $deal->payment_method ?? '',
                $staffName,
                $deal->remarks ?? '',
                $deal->payment_remarks ?? '',
                $deal->invoice_issuer ?? '',
                $deal->agree_received_amount ? 'はい' : 'いいえ',
                $deal->agree_no_return ? 'はい' : 'いいえ',
                $deal->agree_privacy ? 'はい' : 'いいえ',
                $signatureStatus,
                $taxAmount,
                $itemCount,
                $itemNumbers,
                $itemImages,
                $itemProducts,
                $itemClassifications,
                $itemRemarks,
                $itemQuantities,
                $itemPrices,
            ]);
        }

        fclose($output);
    }, $filename, [
        'Content-Type' => 'text/csv; charset=UTF-8',
    ]);
}

private function buildPurchaseListQuery(Request $request)
{
    $storeId = Auth::id();
    $query = Deal::with(['customer', 'buyItems', 'staff'])
                 ->where('store_id', $storeId);

    if ($request->filled('customer_name')) {
        $name = $request->customer_name;
        $query->whereHas('customer', function ($q) use ($name) {
            $q->where('name', 'like', "%{$name}%");
        });
    }

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    if ($request->filled('product_name')) {
        $product = $request->product_name;
        $query->whereHas('buyItems', function ($q) use ($product) {
            $q->where('product', 'like', "%{$product}%");
        });
    }

    return $query;
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
        $masterstaffs = MasterStaff::where('store_id', $storeId)->get();
        return view('purchase.edit', compact('deal', 'mastercampaigns', 'masterstaffs'));
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
            'payment_remarks' => 'nullable|string',
            'remarks' => 'nullable|string',
            'staff_id' => [
                'nullable',
                'integer',
                Rule::exists('master_staff', 'id')->where('store_id', Auth::id()),
            ],
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
            'buy_type', 'arrival_type', 'campaign_id', 'payment_method', 'payment_remarks', 'invoice_issuer', 'remarks', 'staff_id'
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
                } elseif (!empty($itemData['product_img_existing'])) {
                    $item->product_img = $itemData['product_img_existing'];
                }
                $item->save();
            }
        }

        DB::commit();
            return redirect('/purchase/list')->with('success', '契約を更新しました。');

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
            return redirect('/purchase/list')->with('success', '契約を削除しました。');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect('/purchase/list')->with('error', '削除に失敗しました。' . $e->getMessage());
        }
    }

    public function buy_analysis(Request $request)
    {
        $storeId = Auth::id();

        $baseDealsQuery = Deal::query()->where('deals.store_id', $storeId);
        if ($request->filled('date_from')) {
            $baseDealsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $baseDealsQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $arrivalCounts = (clone $baseDealsQuery)
            ->whereNotNull('deals.arrival_type')
            ->where('deals.arrival_type', '!=', '')
            ->select('deals.arrival_type as arrival_type', DB::raw('COUNT(*) as count'))
            ->groupBy('deals.arrival_type')
            ->orderByDesc('count')
            ->get();

        $arrivalTotal = $arrivalCounts->sum('count');
        $arrivalStats = $arrivalCounts->map(function ($row) use ($arrivalTotal) {
            $percent = $arrivalTotal > 0 ? round(($row->count / $arrivalTotal) * 100, 1) : 0;
            return [
                'label' => $row->arrival_type,
                'count' => $row->count,
                'percent' => $percent,
            ];
        });

        $classificationCounts = DB::table('buy_items')
            ->join('deals', 'buy_items.deal_id', '=', 'deals.id')
            ->where('deals.store_id', $storeId)
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '<=', $request->date_to);
            })
            ->whereNotNull('buy_items.classification')
            ->where('buy_items.classification', '!=', '')
            ->select('buy_items.classification as classification', DB::raw('COUNT(*) as count'))
            ->groupBy('buy_items.classification')
            ->orderByDesc('count')
            ->get();

        $classificationTotal = $classificationCounts->sum('count');
        $classificationStats = $classificationCounts->map(function ($row) use ($classificationTotal) {
            $percent = $classificationTotal > 0 ? round(($row->count / $classificationTotal) * 100, 1) : 0;
            return [
                'label' => $row->classification,
                'count' => $row->count,
                'percent' => $percent,
            ];
        });

        $campaignCounts = DB::table('deals')
            ->join('master_campaigns', 'deals.campaign_id', '=', 'master_campaigns.id')
            ->where('deals.store_id', $storeId)
            ->where('master_campaigns.store_id', $storeId)
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '<=', $request->date_to);
            })
            ->select('master_campaigns.campaign as campaign', DB::raw('COUNT(*) as count'))
            ->groupBy('master_campaigns.campaign')
            ->orderByDesc('count')
            ->get();

        $campaignTotal = $campaignCounts->sum('count');
        $campaignStats = $campaignCounts->map(function ($row) use ($campaignTotal) {
            $percent = $campaignTotal > 0 ? round(($row->count / $campaignTotal) * 100, 1) : 0;
            return [
                'label' => $row->campaign,
                'count' => $row->count,
                'percent' => $percent,
            ];
        });

        $genderCounts = (clone $baseDealsQuery)
            ->join('customers', 'deals.customer_id', '=', 'customers.id')
            ->whereNotNull('customers.gender')
            ->select('customers.gender as gender', DB::raw('COUNT(*) as count'))
            ->groupBy('customers.gender')
            ->orderByDesc('count')
            ->get();

        $genderTotal = $genderCounts->sum('count');
        $genderLabelMap = [
            'male' => '男性',
            'female' => '女性',
            'other' => 'その他',
        ];
        $genderStats = $genderCounts->map(function ($row) use ($genderTotal, $genderLabelMap) {
            $percent = $genderTotal > 0 ? round(($row->count / $genderTotal) * 100, 1) : 0;
            return [
                'label' => $genderLabelMap[$row->gender] ?? $row->gender,
                'count' => $row->count,
                'percent' => $percent,
            ];
        });

        return view('customer.buy_analysis', [
            'arrivalStats' => $arrivalStats,
            'arrivalTotal' => $arrivalTotal,
            'classificationStats' => $classificationStats,
            'classificationTotal' => $classificationTotal,
            'campaignStats' => $campaignStats,
            'campaignTotal' => $campaignTotal,
            'genderStats' => $genderStats,
            'genderTotal' => $genderTotal,
        ]);
    }

    public function flyer_analysis(Request $request)
    {
        $storeId = Auth::id();
        $campaigns = MasterCampaign::where('store_id', $storeId)
            ->orderBy('campaign')
            ->get();

        $flyerItems = DB::table('buy_items')
            ->join('deals', 'buy_items.deal_id', '=', 'deals.id')
            ->join('customers', 'deals.customer_id', '=', 'customers.id')
            ->join('master_campaigns', 'deals.campaign_id', '=', 'master_campaigns.id')
            ->where('deals.store_id', $storeId)
            ->where('master_campaigns.store_id', $storeId)
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '<=', $request->date_to);
            })
            ->when($request->filled('campaign_id'), function ($query) use ($request) {
                $query->where('deals.campaign_id', $request->campaign_id);
            })
            ->select(
                'deals.created_at',
                'deals.slip_number',
                'deals.arrival_type',
                'customers.name',
                'buy_items.classification',
                'buy_items.product',
                'buy_items.buy_price',
                'master_campaigns.campaign'
            )
            ->orderByDesc('deals.created_at')
            ->get();

        $campaignCounts = DB::table('deals')
            ->join('master_campaigns', 'deals.campaign_id', '=', 'master_campaigns.id')
            ->where('deals.store_id', $storeId)
            ->where('master_campaigns.store_id', $storeId)
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '<=', $request->date_to);
            })
            ->when($request->filled('campaign_id'), function ($query) use ($request) {
                $query->where('deals.campaign_id', $request->campaign_id);
            })
            ->select('master_campaigns.campaign as campaign', DB::raw('COUNT(*) as count'))
            ->groupBy('master_campaigns.campaign')
            ->orderByDesc('count')
            ->get();

        $campaignTotal = $campaignCounts->sum('count');
        $campaignStats = $campaignCounts->map(function ($row) use ($campaignTotal) {
            $percent = $campaignTotal > 0 ? round(($row->count / $campaignTotal) * 100, 1) : 0;
            return [
                'label' => $row->campaign,
                'count' => $row->count,
                'percent' => $percent,
            ];
        });

        return view('customer.flyer_analysis', [
            'campaigns' => $campaigns,
            'flyerItems' => $flyerItems,
            'campaignStats' => $campaignStats,
            'campaignTotal' => $campaignTotal,
        ]);
    }

    public function repeat_analysis(Request $request)
    {
        $storeId = Auth::id();

        $arrivalTypes = Deal::where('store_id', $storeId)
            ->whereNotNull('arrival_type')
            ->where('arrival_type', '!=', '')
            ->select('arrival_type')
            ->distinct()
            ->orderBy('arrival_type')
            ->pluck('arrival_type');

        $classifications = DB::table('buy_items')
            ->join('deals', 'buy_items.deal_id', '=', 'deals.id')
            ->where('deals.store_id', $storeId)
            ->whereNotNull('buy_items.classification')
            ->where('buy_items.classification', '!=', '')
            ->select('buy_items.classification as classification')
            ->distinct()
            ->orderBy('buy_items.classification')
            ->pluck('classification');

        $dealBase = Deal::query()->where('deals.store_id', $storeId);
        if ($request->filled('date_from')) {
            $dealBase->whereDate('deals.created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $dealBase->whereDate('deals.created_at', '<=', $request->date_to);
        }

        $arrivalType = $request->input('arrival_type');
        $classification = $request->input('classification');

        $repeatCustomers = (clone $dealBase)
            ->join('customers', 'deals.customer_id', '=', 'customers.id')
            ->select(
                'customers.name',
                'customers.phone_number',
                DB::raw('COUNT(DISTINCT deals.id) as visit_count')
            )
            ->groupBy('customers.name', 'customers.phone_number')
            ->having('visit_count', '>=', 2);

        $repeatItems = DB::table('buy_items')
            ->join('deals', 'buy_items.deal_id', '=', 'deals.id')
            ->join('customers', 'deals.customer_id', '=', 'customers.id')
            ->joinSub($repeatCustomers, 'repeat_customers', function ($join) {
                $join->on('customers.name', '=', 'repeat_customers.name')
                    ->on('customers.phone_number', '=', 'repeat_customers.phone_number');
            })
            ->where('deals.store_id', $storeId)
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('deals.created_at', '<=', $request->date_to);
            })
            ->when($arrivalType !== null && $arrivalType !== '', function ($query) use ($arrivalType) {
                $query->whereRaw('TRIM(deals.arrival_type) = ?', [$arrivalType]);
            })
            ->when($classification !== null && $classification !== '', function ($query) use ($classification) {
                $query->whereRaw('TRIM(buy_items.classification) = ?', [$classification]);
            })
            ->select(
                'deals.arrival_type',
                'customers.name',
                'customers.phone_number',
                'deals.created_at',
                'buy_items.classification',
                'buy_items.product',
                'buy_items.buy_price',
                'repeat_customers.visit_count'
            )
            ->orderByDesc('repeat_customers.visit_count')
            ->orderBy('customers.name')
            ->orderByDesc('deals.created_at')
            ->get();

        return view('customer.repeat_analysis', [
            'repeatItems' => $repeatItems,
            'arrivalTypes' => $arrivalTypes,
            'classifications' => $classifications,
        ]);
    }

}
