@extends('layouts.member')

@section('title', 'リピート分析')
@section('content')

<div class="max-w-6xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-emerald-500">リピート分析</h2>

    <form action="{{ route('customer.repeat_analysis') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">契約日時(開始)</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">契約日時(終了)</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">来店区分</label>
                <select name="arrival_type" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">すべて</option>
                    @foreach(['店舗前','折込','顧客','紹介','ホームページ','ポスティング','テレビ','情報誌','テレアポ','Googleマップ','呼び込み','電話問合せ','ティッシュ','LP','SNS','エキテン','DM','LINE査定','2次アポ','リスティング広告'] as $arrivalType)
                        <option value="{{ $arrivalType }}" @selected(request('arrival_type') === $arrivalType)>{{ $arrivalType }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">買取分類</label>
                <select name="classification" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">すべて</option>
                    @foreach(['ブランド','時計','貴金属','携帯・タブレット','ジュエリー','金券','酒類','切手','通貨','古銭','テレカ','勲章','骨董品・絵画','楽器','食器','家電','カメラ','雑貨','喫煙具','万年筆・ボールペン','おもちゃ','工具','衣類','パソコン','その他'] as $classification)
                        <option value="{{ $classification }}" @selected(request('classification') === $classification)>{{ $classification }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex space-x-2 mt-4">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md transition duration-200">
                検索
            </button>
            <a href="{{ route('customer.repeat_analysis') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 text-center">
                クリア
            </a>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-3 py-3 text-left">来店区分</th>
                    <th class="border px-3 py-3 text-left">名前</th>
                    <th class="border px-3 py-3 text-right">来店回数</th>
                    <th class="border px-3 py-3 text-left">契約日時</th>
                    <th class="border px-3 py-3 text-left">商品区分</th>
                    <th class="border px-3 py-3 text-left">商品名</th>
                    <th class="border px-3 py-3 text-right">買取金額</th>
                </tr>
            </thead>
            <tbody>
                @php $lastCustomerKey = null; @endphp
                @forelse ($repeatItems as $item)
                    @php $customerKey = $item->name . '|' . $item->phone_number; @endphp
                    <tr class="hover:bg-emerald-50 transition duration-150">
                        <td class="border px-3 py-2">{{ $item->arrival_type }}</td>
                        <td class="border px-3 py-2">{{ $customerKey !== $lastCustomerKey ? $item->name : '' }}</td>
                        <td class="border px-3 py-2 text-right">{{ $customerKey !== $lastCustomerKey ? number_format($item->visit_count) : '' }}</td>
                        <td class="border px-3 py-2">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i') }}
                        </td>
                        <td class="border px-3 py-2">{{ $item->classification }}</td>
                        <td class="border px-3 py-2">{{ $item->product }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($item->buy_price) }}</td>
                    </tr>
                    @php $lastCustomerKey = $customerKey; @endphp
                @empty
                    <tr>
                        <td colspan="7" class="border px-3 py-10 text-center text-gray-500">
                            該当データがありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
