<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>@yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
</head>
<body>
    
<header>
    <div class="p-4 flex justify-between items-center border-b border-gray-200">
        <h1 class="text-2xl">
            <img src="{{asset('images/logo.png')}}" alt="" class="h-8">
        </h1>
        <div class="flex items-center gap-2">
            <img src="{{asset('images/user.png')}}" alt="ユーザー" class="w-8 h-8 rounded-full">
            <p class="font-medium">ユーザー名</p>
        </div>
    </div>
</header>
<div class="flex flex-wrap ">
<aside class="w-full md:w-1/4 p-0 bg-stone-950 text-white">
<nav role="navigation" aria-label="サイドナビ">
    <ul class="p-4 space-y-2">
        <!-- 見積グループ -->
        <li class="mt-2 mb-1 text-xs font-bold text-blue-300">見積管理</li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-blue-600 text-white font-medium hover:bg-blue-700 transition-colors duration-200">
                <i class="fa-solid fa-file-pen w-4 h-4 text-white"></i>
                <span>見積登録</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-blue-200 hover:bg-blue-800 transition-colors duration-200">
                <i class="fa-solid fa-clock-rotate-left w-4 h-4 text-blue-200"></i>
                <span>見積履歴</span>
            </a>
        </li>

        <!-- 契約グループ -->
        <li class="mt-4 mb-1 text-xs font-bold text-green-300">契約管理</li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-green-200 hover:bg-green-800 transition-colors duration-200">
                <i class="fa-solid fa-file-signature w-4 h-4 text-green-200"></i>
                <span>契約登録</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-green-200 hover:bg-green-800 transition-colors duration-200">
                <i class="fa-solid fa-file-lines w-4 h-4 text-green-200"></i>
                <span>契約履歴</span>
            </a>
        </li>

        <!-- 販売グループ -->
        <li class="mt-4 mb-1 text-xs font-bold text-amber-300">販売管理</li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-amber-200 hover:bg-amber-800 transition-colors duration-200">
                <i class="fa-solid fa-cart-plus w-4 h-4 text-amber-200"></i>
                <span>販売登録</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-amber-200 hover:bg-amber-800 transition-colors duration-200">
                <i class="fa-solid fa-receipt w-4 h-4 text-amber-200"></i>
                <span>販売履歴</span>
            </a>
        </li>

        <!-- 金庫・入出金グループ -->
        <li class="mt-4 mb-1 text-xs font-bold text-purple-300">金庫・入出金管理</li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-purple-200 hover:bg-purple-800 transition-colors duration-200">
                <i class="fa-solid fa-money-bill-wave w-4 h-4 text-purple-200"></i>
                <span>金庫更新</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-purple-200 hover:bg-purple-800 transition-colors duration-200">
                <i class="fa-solid fa-right-left w-4 h-4 text-purple-200"></i>
                <span>入出金管理</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-purple-200 hover:bg-purple-800 transition-colors duration-200">
                <i class="fa-solid fa-book w-4 h-4 text-purple-200"></i>
                <span>出納帳</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-purple-200 hover:bg-purple-800 transition-colors duration-200">
                <i class="fa-solid fa-money-check-dollar w-4 h-4 text-purple-200"></i>
                <span>現金残高確認</span>
            </a>
        </li>

        <!-- 顧客・売上グループ -->
        <li class="mt-4 mb-1 text-xs font-bold text-pink-300">顧客・売上管理</li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-pink-200 hover:bg-pink-800 transition-colors duration-200">
                <i class="fa-solid fa-users w-4 h-4 text-pink-200"></i>
                <span>顧客管理</span>
            </a>
        </li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-pink-200 hover:bg-pink-800 transition-colors duration-200">
                <i class="fa-solid fa-chart-line w-4 h-4 text-pink-200"></i>
                <span>売上管理</span>
            </a>
        </li>

        <!-- マスタ管理グループ -->
        <li class="mt-4 mb-1 text-xs font-bold text-gray-300">マスタ管理</li>
        <li>
            <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-200 hover:bg-gray-800 transition-colors duration-200"></a>
                <i class="fa-solid fa-gear w-4 h-4 text-gray-200"></i>
                <span>マスタ管理</span>
            </a>
        </li>
    </ul>
</nav>
</aside>
<main class="w-full md:w-3/4 p-6 bg-amber-50 min-h-screen" id="main">

  @yield('content')

</main>
</div>




<script src="{{ asset('js/register_app.js') }}" charset="UTF-8"></script>
</body>
</html>