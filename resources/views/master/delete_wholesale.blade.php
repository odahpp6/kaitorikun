@extends('layouts.member')

@section('title', '卸売り先削除確認')
@section('content')

<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h1 class="text-center text-2xl font-bold my-4">卸売り先削除確認</h1>
    
    {{-- コントローラーから渡された単一のレコード情報 ($wholesale) を表示 --}}
    <div class="mb-4 p-4 border rounded-lg bg-gray-50">
        <p><strong>ID:</strong> {{ $wholesale->id }}</p>
        <p><strong>卸売り会社名:</strong> {{ $wholesale->wholesale }}</p>
        <p><strong>備考:</strong> {{ $wholesale->remarks }}</p>
        <p><strong>登録日:</strong> {{ $wholesale->created_at->format('Y/m/d H:i') }}</p>
    </div>

    {{-- 削除フォーム: master.delete_wholesale ルートをDELETEメソッドで呼び出す --}}
    <div class="flex justify-center gap-4">
        
        <form action="{{ route('master.delete_wholesale', ['id' => $wholesale->id]) }}" method="POST">
            @csrf
            @method('DELETE') {{-- 削除処理はDELETEメソッドを使用 --}}

            <button type="submit" 
                    class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition-colors duration-200">
                この卸売り先を削除する
            </button>
        </form>

        {{-- キャンセルボタン (一覧に戻る) --}}
        <a href="{{ route('master.list_wholesale') }}" class="bg-gray-300 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-400 transition-colors duration-200">
            キャンセルして一覧に戻る
        </a>
    </div>
</div>

@endsection