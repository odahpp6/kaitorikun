@extends('layouts.member')

@section('title', 'チラシ効果分析')
@section('content')

<div class="max-w-6xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-emerald-500">チラシ効果分析</h2>

    <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-3">チラシキャンペーン分析</h3>
        <div class="mb-4 h-64">
            <canvas id="campaignChart"></canvas>
        </div>
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

    <form action="{{ route('customer.flyer_analysis') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">契約日時(開始)</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">契約日時(終了)</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">キャンペーン区分</label>
                <select name="campaign_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">すべて</option>
                    @foreach ($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" @selected((string) request('campaign_id') === (string) $campaign->id)>
                            {{ $campaign->campaign }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-md transition duration-200">
                    集計
                </button>
                <a href="{{ route('customer.flyer_analysis') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 text-center">
                    クリア
                </a>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-3 py-3 text-left">キャンペーン区分</th>
                    <th class="border px-3 py-3 text-left">契約日時</th>
                    <th class="border px-3 py-3 text-left">伝票番号</th>
                    <th class="border px-3 py-3 text-left">顧客名</th>
                    <th class="border px-3 py-3 text-left">来店区分</th>
                    <th class="border px-3 py-3 text-left">商品区分</th>
                    <th class="border px-3 py-3 text-left">商品名</th>
                    <th class="border px-3 py-3 text-right">買取金額</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($flyerItems as $item)
                    <tr class="hover:bg-emerald-50 transition duration-150">
                        <td class="border px-3 py-2">{{ $item->campaign ?? '—' }}</td>
                        <td class="border px-3 py-2">
                            {{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d H:i') }}
                        </td>
                        <td class="border px-3 py-2 font-mono text-xs">{{ $item->slip_number ?? '—' }}</td>
                        <td class="border px-3 py-2">{{ $item->name }}</td>
                        <td class="border px-3 py-2">{{ $item->arrival_type ?? '—' }}</td>
                        <td class="border px-3 py-2">{{ $item->classification }}</td>
                        <td class="border px-3 py-2">{{ $item->product }}</td>
                        <td class="border px-3 py-2 text-right">{{ number_format($item->buy_price) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="border px-3 py-10 text-center text-gray-500">
                            該当データがありません。
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    const campaignLabels = @json($campaignStats->pluck('label'));
    const campaignCounts = @json($campaignStats->pluck('count'));

    const chartColors = [
        '#0f766e', '#14b8a6', '#22c55e', '#84cc16', '#eab308',
        '#f97316', '#f59e0b', '#ef4444', '#ec4899', '#8b5cf6',
        '#3b82f6', '#06b6d4'
    ];

    const buildPieChart = (canvasId, labels, data) => {
        const ctx = document.getElementById(canvasId);
        if (!ctx || !labels.length) return;
        return new Chart(ctx, {
            type: 'pie',
            data: {
                labels,
                datasets: [{
                    data,
                    backgroundColor: labels.map((_, i) => chartColors[i % chartColors.length]),
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 14 }
                    }
                }
            }
        });
    };

    buildPieChart('campaignChart', campaignLabels, campaignCounts);
</script>

@endsection
