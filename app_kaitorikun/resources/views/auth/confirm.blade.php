@extends('layouts.member')

@section('title', '削除確認画面')
@section('content')

<form action="{{ route('user.destroy', ['id' => $user->id]) }}" method="POST">
    @csrf
    @method('DELETE')



<p>以下削除します</p>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">パスワード<span class="text-red-600 font-medium">※必須</span></p>
    
 
    <p>{{ old('password', $user->raw_password) }}</p>

     </div>
    <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">Email<span class="text-red-600 font-medium">※必須</span></p>

    <p>{{ old('email', $user->email) }}</p>
    
     </div>
    </div>

    <div class="flex flex-wrap mb-4">
    <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">会社名<span class="text-red-600 font-medium">※必須</span></p>

    <p>{{ old('company_name', $user->company_name) }}</p>

     </div>
    <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">担当者名</p>
    
    <p>{{ old('name', $user->name) }}</p>
     </div>
    </div>

    <div class="flex flex-wrap mb-4">
    <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">郵便番号</p>
   
    <p>{{ old('postal_code', $user->postal_code) }}</p>
     </div>
    <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">住所</p>

    <p>{{ old('address', $user->address) }}</p>
   </div>
  </div>

  <div class="flex flex-wrap mb-4">
    <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">電話番号</p>
 
    <p>{{ old('phone_number', $user->phone_number) }}</p>
   </div>

  </div>


<input type="submit" value="削除"  class="bg-blue-100 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>


@endsection
