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
            <a href="/dashboard"><img src="{{asset('images/logo.png')}}" alt="" class="h-8"></a>
        </h1>
      <div class="flex items-center gap-2">
  <img src="{{asset('images/user.png')}}" alt="ユーザー" class="w-8 h-8 rounded-full">
        <p class="font-medium">
        @auth
            {{ Auth::user()->name }}
        @else
            ゲスト
        @endauth
    </p>
    <form method="POST" action="{{ route('logout') }}"> 
        @csrf
        <button type="submit" class="text-sm text-red-600 hover:underline">ログアウト</button>
    </form>
</div>
    </div>
</header>
<div class="flex flex-wrap ">
<aside class="w-full md:w-1/6 p-0 bg-stone-950 text-white">
<nav role="navigation" aria-label="サイドナビ">
    <ul class="p-4 space-y-2">
        <!-- 見積グループ -->
        <li class="mt-2 mb-1 text-xs font-bold text-blue-300">見積管理</li>
        <li>
            <a href="/estimate" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->is('estimate') ? 'bg-blue-600 text-white font-medium hover:bg-blue-700' : 'text-blue-200 hover:bg-blue-800' }}">
                <i class="fa-solid fa-file-pen w-4 h-4 {{ request()->is('estimate') ? 'text-white' : 'text-blue-200' }}"></i>
                <span>見積登録</span>
            </a>
        </li>
        <li>
            <a href="/estimate/list" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->is('estimate/list*') ? 'bg-blue-600 text-white font-medium hover:bg-blue-700' : 'text-blue-200 hover:bg-blue-800' }}">
                <i class="fa-solid fa-clock-rotate-left w-4 h-4 {{ request()->is('estimate/list*') ? 'text-white' : 'text-blue-200' }}"></i>
                <span>見積履歴</span>
            </a>
        </li>

        <!-- 契約グループ -->
        <li class="mt-4 mb-1 text-xs font-bold text-green-300">契約管理</li>
        <li>
            <a href="/purchase" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->is('purchase') ? 'bg-green-600 text-white font-medium hover:bg-green-700' : 'text-green-200 hover:bg-green-800' }}">
                <i class="fa-solid fa-file-signature w-4 h-4 {{ request()->is('purchase') ? 'text-white' : 'text-green-200' }}"></i>
                <span>契約登録</span>
            </a>
        </li>
        <li>
            <a href="/purchase/list" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->is('purchase/list*') ? 'bg-green-600 text-white font-medium hover:bg-green-700' : 'text-green-200 hover:bg-green-800' }}">
                <i class="fa-solid fa-file-lines w-4 h-4 {{ request()->is('purchase/list*') ? 'text-white' : 'text-green-200' }}"></i>
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
            <a href="/master/create_wholesale" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->is('master/create_wholesale') ? 'bg-gray-600 text-white font-medium hover:bg-gray-700' : 'text-gray-200 hover:bg-gray-800' }}">
                <i class="fa-solid fa-gear w-4 h-4 {{ request()->is('master/create_wholesale') ? 'text-white' : 'text-gray-200' }}"></i>
                <span>取引先マスタ登録</span>
            </a>
        </li>
        <li>
            <a href="/master/list_wholesale" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->is('master/list_wholesale') ? 'bg-gray-600 text-white font-medium hover:bg-gray-700' : 'text-gray-200 hover:bg-gray-800' }}">
                <i class="fa-solid fa-list w-4 h-4 {{ request()->is('master/list_wholesale') ? 'text-white' : 'text-gray-200' }}"></i>
                <span>取引先マスタ一覧</span>
            </a>
        </li>
          <li>
            <a href="/master/create_campaign" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->is('master/create_campaign') ? 'bg-gray-600 text-white font-medium hover:bg-gray-700' : 'text-gray-200 hover:bg-gray-800' }}">
                <i class="fa-solid fa-gear w-4 h-4 {{ request()->is('master/create_campaign') ? 'text-white' : 'text-gray-200' }}"></i>
                <span>折り込みマスタ登録</span>
            </a>
        </li>
        <li>
            <a href="/master/campaign_list" class="flex items-center gap-3 px-4 py-2 rounded-lg transition-colors duration-200 {{ request()->is('master/campaign_list') ? 'bg-gray-600 text-white font-medium hover:bg-gray-700' : 'text-gray-200 hover:bg-gray-800' }}">
                <i class="fa-solid fa-list w-4 h-4 {{ request()->is('master/campaign_list') ? 'text-white' : 'text-gray-200' }}"></i>
                <span>折り込みマスタ一覧</span>
            </a>
        </li>

    </ul>
</nav>
</aside>
<main class="w-full md:w-5/6 p-6 bg-amber-50 min-h-screen" id="main">

  @yield('content')

</main>
</div>
<style>
    body {
  opacity: 0;
   transition: opacity 1.6s cubic-bezier(0.4, 0, 0.2, 1);
}
body.is-loaded {
  opacity: 1;
}
</style>

<script>
    window.addEventListener('load', () => {
  document.body.classList.add('is-loaded');
});
</script>

</body>
</html>
