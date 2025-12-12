@extends('layouts.member')

@section('title', '折り込みキャンペーン表示')
@section('content')



<h2>折り込みキャンペーン表示</h2>

  <!-- 成功メッセージ -->
             @if (session('success'))
    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
      <div class="flex">
        <div class="flex-shrink-0">
          <i class="fa-solid fa-check-circle text-green-400"></i>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-green-800">
            {{ session('success') }}
          </h3>
        </div>
      </div>
    </div>
  @endif

   <table class="w-full border border-gray-300 text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-2">名称</th>
                <th class="border px-2 py-2">発行日</th>
                <th class="border px-2 py-2"></th>
                <th class="border px-2 py-2">備考</th>
            </tr>
        </thead>
        <tbody>
          
            @foreach ($mastercampaigns as $mastercampaign)
            <tr class="hover:bg-gray-50">
                <td class="border px-2 py-1">{{ $mastercampaign->campaign }}</td>
                <td class="border px-2 py-1 text-right">{{ $mastercampaign->distribution_date }}</td>
                <td class="border px-2 py-1 whitespace-pre-wrap">{{ $mastercampaign ->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
      
    </table>




 <script src="{{ asset('js/register_app.js') }}" charset="UTF-8"></script>
@endsection