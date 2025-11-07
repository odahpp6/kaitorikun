@extends('layouts.member')

@section('title', 'ユーザー登録')
@section('content')

<form @submit.prevent="handleSubmit" ref="formEl" method="POST">
@csrf


<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">パスワード<span class="text-red-600 font-medium">※必須</span></p>
    <input type="password" v-on:input="checkPassword($event)" v-bind:class="message.password.class" class=" border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="パスワード" name="password">
    <p>@{{message.password.text}}</p>
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">Email<span class="text-red-600 font-medium">※必須</span></p>
    <input type="email" v-on:input="checkEmail($event)" v-bind:class="message.email.class" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="test@happy-mentor.co.jp" name="email">
      <p>@{{message.email.text}}</p>
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">会社名<span class="text-red-600 font-medium">※必須</span></p>
    <input type="text" v-on:input="checkCompany($event)"v-bind:class="message.company.class" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="会社名" name="company_name">
     <p>@{{message.company.text}}</p>
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">担当者名</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="担当者名" name="name">
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">郵便番号</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="1070052" name="postal_code">
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">住所</p>
    <input type="text" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="担当者名" name="address">
 </div>
</div>


<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">電話番号</p>
    <input type="tel" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="0312345678" name="phone_number">
 </div>

</div>


<input type="submit" value="登録" v-bind:class="message.buttunClass"  class="bg-blue-100 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>


@endsection