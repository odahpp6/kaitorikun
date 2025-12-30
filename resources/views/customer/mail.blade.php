@extends('layouts.member')

@section('title', '顧客メール送信')
@section('content')

<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-pink-500">顧客メール送信</h2>

    @if (session('status'))
        <div class="mb-4 rounded-md bg-green-50 border border-green-200 p-3 text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <form action="{{ route('customer.mail.send') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm text-gray-600 mb-1">件名</label>
            <input type="text" name="subject" value="{{ old('subject') }}"
                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">
            @error('subject')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">内容</label>
            <textarea name="body" rows="6"
                      class="w-full border-gray-300 rounded-md shadow-sm focus:ring-pink-500 focus:border-pink-500">{{ old('body') }}</textarea>
            @error('body')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="border rounded-md">
            <div class="flex items-center justify-between p-3 bg-gray-50 border-b">
                <p class="text-sm font-semibold text-gray-700">送信先一覧（メールアドレスがある顧客のみ）</p>
                <label class="text-sm text-gray-600 flex items-center gap-2">
                    <input type="checkbox" id="select-all" class="rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                    全て選択
                </label>
            </div>
            <div class="max-h-72 overflow-y-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="border-b px-3 py-2 text-center w-12">送信</th>
                            <th class="border-b px-3 py-2 text-left">顧客名</th>
                            <th class="border-b px-3 py-2 text-left">メールアドレス</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-pink-50 transition">
                                <td class="border-b px-3 py-2 text-center">
                                    <input type="checkbox" name="customers[]" value="{{ $customer->id }}" class="customer-checkbox rounded border-gray-300 text-pink-600 focus:ring-pink-500">
                                </td>
                                <td class="border-b px-3 py-2 font-medium">{{ $customer->name }}</td>
                                <td class="border-b px-3 py-2">{{ $customer->email }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-3 py-6 text-center text-gray-500">
                                    送信可能な顧客がいません。
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @error('customers')
            <p class="text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div class="pt-2">
            <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-md transition">
                選択した顧客へ送信
            </button>
        </div>
    </form>
</div>

<script>
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.customer-checkbox');

    selectAll?.addEventListener('change', (event) => {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = event.target.checked;
        });
    });
</script>

@endsection
