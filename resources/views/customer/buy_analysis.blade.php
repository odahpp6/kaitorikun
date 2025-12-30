@extends('layouts.member')

@section('title', '買取上分析')
@section('content')

<div class="max-w-6xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-emerald-500">買取上分析</h2>

    <form action="{{ route('customer.buy_analysis') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">集計開始日</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">集計終了日</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md transition duration-200">
                    集計
                </button>
                <a href="{{ route('customer.buy_analysis') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 text-center">
                    クリア
                </a>
            </div>
        </div>
    </form>

    <div class="mb-10">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">来店区分比率</h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border px-3 py-3 text-left">来店区分</th>
                        <th class="border px-3 py-3 text-right">件数</th>
                        <th class="border px-3 py-3 text-right">比率(%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($arrivalStats as $row)
                        <tr class="hover:bg-emerald-50 transition duration-150">
                            <td class="border px-3 py-2">{{ $row['label'] }}</td>
                            <td class="border px-3 py-2 text-right">{{ number_format($row['count']) }}</td>
                            <td class="border px-3 py-2 text-right">{{ number_format($row['percent'], 1) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border px-3 py-10 text-center text-gray-500">
                                該当データがありません。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <th class="border px-3 py-2 text-left">合計</th>
                        <th class="border px-3 py-2 text-right">{{ number_format($arrivalTotal) }}</th>
                        <th class="border px-3 py-2 text-right">{{ $arrivalTotal > 0 ? '100.0' : '0.0' }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div>
        <h3 class="text-lg font-semibold text-gray-700 mb-3">買取分類比率</h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border px-3 py-3 text-left">分類</th>
                        <th class="border px-3 py-3 text-right">件数</th>
                        <th class="border px-3 py-3 text-right">比率(%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classificationStats as $row)
                        <tr class="hover:bg-emerald-50 transition duration-150">
                            <td class="border px-3 py-2">{{ $row['label'] }}</td>
                            <td class="border px-3 py-2 text-right">{{ number_format($row['count']) }}</td>
                            <td class="border px-3 py-2 text-right">{{ number_format($row['percent'], 1) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border px-3 py-10 text-center text-gray-500">
                                該当データがありません。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <th class="border px-3 py-2 text-left">合計</th>
                        <th class="border px-3 py-2 text-right">{{ number_format($classificationTotal) }}</th>
                        <th class="border px-3 py-2 text-right">{{ $classificationTotal > 0 ? '100.0' : '0.0' }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-10">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">チラシキャンペーン分析</h3>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300 text-sm">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="border px-3 py-3 text-left">キャンペーン区分</th>
                        <th class="border px-3 py-3 text-right">件数</th>
                        <th class="border px-3 py-3 text-right">比率(%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($campaignStats as $row)
                        <tr class="hover:bg-emerald-50 transition duration-150">
                            <td class="border px-3 py-2">{{ $row['label'] }}</td>
                            <td class="border px-3 py-2 text-right">{{ number_format($row['count']) }}</td>
                            <td class="border px-3 py-2 text-right">{{ number_format($row['percent'], 1) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="border px-3 py-10 text-center text-gray-500">
                                該当データがありません。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50">
                        <th class="border px-3 py-2 text-left">合計</th>
                        <th class="border px-3 py-2 text-right">{{ number_format($campaignTotal) }}</th>
                        <th class="border px-3 py-2 text-right">{{ $campaignTotal > 0 ? '100.0' : '0.0' }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@endsection
