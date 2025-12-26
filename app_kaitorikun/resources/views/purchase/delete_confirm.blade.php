@extends('layouts.member')

@section('title', '買取削除確認')
@section('content')

<div class="max-w-6xl mx-auto p-6 space-y-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取削除確認</h2>

    <div class="p-4 border rounded-lg bg-gray-50 text-sm">
        <div class="flex flex-wrap -mx-3">
            <div class="w-full md:w-1/2 px-3">
                <p class="text-xs text-gray-500">顧客名</p>
                <p class="font-semibold">{{ $deal->customer->name ?? '—' }}</p>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <p class="text-xs text-gray-500">登録日</p>
                <p class="font-semibold">{{ $deal->created_at?->format('Y/m/d H:i') ?? '—' }}</p>
            </div>
        </div>
        <div class="flex flex-wrap -mx-3 mt-3">
            <div class="w-full md:w-1/2 px-3">
                <p class="text-xs text-gray-500">伝票番号</p>
                <p class="font-semibold">{{ $deal->slip_number ?? '—' }}</p>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <p class="text-xs text-gray-500">総合計</p>
                <p class="font-semibold">{{ number_format($deal->total_price ?? 0) }} 円</p>
            </div>
        </div>
    </div>

    <table class="w-full border border-gray-300 text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-2">商品名</th>
                <th class="border px-2 py-2">分類</th>
                <th class="border px-2 py-2">個数</th>
                <th class="border px-2 py-2">買取金額</th>
                <th class="border px-2 py-2">備考</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($deal->buyItems as $item)
                <tr class="hover:bg-gray-50">
                    <td class="border px-2 py-1">{{ $item->product }}</td>
                    <td class="border px-2 py-1">{{ $item->classification }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($item->quantity) }}</td>
                    <td class="border px-2 py-1 text-right">{{ number_format($item->buy_price) }}</td>
                    <td class="border px-2 py-1 whitespace-pre-wrap">{{ $item->remarks_2 }}</td>
                </tr>
            @empty
                <tr>
                    <td class="border px-2 py-3 text-center text-gray-500" colspan="5">商品がありません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="flex justify-center gap-4">
        <form action="{{ route('purchase.delete', ['id' => $deal->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">削除する</button>
        </form>
        <a href="{{ route('purchase.detail', ['id' => $deal->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">詳細に戻る</a>
    </div>
</div>

@endsection
