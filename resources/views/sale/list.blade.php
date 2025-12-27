@extends('layouts.member')

@section('title', '買取販売一覧')
@section('content')

<div class="max-w-6xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取販売一覧</h2>

    <form action="{{ route('sale.list') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">商品名</label>
                <input type="text" name="product_name" value="{{ request('product_name') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="例：時計">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">卸先</label>
                <select name="wholesale_name" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">選択</option>
                    @foreach($wholesales ?? [] as $wholesale)
                        <option value="{{ $wholesale->wholesale }}" @selected(request('wholesale_name') === $wholesale->wholesale)>
                            {{ $wholesale->wholesale }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">商品番号</label>
                <input type="text" name="product_number" value="{{ request('product_number') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="例：123">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">分類</label>
                <select name="classification" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <option value="">選択</option>
                    @foreach(['ブランド','時計','貴金属','携帯・タブレット','ジュエリー','金券','酒類','切手','通貨','古銭','テレカ','勲章','骨董品・絵画','楽器','食器','家電','カメラ','雑貨','喫煙具','万年筆・ボールペン','おもちゃ','工具','衣類','パソコン','その他'] as $cat)
                        <option value="{{ $cat }}" @selected(request('classification') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">登録日時（期間）</label>
                <div class="flex items-center space-x-2">
                    <input type="date" name="created_from" value="{{ request('created_from') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <span class="text-gray-400">〜</span>
                    <input type="date" name="created_to" value="{{ request('created_to') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                </div>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">買取日時（月）</label>
                <div class="flex items-center space-x-2">
                    <input type="month" name="purchase_month_from" value="{{ request('purchase_month_from') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                    <span class="text-gray-400">〜</span>
                    <input type="month" name="purchase_month_to" value="{{ request('purchase_month_to') }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm">
                </div>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                    検索
                </button>
                <a href="{{ route('sale.list') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 text-center">
                    クリア
                </a>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-2 py-2">登録日</th>
                    <th class="border px-2 py-2">商品名</th>
                    <th class="border px-2 py-2">分類</th>
                    <th class="border px-2 py-2 text-right">販売価格</th>
                    <th class="border px-2 py-2 text-right">買取金額</th>
                    <th class="border px-2 py-2">伝票番号</th>
                    <th class="border px-2 py-2">販売確定</th>
                    <th class="border px-2 py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr class="hover:bg-gray-50">
                        <td class="border px-2 py-1">{{ $sale->created_at?->format('Y/m/d') ?? '—' }}</td>
                        <td class="border px-2 py-1">
                            <a href="{{ route('sale.detail', $sale->id) }}" class="text-blue-600 hover:underline">
                                {{ $sale->product }}
                            </a>
                        </td>
                        <td class="border px-2 py-1">{{ $sale->classification }}</td>
                        <td class="border px-2 py-1 text-right">{{ number_format($sale->selling_price ?? 0) }}</td>
                        <td class="border px-2 py-1 text-right">{{ number_format($sale->buy_price) }}</td>
                        <td class="border px-2 py-1">
                            @if ($sale->deal_id)
                                <a href="{{ route('purchase.detail', $sale->deal_id) }}" class="text-blue-600 hover:underline">
                                    {{ $sale->deal?->slip_number ?? '—' }}
                                </a>
                            @else
                                —
                            @endif
                        </td>
                        <td class="border px-2 py-1">{{ $sale->is_confirmed ? '確定' : '未確定' }}</td>
                        <td class="border px-2 py-1">
                            <a href="{{ route('sale.edit', $sale->id) }}" class="text-blue-600 hover:underline">更新</a>
                            |
                            <a href="{{ route('sale.delete_confirm', $sale->id) }}" class="text-red-600 hover:underline">削除</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="border px-2 py-4 text-center text-gray-500" colspan="8">登録データがありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sales->appends(request()->query())->links() }}
    </div>
</div>

@endsection
