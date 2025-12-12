@extends('layouts.member')

@section('title', '折り込みマスター削除確認')
@section('content')


<h2>キャンペーン登録</h2>
<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">キャンペーン名<span class="text-red-600 font-medium">※必須</span></p>
  <p>{{ $mastercampaign->campaign }}"></p> 
    
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600 mb-2">配布日<span class="text-red-600 font-medium">※必須</span></p>
     <p>{{$mastercampaign->distribution_date}}</p>
 
      
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600 mb-2">備考</p>
    <p>{{ $mastercampaign->remarks }}</p>
 </div>
</div>

<form  action="{{route('master.delete_campaign_excecute', ['id' => $mastercampaign->id])}}" ref="formEl" method="POST">
@method('DELETE')  
@csrf


<input type="submit" value="削除" class="bg-blue-100 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>


@endsection