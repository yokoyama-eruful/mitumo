@extends('new.layouts.app')

@section('title', 'mitumo Users')

@section('main')
    <section class="mb-4 flex justify-end">
        <a href="{{ route('new.account.create') }}"
            class="new-ghost-button inline-flex items-center gap-2">
            <i class="fa-solid fa-user-plus text-xs"></i>
            <span>ユーザー追加</span>
        </a>
    </section>

    <section class="new-ui-panel overflow-hidden">
        <div class="new-table-wrap">
            <table class="new-table">
                <thead class="text-xs uppercase tracking-[0.18em]">
                    <tr>
                        <th class="px-6 py-4 font-medium">職員番号</th>
                        <th class="px-6 py-4 font-medium">名前</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($userList as $user)
                        <tr>
                            <td class="px-6 py-4">{{ $user->staff_id }}</td>
                            <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('new.account.edit', ['id' => $user->id]) }}"
                                        class="rounded-full border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-950">
                                        編集
                                    </a>
                                    @if ($user->id === Auth::user()->id)
                                        <span class="new-table-chip">
                                            active
                                        </span>
                                    @else
                                        <form action="{{ route('new.account.destroy', ['id' => $user->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="rounded-full border border-rose-200 px-4 py-2 text-sm font-medium text-rose-700 transition hover:border-rose-600 hover:bg-rose-600 hover:text-white"
                                                onclick='return confirm("本当に削除しますか")'>
                                                削除
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-5" style="border-top: 1px solid var(--new-soft-border);">
            {{ $userList->appends(request()->query())->links('new.layouts.pagination') }}
        </div>
    </section>
@endsection
