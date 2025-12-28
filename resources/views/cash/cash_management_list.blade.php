@extends('layouts.member')

@section('title', '現金出納帳一覧')
@section('content')

<div class="max-w-6xl mx-auto p-6 space-y-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">現金出納帳</h2>

    <table class="w-full border border-gray-300 text-sm">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-2">更新日付</th>
                <th class="border px-2 py-2">入金</th>
                <th class="border px-2 py-2">出金</th>
                <th class="border px-2 py-2">残高</th>
                <th class="border px-2 py-2">差異</th>
                <th class="border px-2 py-2">明細種類</th>
                <th class="border px-2 py-2">相手先</th>
                <th class="border px-2 py-2">内容</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr class="hover:bg-gray-50">
                    <td class="border px-2 py-2">{{ $row['updated_at']?->format('Y/m/d H:i') ?? '—' }}</td>
                    <td class="border px-2 py-2 text-right">{{ $row['in'] !== null ? number_format($row['in']) : '—' }}</td>
                    <td class="border px-2 py-2 text-right">{{ $row['out'] !== null ? number_format($row['out']) : '—' }}</td>
                    <td class="border px-2 py-2 text-right">{{ $row['balance'] !== null ? number_format($row['balance']) : '—' }}</td>
                    <td class="border px-2 py-2 text-right {{ ($row['difference'] ?? null) !== null && $row['difference'] !== 0 ? 'text-rose-600 font-semibold' : '' }}">
                        {{ $row['difference'] !== null ? number_format($row['difference']) : '—' }}
                    </td>
                    <td class="border px-2 py-2">{{ $row['detail_type'] ?? '—' }}</td>
                    <td class="border px-2 py-2">{{ $row['counterparty'] ?? '—' }}</td>
                    <td class="border px-2 py-2 whitespace-pre-wrap">{{ $row['content'] ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td class="border px-2 py-3 text-center text-gray-500" colspan="8">データがありません。</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
