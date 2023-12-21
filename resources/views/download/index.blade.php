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

    <main class="text-gray-600 w-[900px] mx-auto mt-5 text-xl">
        <div class="flex flex-col items-center">
            <div class="flex items-center justify-center mb-5">
                <div class="mr-10">
                    敬称選択
                    @if ($keisyo == '様')
                        <a href="" class=" bg-gray-300 text-white font-bold py-2 px-4 rounded shadow-inner">様</a>
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => '御中']) }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-md">御中</a>
                    @else
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => '様']) }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">様</a>
                        <a href="" class="bg-gray-300 text-white font-bold py-2 px-4 rounded">御中</a>
                    @endif
                </div>
                <div class="mr-10">
                    ファイル選択
                    @if ($fileType == 'excel')
                        <a href=""
                            class="bg-green-300 text-white font-bold py-2 px-4 rounded shadow-inner">excel</a>
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => 'pdf', 'keisyo' => $keisyo]) }}"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">pdf</a>
                    @else
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => 'excel', 'keisyo' => $keisyo]) }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md">excel</a>
                        <a href="" class="bg-red-300 text-white font-bold py-2 px-4 rounded">pdf</a>
                    @endif
                </div>
            </div>
            <div class="grid grid-cols-3 gap-10 mt-5">
                @foreach ($estimateType as $estimateType)
                    @if ($fileType == 'pdf')
                        <form method="POST"
                            action="{{ route('estimate.download.type', ['id' => $estimate->id, 'category' => $estimateType, 'fileType' => $fileType, 'keisyo' => $keisyo]) }}"
                            class="bg-red-50 hover:bg-red-100">
                            @csrf
                            <button
                                class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                                {{ $estimateType }}
                            </button>
                        </form>
                    @else
                        <form method="POST"
                            action="{{ route('estimate.download.type', ['id' => $estimate->id, 'category' => $estimateType, 'fileType' => $fileType, 'keisyo' => $keisyo]) }}"
                            class="bg-green-50 hover:bg-green-100">
                            @csrf
                            <button
                                class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                                {{ $estimateType }}
                            </button>
                        </form>
                    @endif
                @endforeach
            </div>

            <div class="text-gray-600 w-full">
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
