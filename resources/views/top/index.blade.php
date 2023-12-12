@extends('layouts.app')

@section('main')

<main class="min-h-[800px] flex justify-center text-gray-600">
    <div class="container w-[800px]">
        <div class="p-4">
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
            <a href="{{ route('estimate.create') }}">
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
</main>

@endsection