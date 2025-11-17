@extends('layouts.member')

@section('title', '見積一覧')
@section('content')






<div class="max-w-5xl mx-auto p-4 bg-white rounded-lg shadow-md">


  <table class="w-full border border-gray-300 text-sm mb-4">
    <thead>
    <tr class="bg-gray-100">
      <th class="border px-2 py-2">見積登録日</th>
      <th class="border px-2 py-2">タイトル</th>
      <th class="border px-2 py-2">削除</th>
     </tr>
    </thead>
    <tbody>


   @foreach ($Estimates as $Estimate)
    <tr class="hover:bg-gray-50">
    
      <td class="border px-2 py-1">
        <a href="">{{$Estimate->created_at}}</a>
    
      </td>
      <td class="border px-2 py-1">
      {{$Estimate->title}}
      </td>
      <td class="border px-2 py-1">
     
      </td>
    </tr>
    @endforeach
            </tbody>
          </table>
       
  </div>





@endsection