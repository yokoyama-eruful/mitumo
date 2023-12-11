<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>mitumo</title>
    @vite(['resources/css/app.css'])
</head>

<body class="min-h-screen flex flex-col justify-between">
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

    <main class="text-gray-600 w-[900px] mx-auto mt-5">
        <div class="flex flex-col items-center">
            <div class="flex items-center justify-center mb-5">
                <div id="target" class="mr-10">
                    敬称選択
                    <input type="radio" name="keisyou" value="様" checked>様
                    <input type="radio" name="keisyou" value="御中">御中
                </div>
                <label class="text-sm text-gray-500 mr-3">Excel</label>
                <input type="checkbox" id="toggle"
                    class="relative w-[3.25rem] h-7 bg-green-200 checked:bg-red-200 border-2 rounded-full transition-colors duration-200 ring-1 ring-transparent appearance-none
                    before:inline-block before:w-6 before:h-6 before:bg-white before:translate-x-0 checked:before:translate-x-full before:rounded-full before:transition"
                    value="excel">
                <label class="text-sm text-gray-500 ml-3">PDF</label>
            </div>
            <div class="flex flex-row space-x-10 pb-10">
                <form method="POST" action="{{ route('estimate.download.standard', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="normal"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            普通火災見積
                        </div>
                    </button>
                </form>

                <form id="comprehensive_form" method="POST"
                    action="{{ route('estimate.download.comprehensive', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="comprehensive"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            総合火災見積
                        </div>
                    </button>
                </form>

                <form id="normal_comprehensive_form" method="POST"
                    action="{{ route('estimate.download.standardAndComprehensive', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="normal_comprehensive"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            普通火災見積<br>&<br>総合火災見積
                        </div>
                    </button>
                </form>
            </div>

            <div class="flex flex-row space-x-10 pb-10">
                <form id="recommend_form" method="POST"
                    action="{{ route('estimate.download.standardOfRecommend', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="recommend"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            <div>
                                普通火災見積<br>おすすめ契約
                            </div>
                        </div>
                    </button>
                </form>

                <form id="recommend_comprehensive_form" method="POST"
                    action="{{ route('estimate.download.comprehensiveOfRecommend', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="recommend_comprehensive"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            総合火災見積<br>おすすめ契約
                        </div>
                    </button>
                </form>

                <form id="recommend_normal_comprehensive_form" method="POST"
                    action="{{ route('estimate.download.standardOfRecommendAndComprehensiveOfRecommend', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="recommend_normal_comprehensive"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            普通火災見積<br>&<br>総合火災見積<br>おすすめ契約
                        </div>
                    </button>
                </form>
            </div>

            <div class="flex flex-row space-x-10 pb-10">
                <form id="earthquake_normal_form" method="POST"
                    action="{{ route('estimate.download.standardOfEarthquake', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="earthquake_normal"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            普通火災見積<br>地震<br>おすすめ契約
                        </div>
                    </button>
                </form>

                <form id="earthquake_comprehensive_form" method="POST"
                    action="{{ route('estimate.download.comprehensiveOfEarthquake', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="earthquake_comprehensive"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            総合火災見積<br>地震<br>おすすめ契約
                        </div>
                    </button>
                </form>

                <form id="all_form" method="POST"
                    action="{{ route('estimate.download.all', ['id' => $estimate->id]) }}"
                    class="bg-green-50 hover:bg-green-100">
                    @csrf
                    <button name="all"
                        class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                        <div class="flex flex-col justify-center items-center">
                            すべて
                        </div>
                    </button>
                </form>
            </div>
        </div>

        <div class="text-gray-600">
            <div class="px-5 py-5 mx-auto">
                <a href="{{ route('estimate.edit', ['id' => $estimate->id]) }}"
                    class="w-full flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-8 h-8 mr-3 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                            <i class="fa-solid fa-pen"></i>
                        </div>
                        <h2 class="text-gray-900 text-lg title-font font-medium">入力情報の変更</h2>
                    </div>
                    <div class="flex-grow">
                        <p class="leading-relaxed text-base">情報入力画面に戻ります</p>
                    </div>
                </a>
            </div>
        </div>
    </main>
</body>

</html>
