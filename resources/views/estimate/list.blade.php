@extends('layouts.member')

@section('title', '見積履歴')
@section('content')






<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">見積履歴</h2>

   <!-- 成功メッセージ -->
             @if (session('success'))
    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <i class="fa-solid fa-check-circle text-green-400"></i>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-green-800">
            {{ session('success') }}
          </h3>
        </div>
      </div>
    </div>
  @endif

  <form action="{{ route('estimate.list') }}" method="GET" class="mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div>
        <label class="block text-sm font-bold mb-1">タイトル名</label>
        <input type="text" name="title" class="w-full border border-gray-300 rounded-md px-3 py-3" value="{{ request('title') }}">
      </div>
      <div>
        <label class="block text-sm font-bold mb-1">日付（開始）</label>
        <input type="date" name="date_from" class="w-full border border-gray-300 rounded-md px-3 py-3" value="{{ request('date_from') }}">
      </div>
      <div>
        <label class="block text-sm font-bold mb-1">日付（終了）</label>
        <input type="date" name="date_to" class="w-full border border-gray-300 rounded-md px-3 py-3" value="{{ request('date_to') }}">
      </div>
      <div>
        <label class="block text-sm font-bold mb-1">買取品目</label>
        <input type="text" name="item_text" class="w-full border border-gray-300 rounded-md px-3 py-3" value="{{ request('item_text') }}">
      </div>
    </div>
    <div class="mt-4 flex flex-wrap gap-3">
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
        検索
      </button>
      <a href="{{ route('estimate.list') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-lg transition duration-200">
        クリア
      </a>
    </div>
  </form>

  <table class="w-full border border-gray-300 text-sm mb-4">
    <thead>
    <tr class="bg-gray-100">
      <th class="border px-2 py-2">見積登録日</th>
      <th class="border px-2 py-2">タイトル</th>
      <th class="border px-2 py-2">削除</th>
     </tr>
    </thead>
    <tbody>


   @foreach ($Estimates as $Estimate)
    <tr class="hover:bg-gray-50">
    
      <td class="border px-2 py-1">
        <a href="/estimate/{{$Estimate->id}}/detail" class="text-blue-600 hover:underline">{{$Estimate->created_at}}</a>
    
      </td>
      <td class="border px-2 py-1">
      {{$Estimate->title}}
      </td>
      <td class="border px-2 py-1">
     <a href="/estimate/{{$Estimate->id}}/delete_confirm"class="text-blue-600 hover:underline">削除</a>
      </td>
    </tr>
    @endforeach
            </tbody>
  </table>

  <div class="mt-4">
    {{ $Estimates->appends(request()->query())->links() }}
  </div>
       
  </div>





@endsection
