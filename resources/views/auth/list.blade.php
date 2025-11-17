@extends('layouts.member')

@section('title', 'ユーザー一覧画面')
@section('content')

<h3 class="ttl">ユーザー一覧画面</h3>
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
<div class="overflow-x-auto">
  <table class="w-full border-collapse border border-gray-300 bg-white">
    <thead class="bg-blue-600 text-white">
      <tr>
        <th class="border border-gray-300 px-4 py-2 text-left">id</th>
        <th class="border border-gray-300 px-4 py-2 text-left">会社名</th>
        <th class="border border-gray-300 px-4 py-2 text-left">担当者名</th>
        <th class="border border-gray-300 px-4 py-2 text-left">メールアドレス</th>
        <th class="border border-gray-300 px-4 py-2 text-left">更新</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($users as $user)
      <tr class="hover:bg-gray-100">
        <td class="border border-gray-300 px-4 py-2">{{ $user->id }}</td>
        <td class="border border-gray-300 px-4 py-2"><a href="/user/{{$user->id}}/detail" class="text-blue-400 underline hover:text-blue-800">{{ $user->company_name }}</a></td>
        <td class="border border-gray-300 px-4 py-2">{{ $user->name }}</td>
        <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
        <td class="border border-gray-300 px-4 py-2"><a href="/user/{{$user->id}}/edit" class="text-blue-400 underline hover:text-blue-800">更新</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
















@endsection