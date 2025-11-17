@extends('layouts.member')

@section('title', 'ユーザー登録')
@section('content')


<h3 class="ttl">ユーザー詳細画面</h3>
<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">パスワード</p>
  <p>{{$user->raw_password}}</p>
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">Email</p>

      <p>{{$user->email}}</p>
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">会社名</p>
    
     <p>{{$user->company_name}}</p>
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">担当者名</p>
 
    <p>{{$user->name}}</p>
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">郵便番号</p>
       <p>{{$user->postal_code}}</p>
  </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">住所</p>
    <p>{{$user->address}}</p>
  </div>
</div>


<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">電話番号</p>

    <p>{{$user->phone_number}}</p>
  </div>
</div>



@endsection