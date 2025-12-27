@extends('layouts.member')

@section('title', '買取販売編集')
@section('content')

<div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取販売編集</h2>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form class="space-y-4" action="{{ route('sale.update', $sale->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="flex flex-wrap -mx-2">
            <div class="w-full md:w-1/2 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">伝票番号</label>
                <p class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-50">
                    {{ $deal?->slip_number ?? '—' }}
                </p>
                <input type="hidden" name="slip_number" value="{{ $deal?->slip_number ?? '' }}">
            </div>
            <div class="w-full md:w-1/2 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">買取契約</label>
                @if ($dealId)
                    <a href="/purchase/{{ $dealId }}/detail" class="text-blue-600 hover:underline">詳細</a>
                    <input type="hidden" name="deal_id" value="{{ $dealId }}">
                @else
                    <p class="text-gray-500">未指定</p>
                @endif
            </div>
        </div>
        <div class="flex flex-wrap -mx-2">
            @php
                $selectedImage = old('product_img_existing', $sale->product_img);
            @endphp
            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">商品画像</label>
                <input type="file" name="product_img" class="w-full text-sm border border-gray-300 bg-white rounded p-1" data-preview="product">
                <input type="hidden" name="product_img_existing" value="{{ $selectedImage ?? '' }}">
                <img class="mt-2 w-full h-28 object-contain border border-gray-200 rounded {{ $selectedImage ? '' : 'hidden' }}" data-preview="product" @if ($selectedImage) src="{{ asset('storage/' . $selectedImage) }}" @endif>
            </div>

            <div class="w-full md:w-1/2 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">商品名 <span class="text-red-500">必須</span></label>
                <input type="text" name="product" maxlength="100" value="{{ old('product', $sale->product) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">買取分類 <span class="text-red-500">必須</span></label>
                <select name="classification" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">選択</option>
                    @foreach(['ブランド','時計','貴金属','携帯・タブレット','ジュエリー','金券','酒類','切手','通貨','古銭','テレカ','勲章','骨董品・絵画','楽器','食器','家電','カメラ','雑貨','喫煙具','万年筆・ボールペン','おもちゃ','工具','衣類','パソコン','その他'] as $cat)
                        <option value="{{ $cat }}" @selected(old('classification', $sale->classification) === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-wrap -mx-2">
            <div class="w-full px-2 mb-4">
                <label class="block text-sm font-bold mb-1">備考１</label>
                <textarea name="remarks" rows="1" class="w-full border border-gray-300 rounded px-3 py-2">{{ old('remarks') }}</textarea>
            </div>
        </div>

        <div class="flex flex-wrap -mx-2">
            <div class="w-full md:w-1/3 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">買取価格 <span class="text-red-500">必須</span></label>
                <input type="number" name="buy_price" min="0" value="{{ old('buy_price', $sale->buy_price) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-right" required>
            </div>

            <div class="w-full md:w-1/3 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">数量 <span class="text-red-500">必須</span></label>
                <input type="number" name="quantity" min="1" value="{{ old('quantity', $sale->quantity ?? 1) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-right" required>
            </div>

            <div class="w-full md:w-1/3 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">販売価格 <span class="text-red-500">必須</span></label>
                <input type="number" name="selling_price" min="0" value="{{ old('selling_price', $sale->selling_price) }}" class="w-full border border-gray-300 rounded px-3 py-2 text-right" required>
            </div>
        </div>

        <div class="flex flex-wrap -mx-2">
            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">販売日</label>
                <input type="date" name="sale_date" value="{{ old('sale_date', $sale->sale_date) }}" class="w-full border border-gray-300 rounded px-3 py-2">
            </div>

            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">入金日 <span class="text-red-500">必須</span></label>
                <input type="date" name="deposit_date" value="{{ old('deposit_date', $sale->deposit_date) }}" class="w-full border border-gray-300 rounded px-3 py-2" required>
            </div>

            <div class="w-full md:w-1/2 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">卸先 <span class="text-red-500">必須</span></label>
                <select name="destination_id" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    <option value="">選択</option>
                    @foreach($wholesales ?? [] as $wholesale)
                        <option value="{{ $wholesale->id }}" @selected((string) old('destination_id', $sale->wholesale) === (string) $wholesale->id)>{{ $wholesale->wholesale }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-wrap -mx-2">
            <div class="w-full px-2 mb-4">
                <label class="block text-sm font-bold mb-1">販売確定 <span class="text-red-500">必須</span></label>
                <div class="flex flex-wrap gap-4 text-sm">
                    <label class="flex items-center gap-2">
                        <input type="radio" name="is_confirmed" value="1" required @checked(old('is_confirmed', (int) $sale->is_confirmed) === 1)>
                        <span>確定</span>
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="radio" name="is_confirmed" value="0" required @checked(old('is_confirmed', (int) $sale->is_confirmed) === 0)>
                        <span>未確定</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-center pt-4">
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                更新
            </button>
        </div>
    </form>
</div>

@endsection
