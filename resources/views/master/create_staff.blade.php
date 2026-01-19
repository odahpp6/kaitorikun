@extends('layouts.member')

@section('title', 'スタッフ登録')
@section('content')
<form  action="{{ route('master.store_staff') }}" ref="formEl" method="POST">

@csrf


<h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">スタッフ登録</h2>
<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/3">
    <p class="text-sm text-stone-600 mb-2">スタッフ名<span class="text-red-600 font-medium">※必須</span></p>
    <input type="text"  class=" border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="スタッフ名" name="staff_name">
     @error('staff_name')
          <p class="error text-red-600"><span>{{ $message }}</span></p>
    @enderror
 </div>
  <div class="w-full md:w-1/3">
    <p class="text-sm text-stone-600 mb-2">役職</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="役職" name="position">
     @error('position')
          <p class="error text-red-600"><span>{{ $message }}</span></p>
    @enderror
 </div>
  <div class="w-full md:w-1/3">
    <p class="text-sm text-stone-600 mb-2">備考</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="備考" name="remarks">
     @error('remarks')
          <p class="error text-red-600"><span>{{ $message }}</span></p>
    @enderror
 </div>
</div>


<input type="submit" value="登録" v-bind:class="message.buttunClass"  class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>



<h2 class="text-2xl font-bold text-gray-800 mb-6 mt-8 pb-2 border-b-2 border-blue-500">スタッフ登録一覧表示</h2>
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
                <th class="border px-2 py-2">役職</th>
                <th class="border px-2 py-2">備考</th>
                <th class="border px-2 py-2">削除</th>
            </tr>
        </thead>
        <tbody>
          
            @foreach ($staffs as $staff)
            <tr class="hover:bg-gray-50">
                <td class="border px-2 py-1">{{ $staff->staff_name }}</td>
                    <td class="border px-2 py-1">{{ $staff->created_at }}</td>
                    <td class="border px-2 py-1">{{ $staff->position }}</td>
                <td class="border px-2 py-1 whitespace-pre-wrap">{{ $staff->remarks }}</td>
                <td class="border px-2 py-1 whitespace-pre-wrap">
                  <a href="/master/{{ $staff->id }}/delete_confirm" class="text-blue-600 hover:text-blue-700">削除</a></td>
              </tr>
            @endforeach
        </tbody>
      
    </table>

@endsection