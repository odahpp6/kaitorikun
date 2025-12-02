@extends('layouts.member')

@section('title', '見積詳細')
@section('content')

<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h1 class="text-center text-2xl font-bold my-4">見積詳細</h1>
    
    <div class="mb-4 p-4 border rounded-lg bg-gray-50">
        <p><strong>タイトル:</strong> {{ $Estimate->title }}</p>
        <p><strong>登録日:</strong> {{ $Estimate->created_at->format('Y/m/d H:i') }}</p>
    </div>

    <table class="w-full border border-gray-300 text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-2">品目</th>
                <th class="border px-2 py-2">査定価格 (num1)</th>
                <th class="border px-2 py-2">数量 (num2)</th>
                <th class="border px-2 py-2">備考</th>
            </tr>
        </thead>
        <tbody>
            {{-- ★Eager Loadingで取得したリレーション（$Estimate->items）をループ --}}
            @foreach ($Estimate->items as $item)
            <tr class="hover:bg-gray-50">
                <td class="border px-2 py-1">{{ $item->text }}</td>
                {{-- 数値を3桁区切りで表示 --}}
                <td class="border px-2 py-1 text-right">{{ number_format($item->num1) }}</td>
                <td class="border px-2 py-1 text-right">{{ number_format($item->num2) }}</td>
                {{-- 備考欄の改行を有効化 (備考のデータは登録画面のv-modelで改行が\nとして保存されているはずです) --}}
                <td class="border px-2 py-1 whitespace-pre-wrap">{{ $item->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
        
        <tfoot>
             <tr class="bg-gray-100 font-bold">
                <td class="border px-2 py-2 text-right" colspan="3">調整金額</td>
                <td class="border px-2 py-2 text-right">{{ number_format($Estimate->adjustment) }}</td>
            </tr>
            <tr class="bg-blue-100 font-bold">
                <td class="border px-2 py-2 text-right" colspan="3">総合計</td>
                {{-- 明細の num1 の合計 + 調整金額 --}}
                <td class="border px-2 py-2 text-right">{{ number_format($Estimate->items->sum('num1') + $Estimate->adjustment) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="flex justify-center gap-4">
        <a href="/estimate/{{$Estimate->id}}/edit" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">更新</a> 
        <a href="{{ route('estimate.list') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded shadow">一覧に戻る</a>
        {{-- 必要に応じて印刷ボタンなどを追加 --}}
    </div>

</div>

@endsection

 
 



