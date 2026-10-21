@extends('layouts.app')

@section('main')
    <main class="w-full flex-1 bg-slate-50 px-5 py-7 text-slate-900 sm:px-8 lg:px-12">
        <div class="w-full">
            <a href="{{ route('account.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900">
                <i class="fa-solid fa-arrow-left text-xs"></i> ユーザー一覧
            </a>
            <header class="mb-6 mt-5">
                <p class="text-xs font-semibold tracking-wide text-emerald-700">ユーザー管理</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight">{{ isset($user) ? 'ユーザー情報の編集' : 'ユーザーの追加' }}</h1>
            </header>

            <section class="w-full rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7 lg:p-8">
                <h2 class="text-base font-semibold">基本情報</h2>

                    @if ($errors->any())
                        <div id="alert" role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 md:col-span-2">
                            <p class="font-semibold"><i class="fa-solid fa-circle-exclamation mr-2"></i>入力内容を確認してください</p>
                            <ul class="mt-2 list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('account.save', ['id' => isset($user) ? $user->id : 'new']) }}" method="POST" class="mt-5 grid gap-x-8 gap-y-5 md:grid-cols-2">
                        @csrf
                        <div>
                            <label for="staff_id" class="mb-2 block text-sm font-medium text-slate-700">職員番号 <span class="text-xs text-emerald-700">必須</span></label>
                            <div class="flex items-center rounded-lg border border-slate-300 bg-white px-3 transition focus-within:border-emerald-600 focus-within:ring-2 focus-within:ring-emerald-600/15">
                                <i class="fa-solid fa-hashtag w-6 text-slate-400"></i>
                                <input id="staff_id" type="text" name="staff_id" autocomplete="off" value="{{ isset($user) ? $user->staff_id : old('staff_id') }}" required class="w-full border-0 bg-transparent py-3 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:ring-0" placeholder="例）A-001">
                            </div>
                        </div>

                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-slate-700">ユーザー名 <span class="text-xs text-emerald-700">必須</span></label>
                            <div class="flex items-center rounded-lg border border-slate-300 bg-white px-3 transition focus-within:border-emerald-600 focus-within:ring-2 focus-within:ring-emerald-600/15">
                                <i class="fa-regular fa-user w-6 text-slate-400"></i>
                                <input id="name" type="text" name="name" autocomplete="name" value="{{ isset($user) ? $user->name : old('name') }}" required class="w-full border-0 bg-transparent py-3 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:ring-0" placeholder="氏名を入力">
                            </div>
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-medium text-slate-700">メールアドレス <span class="text-xs text-emerald-700">必須</span></label>
                            <div class="flex items-center rounded-lg border border-slate-300 bg-white px-3 transition focus-within:border-emerald-600 focus-within:ring-2 focus-within:ring-emerald-600/15">
                                <i class="fa-regular fa-envelope w-6 text-slate-400"></i>
                                <input id="email" type="email" name="email" autocomplete="email" value="{{ isset($user) ? $user->email : old('email') }}" required class="w-full border-0 bg-transparent py-3 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:ring-0" placeholder="name@example.jp">
                            </div>
                        </div>

                        @if (isset($user))
                            <div class="rounded-lg bg-slate-50 p-3 md:col-span-2">
                                <p class="text-xs font-semibold text-slate-700">パスワードの変更</p>
                                <p class="mt-1 text-xs leading-5 text-slate-500">変更するときだけ、新しいパスワードを入力してください。</p>
                            </div>
                        @endif

                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label for="password" class="mb-2 block text-sm font-medium text-slate-700">パスワード <span class="text-xs text-emerald-700">{{ isset($user) ? '任意' : '必須' }}</span></label>
                                <div class="flex items-center rounded-lg border border-slate-300 bg-white px-3 transition focus-within:border-emerald-600 focus-within:ring-2 focus-within:ring-emerald-600/15">
                                    <i class="fa-solid fa-key w-6 text-slate-400"></i>
                                    <input id="password" type="password" name="password" autocomplete="new-password" {{ isset($user) ? '' : 'required' }} class="w-full min-w-0 border-0 bg-transparent py-3 text-sm text-slate-900 outline-none focus:ring-0">
                                </div>
                            </div>
                            <div>
                                <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">パスワード確認 <span class="text-xs text-emerald-700">{{ isset($user) ? '任意' : '必須' }}</span></label>
                                <div class="flex items-center rounded-lg border border-slate-300 bg-white px-3 transition focus-within:border-emerald-600 focus-within:ring-2 focus-within:ring-emerald-600/15">
                                    <i class="fa-solid fa-check w-6 text-slate-400"></i>
                                    <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password" {{ isset($user) ? '' : 'required' }} class="w-full min-w-0 border-0 bg-transparent py-3 text-sm text-slate-900 outline-none focus:ring-0">
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-end md:col-span-2">
                            <a href="{{ route('account.index') }}" class="rounded-lg px-4 py-2.5 text-center text-sm font-medium text-slate-600 transition hover:bg-slate-100">キャンセル</a>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-700/20">
                                {{ isset($user) ? '変更を保存' : 'ユーザーを登録' }}
                            </button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>
@endsection
