<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <style>
        @font-face {
            font-family: 'ipaexg';
            font-style: normal;
            font-weight: normal;
            src: url('{{ public_path('ipaexg.ttf') }}') format('truetype');
        }
        @font-face {
            font-family: 'ipaexg';
            font-style: normal;
            font-weight: bold;
            src: url('{{ public_path('ipaexg.ttf') }}') format('truetype');
        }
        html, body, table, th, td, div, span, p, h1, h2, h3, h4, h5, h6 {
            font-family: 'ipaexg', sans-serif;
        }
        body {
            font-size: 10px;
            line-height: 1.3;
        }
        h1 {
            font-size: 16px;
            text-align: center;
            margin: 0 0 8px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }
        th {
            background: #f2f2f2;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
<h1>見積書</h1>

<table>
    <tr>
        <th>タイトル</th>
        <td>{{ $Estimate->title }}</td>
        <th>登録日</th>
        <td>{{ $Estimate->created_at->format('Y/m/d H:i') }}</td>
    </tr>
</table>

<table>
    <tr>
        <th class="text-center">品目</th>
        <th class="text-right">査定価格</th>
        <th class="text-right">数量</th>
        <th>備考</th>
    </tr>
    @foreach ($Estimate->items as $item)
        <tr>
            <td>{{ $item->text }}</td>
            <td class="text-right">{{ number_format($item->num1) }}</td>
            <td class="text-right">{{ number_format($item->num2) }}</td>
            <td class="text-right">{{ $item->remarks }}</td>
        </tr>
    @endforeach
    <tr>
        <th class="text-right" colspan="3">調整金額</th>
        <td class="text-right">{{ number_format($Estimate->adjustment) }}</td>
    </tr>
    <tr>
        <th class="text-right" colspan="3">総合計</th>
        <td class="text-right">{{ number_format($Estimate->items->sum('num1') + $Estimate->adjustment) }}</td>
    </tr>
</table>
</body>
</html>
