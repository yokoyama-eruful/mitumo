@extends('layouts.app')

@section('main')
    <main class="text-gray-600 w-[900px] mx-auto mt-5 text-xl">
        <div class="flex flex-col items-center">
        <table class="w-[900px] border-collapse bg-gray-200 text-left text-sm text-gray-500 leading-normal">
            <thead class="">
                <tr class="">
                    <th class="px-5 py-3 w-1/4">
                        評価シート種類
                    </th>
                    <th class="px-5 py-3 w-1/4">
                        ファイル
                    </th>
                    <th class="p-3 w-1/4">
                    </th>
                </tr>
            </thead>
            <tbody class="w-screen">
                    <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-indigo-100">
                        <td class="p-3 w-1/4">
                            {{ $estimate->assess_item }}
                        </td>
                        <td class="p-3 w-1/4">
                            excel
                        </td>
                                <td class="p-3 w-1/4">
                                    <form method="POST"
                                    action="{{ route('futsu2.assess.download', ['id' => $estimate->id, 'fileType' => 'excel']) }}">
                                        @csrf
                                        <button class="text-blue-500 hover:text-blue-800"><i
                                                class="fa fa-download"></i>ダウンロード</button>
                                    </form>
                                </td>
                    </tr>
                    <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-indigo-100">
                        <td class="p-3 w-1/4">
                            {{ $estimate->assess_item }}
                        </td>
                        <td class="p-3 w-1/4">
                            pdf
                        </td>
                                <td class="p-3 w-1/4">
                                    <form method="POST"
                                    action="{{ route('futsu2.assess.download', ['id' => $estimate->id, 'fileType' => 'pdf']) }}">
                                        @csrf
                                        <button class="text-blue-500 hover:text-blue-800"><i
                                                class="fa fa-download"></i>ダウンロード</button>
                                    </form>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
        <div class="text-gray-600">
            <div class="px-5 py-5 mx-auto">
                <a href="{{ route('futsu2.edit', ['id' => $estimate->id]) }}"
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
