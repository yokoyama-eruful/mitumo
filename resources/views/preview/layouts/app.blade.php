@extends('layouts.app')

@section('main')
<main class="text-gray-600 w-[900px] mx-auto mt-5">
    @include('preview.layouts.tab')
    <div class="block h-[826px] shadow-md outline outline-1 outline-gray-100 p-5">
        @yield('contents')
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

@endsection