<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{
    public function cash_balance_view()
    {
        return view('cash.balance');
    }

    public function cash_balance_register(Request $request)
    {
        $validated = $request->validate([
            'bill_10000' => 'required|integer|min:0',
            'bill_5000' => 'required|integer|min:0',
            'bill_1000' => 'required|integer|min:0',
            'coin_500' => 'required|integer|min:0',
            'coin_100' => 'required|integer|min:0',
            'coin_50' => 'required|integer|min:0',
            'coin_10' => 'required|integer|min:0',
            'coin_5' => 'required|integer|min:0',
            'coin_1' => 'required|integer|min:0',
        ]);

        $totalAmount =
            ($validated['bill_10000'] * 10000) +
            ($validated['bill_5000'] * 5000) +
            ($validated['bill_1000'] * 1000) +
            ($validated['coin_500'] * 500) +
            ($validated['coin_100'] * 100) +
            ($validated['coin_50'] * 50) +
            ($validated['coin_10'] * 10) +
            ($validated['coin_5'] * 5) +
            ($validated['coin_1'] * 1);

        $cash = new Cash();
        $cash->store_id = Auth::id();
        $cash->bill_10000 = $validated['bill_10000'];
        $cash->bill_5000 = $validated['bill_5000'];
        $cash->bill_1000 = $validated['bill_1000'];
        $cash->coin_500 = $validated['coin_500'];
        $cash->coin_100 = $validated['coin_100'];
        $cash->coin_50 = $validated['coin_50'];
        $cash->coin_10 = $validated['coin_10'];
        $cash->coin_5 = $validated['coin_5'];
        $cash->coin_1 = $validated['coin_1'];
        $cash->total_amount = $totalAmount;
        $cash->save();

        return redirect()->back()->with('success', '現金残高を登録しました。');
    }
}
