@extends('layouts.app')

@section('main')
    <main class="w-[800px] m-auto text-black">
        <div class="flex flex-wrap space-x-10 py-10 justify-center">
            <form method="POST"
                action="{{ route('estimate.assess.download', ['id' => $estimate->id, 'fileType' => 'excel']) }}"
                class="bg-green-50 hover:bg-green-100">
                @csrf
                <button
                    class="h-48 w-48 outline outline-1 outline-slate-300 shadow-md rounded text-center flex justify-center items-center text-xl">
                    <div class="flex flex-col justify-center items-center">
                        <i class="fa-solid fa-file-excel text-3xl"></i>
                        Excel
                    </div>
                </button>
            </form>

            <form method="POST"
                action="{{ route('estimate.assess.download', ['id' => $estimate->id, 'fileType' => 'pdf']) }}"
                class="bg-red-50 hover:bg-red-100">
                @csrf
                <button
                    class="h-48 w-48 outline outline-1 outline-slate-300 shadow-md rounded text-center flex justify-center items-center text-xl">
                    <div class="flex flex-col justify-center items-center">
                        <i class="fa-solid fa-file-pdf text-3xl"></i>
                        PDF
                    </div>
                </button>
            </form>
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
