@extends('layouts.app')

@section('main')
    <main class="w-full flex-1 bg-slate-50 px-5 py-7 text-slate-900 sm:px-8 lg:px-12">
        <div class="w-full">
            <div class="mb-7 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-emerald-700">ユーザー管理</p>
                    <h1 class="mt-1 text-2xl font-bold tracking-tight">ユーザー一覧</h1>
                </div>
                <a href="{{ route('account.create') }}" class="inline-flex items-center justify-center gap-2 self-start rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-4 focus:ring-emerald-700/20 sm:self-auto">
                    <i class="fa-solid fa-plus text-xs"></i> ユーザーを追加
                </a>
            </div>

            <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-left text-sm">
                        <thead class="border-b border-slate-200 bg-slate-50 text-xs font-semibold text-slate-500">
                            <tr>
                                <th scope="col" class="px-5 py-3.5">職員番号</th>
                                <th scope="col" class="px-5 py-3.5">ユーザー名</th>
                                <th scope="col" class="px-5 py-3.5">状態</th>
                                <th scope="col" class="px-5 py-3.5 text-right">操作</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($userList as $user)
                                <tr class="transition hover:bg-slate-50">
                                    <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $user->staff_id }}</td>
                                    <td class="px-5 py-4 font-medium text-slate-800">{{ $user->name }}</td>
                                    <td class="px-5 py-4">
                                        @if ($user->id === Auth::user()->id)
                                            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-emerald-700"><span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span>自分</span>
                                        @else
                                            <span class="text-xs text-slate-400">登録済み</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-1">
                                            <form action="{{ route('account.edit', ['id' => $user->id]) }}" method="GET">
                                                <button type="submit" class="rounded-md px-2.5 py-1.5 text-xs font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-emerald-600/30">
                                                    編集
                                                </button>
                                            </form>
                                            @if ($user->id !== Auth::user()->id)
                                                <form action="{{ route('account.destroy', ['id' => $user->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-md px-2.5 py-1.5 text-xs font-medium text-slate-400 transition hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-200" onclick='return confirm("本当に削除しますか")'>
                                                        削除
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-14 text-center">
                                        <p class="font-medium text-slate-700">ユーザーはまだ登録されていません</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center border-t border-slate-100 bg-white px-4 py-4">
                    {{ $userList->appends(request()->query())->links('layouts.pagination') }}
                </div>
            </section>
        </div>
    </main>
@endsection
