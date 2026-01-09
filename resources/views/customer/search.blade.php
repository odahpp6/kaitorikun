@extends('layouts.member')

@section('title', '顧客管理')
@section('content')

<div class="max-w-6xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">顧客管理</h2>

    <form action="{{ route('customer.search') }}" method="GET" class="mb-8 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xs text-gray-500 mb-1">顧客名</label>
                <input type="text" name="name" value="{{ request('name') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="例：田中">
            </div>
            <div>
                <label class="block text-xs text-gray-500 mb-1">電話番号</label>
                <input type="text" name="phone_number" value="{{ request('phone_number') }}"
                       class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="例：09012345678">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                    検索
                </button>
                <a href="{{ route('customer.search') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-md transition duration-200 text-center">
                    クリア
                </a>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border px-3 py-3 text-left">最終来店日時</th>
                    <th class="border px-3 py-3 text-left">顧客名</th>
                    <th class="border px-3 py-3 text-left">電話番号</th>
                    <th class="border px-3 py-3 text-left">住所</th>
                    <th class="border px-3 py-3 text-right">来店回数</th>
                    <th class="border px-3 py-3 text-center">詳細</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $customer)
                <tr class="hover:bg-blue-50 transition duration-150">
                    <td class="border px-3 py-2 text-gray-600">
                        @if ($customer->last_visit_at)
                            {{ \Illuminate\Support\Carbon::parse($customer->last_visit_at)->format('Y/m/d H:i') }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="border px-3 py-2 font-bold">{{ $customer->name }}</td>
                    <td class="border px-3 py-2">{{ $customer->phone_number }}</td>
                    <td class="border px-3 py-2">
                        {{ $customer->prefecture }}{{ $customer->city }}{{ $customer->address_detail }}{{ $customer->address_building }}
                    </td>
                    <td class="border px-3 py-2 text-right">{{ $customer->deals_count }}</td>
                    <td class="border px-3 py-2 text-center">
                        @php
                            $dealIds = $customer->deal_ids ? explode(',', $customer->deal_ids) : [];
                            $slipNumbers = $customer->slip_numbers ? explode(',', $customer->slip_numbers) : [];
                            $dealCount = min(count($dealIds), count($slipNumbers));
                        @endphp
                        @if ($dealCount === 0)
                            <span class="text-gray-400">—</span>
                        @else
                            @for ($i = 0; $i < $dealCount; $i++)
                                <a href="{{ route('purchase.detail', $dealIds[$i]) }}" class="text-blue-600 hover:underline">
                                    {{ $slipNumbers[$i] }}
                                </a>
                                @if ($i < $dealCount - 1)
                                    <span class="text-gray-300">/</span>
                                @endif
                            @endfor
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="border px-3 py-10 text-center text-gray-500">
                        該当する顧客データが見つかりませんでした。
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $customers->appends(request()->query())->links() }}
    </div>
</div>

@endsection
