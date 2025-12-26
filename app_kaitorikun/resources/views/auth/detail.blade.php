@extends('layouts.member')

@section('title', 'ユーザー登録')
@section('content')


<h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">ユーザー詳細画面</h2>

<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
  <table class="w-full border-collapse">
    <tbody>
      <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 bg-gray-100 font-semibold text-gray-700 w-1/3">パスワード</td>
        <td class="px-6 py-4 text-gray-900">{{$user->raw_password}}</td>
      </tr>
      <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 bg-gray-100 font-semibold text-gray-700 w-1/3">Email</td>
        <td class="px-6 py-4 text-gray-900">{{$user->email}}</td>
      </tr>
      <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 bg-gray-100 font-semibold text-gray-700 w-1/3">会社名</td>
        <td class="px-6 py-4 text-gray-900">{{$user->company_name}}</td>
      </tr>
      <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 bg-gray-100 font-semibold text-gray-700 w-1/3">担当者名</td>
        <td class="px-6 py-4 text-gray-900">{{$user->name}}</td>
      </tr>
      <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 bg-gray-100 font-semibold text-gray-700 w-1/3">郵便番号</td>
        <td class="px-6 py-4 text-gray-900">{{$user->postal_code}}</td>
      </tr>
      <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 bg-gray-100 font-semibold text-gray-700 w-1/3">住所</td>
        <td class="px-6 py-4 text-gray-900">{{$user->address}}</td>
      </tr>
      <tr class="hover:bg-gray-50 transition-colors">
        <td class="px-6 py-4 bg-gray-100 font-semibold text-gray-700 w-1/3">電話番号</td>
        <td class="px-6 py-4 text-gray-900">{{$user->phone_number}}</td>
      </tr>
    </tbody>
  </table>
</div>



@endsection