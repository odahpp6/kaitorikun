@extends('layouts.member')

@section('title', 'マスター登録')
@section('content')
<form  action="{{ route('register') }}" ref="formEl" method="POST">

@csrf


<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">キャンペーン名<span class="text-red-600 font-medium">※必須</span></p>
    <input type="password" v-on:input="checkPassword($event)" v-bind:class="message.password.class" class=" border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="キャンペーン名" name="password">
    <p>@{{message.password.text}}</p>
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600mb-2">配布日<span class="text-red-600 font-medium">※必須</span></p>
    <input type="email" v-on:input="checkEmail($event)" v-bind:class="message.email.class" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="test@happy-mentor.co.jp" name="email">
      <p>@{{message.email.text}}</p>
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">備考</p>
    <input type="tel" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="0312345678" name="phone_number">
 </div>
</div>


<input type="submit" value="登録" v-bind:class="message.buttunClass"  class="bg-blue-100 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>


 <script src="{{ asset('js/register_app.js') }}" charset="UTF-8"></script>
@endsection