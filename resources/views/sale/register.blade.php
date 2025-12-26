@extends('layouts.member')

@section('title', '買取販売登録')
@section('content')

<div class="flex flex-wrap -mx-2">
    <div class="w-full md:w-1/4 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">商品画像</label>
        <input type="file" name="product_img" class="w-full text-sm border border-gray-300 bg-white rounded p-1" data-preview="product" disabled>
        <img class="mt-2 w-full h-28 object-contain border border-gray-200 rounded hidden" data-preview="product">
    </div>

    <div class="w-full md:w-1/2 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">商品名 <span class="text-red-500">必須</span></label>
        <input type="text" name="product" maxlength="100" class="w-full border border-gray-300 rounded px-3 py-2" required disabled>
    </div>

    <div class="w-full md:w-1/4 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">買取分類 <span class="text-red-500">必須</span></label>
        <select name="classification" class="w-full border border-gray-300 rounded px-3 py-2" required disabled>
            <option value="">選択</option>
            @foreach(['ブランド','時計','貴金属','携帯・タブレット','ジュエリー','金券','酒類','切手','通貨','古銭','テレカ','勲章','骨董品・絵画','楽器','食器','家電','カメラ','雑貨','喫煙具','万年筆・ボールペン','おもちゃ','工具','衣類','パソコン','その他'] as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="flex flex-wrap -mx-2">
    <div class="w-full md:w-1/2 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">備考１</label>
        <textarea name="remarks" rows="1" class="w-full border border-gray-300 rounded px-3 py-2" disabled></textarea>
    </div>

    <div class="w-full md:w-1/4 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">数量 <span class="text-red-500">必須</span></label>
        <input type="number" name="quantity" min="1" value="1" class="w-full border border-gray-300 rounded px-3 py-2 text-right" required disabled>
    </div>

    <div class="w-full md:w-1/4 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">販売価格 <span class="text-red-500">必須</span></label>
        <input type="number" name="selling_price" min="0" class="w-full border border-gray-300 rounded px-3 py-2 text-right" required disabled>
    </div>
</div>

<div class="flex flex-wrap -mx-2">
    <div class="w-full md:w-1/4 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">販売日 <span class="text-red-500">必須</span></label>
        <input type="date" name="sale_date" class="w-full border border-gray-300 rounded px-3 py-2" required disabled>
    </div>

    <div class="w-full md:w-1/4 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">入金日</label>
        <input type="date" name="deposit_date" class="w-full border border-gray-300 rounded px-3 py-2" disabled>
    </div>

    <div class="w-full md:w-1/2 px-2 mb-4">
        <label class="block text-sm font-bold mb-1">卸先 <span class="text-red-500">必須</span></label>
        <select name="destination_id" class="w-full border border-gray-300 rounded px-3 py-2" required disabled>
            <option value="">選択</option>
        </select>
    </div>
</div>

<div class="flex flex-wrap -mx-2">
    <div class="w-full px-2 mb-4">
        <label class="block text-sm font-bold mb-1">販売確定 <span class="text-red-500">必須</span></label>
        <div class="flex flex-wrap gap-4 text-sm">
            <label class="flex items-center gap-2">
                <input type="radio" name="is_confirmed" value="1" required disabled>
                <span>確定</span>
            </label>
            <label class="flex items-center gap-2">
                <input type="radio" name="is_confirmed" value="0" required disabled>
                <span>未確定</span>
            </label>
        </div>
    </div>
</div>

@endsection
