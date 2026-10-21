<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#ffffff">

    <title>mitumo | ログイン</title>
    @vite(['resources/css/app.css'])
</head>

<body class="flex min-h-screen items-center justify-center bg-slate-50 px-5 py-10 text-slate-900 antialiased">
    <main class="w-full max-w-[400px] rounded-2xl bg-white p-7 shadow-sm ring-1 ring-slate-200 sm:p-10">
        <header class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="" class="mx-auto mb-3 h-24 w-24 object-contain">
            <h1 class="font-montserrat text-3xl font-bold tracking-wide text-[#1D264E]">mitumo</h1>
            <p class="mt-1 text-xs text-slate-400">{{ trim(config('version.type') . ' ' . config('version.number')) }}</p>
        </header>

        <form action="{{ route('auth.login.submit') }}" method="POST" class="w-full">
            @csrf
            <div class="mb-5">
                <label for="staff_id" class="mb-2 block text-sm font-semibold text-slate-700">ID</label>
                <div class="flex h-12 items-center rounded-lg border border-slate-200 px-3.5 transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10">
                    <img src="{{ asset('images/people.png') }}" alt="" aria-hidden="true" class="h-[18px] w-[18px] flex-none opacity-50">
                    <input id="staff_id" type="text" name="staff_id" placeholder="IDを入力"
                        class="h-full min-w-0 w-full border-0 bg-transparent pl-3 text-sm font-montserrat text-slate-800 outline-none placeholder:text-slate-400 focus:ring-0" autocomplete="username" required>
                </div>
            </div>

            <div class="mb-6">
                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">パスワード</label>
                <div class="flex h-12 items-center rounded-lg border border-slate-200 px-3.5 transition focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-500/10">
                    <img src="{{ asset('images/lock.png') }}" alt="" aria-hidden="true" class="h-[18px] w-[18px] flex-none opacity-50">
                    <input id="password" type="password" name="password" placeholder="パスワードを入力"
                        class="h-full min-w-0 w-full border-0 bg-transparent pl-3 text-sm font-montserrat text-slate-800 outline-none placeholder:text-slate-400 focus:ring-0" autocomplete="current-password" required>
                </div>
            </div>

            @error('error')
                <p class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700" role="alert">{{ $message }}</p>
            @enderror

            <button type="submit" class="flex h-12 w-full items-center justify-center rounded-lg bg-[#1769e0] text-sm font-bold text-white transition hover:bg-[#1258c4] focus:outline-none focus:ring-4 focus:ring-blue-500/20">ログイン</button>
        </form>
    </main>
</body>

</html>
