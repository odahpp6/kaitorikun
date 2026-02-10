@extends('layouts.member')

@section('title', '商品履歴')
@section('content')

<div class="max-w-6xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">商品履歴</h2>

    <form action="{{ route('purchase.products_list') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">顧客名</label>
                <input type="text" name="customer_name" value="{{ request('customer_name') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="例：田中">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">買取日時（期間）</label>
                <div class="flex items-center space-x-2">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <span class="text-gray-400">〜</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">伝票番号</label>
                <input type="text" name="slip_number" value="{{ request('slip_number') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="例：20250101-ABCD">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">販売登録</label>
                <select name="sale_status" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="" @selected(request('sale_status') === null || request('sale_status') === '')>すべて</option>
                    <option value="1" @selected(request('sale_status') === '1')>登録済み</option>
                    <option value="0" @selected(request('sale_status') === '0')>未登録</option>
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                    検索
                </button>
                <a href="{{ route('purchase.products_list') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 text-center">
                    クリア
                </a>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-3 py-3 text-left">契約日</th>
                    <th class="border px-3 py-3 text-left">商品名</th>
                    <th class="border px-3 py-3 text-left">顧客名</th>
                    <th class="border px-3 py-3 text-left">伝票番号</th>
                    <th class="border px-3 py-3 text-center">販売登録</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                <tr class="hover:bg-blue-50 transition duration-150">
                    <td class="border px-3 py-2 text-gray-600">
                        {{ $item->deal_created_at ? \Carbon\Carbon::parse($item->deal_created_at)->format('Y/m/d') : '—' }}
                    </td>
                    <td class="border px-3 py-2">
                        {{ $item->product ?? '—' }}
                    </td>
                    <td class="border px-3 py-2 font-bold">
                        {{ $item->customer_name ?? '—' }}
                    </td>
                    <td class="border px-3 py-2 font-mono text-xs">
                        <a href="{{ route('purchase.detail', $item->deal_id) }}" class="text-blue-600 hover:underline">
                            {{ $item->slip_number ?? '—' }}
                        </a>
                    </td>
                    <td class="border px-3 py-2 text-center">
                        @if ((int) $item->is_sale_registered === 1)
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded">登録済み</span>
                        @else
                            <span class="inline-flex items-center px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 rounded">未登録</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border px-3 py-10 text-center text-gray-500">
                        該当する商品履歴が見つかりませんでした。
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $items->appends(request()->query())->links() }}
    </div>
</div>

@endsection
