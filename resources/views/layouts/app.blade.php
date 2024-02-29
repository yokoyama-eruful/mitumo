<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>火災見積書入力フォーム</title>
    @vite(['resources/css/app.css'])
    <style>
        @font-face {
            font-family: kyosai;
            font-style: normal;
            font-weight: normal;
            src: url('{{ asset('fonts/kyosai.ttf') }}');
        }

        body {
            font-family: kyosai;
        }
    </style>
</head>



<body class="flex flex-col min-h-screen max-w-full">
    <header id="header" class="bg-white w-full z-10 top-0 shadow">
        <div class="w-full container mx-auto flex flex-wrap items-center mt-0 pt-3 pb-3 md:pb-0">
            <div class="w-1/2 pl-5 md:pl-0">
                <a class="text-gray-900 text-base xl:text-xl no-underline hover:no-underline font-bold pl-4"
                    href="{{ route('top.index') }}">
                    <i class="fa-solid fa-file-invoice"></i> mitumo
                </a>
            </div>
            <div class="w-1/2 pr-0">

            </div>

            <div class="w-full flex lg:items-center mt-2 lg:mt-0 bg-white z-20" id="nav-content">
                <ul class="flex flex-1 px-4">
                    <li class="mr-6 my-2 md:my-0">
                        <a href="{{ route('top.index') }}"
                            class="ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-pink-500">
                            <i class="fas fa-home fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">ホーム</span>
                        </a>
                    </li>
                    <li class="mr-6 my-2 md:my-0">
                        @if (Auth::user()->id == 1)
                            <a href="{{ route('account.index') }}"
                                class="ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-pink-500">
                                <i class="fas fa-user-plus fa-fw mr-3"></i><span
                                    class="pb-1 md:pb-0 text-sm">ユーザー追加</span>
                            </a>
                        @endif
                    </li>
                    <li class="mr-6 my-2 md:my-0">
                        <a href="{{ route('top.show') }}"
                            class="ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:border-pink-500">
                            <i class="fa-solid fa-code-compare"></i><span class="pb-1 md:pb-0 text-sm">バージョン確認</span>
                        </a>
                    </li>
                </ul>
                <div class="relative pull-right pl-4 pr-4 md:pr-0">
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="transition duration-300 rounded-full py-2 px-6 text-lx text-red-600 hover:text-red-900 font-medium focus:outline-none">
                            <span class="pr-1">ログアウト</span>
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    @yield('main')
    <footer class="mt-auto bg-gray-200 py-4 max-w-full">
        <div class="container mx-auto text-center text-gray-600 text-sm">
            &copy; 2023 広島県共済. All Rights Reserved.
        </div>
    </footer>
</body>

</html>
