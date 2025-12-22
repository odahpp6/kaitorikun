@extends('layouts.member')

@section('title', '見積詳細')
@section('content')
@php
    $customer = $deal->customer;
    $genderLabels = ['male' => '男性', 'female' => '女性', 'other' => 'その他'];
    $gender = $customer ? ($genderLabels[$customer->gender] ?? $customer->gender) : null;
    $birthDate = null;
    if ($customer && $customer->birth_y && $customer->birth_m && $customer->birth_d) {
        $birthDate = sprintf('%04d/%02d/%02d', $customer->birth_y, $customer->birth_m, $customer->birth_d);
    }
    $paymentMethod = $deal->payment_method ?: '—';
@endphp

<div class="max-w-6xl mx-auto p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取契約詳細</h2>
    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-blue-500">
        <h2 class="text-xl font-bold mb-6 flex items-center">
            <span class="bg-blue-500 text-white rounded-full w-8 h-8 flex items-center justify-center mr-2 text-sm">1</span>
            顧客情報（親）
        </h2>

        <div class="space-y-6 text-sm">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">身分証明書種類</p>
                    <p class="font-semibold">{{ $customer->proof_type ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">身分証明書番号</p>
                    <p class="font-semibold">{{ $customer->proof_num ?? '—' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">身分証明書画像（表面）</p>
                    @if (!empty($customer->proof_img_1))
                        <img src="{{ asset('storage/' . $customer->proof_img_1) }}" alt="身分証明書画像（表面）" class="mt-2 w-full h-28 object-contain border border-gray-200 rounded">
                    @else
                        <p class="text-gray-500">未登録</p>
                    @endif
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">身分証明書画像（裏面）</p>
                    @if (!empty($customer->proof_img_2))
                        <img src="{{ asset('storage/' . $customer->proof_img_2) }}" alt="身分証明書画像（裏面）" class="mt-2 w-full h-28 object-contain border border-gray-200 rounded">
                    @else
                        <p class="text-gray-500">未登録</p>
                    @endif
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">顧客名</p>
                    <p class="font-semibold">{{ $customer->name ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">フリガナ</p>
                    <p class="font-semibold">{{ $customer->furigana ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">電話番号</p>
                    <p class="font-semibold">{{ $customer->phone_number ?? '—' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">生年月日(西暦)</p>
                    <p class="font-semibold">{{ $birthDate ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">性別</p>
                    <p class="font-semibold">{{ $gender ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">職業</p>
                    <p class="font-semibold">{{ $customer->occupation ?? '—' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">郵便番号</p>
                    <p class="font-semibold">{{ $customer->postal_code ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">都道府県</p>
                    <p class="font-semibold">{{ $customer->prefecture ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">市区町村</p>
                    <p class="font-semibold">{{ $customer->city ?? '—' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-2/3 px-3">
                    <p class="text-xs text-gray-500">番地以降</p>
                    <p class="font-semibold">{{ $customer->address_detail ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">建物名</p>
                    <p class="font-semibold">{{ $customer->address_building ?? '—' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-gray-800">
        <h2 class="text-xl font-bold mb-6 flex items-center">
            <span class="bg-gray-800 text-white rounded-full w-8 h-8 flex items-center justify-center mr-2 text-sm">2</span>
            取引情報
        </h2>

        <div class="space-y-6 text-sm">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">登録日時</p>
                    <p class="font-semibold">{{ $deal->created_at?->format('Y/m/d H:i') ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">伝票番号</p>
                    <p class="font-semibold">{{ $deal->slip_number ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">合計金額</p>
                    <p class="font-semibold">{{ $deal->total_price !== null ? number_format($deal->total_price) : '—' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">買取区分</p>
                    <p class="font-semibold">{{ $deal->buy_type ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">来店区分</p>
                    <p class="font-semibold">{{ $deal->arrival_type ?? '—' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">キャンペーン区分</p>
                    <p class="font-semibold">{{ $deal->campaign_id ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">お支払い方法</p>
                    <p class="font-semibold">{{ $paymentMethod }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-gray-800">
        <h2 class="text-xl font-bold mb-6 flex items-center">
            <span class="bg-gray-800 text-white rounded-full w-8 h-8 flex items-center justify-center mr-2 text-sm">3</span>
            商品情報
        </h2>

        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-2 py-2">#</th>
                        <th class="border px-2 py-2">商品画像</th>
                        <th class="border px-2 py-2">商品名</th>
                        <th class="border px-2 py-2">買取分類</th>
                        <th class="border px-2 py-2">備考</th>
                        <th class="border px-2 py-2">個数</th>
                        <th class="border px-2 py-2">買取金額</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deal->buyItems as $index => $item)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-2 py-1 text-center">{{ $index + 1 }}</td>
                            <td class="border px-2 py-1">
                                @if (!empty($item->product_img))
                                    <img src="{{ asset('storage/' . $item->product_img) }}" alt="商品画像" class="w-24 h-20 object-contain border border-gray-200 rounded">
                                @else
                                    <span class="text-gray-500">未登録</span>
                                @endif
                            </td>
                            <td class="border px-2 py-1">{{ $item->product }}</td>
                            <td class="border px-2 py-1">{{ $item->classification }}</td>
                            <td class="border px-2 py-1 whitespace-pre-wrap">{!! nl2br(e($item->remarks_2)) !!}</td>
                            <td class="border px-2 py-1 text-right">{{ $item->quantity ?? 1 }}</td>
                            <td class="border px-2 py-1 text-right">{{ number_format($item->buy_price) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="border px-2 py-4 text-center text-gray-500" colspan="7">商品が登録されていません</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md border-t-4 border-gray-800">
        <h2 class="text-xl font-bold mb-6 flex items-center">
            <span class="bg-gray-800 text-white rounded-full w-8 h-8 flex items-center justify-center mr-2 text-sm">4</span>
            同意・署名
        </h2>

        <div class="space-y-4 text-sm">
            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">提示金額受領</p>
                    <p class="font-semibold">{{ $deal->agree_received_amount ? '同意' : '未同意' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">返品不可同意</p>
                    <p class="font-semibold">{{ $deal->agree_no_return ? '同意' : '未同意' }}</p>
                </div>
                <div class="w-full md:w-1/3 px-3">
                    <p class="text-xs text-gray-500">個人情報同意</p>
                    <p class="font-semibold">{{ $deal->agree_privacy ? '同意' : '未同意' }}</p>
                </div>
            </div>

            <div class="flex flex-wrap -mx-3">
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">適格請求書発行事業者</p>
                    <p class="font-semibold">{{ $deal->invoice_issuer ?? '—' }}</p>
                </div>
                <div class="w-full md:w-1/2 px-3">
                    <p class="text-xs text-gray-500">署名画像</p>
                    @if (!empty($deal->signature_image_data))
                        <img src="{{ asset('storage/' . $deal->signature_image_data) }}" alt="署名" class="mt-2 w-full max-w-xl h-40 object-contain border border-gray-200 rounded">
                    @else
                        <p class="text-gray-500">未登録</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
