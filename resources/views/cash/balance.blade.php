@extends('layouts.member')

@section('title', '見積詳細')
@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-slate-900">現金残高登録</h1>
        <p class="mt-2 text-sm text-slate-600">各金種の枚数を入力して登録してください。</p>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-md border border-rose-200 bg-rose-50 px-4 py-3 text-rose-800">
            <ul class="list-disc pl-5 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('cash_balance.register') }}" class="space-y-6">
        @csrf

        <div class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">種別</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">枚数</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="bill_10000" class="font-medium">1万円札</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="bill_10000" id="bill_10000" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('bill_10000', 0) }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="bill_5000" class="font-medium">5千円札</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="bill_5000" id="bill_5000" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('bill_5000', 0) }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="bill_1000" class="font-medium">千円札</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="bill_1000" id="bill_1000" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('bill_1000', 0) }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="coin_500" class="font-medium">500円玉</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="coin_500" id="coin_500" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('coin_500', 0) }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="coin_100" class="font-medium">100円玉</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="coin_100" id="coin_100" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('coin_100', 0) }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="coin_50" class="font-medium">50円玉</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="coin_50" id="coin_50" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('coin_50', 0) }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="coin_10" class="font-medium">10円玉</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="coin_10" id="coin_10" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('coin_10', 0) }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="coin_5" class="font-medium">5円玉</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="coin_5" id="coin_5" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('coin_5', 0) }}" required>
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-4 text-sm text-slate-700">
                        <label for="coin_1" class="font-medium">1円玉</label>
                    </td>
                    <td class="px-4 py-4">
                        <input type="number" name="coin_1" id="coin_1" class="w-full rounded-md border-slate-300 text-base shadow-sm focus:border-slate-500 focus:ring-slate-500" min="0" value="{{ old('coin_1', 0) }}" required>
                    </td>
                </tr>
                </tbody>
                <tfoot class="bg-slate-50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-700">合計</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900">
                        <span id="total-amount">0</span> 円
                    </th>
                </tr>
                </tfoot>
            </table>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="inline-flex items-center rounded-md bg-slate-900 px-6 py-2 text-base font-semibold text-white shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">登録</button>
        </div>
    </form>
</div>

<script>
    const fields = [
        { id: 'bill_10000', value: 10000 },
        { id: 'bill_5000', value: 5000 },
        { id: 'bill_1000', value: 1000 },
        { id: 'coin_500', value: 500 },
        { id: 'coin_100', value: 100 },
        { id: 'coin_50', value: 50 },
        { id: 'coin_10', value: 10 },
        { id: 'coin_5', value: 5 },
        { id: 'coin_1', value: 1 },
    ];

    const totalEl = document.getElementById('total-amount');

    const updateTotal = () => {
        const total = fields.reduce((sum, field) => {
            const input = document.getElementById(field.id);
            const count = Number(input?.value || 0);
            return sum + count * field.value;
        }, 0);
        totalEl.textContent = total.toLocaleString('ja-JP');
    };

    fields.forEach((field) => {
        const input = document.getElementById(field.id);
        if (!input) return;
        input.addEventListener('input', updateTotal);
        input.addEventListener('change', updateTotal);
    });

    updateTotal();
</script>
@endsection
