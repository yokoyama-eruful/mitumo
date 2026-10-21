<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>火災見積書入力フォーム</title>
    @vite(['resources/css/app.css'])
    {{-- <style>
        @font-face {
            font-family: 'KyosaiFont';
            src: url(/fonts/kyosai.ttf);
        }

        body {
            font-family: KyosaiFont;
        }
    </style> --}}
</head>


<body class="flex flex-col min-h-screen max-w-full">
    <header id="header" class="bg-white w-full z-10 top-0 shadow">
        <div class="w-full flex items-center mt-0 px-8 py-2">
            <div class="flex-none">
                <a class="flex items-center gap-2 text-gray-900 text-base xl:text-xl no-underline hover:no-underline font-bold"
                    href="{{ route('top.index') }}">
                    <img src="{{ asset('images/logo.png') }}" alt="" class="h-8 w-8 object-contain">
                    <span class="font-montserrat">mitumo</span>
                </a>
            </div>
            <div class="flex flex-1 items-center bg-white z-20" id="nav-content">
                <ul class="flex flex-1 items-center justify-start gap-1 px-4">
                    <li class="my-0">
                        <a href="{{ route('top.index') }}"
                            class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 no-underline transition duration-200 hover:bg-slate-100 hover:text-slate-900">
                            <i class="fas fa-home fa-fw mr-2 text-slate-400"></i><span>ホーム</span>
                        </a>
                    </li>
                    <li class="my-0">
                        @if (Auth::user()->id == 1)
                            <a href="{{ route('account.index') }}"
                                class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 no-underline transition duration-200 hover:bg-slate-100 hover:text-slate-900">
                                <i class="fas fa-user-plus fa-fw mr-2 text-slate-400"></i><span>ユーザー追加</span>
                            </a>
                        @endif
                    </li>
                    <li class="my-0">
                        <a href="{{ route('top.show') }}"
                            class="flex items-center rounded-lg px-3 py-2 text-sm font-medium text-slate-600 no-underline transition duration-200 hover:bg-slate-100 hover:text-slate-900">
                            <i class="fa-solid fa-code-compare fa-fw mr-2 text-slate-400"></i><span>バージョン確認</span>
                        </a>
                    </li>
                </ul>
                <div class="relative pl-4">
                    <form action="{{ route('auth.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="transition duration-200 rounded-lg py-2 px-4 text-sm text-slate-500 hover:bg-red-50 hover:text-red-700 font-medium focus:outline-none">
                            <span class="pr-1">ログアウト</span>
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    @yield('main')
    <footer class="mt-auto bg-gray-200 py-1 max-w-full">
        <div class="mx-2 text-right text-gray-600 text-xs">
            &copy; 2023 広島県共済. All Rights Reserved.
        </div>
    </footer>
</body>

</html>
