@extends('layouts.member')

@section('title', '折り込みマスター更新編集')
@section('content')
<form  action="{{route('master.update_campaign', ['id' => $mastercampaign->id])}}" ref="formEl" method="POST">
@method('PUT')  
@csrf


<h2 class="text-2xl font-bold text-gray-800 mb-6 pb-2 border-b-2 border-blue-500">キャンペーン登録</h2>
<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600mb-2">キャンペーン名<span class="text-red-600 font-medium">※必須</span></p>
    <input type="text" class=" border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="キャンペーン名" name="campaign" value="{{ old('campaign',$mastercampaign->campaign) }}">
    
 </div>
  <div class="w-full md:w-1/2">
     <p class="text-sm text-stone-600 mb-2">配布日<span class="text-red-600 font-medium">※必須</span></p>
    <input type="date" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4"  name="distribution_date" value="{{ old('distribution_date',$mastercampaign->distribution_date) }}">
      
 </div>
</div>

<div class="flex flex-wrap mb-4">
  <div class="w-full md:w-1/2">
    <p class="text-sm text-stone-600 mb-2">備考</p>
    <input type="tel" class="border border-gray-300 rounded-lg p-2 w-[90%] mb-4" placeholder="0312345678" name="remarks" value="{{ old('remarks',$mastercampaign->remarks) }}">
 </div>
</div>


<input type="submit" value="更新" class="bg-blue-100 text-white px-6 py-2 rounded-lg hover:bg-blue-700 cursor-pointer transition-colors duration-200">
</form>


@endsection