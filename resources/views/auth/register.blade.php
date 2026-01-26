@extends('layouts.member')

@section('title', 'ユーザー登録')
@section('content')


<h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">ユーザー登録画面</h2>

<form @submit.prevent="handleSubmit" action="{{ route('register') }}" ref="formEl" method="POST">

@csrf
@if ($errors->any())
  <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700">
    <p class="font-semibold mb-2">入力内容に誤りがあります</p>
    <ul class="list-disc pl-5 space-y-1">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif


<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">パスワード<span class="text-red-600 font-medium">※必須</span></p>
    <input type="password" v-on:input="checkPassword($event)" v-bind:class="message.password.class" class=" border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="パスワード" name="password">
    <p>@{{message.password.text}}</p>
    @error('password')
      <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">Email<span class="text-red-600 font-medium">※必須</span></p>
    <input type="email" v-on:input="checkEmail($event)" v-bind:class="message.email.class" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="test@happy-mentor.co.jp" name="email" value="{{ old('email') }}">
      <p>@{{message.email.text}}</p>
      @error('email')
        <p class="text-sm text-red-600">{{ $message }}</p>
      @enderror
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">会社名<span class="text-red-600 font-medium">※必須</span></p>
    <input type="text" v-on:input="checkCompany($event)"v-bind:class="message.company.class" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="会社名" name="company_name" value="{{ old('company_name') }}">
     <p>@{{message.company.text}}</p>
     @error('company_name')
       <p class="text-sm text-red-600">{{ $message }}</p>
     @enderror
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">担当者名</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="担当者名" name="name" value="{{ old('name') }}">
    @error('name')
      <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">郵便番号</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="1070052" name="postal_code" value="{{ old('postal_code') }}">
    @error('postal_code')
      <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">住所</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="担当者名" name="address" value="{{ old('address') }}">
    @error('address')
      <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
 </div>
</div>


<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">電話番号</p>
    <input type="tel" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="0312345678" name="phone_number" value="{{ old('phone_number') }}">
    @error('phone_number')
      <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror
 </div>

</div>


<input type="submit" value="登録" v-bind:class="message.buttunClass"  class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>


 <script src="{{ asset('js/register_app.js') }}" charset="UTF-8"></script>
@endsection
