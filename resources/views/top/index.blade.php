<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>火災見積書入力フォーム</title>
    @vite(['resources/css/app.css'])
</head>

<body class="flex justify-center pt-10">
    <div class="w-[800px]">
        <header class="sticky text-gray-600 body-font">
            <div class="container mx-auto flex flex-wrap p-5 flex-row items-center">
                <a class="flex title-font font-medium items-center text-gray-900 mb-4">
                    <div class="text-2xl"><i class="fa-solid fa-file-invoice"></i></div>
                    <span class="ml-3 text-xl">mitumo</span>
                </a>
                <nav
                    class="mr-auto ml-4 py-1 pl-4 border-l border-gray-400	flex flex-wrap items-center text-base justify-center">
                    <a href="{{route('top.edit')}}" class="mr-5 hover:text-gray-900 hover:scale-105">新規作成</a>
                    <a href="" class="mr-5 hover:text-gray-900 hover:scale-105">一覧から探す</a>
                </nav>

                <form action="{{route('auth.logout')}}" method="POST">
                    @csrf
                    <button
                        class="inline-flex items-center bg-red-100 border-0 py-1 px-3 focus:outline-none hover:bg-red-200 rounded text-base mt-4">
                        <p class="pr-1">ログアウト</p>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </header>

        <main class="text-gray-600 body-font">
            <div class="container px-5 py-24 flex flex-col mr-24">
                <div class="flex flex-col -m-4">
                    <div class="p-4 md:w-full">
                        <a href=""
                            class="flex outline outline-1 outline-slate-300 shadow-md rounded-lg p-8 flex-row hover:bg-gray-200">

                            <div
                                class="w-16 h-16 mb-4 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 flex-shrink-0">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </div>
                            <div class="flex-grow">
                                <h2 class="text-gray-900 text-lg title-font font-medium mb-3">見積書を探す</h2>
                                <p class="leading-relaxed text-base">過去に作成した見積書の一覧を表示します。</p>
                            </div>
                        </a>
                    </div>
                    <div class="p-4">
                        <a href="{{route('top.edit')}}">
                            <div
                                class="flex outline outline-1 outline-slate-300 shadow-md rounded-lg  p-8 flex-row hover:bg-gray-200">
                                <div
                                    class="w-16 h-16 mb-4 inline-flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 flex-shrink-0">
                                    <i class="fa-solid fa-plus"></i>
                                </div>
                                <div class="flex-grow">
                                    <h2 class="text-gray-900 text-lg title-font font-medium mb-3">見積書を作成</h2>
                                    <p class="leading-relaxed text-base">新規契約者のための見積書を作成します。</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>

</html>