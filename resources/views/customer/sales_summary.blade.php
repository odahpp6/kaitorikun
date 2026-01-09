@extends('layouts.member')

@section('title', '売上管理')
@section('content')

<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-indigo-500">売上管理</h2>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-3 py-3 text-left">年度</th>
                    <th class="border px-3 py-3 text-left">月</th>
                    <th class="border px-3 py-3 text-right">買取金額</th>
                    <th class="border px-3 py-3 text-right">売上金額</th>
                    <th class="border px-3 py-3 text-right">粗利</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($summaries as $row)
                    <tr class="hover:bg-indigo-50 transition duration-150">
                        <td class="border px-3 py-2">{{ $row['year'] }}</td>
                        <td class="border px-3 py-2">{{ $row['month'] }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($row['purchase_total']) }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($row['sales_total']) }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($row['gross_profit']) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="border px-3 py-10 text-center text-gray-500">
                            該当データがありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
