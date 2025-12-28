<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\CashManagement;
use App\Models\Deal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashManagementController extends Controller
{
    public function cash_management_view()
    {
        return view('cash.cash_management');
    }

    public function cash_management_register(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:in,out',
            'amount' => 'required|integer|min:1',
            'description' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $management = new CashManagement();
        $management->store_id = Auth::id();
        $management->type = $validated['type'];
        $management->amount = $validated['amount'];
        $management->description = $validated['description'];
        $management->remarks = $validated['remarks'] ?? null;
        $management->save();

        return redirect()->back()->with('success', '現金出納帳を登録しました。');
    }

    public function cash_management_list()
    {
        $cashUpdates = Cash::where('store_id', Auth::id())
            ->orderBy('updated_at')
            ->get();

        $entryRows = CashManagement::where('store_id', Auth::id())
            ->get()
            ->map(function ($management) {
                $isIn = $management->type === 'in';

                return [
                    'at' => $management->updated_at,
                    'amount' => $isIn ? (int) $management->amount : (int) -$management->amount,
                ];
            })
            ->concat(
                Deal::where('store_id', Auth::id())
                    ->get()
                    ->map(function ($deal) {
                        return [
                            'at' => $deal->created_at,
                            'amount' => (int) -$deal->total_price,
                        ];
                    })
            );

        $cashDiffs = [];
        $previousCash = null;
        foreach ($cashUpdates as $cash) {
            if (!$previousCash) {
                $cashDiffs[$cash->id] = null;
                $previousCash = $cash;
                continue;
            }

            $net = $entryRows
                ->filter(function ($entry) use ($previousCash, $cash) {
                    return $entry['at']->gt($previousCash->updated_at)
                        && $entry['at']->lte($cash->updated_at);
                })
                ->sum('amount');

            $expected = (int) $previousCash->total_amount + (int) $net;
            $cashDiffs[$cash->id] = (int) $cash->total_amount - $expected;
            $previousCash = $cash;
        }

        $cashRows = $cashUpdates
            ->sortByDesc('updated_at')
            ->map(function ($cash) use ($cashDiffs) {
                $remarks = $cash->remarks ? ' 備考：' . $cash->remarks : '';

                return [
                    'updated_at' => $cash->updated_at,
                    'in' => null,
                    'out' => null,
                    'balance' => $cash->total_amount,
                    'difference' => $cashDiffs[$cash->id] ?? null,
                    'detail_type' => '金庫更新',
                    'counterparty' => null,
                    'content' => '金庫更新' . $remarks,
                ];
            });

        $managementRows = CashManagement::where('store_id', Auth::id())
            ->latest('updated_at')
            ->get()
            ->map(function ($management) {
                $isIn = $management->type === 'in';
                $counterparty = $management->related_table ? $management->related_table : null;
                if ($management->related_id) {
                    $counterparty = trim(($counterparty ?? '') . ' #' . $management->related_id);
                }

                return [
                    'updated_at' => $management->updated_at,
                    'in' => $isIn ? $management->amount : null,
                    'out' => $isIn ? null : $management->amount,
                    'balance' => null,
                    'difference' => null,
                    'detail_type' => $isIn ? '入金' : '出金',
                    'counterparty' => $counterparty,
                    'content' => $management->description,
                ];
            });

        $dealRows = Deal::with('customer')
            ->where('store_id', Auth::id())
            ->latest('created_at')
            ->get()
            ->map(function ($deal) {
                return [
                    'updated_at' => $deal->created_at,
                    'in' => null,
                    'out' => $deal->total_price,
                    'balance' => null,
                    'difference' => null,
                    'detail_type' => '買取',
                    'counterparty' => $deal->customer?->name,
                    'content' => '買取',
                ];
            });

        $rows = $cashRows
            ->concat($managementRows)
            ->concat($dealRows)
            ->sortByDesc('updated_at')
            ->values();

        return view('cash.cash_management_list', compact('rows'));
    }
}
