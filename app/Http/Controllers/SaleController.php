<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\MasterWholesale;
use App\Models\Deal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    

    // 商品登録画面表示
    public function register_view()
    {
        $storeId = Auth::id();
        $wholesales = MasterWholesale::where('store_id', $storeId)->get();
        $dealId = request('deal_id');
        $deal = null;
        if (!$dealId && request()->filled('slip_number')) {
            $deal = Deal::where('slip_number', request('slip_number'))
                ->where('store_id', $storeId)
                ->first();
            $dealId = $deal?->id;
        }
        if ($dealId && !$deal) {
            $deal = Deal::where('id', $dealId)
                ->where('store_id', $storeId)
                ->first();
        }

        $prefillProduct = null;
        if (request()->filled('product')) {
            $prefillProduct = request('product');
        } elseif (request()->filled('items.0.product')) {
            $prefillProduct = request('items.0.product');
        }

        $prefillClassification = null;
        if (request()->filled('classification')) {
            $prefillClassification = request('classification');
        } elseif (request()->filled('items.0.classification')) {
            $prefillClassification = request('items.0.classification');
        }

        $prefillProductImg = null;
        if (request()->filled('product_img')) {
            $prefillProductImg = request('product_img');
        } elseif (request()->filled('items.0.product_img')) {
            $prefillProductImg = request('items.0.product_img');
        }

        if (!$prefillProduct || !$prefillClassification) {
            $dealItem = $deal?->buyItems()->orderBy('id')->first();
            if ($dealItem) {
                if (!$prefillProduct) {
                    $prefillProduct = $dealItem->product;
                }
                if (!$prefillClassification) {
                    $prefillClassification = $dealItem->classification;
                }
            }
        }

        return view('sale.register', compact(
            'wholesales',
            'dealId',
            'prefillProduct',
            'prefillClassification',
            'prefillProductImg'
        ));
    }
    //DB登録処理
    public function register(Request $request)
    {
        // バリデーション
        $validatedData = $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'product' => 'required|string|max:255',
            'classification' => 'required|string|max:50',
            'buy_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'nullable|date',
            'deposit_date' => 'required|date',
            'destination_id' => 'required|exists:master_wholesales,id',
            'is_confirmed' => 'required|boolean',
            'product_img' => 'nullable|image|max:10240',
            'product_img_existing' => 'nullable|string',
        ]);

        $sale = new Sale();
        $sale->store_id = Auth::id();
        $sale->deal_id = $validatedData['deal_id'];
        $sale->product = $validatedData['product'];
        $sale->classification = $validatedData['classification'];
        $sale->quantity = $validatedData['quantity'];
        $sale->buy_price = $validatedData['buy_price'];
        $sale->unit_price = $validatedData['quantity'] > 0
            ? ($validatedData['selling_price'] / $validatedData['quantity'])
            : 0;
        $sale->selling_price = $validatedData['selling_price'];
        $sale->sale_date = $validatedData['sale_date'] ?? null;
        $sale->deposit_date = $validatedData['deposit_date'];
        $sale->is_confirmed = (bool) $validatedData['is_confirmed'];
        $sale->wholesale = $validatedData['destination_id'];

        if ($request->hasFile('product_img')) {
            $sale->product_img = $request->file('product_img')->store('products', 'public');
        } elseif (!empty($validatedData['product_img_existing'])) {
            $sale->product_img = $validatedData['product_img_existing'];
        }

        $sale->save();

        return redirect()->back()->with('success', '商品が正常に登録されました。');
    }
    // 商品詳細画面表示
    public function detail($id)
    {
        $storeId = Auth::id();
        $sale = Sale::where('id', $id)->
                      where('store_id', $storeId)->
                      firstOrFail();

        $deal = $sale->deal_id
            ? Deal::where('id', $sale->deal_id)->where('store_id', $storeId)->first()
            : null;
        $wholesale = $sale->wholesale
            ? MasterWholesale::where('id', $sale->wholesale)->where('store_id', $storeId)->first()
            : null;
        $relatedSales = $sale->deal_id
            ? Sale::where('store_id', $storeId)
                ->where('deal_id', $sale->deal_id)
                ->orderBy('id')
                ->get()
            : collect();

        return view('sale.detail', compact('sale', 'deal', 'wholesale', 'relatedSales'));
    }
    //商品一覧画面表示
    public function list()
    {
        $storeId = Auth::id();
        $query = Sale::with(['deal', 'wholesaleInfo'])
            ->where('store_id', $storeId);

        if (request()->filled('product_name')) {
            $query->where('product', 'like', '%' . request('product_name') . '%');
        }
        if (request()->filled('wholesale_name')) {
            $wholesaleName = request('wholesale_name');
            $query->whereHas('wholesaleInfo', function ($q) use ($wholesaleName) {
                $q->where('wholesale', 'like', '%' . $wholesaleName . '%');
            });
        }
        if (request()->filled('product_number')) {
            $query->where('id', request('product_number'));
        }
        if (request()->filled('classification')) {
            $query->where('classification', request('classification'));
        }
        if (request()->filled('deposit_date_from')) {
            $query->whereDate('deposit_date', '>=', request('deposit_date_from'));
        }
        if (request()->filled('deposit_date_to')) {
            $query->whereDate('deposit_date', '<=', request('deposit_date_to'));
        }
        if (request()->filled('purchase_month_from') || request()->filled('purchase_month_to')) {
            $fromMonth = request('purchase_month_from');
            $toMonth = request('purchase_month_to');
            $fromDate = $fromMonth ? Carbon::parse($fromMonth . '-01')->startOfMonth() : null;
            $toDate = $toMonth ? Carbon::parse($toMonth . '-01')->endOfMonth() : null;

            $query->whereHas('deal', function ($q) use ($fromDate, $toDate) {
                if ($fromDate) {
                    $q->whereDate('created_at', '>=', $fromDate);
                }
                if ($toDate) {
                    $q->whereDate('created_at', '<=', $toDate);
                }
            });
        }

        $totalsQuery = clone $query;
        $totalSellingPrice = (int) $totalsQuery->sum('selling_price');
        $totalBuyPrice = (int) $totalsQuery->sum('buy_price');
        $totalGrossProfit = $totalSellingPrice - $totalBuyPrice;

        $sales = $query->orderBy('created_at', 'desc')->paginate(20);
        $wholesales = MasterWholesale::where('store_id', $storeId)->get();

        return view('sale.list', compact(
            'sales',
            'wholesales',
            'totalSellingPrice',
            'totalBuyPrice',
            'totalGrossProfit'
        ));
    }
    //販売登録修正画面表示
    public function edit($id)
    {
        $storeId = Auth::id();
        $sale = Sale::where('id', $id)->
                      where('store_id', $storeId)->
                      firstOrFail();

        $wholesales = MasterWholesale::all();
        $dealId = $sale->deal_id;
        $deal = $dealId
            ? Deal::where('id', $dealId)->where('store_id', $storeId)->first()
            : null;

        return view('sale.edit', compact('sale', 'wholesales', 'dealId', 'deal'));
    }
    //販売登録更新処理
    public function update(Request $request, $id)
    {
        $storeId = Auth::id();
        $sale = Sale::where('id', $id)->
                      where('store_id', $storeId)->
                      firstOrFail();
        // バリデーション
        $validatedData = $request->validate([
            'deal_id' => 'required|exists:deals,id',
            'product' => 'required|string|max:255',
            'classification' => 'required|string|max:50',
            'buy_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'nullable|date',
            'deposit_date' => 'required|date',
            'destination_id' => 'required|exists:master_wholesales,id',
            'is_confirmed' => 'required|boolean',
            'product_img' => 'nullable|image|max:10240',
            'product_img_existing' => 'nullable|string',
        ]);

        $sale->deal_id = $validatedData['deal_id'];
        $sale->product = $validatedData['product'];
        $sale->classification = $validatedData['classification'];
        $sale->quantity = $validatedData['quantity'];
        $sale->buy_price = $validatedData['buy_price'];
        $sale->selling_price = $validatedData['selling_price'];
        $sale->sale_date = $validatedData['sale_date'] ?? null;
        $sale->deposit_date = $validatedData['deposit_date'];
        $sale->is_confirmed = (bool) $validatedData['is_confirmed'];
        $sale->wholesale = $validatedData['destination_id'];
        $sale->unit_price = $validatedData['quantity'] > 0
            ? ($validatedData['selling_price'] / $validatedData['quantity'])
            : 0;
        if ($request->hasFile('product_img')) {
            // 古い画像を削除
            if ($sale->product_img) {
                Storage::disk('public')->delete($sale->product_img);                    
            }
            $sale->product_img = $request->file('product_img')->store('products', 'public');
        } elseif (!empty($validatedData['product_img_existing'])) {
            $sale->product_img = $validatedData['product_img_existing'];
        }
        
        $sale->save(); 
        return redirect()->route('sale.detail', ['id' => $sale->id])->with('success', '販売登録が正常に更新されました。');
    }
    //販売登録削除確認
    public function delete_confirm($id)
    {
        $storeId = Auth::id();
        $sale = Sale::where('id', $id)->    
                      where('store_id', $storeId)->
                      firstOrFail();
        return view('sale.delete_confirm', compact('sale'));
    }
    //販売登録削除実行
    public function delete($id)
    {
        $storeId = Auth::id();
        $sale = Sale::where('id', $id)->    
                      where('store_id', $storeId)->
                      firstOrFail();
        // 画像削除
        if ($sale->product_img) {
            Storage::disk('public')->delete($sale->product_img);                    
        }
        $sale->delete();
        return redirect()->route('sale.list')->with('success', '販売登録が正常に削除されました。');
    }

    public function sales_summary()
    {
        $storeId = Auth::id();

        $purchaseRows = DB::table('buy_items')
            ->join('deals', 'buy_items.deal_id', '=', 'deals.id')
            ->where('deals.store_id', $storeId)
            ->select(
                DB::raw('YEAR(deals.created_at) as year'),
                DB::raw('MONTH(deals.created_at) as month'),
                DB::raw('SUM(buy_items.buy_price) as purchase_total')
            )
            ->groupBy('year', 'month')
            ->get();

        $salesRows = DB::table('sale')
            ->where('store_id', $storeId)
            ->where('is_confirmed', 1)
            ->whereNotNull('deposit_date')
            ->select(
                DB::raw('YEAR(deposit_date) as year'),
                DB::raw('MONTH(deposit_date) as month'),
                DB::raw('SUM(selling_price) as sales_total')
            )
            ->groupBy('year', 'month')
            ->get();

        $summaryMap = [];

        foreach ($purchaseRows as $row) {
            $key = $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT);
            $summaryMap[$key] = [
                'year' => (int) $row->year,
                'month' => (int) $row->month,
                'purchase_total' => (float) $row->purchase_total,
                'sales_total' => 0.0,
            ];
        }

        foreach ($salesRows as $row) {
            $key = $row->year . '-' . str_pad($row->month, 2, '0', STR_PAD_LEFT);
            if (!isset($summaryMap[$key])) {
                $summaryMap[$key] = [
                    'year' => (int) $row->year,
                    'month' => (int) $row->month,
                    'purchase_total' => 0.0,
                    'sales_total' => 0.0,
                ];
            }
            $summaryMap[$key]['sales_total'] = (float) $row->sales_total;
        }

        $summaries = array_values($summaryMap);
        usort($summaries, function ($a, $b) {
            if ($a['year'] === $b['year']) {
                return $b['month'] <=> $a['month'];
            }
            return $b['year'] <=> $a['year'];
        });

        foreach ($summaries as &$row) {
            $row['gross_profit'] = $row['sales_total'] - $row['purchase_total'];
        }
        unset($row);

        return view('customer.sales_summary', compact('summaries'));
    }
}                    
