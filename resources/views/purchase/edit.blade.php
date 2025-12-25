@extends('layouts.member')

@section('title', '見積詳細')
@section('content')

<h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取契約詳細</h2>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $customer = $deal->customer;
    $initialItems = old('items');
    if ($initialItems === null) {
        $initialItems = $deal->buyItems->map(function ($item) {
            return [
                'product' => $item->product,
                'classification' => $item->classification,
                'remarks_2' => $item->remarks_2,
                'quantity' => $item->quantity,
                'buy_price' => $item->buy_price,
            ];
        })->values()->toArray();
    }
@endphp

  <form action="{{ route('purchase.update', $deal->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" id="purchase-form">
        @csrf
        @method('PUT')
        <div id="item-container" class="space-y-4">
        </div>
        @error('items.*.product')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('items.*.quantity')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror
        @error('items.*.buy_price')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="mt-6 space-y-3">
            <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-right">
                <p class="text-sm text-gray-600">総合計</p>
                <p class="text-2xl font-bold">
                    <span id="total-price-display">{{ number_format(old('total_price', $deal->total_price ?? 0)) }}</span>円
                </p>
                <input type="hidden" name="total_price" id="total-price-input" value="{{ old('total_price', $deal->total_price ?? 0) }}">
            </div>
            <button type="button" onclick="addItem()" id="add-button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                ＋ 商品を追加する (最大30件)
            </button>
        </div>
{{-- 修正後の商品テンプレート：name属性を INDEX に置き換え可能にする --}}
<div id="item-template" style="display: none;">
    <div class="item-row border p-4 rounded-lg bg-gray-50 relative">
        <div class="flex flex-wrap -mx-2">
            
            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">商品画像</label>
                {{-- items[INDEX][xxx] 形式に変更 --}}
                <input type="file" name="items[INDEX][product_img]" class="w-full text-sm border border-gray-300 bg-white rounded p-1" data-preview="product" disabled>
                <img class="mt-2 w-full h-28 object-contain border border-gray-200 rounded hidden" data-preview="product">
            </div>

            <div class="w-full md:w-1/2 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">商品名 <span class="text-red-500">必須</span></label>
                <input type="text" name="items[INDEX][product]" maxlength="100" class="w-full border border-gray-300 rounded px-3 py-2" required disabled>
            </div>

            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">買取分類 <span class="text-red-500">必須</span></label>
                <select name="items[INDEX][classification]" class="w-full border border-gray-300 rounded px-3 py-2" required disabled>
                    <option value="">選択</option>
                    @foreach(['ブランド','時計','貴金属','携帯・タブレット','ジュエリー','金券','酒類','切手','通貨','古銭','テレカ','勲章','骨董品・絵画','楽器','食器','家電','カメラ','雑貨','喫煙具','万年筆・ボールペン','おもちゃ','工具','衣類','パソコン','その他'] as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-1/2 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">備考</label>
                <textarea name="items[INDEX][remarks_2]" rows="1" class="w-full border border-gray-300 rounded px-3 py-2" disabled></textarea>
            </div>

            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">個数</label>
                <input type="number" name="items[INDEX][quantity]" min="1" value="1" class="w-full border border-gray-300 rounded px-3 py-2 text-right" disabled>
            </div>

            <div class="w-full md:w-1/4 px-2 mb-4">
                <label class="block text-sm font-bold mb-1">買取金額 <span class="text-red-500">必須</span></label>
                <input type="number" name="items[INDEX][buy_price]" min="0" class="w-full border border-gray-300 rounded px-3 py-2 text-right" required disabled>
            </div>
        </div>
        <button type="button" onclick="removeItem(this)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">×</button>
    </div>
</div>

<div class="max-w-6xl mx-auto p-6">
  

        {{-- 1. 顧客情報セクション --}}
        <div class="bg-white p-6 rounded-lg border border-gray-200 border-t-4 border-blue-500">
            <h2 class="text-xl font-bold mb-6 flex items-center">
                <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-2 text-sm">1</span>
                顧客情報（親）
            </h2>

            <div class="space-y-6">
                {{-- 本人確認書類 --}}
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3">
                    <label class="block text-sm font-bold mb-1">身分証明書種類 <span class="text-red-500">必須</span></label>
                    <select name="proof_type" class="w-full border border-gray-300 rounded-md" required>
                        <option value="">選択してください</option>
                        @foreach(['免許証','マイナンバーカード','パスポート','在留カード','その他'] as $type)
                            <option value="{{ $type }}" @selected(old('proof_type', $customer->proof_type ?? null) === $type)>{{ $type }}</option>
                        @endforeach
                    </select>
                    @error('proof_type')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                    <label class="block text-sm font-bold mb-1">身分証明書番号 <span class="text-red-500">必須</span></label>
                    <input type="text" name="proof_num" class="w-full border border-gray-300 rounded-md" value="{{ old('proof_num', $customer->proof_num ?? null) }}" required>
                    @error('proof_num')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    </div>
                </div>
                
                {{-- 本人確認画像 --}}
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3">
                    <label class="block text-sm font-bold mb-1">身分証明書画像（表面） ※最大ファイルサイズ: 100MB <span class="text-red-500">必須</span></label>
                    <input type="file" name="proof_img_1" class="w-full text-sm border border-gray-300 rounded-md" >
                    @error('proof_img_1')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <img id="proof_preview_1" class="mt-2 w-full h-28 object-contain border border-gray-200 rounded hidden" alt="身分証明書画像（表面）プレビュー">
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                    <label class="block text-sm font-bold mb-1">身分証明書画像（裏面） ※最大ファイルサイズ: 100MB</label>
                    <input type="file" name="proof_img_2" class="w-full text-sm border border-gray-300 rounded-md">
                    @error('proof_img_2')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <img id="proof_preview_2" class="mt-2 w-full h-28 object-contain border border-gray-200 rounded hidden" alt="身分証明書画像（裏面）プレビュー">
                    </div>
                </div>

                {{-- 基本情報 --}}
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">顧客名 <span class="text-red-500">必須</span></label>
                        <input type="text" name="name" maxlength="50" class="w-full border border-gray-300 rounded-md animate-shadow-red" value="{{ old('name', $customer->name ?? null) }}" required>
                        @error('name')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">フリガナ</label>
                        <input type="text" name="furigana" maxlength="50" placeholder="カタカナ" class="w-full border border-gray-300 rounded-md" value="{{ old('furigana', $customer->furigana ?? null) }}">
                        @error('furigana')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">電話番号 <span class="text-red-500">必須</span></label>
                        <input type="tel" name="phone_number" placeholder="09012345678" class="w-full border border-gray-300 rounded-md" value="{{ old('phone_number', $customer->phone_number ?? null) }}" required>
                        @error('phone_number')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">Email</label>
                        <input type="email" name="email" class="w-full border border-gray-300 rounded-md" value="{{ old('email', $customer->email ?? null) }}">
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 生年月日 --}}
                @php
                    $eraLabel = function ($year) {
                        if ($year >= 2019) {
                            return '令和' . ($year - 2018) . '年';
                        }
                        if ($year >= 1989) {
                            return '平成' . ($year - 1988) . '年';
                        }
                        if ($year >= 1926) {
                            return '昭和' . ($year - 1925) . '年';
                        }
                        return '';
                    };
                @endphp
                <div class="flex flex-wrap gap-4 items-end">
                    <div class="w-40">
                        <label class="block text-sm font-bold mb-1">生年月日(西暦) <span class="text-red-500">必須</span></label>
                        <select name="birth_y" class="w-full border border-gray-300 rounded-md" required>
                            @for ($i = date('Y') - 18; $i >= 1920; $i--)
                                @php $era = $eraLabel($i); @endphp
                                <option value="{{ $i }}" @selected((string) old('birth_y', $customer->birth_y ?? null) === (string) $i)>
                                    {{ $i }}年@if ($era) ({{ $era }})@endif
                                </option>
                            @endfor
                        </select>
                        @error('birth_y')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-24">
                        <select name="birth_m" class="w-full border border-gray-300 rounded-md" required>
                            @for ($m = 1; $m <= 12; $m++) <option value="{{ $m }}" @selected((string) old('birth_m', $customer->birth_m ?? null) === (string) $m)>{{ $m }}月</option> @endfor
                        </select>
                        @error('birth_m')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-24">
                        <select name="birth_d" class="w-full border border-gray-300 rounded-md" required>
                            @for ($d = 1; $d <= 31; $d++) <option value="{{ $d }}" @selected((string) old('birth_d', $customer->birth_d ?? null) === (string) $d)>{{ $d }}日</option> @endfor
                        </select>
                        @error('birth_d')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 性別・職業 --}}
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">性別 <span class="text-red-500">必須</span></label>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="male" @checked(old('gender', $customer->gender ?? null) === 'male') required>
                                <span>男性</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="female" @checked(old('gender', $customer->gender ?? null) === 'female') required>
                                <span>女性</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="gender" value="other" @checked(old('gender', $customer->gender ?? null) === 'other') required>
                                <span>その他</span>
                            </label>
                        </div>
                        @error('gender')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">職業 <span class="text-red-500">必須</span></label>
                        <select name="occupation" class="w-full border border-gray-300 rounded-md" required>
                            <option value="">選択してください</option>
                            <option value="会社員" @selected(old('occupation', $customer->occupation ?? null) === '会社員')>会社員</option>
                            <option value="役員" @selected(old('occupation', $customer->occupation ?? null) === '役員')>役員</option>
                            <option value="個人事業主" @selected(old('occupation', $customer->occupation ?? null) === '個人事業主')>個人事業主</option>
                            <option value="パートアルバイト" @selected(old('occupation', $customer->occupation ?? null) === 'パートアルバイト')>パートアルバイト</option>
                            <option value="学生" @selected(old('occupation', $customer->occupation ?? null) === '学生')>学生</option>
                            <option value="定年退職" @selected(old('occupation', $customer->occupation ?? null) === '定年退職')>定年退職</option>
                            <option value="その他" @selected(old('occupation', $customer->occupation ?? null) === 'その他')>その他</option>
                        </select>
                        @error('occupation')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 住所 --}}
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/3 px-3">
                        <label class="block text-sm font-bold mb-1">郵便番号</label>
                        <input type="text" name="postal_code" placeholder="1234567" class="w-full border border-gray-300 rounded-md" value="{{ old('postal_code', $customer->postal_code ?? null) }}">
                        @error('postal_code')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3">
                        <label class="block text-sm font-bold mb-1">都道府県 <span class="text-red-500">必須</span></label>
                        <select name="prefecture" class="w-full border border-gray-300 rounded-md" required>
                            <option value="">選択</option>
                            @foreach(['北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県','茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県','新潟県','富山県','石川県','福井県','山梨県','長野県','岐阜県','静岡県','愛知県','三重県','滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県','鳥取県','島根県','岡山県','広島県','山口県','徳島県','香川県','愛媛県','高知県','福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県'] as $pref)
                                <option value="{{ $pref }}" @selected(old('prefecture', $customer->prefecture ?? null) === $pref)>{{ $pref }}</option>
                            @endforeach
                        </select>
                        @error('prefecture')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3">
                        <label class="block text-sm font-bold mb-1">市区町村 <span class="text-red-500">必須</span></label>
                        <input type="text" name="city" class="w-full border border-gray-300 rounded-md" value="{{ old('city', $customer->city ?? null) }}" required>
                        @error('city')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-2/3 px-3">
                        <label class="block text-sm font-bold mb-1">番地以降 <span class="text-red-500">必須</span></label>
                        <input type="text" name="address_detail" class="w-full border border-gray-300 rounded-md" value="{{ old('address_detail', $customer->address_detail ?? null) }}" required>
                        @error('address_detail')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/3 px-3">
                        <label class="block text-sm font-bold mb-1">建物名</label>
                        <input type="text" name="address_building" class="w-full border border-gray-300 rounded-md" value="{{ old('address_building', $customer->address_building ?? null) }}">
                        @error('address_building')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- 区分設定 --}}
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">買取区分 <span class="text-red-500">必須</span></label>
                        <select name="buy_type" class="w-full border border-gray-300 rounded-md" required>
                            <option value="店頭買取" @selected(old('buy_type', $deal->buy_type ?? '店頭買取') === '店頭買取')>店頭買取</option>
                            <option value="そのほか" @selected(old('buy_type', $deal->buy_type ?? null) === 'そのほか')>そのほか</option>
                        </select>
                        @error('buy_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">来店区分 <span class="text-red-500">必須</span></label>
                        <select name="arrival_type" class="w-full border border-gray-300 rounded-md" required>
                            <option value="">選択してください</option>
                            @foreach(['店舗前','折込','顧客','紹介','ホームページ','ポスティング','テレビ','情報誌','テレアポ','Googleマップ','呼び込み','電話問合せ','ティッシュ','LP','SNS','エキテン','DM','LINE査定','2次アポ','リスティング広告'] as $arrival)
                                <option value="{{ $arrival }}" @selected(old('arrival_type', $deal->arrival_type ?? '店舗前') === $arrival)>{{ $arrival }}</option>
                            @endforeach
                        </select>
                        @error('arrival_type')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">キャンペーン区分</label>
                        <select name="campaign_id" class="w-full border border-gray-300 rounded-md">
                            <option value="" @selected((string) old('campaign_id', $deal->campaign_id ?? '') === '')></option>
                            @foreach ($mastercampaigns ?? [] as $campaign)
                                <option value="{{ $campaign->id }}" @selected((string) old('campaign_id', $deal->campaign_id ?? null) === (string) $campaign->id)>
                                    {{ $campaign->campaign }}
                                </option>
                            @endforeach
                        </select>
                        @error('campaign_id')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full md:w-1/2 px-3">
                        <label class="block text-sm font-bold mb-1">お支払い方法</label>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="現金" @checked(old('payment_method', $deal->payment_method ?? '現金') === '現金')>
                                <span>現金</span>
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="payment_method" value="振込" @checked(old('payment_method', $deal->payment_method ?? null) === '振込')>
                                <span>振込</span>
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full px-3">
                        <label class="block text-sm font-bold mb-1">備考</label>
                        <textarea name="payment_remarks" rows="2" class="w-full border border-gray-300 rounded-md">{{ old('payment_remarks', $deal->payment_remarks ?? null) }}</textarea>
                        @error('payment_remarks')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-wrap -mx-3">
                    <div class="w-full px-3">
                        <label class="block text-sm font-bold mb-1">取引備考</label>
                        <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded-md">{{ old('remarks', $deal->remarks ?? null) }}</textarea>
                        @error('remarks')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-gray-800 space-y-4">
<h3 class="text-xl">承認サイン</h3>

            <p class="text-sm font-bold">下記の注意事項に同意の上サインをお願いします。<span class="text-red-500">（必須）</span></p>
            <div class="space-y-3 text-sm">
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="agree_received_amount" class="mt-1" @checked(old('agree_received_amount', $deal->agree_received_amount ?? false)) required>
                    <span>提示金額を受領しました。<span class="text-red-500">（必須）</span></span>
                </label>
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="agree_no_return" class="mt-1" @checked(old('agree_no_return', $deal->agree_no_return ?? false)) required>
                    <span>買取商品の返品は致しかねます。また、お売り頂いた商品が盗品・コピー品と判明した場合はご返金頂きます。<span class="text-red-500">（必須）</span></span>
                </label>
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="agree_privacy" class="mt-1" @checked(old('agree_privacy', $deal->agree_privacy ?? false)) required>
                    <span>個人情報取り扱いに関する表明の説明を受け、個人情報の取扱いについて同意します。<span class="text-red-500">（必須）</span></span>
                </label>
            </div>
            @error('agree_received_amount')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('agree_no_return')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('agree_privacy')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <div class="space-y-2 text-sm">
                <p class="font-bold">私（売主）は、消費税における適格請求書発行事業者ではありません。<span class="text-red-500">（どちらか必須）</span></p>
                <label class="flex items-center gap-2">
                    <input type="radio" name="invoice_issuer" value="適格請求書発行事業者ではありません" @checked(old('invoice_issuer', $deal->invoice_issuer ?? null) === '適格請求書発行事業者ではありません') required>
                    <span>私（売主）は、消費税における適格請求書発行事業者ではありません。<span class="text-red-500">（どちらか必須）</span></span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="radio" name="invoice_issuer" value="適格請求書発行事業者です" @checked(old('invoice_issuer', $deal->invoice_issuer ?? null) === '適格請求書発行事業者です') required>
                    <span>私（売主）は、消費税における適格請求書発行事業者です。<span class="text-red-500">（どちらか必須）</span></span>
                </label>
            </div>
            @error('invoice_issuer')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        <div class="mt-4">
            <p class="text-sm text-gray-600 mb-2">上記確認の上サインお願いします<span class="text-red-500">（必須）</span></p>
            <canvas id="sigCanvas" width="600" height="240" class="w-full max-w-xl h-48 border-2 border-gray-400 rounded-md bg-white shadow-sm"></canvas>
            <input type="hidden" name="signature_image_data" id="signature_image_data" value="{{ old('signature_image_data') }}">
            @error('signature_image_data')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-2">※署名後に送信すると画像として保存されます</p>
        </div>
<script src="https://cdn.jsdelivr.net/npm/signature_pad/dist/signature_pad.umd.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

        </div>

        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-gray-800 space-y-3 text-sm leading-relaxed">
            <h3 class="text-base font-bold">〈個人情報取り扱いに関する表明〉</h3>
            <p>買取専門店大吉(以下「当店」)は、法令等の定めにより許容されている場合、または取得の状況からみて利用目的が明らかな場合を除き、ご本人さまの同意を得ることなく下記の利用目的の達成を超えて、個人情報を取扱うことはありません。</p>
            <p>当店が収集する個人情報とは、個人情報の保護に関する法律第2条にいう個人情報にあたる情報をいいます。</p>

            <p class="font-bold">1、個人情報の利用目的ついて</p>
            <p>当店がお客様から収集した個人情報は、法令に基づく他、下記目的の必要な範囲で使用いたします。</p>
            <ul class="list-disc pl-5 space-y-1">
                <li>買取履歴の確認を行い、充実したサービスを行うため</li>
                <li>買取事業に関連する、新規取扱商品、注力商品、広告の検討資料とするため</li>
                <li>買取事業に関するアフターサービス、新規取扱商品・サービスに関する情報のお知らせを行うため</li>
                <li>郵送買取希望商品査定のお申し込みの確認や査定後返送商品のお届けをするため</li>
                <li>買取及び取引に関するお問い合わせ等に対する回答や確認のご連絡のため</li>
                <li>お客様が買取をご希望する商品等をお伺いするため</li>
                <li>買取・査定サービスを向上させるための分析を行うため</li>
                <li>お客様に合ったサービスを提供するため</li>
                <li>当店及びフランチャイズ本部(株式会社エンパワー)が取り扱う商品、サービスに関する提案、その他情報提供(カタログなどの送付を含みます)</li>
            </ul>

            <p class="font-bold">2、第三者ヘの情報の提供等</p>
            <p>当店は、上記利用目的達成のため、必要な範囲内で下記の事業者にお客さまの個人情報を提供ないし共同利用し、一部または全部の管理を委託する場合があります。なお、お客さまご本人より提供停止のお申し出があった場合は提供等を停止いたします。</p>
            <p>事業者：全国の買取専門店大吉及び株式会社エンパワー(大吉FC本部)</p>
            <p>提供等の方法：書面または口頭による直接提供、または電話による口頭提供、またはFAX、電子メールによる提供等</p>
            <p>提供等する情報の項目：上記目的に必要な範囲での、お客様の属性(氏名、住所、年齢、性別、職業等)、来店履歴(来店店舗履歴、過去の査定・買取に関する取引の履歴、電話・メール等による各種お申込みやお問い合わせの履歴、アンケート等に関する事項等)その他当店が買取、査定、問い合わせ、資料請求の申し込み、アンケート調査への回答、キャンペーン等への応募の申し込み等により収集する一切の個人情報</p>
            <p>情報の管理責任者：株式会社エンパワー</p>

            <p class="font-bold">3、個人情報の管理・保護について</p>
            <p>当店が収集したお客様の個人情報については、当店及び提供先においても適切な管理を行い、紛失・破壊・改ざん・不正アクセス・漏えい等の防止に努めます。</p>

            <p class="font-bold">4、個人情報の開示・訂正・削除について</p>
            <p>個人情報はできるだけ正確かつ最新の状態で管理するよう努めています。また、個人情報の開示・削除については、お客様ご自身が当社へご連絡ください。第三者への個人情報の漏えいを防ぐため、当該請求がお客様ご本人によるものであることが確認できた場合に限り対応いたします。</p>

            <p class="font-bold">5、お問い合わせ窓口について</p>
            <p>当店の個人情報の取扱に関するご意見・ご要望、苦情、その他のお問い合わせにつきましては、当店までお願いいたします。</p>

            <p class="font-bold">※出張買取時のクーリングオフのお知らせ</p>
            <ul class="list-disc pl-5 space-y-1">
                <li>訪問にてお売りいただいた場合、本書面を受領した日を含む8日間は、書面により無条件に本契約の申し込みの撤回を行うこと（以下「クーリングオフ」といいます。）ができます。</li>
                <li>クーリングオフ期間内は、商品をお客様の手元に残すことができます。</li>
                <li>クーリングオフの効力は、撤回又は解除の書面を発信した時（郵便消印日付）から生じます。</li>
                <li>ハガキ等に必要事項を記入の上ご郵送ください。簡易書留扱いがより確実です。</li>
            </ul>
        </div>

        {{-- 3. 署名・確認事項 --}}
        <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-gray-800 text-center">
             <button type="submit" class="bg-blue-600 text-white px-12 py-4 rounded-lg text-xl font-bold hover:bg-blue-700 transition shadow-xl">
                上記の内容で契約を修正する
            </button>
        </div>
    </form>
</div>


<style>
@keyframes pulseShadowRed {
  0%, 100% {
    box-shadow: 0 0 0 0 rgba(255, 0, 0, 0.45);
  }
  50% {
    box-shadow: 0 0 28px 12px rgba(255, 0, 0, 0.25);
  }
}

.animate-shadow-red {
  animation: pulseShadowRed 1.6s ease-in-out infinite;
}
</style>

<script>
    const initialItems = @json($initialItems ?? []);
    let itemIdx = 0; // 行を特定するためのカウンター

    function updateButtonState() {
        const container = document.getElementById('item-container');
        const count = container ? container.querySelectorAll('.item-row').length : 0;
        const addButton = document.getElementById('add-button');
        if (!addButton) {
            return;
        }
        const disabled = count >= 30;
        addButton.disabled = disabled;
        addButton.classList.toggle('opacity-50', disabled);
        addButton.classList.toggle('cursor-not-allowed', disabled);
    }

    function updateTotalPrice() {
        const container = document.getElementById('item-container');
        const totalDisplay = document.getElementById('total-price-display');
        const totalInput = document.getElementById('total-price-input');
        if (!container || !totalDisplay || !totalInput) {
            return;
        }
        let total = 0;
        container.querySelectorAll('.item-row').forEach((row) => {
            const priceInput = row.querySelector('input[name^="items"][name$="[buy_price]"]');
            const quantityInput = row.querySelector('input[name^="items"][name$="[quantity]"]');
            const price = Number(priceInput ? priceInput.value : 0);
            const quantity = Number(quantityInput ? quantityInput.value : 1);
            if (!Number.isNaN(price) && !Number.isNaN(quantity)) {
                total += price * quantity;
            }
        });
        totalDisplay.textContent = new Intl.NumberFormat('ja-JP').format(total);
        totalInput.value = total;
    }

    function addItem() {
        const container = document.getElementById('item-container');
        const count = container.querySelectorAll('.item-row').length;

        if (count >= 30) {
            alert("最大30件です");
            return;
        }

        // テンプレートを取得し、INDEXを現在のカウントに置換
        let html = document.getElementById('item-template').innerHTML;
        html = html.replace(/INDEX/g, itemIdx); 
        
        // コンテナに挿入
        container.insertAdjacentHTML('beforeend', html);
        
        // 挿入した行のフォーム要素を有効化
        const newRow = container.lastElementChild;
        newRow.querySelectorAll('input, select, textarea').forEach(el => el.disabled = false);

        itemIdx++;
        updateButtonState();
        updateTotalPrice();
    }

    function removeItem(button) {
        const row = button.closest('.item-row');
        if (row) {
            row.remove();
        }
        updateButtonState();
        updateTotalPrice();
    }

    window.addEventListener('load', () => {
        const container = document.getElementById('item-container');
        if (initialItems.length > 0) {
            initialItems.forEach((item) => {
                addItem();
                const row = container ? container.lastElementChild : null;
                if (!row) {
                    return;
                }
                const productInput = row.querySelector('input[name^="items"][name$="[product]"]');
                const classificationSelect = row.querySelector('select[name^="items"][name$="[classification]"]');
                const remarksInput = row.querySelector('textarea[name^="items"][name$="[remarks_2]"]');
                const quantityInput = row.querySelector('input[name^="items"][name$="[quantity]"]');
                const buyPriceInput = row.querySelector('input[name^="items"][name$="[buy_price]"]');

                if (productInput && item.product !== undefined) {
                    productInput.value = item.product ?? '';
                }
                if (classificationSelect && item.classification !== undefined) {
                    classificationSelect.value = item.classification ?? '';
                }
                if (remarksInput && item.remarks_2 !== undefined) {
                    remarksInput.value = item.remarks_2 ?? '';
                }
                if (quantityInput && item.quantity !== undefined) {
                    quantityInput.value = item.quantity ?? 1;
                }
                if (buyPriceInput && item.buy_price !== undefined) {
                    buyPriceInput.value = item.buy_price ?? '';
                }
            });
        } else {
            addItem();
        }
        updateTotalPrice();
    });

    document.addEventListener('input', (e) => {
        const target = e.target;
        if (target && target.matches('input[name^="items"][name$="[buy_price]"], input[name^="items"][name$="[quantity]"]')) {
            updateTotalPrice();
        }
    });
</script>

<script src="{{ asset('js/purchase.js') }}" charset="UTF-8"></script>

<script>

//
    function updateImagePreview(input, previewEl) {
        const file = input.files && input.files[0];
        if (!file || !previewEl) {
            return;
        }
        const reader = new FileReader();
        reader.onload = () => {
            previewEl.src = reader.result;
            previewEl.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    document.addEventListener('change', (e) => {
        const target = e.target;
        if (target && target.matches('input[type="file"][data-preview="product"]')) {
            const row = target.closest('.item-row');
            const preview = row ? row.querySelector('img[data-preview="product"]') : null;
            updateImagePreview(target, preview);
        }
    });

    const proofInput1 = document.querySelector('input[name="proof_img_1"]');
    const proofInput2 = document.querySelector('input[name="proof_img_2"]');
    const proofPreview1 = document.getElementById('proof_preview_1');
    const proofPreview2 = document.getElementById('proof_preview_2');

    if (proofInput1 && proofPreview1) {
        proofInput1.addEventListener('change', () => updateImagePreview(proofInput1, proofPreview1));
    }
    if (proofInput2 && proofPreview2) {
        proofInput2.addEventListener('change', () => updateImagePreview(proofInput2, proofPreview2));
    }
</script>

<script>
  const canvas = document.getElementById('sigCanvas');
  const signaturePad = new SignaturePad(canvas);
  const form = document.getElementById('purchase-form');
  const signatureInput = document.getElementById('signature_image_data');
  const oldSignature = @json(old('signature_image_data'));

  if (oldSignature) {
    signaturePad.fromDataURL(oldSignature);
    signatureInput.value = oldSignature;
  }

  form.addEventListener('submit', () => {
    signatureInput.value = signaturePad.isEmpty()
      ? ''
      : signaturePad.toDataURL('image/png');
  });
</script>


@endsection
