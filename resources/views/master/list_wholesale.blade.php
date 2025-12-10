@extends('layouts.member')

@section('title', '卸売り登録一覧')
@section('content')



<h2>卸売り登録一覧表示</h2>


   <table class="w-full border border-gray-300 text-sm mb-4">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-2">名称</th>
                <th class="border px-2 py-2">更新日</th>
                <th class="border px-2 py-2">備考</th>
            </tr>
        </thead>
        <tbody>
          
            @foreach ($wholesales as $wholesale)
            <tr class="hover:bg-gray-50">
                <td class="border px-2 py-1">{{ $wholesale->wholesale }}</td>
                    <td class="border px-2 py-1">{{ $wholesale->created_at }}</td>
                <td class="border px-2 py-1 whitespace-pre-wrap">{{ $wholesale->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
      
    </table>




 <script src="{{ asset('js/register_app.js') }}" charset="UTF-8"></script>
@endsection