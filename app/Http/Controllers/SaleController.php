<?php

namespace App\Http\Controllers;
use App\Models\Sale;
use App\Models\MasterWholesale;
use App\Models\Deal;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SaleController extends Controller
{
    

    // 商品登録画面表示
    public function register_view()
    {
        $wholesales = MasterWholesale::all();
        $dealId = request('deal_id');
        if (!$dealId && request()->filled('slip_number')) {
            $dealId = Deal::where('slip_number', request('slip_number'))->value('id');
        }

        return view('sale.register', compact('wholesales', 'dealId'));
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
        }

        $sale->save();

        return redirect()->back()->with('success', '商品が正常に登録されました。');
    }
  
}
