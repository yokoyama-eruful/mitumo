<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>火災見積書入力フォーム</title>
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

    <div>
        <div class="block h-[800px] w-[800px] shadow-md outline outline-1 outline-gray-100 p-5">

            @include('preview.layouts.buildingInfo')

            <div class="flex flex-row">

                @include('preview.layouts.insurancePayout')

                <div>
                    <div
                        class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
                        普通共済金額
                    </div>
                    <div>
                        <div class="flex items-center h-[120px] w-[200px] outline outline-1 outline-gray-500 pl-5">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-row">
                <div
                    class="flex justify-center items-center h-16 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
                </div>
                <div name="reco"
                    class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
                </div>
                <div name="reco2"
                    class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
                </div>
                <div name="reco3"
                    class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
                </div>
            </div>
            <div class="flex flex-row">
                <div
                    class="flex justify-center items-center h-20 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
                    共済掛金
                </div>
                <div name="reco"
                    class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
                </div>
                <div name="reco2"
                    class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
                </div>
                <div name="reco3"
                    class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
                </div>
            </div>
            <div class="flex flex-row">
                <div
                    class="flex justify-center items-center h-20 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
                    １年更新の場合より
                </div>
                <div name="reco"
                    class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
                </div>
                <div name="reco2"
                    class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
                </div>
                <div name="reco3"
                    class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
                </div>
            </div>
        </div>
        @include('preview.layouts.tab')
    </div>
</body>
