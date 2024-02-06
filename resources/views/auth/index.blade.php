<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>火災見積書ログインページ</title>
    @vite(['resources/css/app.css'])
</head>

<body>
    <div class="mt-10 p-4">
        <h1 class="text-center text-2xl text-blue-500 font-bold mb-4 tracking-wider">
            <i class="fa-solid fa-file-invoice text-8xl mb-4"></i>
            <div>mitumo</div>
            <div class="text-xs text-black">{{ config('app.version') }}2404.1.6</div>
        </h1>
        <form action="{{ route('auth.login') }}" method="POST">
            @csrf
            <div class="flex flex-col justify-center items-center">
                <div class="mb-2 w-96">
                    <label for="staff_id" class="block text-gray-700 text-sm font-bold mb-1">ID</label>
                    <input id="staff_id" type="text" name="staff_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mb-2 w-96">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-1">パスワード</label>
                    <input id="password" type="password" name="password"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                @error('error')
                    <p class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mt-2" role="alert">
                        {{ $message }}
                    </p>
                @enderror
                <div>
                    <button type="submit" class="bg-blue-500 rounded h-10 w-20 mt-5 text-white font-semibold">
                        ログイン
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>
