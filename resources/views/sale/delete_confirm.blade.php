@extends('layouts.member')

@section('title', '買取販売削除確認')
@section('content')

<div class="max-w-5xl mx-auto p-6 space-y-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取販売削除確認</h2>

    <div class="p-4 border rounded-lg bg-gray-50 text-sm">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full md:w-1/2 px-3">
                <p class="text-xs text-gray-500">買取販売ID</p>
                <p class="font-semibold">{{ $sale->id ?? '—' }}</p>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <p class="text-xs text-gray-500">登録日時</p>
                <p class="font-semibold">{{ $sale->created_at?->format('Y/m/d H:i') ?? '—' }}</p>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mt-3">
            <div class="w-full md:w-1/2 px-3">
                <p class="text-xs text-gray-500">伝票番号</p>
                <p class="font-semibold">{{ $sale->deal?->slip_number ?? '—' }}</p>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <p class="text-xs text-gray-500">卸先</p>
                <p class="font-semibold">{{ $sale->wholesaleInfo?->wholesale ?? '—' }}</p>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-2 py-2">商品名</th>
                    <th class="border px-2 py-2">買取分類</th>
                    <th class="border px-2 py-2 text-right">買取価格</th>
                    <th class="border px-2 py-2 text-right">販売価格</th>
                    <th class="border px-2 py-2">販売日</th>
                    <th class="border px-2 py-2">入金日</th>
                    <th class="border px-2 py-2">販売確定</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-gray-50">
                    <td class="border px-2 py-1">{{ $sale->product ?? '—' }}</td>
                    <td class="border px-2 py-1">{{ $sale->classification ?? '—' }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($sale->buy_price ?? 0) }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($sale->selling_price ?? 0) }}</td>
                    <td class="border px-2 py-1">{{ $sale->sale_date ?? '—' }}</td>
                    <td class="border px-2 py-1">{{ $sale->deposit_date ?? '—' }}</td>
                    <td class="border px-2 py-1">{{ $sale->is_confirmed ? '確定' : '未確定' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex justify-center gap-4">
        <form action="{{ route('sale.delete', ['id' => $sale->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">削除する</button>
        </form>
        <a href="{{ route('sale.detail', ['id' => $sale->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">詳細に戻る</a>
    </div>
</div>

@endsection
