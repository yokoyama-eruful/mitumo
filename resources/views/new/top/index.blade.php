@extends('new.layouts.app')

@section('title', 'mitumo New UI')

@section('main')
    <section class="mb-4 grid gap-3 lg:grid-cols-2">
        <a href="{{ route('new.estimate.create') }}"
            class="new-ui-card group flex items-center justify-between px-5 py-4">
            <div class="flex min-w-0 items-center gap-4">
                <div
                    class="flex h-11 w-11 flex-none items-center justify-center rounded-2xl bg-sky-100 text-lg text-sky-700 transition group-hover:bg-sky-600 group-hover:text-white">
                    <i class="fa-solid fa-fire"></i>
                </div>
                <div class="min-w-0">
                    <h2 class="truncate text-lg font-semibold sm:text-xl" style="color: var(--new-text);">普通・総合火災共済</h2>
                </div>
            </div>
            <span
                class="ml-4 inline-flex flex-none items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-2 text-sm font-medium text-sky-700 transition group-hover:border-sky-600 group-hover:bg-sky-600 group-hover:text-white">
                <span>作成</span>
                <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-0.5"></i>
            </span>
        </a>

        <a href="{{ route('new.futsu2.create') }}"
            class="new-ui-card group flex items-center justify-between px-5 py-4">
            <div class="flex min-w-0 items-center gap-4">
                <div
                    class="flex h-11 w-11 flex-none items-center justify-center rounded-2xl bg-emerald-100 text-lg text-emerald-700 transition group-hover:bg-emerald-600 group-hover:text-white">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div class="min-w-0">
                    <h2 class="truncate text-lg font-semibold sm:text-xl" style="color: var(--new-text);">普通火災共済II</h2>
                </div>
            </div>
            <span
                class="ml-4 inline-flex flex-none items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-700 transition group-hover:border-emerald-600 group-hover:bg-emerald-600 group-hover:text-white">
                <span>作成</span>
                <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-0.5"></i>
            </span>
        </a>
    </section>

    @livewire('new.search', ['type' => $type])
@endsection
