@extends('layouts.member')

@section('title', '見積一覧')
@section('content')






<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">
  <h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">買取契約一覧</h2>

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
  <table class="w-full border border-gray-300 text-sm mb-4">
    <thead>
    <tr class="bg-gray-100">
      <th class="border px-2 py-2">登録日時</th>
      <th class="border px-2 py-2">伝票番号</th>
      <th class="border px-2 py-2">顧客名</th>
      <th class="border px-2 py-2">商品名</th>
      <th class="border px-2 py-2">金額</th>
      <th class="border px-2 py-2">更新</th>
      <th class="border px-2 py-2">削除</th>
     </tr>
    </thead>
    <tbody>


   @foreach ($deals as $deal)
    <tr class="hover:bg-gray-50">
    
      <td class="border px-2 py-1">
        <a href="/purchase/{{$deal->id}}/detail" class="text-blue-600 hover:underline">{{$deal->created_at}}</a>
      </td>
      <td class="border px-2 py-1">
        {{ $deal->slip_number ?? '—' }}
      </td>
      <td class="border px-2 py-1">
        {{ $deal->customer->name }}
      </td>
      <td class="border px-2 py-1">
      {{$deal->buyItems->first()->product ?? '商品なし'}}
      </td>
  <td class="border px-2 py-1">
      {{$deal->total_price}} 円
      </td>
      <td class="border px-2 py-1">
        <a href="/purchase/{{$deal->id}}/edit" class="text-blue-600 hover:underline">更新</a>
      </td>
      <td class="border px-2 py-1">
        <a href="/purchase/{{$deal->id}}/delete_confirm" class="text-blue-600 hover:underline">削除</a>
      </td>
    </tr>
    @endforeach
            </tbody>
          </table>
       
  </div>





@endsection
