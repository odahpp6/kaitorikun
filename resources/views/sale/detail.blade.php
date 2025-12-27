@extends('layouts.member')

@section('title', '買取販売詳細')
@section('content')

<div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取販売詳細</h2>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/2 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">買取販売ID</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                {{ $sale->id ?? '—' }}
            </p>
        </div>
        <div class="w-full md:w-1/2 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">登録日時</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                {{ $sale->created_at?->format('Y/m/d H:i') ?? '—' }}
            </p>
        </div>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/2 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">伝票番号</label>
            @if ($sale->deal_id)
                <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                    <a href="/purchase/{{ $sale->deal_id }}/detail" class="text-blue-600 hover:underline">
                        {{ $deal?->slip_number ?? '—' }}
                    </a>
                </p>
            @else
                <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 text-gray-500">未指定</p>
            @endif
        </div>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full px-2 mb-4">
            <label class="block text-sm font-bold mb-1">同一伝票番号の買取販売の金額</label>
            <div class="overflow-x-auto">
                <table class="w-full border border-gray-300 text-sm">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-2 py-2">買取販売ID</th>
                            <th class="border px-2 py-2 text-right">買取金額</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($relatedSales as $relatedSale)
                            <tr class="hover:bg-gray-50">
                                <td class="border px-2 py-1">
                                    <a href="{{ route('sale.detail', $relatedSale->id) }}" class="text-blue-600 hover:underline">
                                        {{ $relatedSale->id }}
                                    </a>
                                </td>
                                <td class="border px-2 py-1 text-right">{{ number_format($relatedSale->buy_price ?? 0) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td class="border px-2 py-4 text-center text-gray-500" colspan="2">対象データがありません</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($relatedSales->isNotEmpty())
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td class="border px-2 py-2 text-right font-semibold">合計</td>
                                <td class="border px-2 py-2 text-right font-semibold">{{ number_format($relatedSales->sum('buy_price')) }}</td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
    @php
        $sameSlipTotal = $deal && $deal->total_price !== null ? (int) $deal->total_price : null;
        $sameSlipSalesTotal = $relatedSales->sum('buy_price');
        $sameSlipDiff = $sameSlipTotal !== null ? ($sameSlipTotal - $sameSlipSalesTotal) : null;
    @endphp
    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/2 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">同一伝票の合計金額</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 text-right">
                {{ $sameSlipTotal !== null ? number_format($sameSlipTotal) : '?' }}
            </p>
        </div>
        <div class="w-full md:w-1/2 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">合計金額の差額</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 text-right {{ $sameSlipDiff === 0 ? 'text-gray-900' : 'text-red-600' }}">
                {{ $sameSlipDiff !== null ? number_format($sameSlipDiff) : '?' }}
            </p>
        </div>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/4 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">商品画像</label>
            @if (!empty($sale->product_img))
                <img src="{{ asset('storage/' . $sale->product_img) }}" alt="商品画像" class="mt-1 w-full h-28 object-contain border border-gray-200 rounded">
            @else
                <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 text-gray-500">未登録</p>
            @endif
        </div>

        <div class="w-full md:w-1/2 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">商品名</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                {{ $sale->product ?? '—' }}
            </p>
        </div>

        <div class="w-full md:w-1/4 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">買取分類</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                {{ $sale->classification ?? '—' }}
            </p>
        </div>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/3 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">買取価格</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 text-right">
                {{ number_format($sale->buy_price ?? 0) }}
            </p>
        </div>

        <div class="w-full md:w-1/3 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">数量</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 text-right">
                {{ $sale->quantity ?? 1 }}
            </p>
        </div>

        <div class="w-full md:w-1/3 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">単価</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 text-right">
                {{ number_format($sale->unit_price ?? 0) }}
            </p>
        </div>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/3 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">販売価格</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50 text-right">
                {{ number_format($sale->selling_price ?? 0) }}
            </p>
        </div>

        <div class="w-full md:w-1/3 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">販売日</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                {{ $sale->sale_date ?? '—' }}
            </p>
        </div>

        <div class="w-full md:w-1/3 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">入金日</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                {{ $sale->deposit_date ?? '—' }}
            </p>
        </div>
    </div>

    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/2 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">卸先</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                {{ $wholesale?->wholesale ?? '—' }}
            </p>
        </div>
        <div class="w-full md:w-1/2 px-2 mb-4">
            <label class="block text-sm font-bold mb-1">販売確定</label>
            <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                {{ $sale->is_confirmed ? '確定' : '未確定' }}
            </p>
        </div>
    </div>
</div>

@endsection
