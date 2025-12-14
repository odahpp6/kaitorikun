@extends('layouts.member')

@section('title', '卸売り先マスター登録')
@section('content')
<form  action="{{ route('master.store_wholesale') }}" ref="formEl" method="POST">

@csrf


<h2>卸売り先登録</h2>
<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">卸売り会社名<span class="text-red-600 font-medium">※必須</span></p>
    <input type="text"  class=" border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="卸売り会社名" name="wholesale">
    <p></p>
 </div>
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600 mb-2">備考</p>
    <input type="tel" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="0312345678" name="remarks">
 </div>
</div>


<input type="submit" value="登録" v-bind:class="message.buttunClass"  class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>



<h2 class="my-4 text-2xl">卸売り登録一覧表示</h2>
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
   <table class="w-full border border-gray-300 text-sm mb-4 mt-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-2">名称</th>
                <th class="border px-2 py-2">更新日</th>
                <th class="border px-2 py-2">備考</th>
                <th class="border px-2 py-2">削除</th>
            </tr>
        </thead>
        <tbody>
          
            @foreach ($wholesales as $wholesale)
            <tr class="hover:bg-gray-50">
                <td class="border px-2 py-1">{{ $wholesale->wholesale }}</td>
                    <td class="border px-2 py-1">{{ $wholesale->created_at }}</td>
                <td class="border px-2 py-1 whitespace-pre-wrap">{{ $wholesale->remarks }}</td>
                <td class="border px-2 py-1 whitespace-pre-wrap"><a href="/master/list_wholesale/{{ $wholesale->id }}/delete">削除</a></td>
            </tr>
            @endforeach
        </tbody>
      
    </table>

@endsection