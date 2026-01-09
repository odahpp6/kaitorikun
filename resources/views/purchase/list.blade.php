@extends('layouts.member')

@section('title', '買取契約履歴')
@section('content')

<div class="max-w-6xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取契約履歴</h2>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
            <div class="flex text-green-800">
                <i class="fa-solid fa-check-circle mt-1"></i>
                <div class="ml-3 font-medium">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <form action="{{ route('purchase.search') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">顧客名</label>
                <input type="text" name="customer_name" value="{{ request('customer_name') }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="例：田中">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">契約日（期間）</label>
                <div class="flex items-center space-x-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <span class="text-gray-400">〜</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">商品名</label>
                <input type="text" name="product_name" value="{{ request('product_name') }}" 
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="例：金貨">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                    検索
                </button>
                <a href="{{ route('purchase.search') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 text-center">
                    クリア
                </a>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-3 py-3 text-left">登録日時</th>
                    <th class="border px-3 py-3 text-left">伝票番号</th>
                    <th class="border px-3 py-3 text-left">顧客名</th>
                    <th class="border px-3 py-3 text-left">商品名</th>
                    <th class="border px-3 py-3 text-right">金額</th>
                    <th class="border px-3 py-3 text-center">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deals as $deal)
                <tr class="hover:bg-blue-50 transition duration-150">
                    <td class="border px-3 py-2 text-gray-600">
                        <a href="{{ route('purchase.detail', $deal->id) }}" class="text-blue-600 hover:underline">
                            {{ $deal->created_at->format('Y/m/d H:i') }}
                        </a>
                    </td>
                    <td class="border px-3 py-2 font-mono text-xs">{{ $deal->slip_number ?? '—' }}</td>
                    <td class="border px-3 py-2 font-bold">{{ $deal->customer->name }}</td>
                    <td class="border px-3 py-2">
                        {{ $deal->buyItems->first()->product ?? '商品なし' }}
                        @if($deal->buyItems->count() > 1)
                            <span class="text-gray-400 text-xs">(他{{ $deal->buyItems->count() - 1 }}件)</span>
                        @endif
                    </td>
                    <td class="border px-3 py-2 text-right text-orange-600 font-bold">
                        ¥{{ number_format($deal->total_price) }}
                    </td>
                    <td class="border px-3 py-2 text-center space-x-2">
                        <a href="{{ route('purchase.edit', $deal->id) }}" class="text-blue-500 hover:text-blue-700 font-medium">更新</a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('purchase.delete_confirm', $deal->id) }}" class="text-red-500 hover:text-red-700 font-medium">削除</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border px-3 py-10 text-center text-gray-500">
                        該当する契約データが見つかりませんでした。
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
       
        {{ $deals->appends(request()->query())->links() }}
    
    </div>
</div>

@endsection
