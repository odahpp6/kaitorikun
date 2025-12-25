@extends('layouts.member')

@section('title', 'ダッシュボード')
@section('content')

<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">
    <h1 class="text-center text-2xl font-bold my-4">ダッシュボード</h1>
<h2 class="text-xl">今月の売り上と買取金額</h2>


<div class="grid grid-cols-1 md:grid-cols-2 gap-4 my-4">
    <div class="p-4 bg-blue-100 rounded-lg text-center">
        <h3 class="text-lg font-semibold mb-2">売上金額</h3>
        <p class="text-2xl font-bold text-blue-600"></p>
    </div>
    <div class="p-4 bg-green-100 rounded-lg text-center">
        <h3 class="text-lg font-semibold mb-2">買取金額</h3>
        <p class="text-2xl font-bold text-green-600"></p>
    </div>
</div>

<h2 class="text-2xl">メニュー</h2>
<div class="flex flex-wrap mt-4">
  <div class="md:w-1/4 w-full p-2">
    <a href="" class="text-blue rounded-lg bg-blue-100 p-4 block mb-4 text-center">
      <i class="fa-solid fa-file-pen w-8 h-8 text-blue-600 text-2xl"></i>
      <span class="text-blue-600">見積登録</span>
    </a>
  </div>
<div class="md:w-1/4 w-full p-2">
    <a href="" class="text-blue rounded-lg bg-green-100 p-4 block mb-4 text-center">
      <i class="fa-solid fa-clock-rotate-left w-8 h-8 text-2xl text-green-600"></i>
      <span class="text-green-600">契約登録</span>
    </a>
 </div>
 <div class="md:w-1/4 w-full p-2">
    <a href="" class="text-blue rounded-lg bg-amber-100 p-4 block mb-4 text-center">
      <i class="fa-solid fa-clock-rotate-left w-8 h-8 text-2xl text-amber-600"></i>
      <span class="text-amber-600">販売登録</span>
    </a>
 </div>
 <div class="md:w-1/4 w-full p-2">
    <a href="" class="text-blue rounded-lg bg-purple-100 p-4 block mb-4 text-center">
      <i class="fa-solid fa-clock-rotate-left w-8 h-8 text-2xl text-purple-600"></i>
      <span class="text-purple-600">金庫更新</span>
    </a>
 </div>
  <div class="md:w-1/4 w-full p-2">
    <a href="" class="text-blue rounded-lg bg-purple-100 p-4 block mb-4 text-center">
      <i class="fa-solid fa-clock-rotate-left w-8 h-8 text-2xl text-purple-600"></i>
      <span class="text-purple-600">入出金管理</span>
    </a>
 </div>
 <div class="md:w-1/4 w-full p-2">
    <a href="" class="text-blue rounded-lg bg-purple-100 p-4 block mb-4 text-center">
      <i class="fa-solid fa-clock-rotate-left w-8 h-8 text-2xl text-purple-600"></i>
      <span class="text-purple-600">現金残高管理</span>
    </a>
 </div>


 <!-- 以下、3つ繰り返し -->
</div>

    

</div>

@endsection
