@extends('layouts.member')

@section('title', 'スタッフ削除確認')
@section('content')

<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">スタッフ削除確認</h1>
    
    {{-- コントローラーから渡された単一のレコード情報 ($staff) を表示 --}}
    <div class="mb-4 p-4 border rounded-lg bg-gray-50">
        <p><strong>ID:</strong> {{ $staff->id }}</p>
        <p><strong>スタッフ:</strong> {{ $staff->staff_name }}</p>
        <p><strong>備考:</strong> {{ $staff->remarks }}</p>
        <p><strong>登録日:</strong> {{ $staff->created_at->format('Y/m/d H:i') }}</p>
    </div>

    {{-- 削除フォーム: master.delete_staff ルートをDELETEメソッドで呼び出す --}}
    <div class="flex justify-center gap-4">
        
        <form action="{{ route('master.delete_staff', ['id' => $staff->id]) }}" method="POST">
            @csrf
            @method('DELETE') {{-- 削除処理はDELETEメソッドを使用 --}}

            <button type="submit" 
                    class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors duration-200">
                このスタッフを削除する
            </button>
        </form>

        {{-- キャンセルボタン (一覧に戻る) --}}
        <a href="{{ route('master.store_staff') }}" class="bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-400 transition-colors duration-200">
            キャンセルして戻る
        </a>
    </div>
</div>

@endsection