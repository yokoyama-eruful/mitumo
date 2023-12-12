<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>火災見積書入力フォーム</title>
    @vite(['resources/css/app.css'])
</head>

<body>
    <header class="top-0 z-10 bg-white shadow-md">
        <div class="container mx-auto flex items-center justify-between py-2 px-6">
            <a href="{{ route('top.index') }}" class="flex items-center text-gray-900 hover:text-gray-700">
                <div class="text-2xl"><i class="fa-solid fa-file-invoice"></i></div>
                <span class="ml-3 text-xl font-medium">mitumo</span>
            </a>
            <nav class="flex space-x-6">
                <a href="{{ route('top.index') }}"
                    class="text-gray-600 hover:text-gray-900 transition duration-300">トップに戻る</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-300">一覧から探す</a>
            </nav>
            <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-100 hover:bg-red-200 transition duration-300 rounded-full py-2 px-6 text-sm text-red-600 font-medium focus:outline-none">
                    <span class="pr-1">ログアウト</span>
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>
    @yield('main')
    <footer class="bg-gray-200 py-4">
        <div class="container mx-auto text-center text-gray-600 text-sm">
            &copy; 2023 広島県共済. All Rights Reserved.
        </div>
    </footer>
</body>

</html>