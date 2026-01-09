@extends('layouts.member')

@section('title', '入出金管理')
@section('content')
<div class="max-w-6xl mx-auto p-6 space-y-6 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">入出金管理</h2>
    <p class="text-sm text-gray-600">入出金の内容を入力して登録してください。</p>

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

    <form method="POST" action="{{ route('cash_management.register') }}" class="space-y-6">
        @csrf

        <div class="p-4 border rounded-lg bg-gray-50 text-sm space-y-6">
                <div>
                    <p class="text-sm font-semibold text-gray-700">区分</p>
                    <div class="mt-2 flex items-center gap-6">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="type" value="in" @checked(old('type', 'in') === 'in') required>
                            <span>入金</span>
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="type" value="out" @checked(old('type') === 'out') required>
                            <span>出金</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700">金額</label>
                    <input type="number" name="amount" id="amount" min="1" class="mt-2 w-full rounded-md border-gray-300 text-base shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('amount') }}" required>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700">内容</label>
                    <input type="text" name="description" id="description" class="mt-2 w-full rounded-md border-gray-300 text-base shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('description') }}" required>
                </div>

                <div>
                    <label for="remarks" class="block text-sm font-semibold text-gray-700">備考</label>
                    <input type="text" name="remarks" id="remarks" class="mt-2 w-full rounded-md border-gray-300 text-base shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('remarks') }}">
                </div>
            </div>

        <div class="flex justify-center">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded shadow">登録</button>
        </div>
    </form>
</div>
@endsection
