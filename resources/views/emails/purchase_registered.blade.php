<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #111111;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        th, td {
            border: 1px solid #999999;
            padding: 6px 8px;
            vertical-align: top;
        }
        th {
            background: #f3f4f6;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
@php
    $customer = $deal->customer;
    $genderLabels = ['male' => '男性', 'female' => '女性', 'other' => 'その他'];
    $gender = $customer ? ($genderLabels[$customer->gender] ?? $customer->gender) : null;
    $birthDate = null;
    if ($customer && $customer->birth_y && $customer->birth_m && $customer->birth_d) {
        $birthDate = sprintf('%04d/%02d/%02d', $customer->birth_y, $customer->birth_m, $customer->birth_d);
    }
    $paymentMethod = $deal->payment_method ?: '—';
    $paymentRemarks = $deal->payment_remarks ?: '—';
    $dealRemarks = $deal->remarks ?: '—';
@endphp

<h1>買取契約詳細</h1>

<table>
    <tr>
        <th colspan="4">顧客情報</th>
    </tr>
    <tr>
        <th>顧客名</th>
        <td>{{ $customer->name ?? '—' }}</td>
        <th>フリガナ</th>
        <td>{{ $customer->furigana ?? '—' }}</td>
    </tr>
    <tr>
        <th>電話番号</th>
        <td>{{ $customer->phone_number ?? '—' }}</td>
        <th>Email</th>
        <td>{{ $customer->email ?? '—' }}</td>
    </tr>
    <tr>
        <th>生年月日</th>
        <td>{{ $birthDate ?? '—' }}</td>
        <th>性別</th>
        <td>{{ $gender ?? '—' }}</td>
    </tr>
    <tr>
        <th>住所</th>
        <td colspan="3">
            {{ $customer->postal_code ?? '—' }} {{ $customer->prefecture ?? '' }}{{ $customer->city ?? '' }}
            {{ $customer->address_detail ?? '' }} {{ $customer->address_building ?? '' }}
        </td>
    </tr>
</table>

<table>
    <tr>
        <th colspan="4">取引情報</th>
    </tr>
    <tr>
        <th>登録日時</th>
        <td>{{ $deal->created_at?->format('Y/m/d H:i') ?? '—' }}</td>
        <th>伝票番号</th>
        <td>{{ $deal->slip_number ?? '—' }}</td>
    </tr>
    <tr>
        <th>お支払い方法</th>
        <td>{{ $paymentMethod }}</td>
        <th>買取区分</th>
        <td>{{ $deal->buy_type ?? '—' }}</td>
    </tr>
    <tr>
        <th>備考</th>
        <td colspan="3">{{ $paymentRemarks }}</td>
    </tr>
    <tr>
        <th>来店区分</th>
        <td>{{ $deal->arrival_type ?? '—' }}</td>
        <th>適格請求書発行事業者</th>
        <td>{{ $deal->invoice_issuer ?? '—' }}</td>
    </tr>
    <tr>
        <th>取引備考</th>
        <td colspan="3">{{ $dealRemarks }}</td>
    </tr>
</table>

<table>
    <tr>
        <th>#</th>
        <th>商品名</th>
        <th>買取分類</th>
        <th>備考</th>
        <th class="text-right">個数</th>
        <th class="text-right">買取金額</th>
    </tr>
    @forelse ($deal->buyItems as $index => $item)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->product }}</td>
            <td>{{ $item->classification }}</td>
            <td>{!! nl2br(e($item->remarks_2)) !!}</td>
            <td class="text-right">{{ $item->quantity ?? 1 }}</td>
            <td class="text-right">{{ number_format($item->buy_price) }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6">商品が登録されていません</td>
        </tr>
    @endforelse
    <tr>
        <th colspan="5" class="text-right">合計金額</th>
        <td class="text-right">{{ $deal->total_price !== null ? number_format($deal->total_price) : '—' }}</td>
    </tr>
</table>
</body>
</html>
