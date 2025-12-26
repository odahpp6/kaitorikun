@extends('layouts.member')

@section('title', 'ユーザー登録')
@section('content')


<h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">ユーザー更新画面</h2>

<form action="{{ route('user.update', ['id' => $user->id]) }}" ref="formEl" method="POST">
 @method('PUT') {{-- PUT メソッドを明示 --}}
@csrf

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">パスワード<span class="text-red-600 font-medium">※必須</span></p>
    <input type="password" v-on:input="checkPassword($event)" v-bind:class="message.password.class" class=" border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="パスワード" name="password" value="{{ old('password', $user->raw_password) }}">

    @error('password')
          <p class="error text-red-600"><span>{{ $message }}</span></p>
    @enderror
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">Email<span class="text-red-600 font-medium">※必須</span></p>
    <input type="email" v-on:input="checkEmail($event)" v-bind:class="message.email.class" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="test@happy-mentor.co.jp" name="email" value="{{ old('email', $user->email) }}">
    @error('email')
          <p class="error text-red-600"><span>{{ $message }}</span></p>
    @enderror
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">会社名<span class="text-red-600 font-medium">※必須</span></p>
    <input type="text" v-on:input="checkCompany($event)"v-bind:class="message.company.class" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="会社名" name="company_name" value="{{ old('company_name', $user->company_name) }}">
    @error('company_name')
          <p class="error text-red-600"><span>{{ $message }}</span></p>
    @enderror
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">担当者名</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="担当者名" name="name" value="{{ old('name', $user->name) }}">
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">郵便番号</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="1070052" name="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">住所</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="担当者名" name="address" value="{{ old('address', $user->address) }}">
 </div>
</div>


<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">電話番号</p>
    <input type="tel" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="0312345678" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
 </div>

</div>


<input type="submit" value="更新" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>


@endsection
