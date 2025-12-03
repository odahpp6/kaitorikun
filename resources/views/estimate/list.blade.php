@extends('layouts.member')

@section('title', '見積一覧')
@section('content')






<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">

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
       
  </div>





@endsection