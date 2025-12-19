@extends('layouts.member')

@section('title', '見積詳細')
@section('content')
<div id="item-container" class="space-y-4">
    </div>

<div class="mt-6">
    <button type="button" onclick="addItem()" id="add-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
        ＋ 商品を追加する (最大30件)
    </button>
</div>

<div id="item-template" style="display: none;">
    <div class="item-row border p-4 rounded-lg bg-gray-50 relative">
        <div class="flex flex-wrap -mx-2">
            
            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">商品画像</label>
                <input type="file" name="items[][product_img]" class="w-full text-sm border bg-white rounded p-1" disabled>
            </div>

            <div class="w-full md:w-1/2 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">商品名 <span class="text-red-500">*</span></label>
                <input type="text" name="items[][product]" maxlength="100" placeholder="例：ロレックス サブマリーナ" class="w-full border rounded px-3 py-2" required disabled>
            </div>

            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">買取分類 <span class="text-red-500">*</span></label>
                <select name="items[][classification]" class="w-full border rounded px-3 py-2" required disabled>
                    <option value="">選択してください</option>
                    @foreach(['ブランド','時計','貴金属','携帯・タブレット','ジュエリー','金券','酒類','切手','通貨','古銭','テレカ','勲章','骨董品・絵画','楽器','食器','家電','カメラ','雑貨','喫煙具','万年筆・ボールペン','おもちゃ','工具','衣類','パソコン','その他'] as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-3/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">備考</label>
                <textarea name="items[][remarks_2]" rows="1" class="w-full border rounded px-3 py-2" placeholder="商品の状態、付属品など" disabled></textarea>
            </div>

            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">買取金額 <span class="text-red-500">*</span></label>
                <input type="number" name="items[][buy_price]" min="0" placeholder="0" class="w-full border rounded px-3 py-2 text-right" required disabled>
            </div>
        </div>

        <button type="button" onclick="removeItem(this)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 shadow-sm">
            ×
        </button>
    </div>
</div>

<script>
    const MAX_ITEMS = 30;

    function addItem() {
        const container = document.getElementById('item-container');
        const rowCount = container.querySelectorAll('.item-row').length;

        if (rowCount >= MAX_ITEMS) {
            alert("一度に登録できるのは" + MAX_ITEMS + "件までです。");
            return;
        }

        // テンプレートを複製
        const template = document.getElementById('item-template').firstElementChild.cloneNode(true);
        
        // 複製した要素内のinput/select/textareaを有効化（disabled解除）
        template.querySelectorAll('input, select, textarea').forEach(el => {
            el.disabled = false;
        });

        container.appendChild(template);
        updateButtonState();
    }

    function removeItem(btn) {
        const container = document.getElementById('item-container');
        if (container.querySelectorAll('.item-row').length > 1) {
            btn.closest('.item-row').remove();
        } else {
            alert("最低1つ以上の商品登録が必要です。");
        }
        updateButtonState();
    }

    function updateButtonState() {
        const count = document.getElementById('item-container').querySelectorAll('.item-row').length;
        document.getElementById('add-button').disabled = (count >= MAX_ITEMS);
    }

    // 初期状態で1行表示
    window.onload = addItem;
</script>


@endsection