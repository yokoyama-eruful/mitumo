@extends('layouts.app')

@section('main')
    <main class="text-gray-600 w-[900px] mx-auto mt-5 text-xl">
        <div class="flex flex-col items-center">
            <div class="flex items-center justify-center mb-5">
                <div class="mr-10">
                    敬称選択
                    @if ($keisyo == '様')
                        <a class=" bg-gray-300 text-white font-bold py-2 px-4 rounded shadow-inner cursor-not-allowed">様</a>
                        <a href="{{ route('futsu2.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => '御中']) }}"
                            class="outline outline-[1px] outline-gray-500 hover:bg-gray-500 text-gray hover:text-white font-bold py-2 px-4 rounded shadow-lg">御中</a>
                    @else
                        <a href="{{ route('futsu2.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => '様']) }}"
                            class="outline outline-[1px] outline-gray-500 hover:bg-gray-500 text-gray hover:text-white font-bold py-2 px-4 rounded shadow-lg">様</a>
                        <a class="bg-gray-300 text-white font-bold py-2 px-4 rounded cursor-not-allowed">御中</a>
                    @endif
                </div>
                <div class="mr-10">
                    ファイル選択
                    @if ($fileType == 'excel')
                        <a
                            class="bg-green-200 text-white font-bold py-2 px-4 rounded shadow-inner cursor-not-allowed">excel</a>
                        <a href="{{ route('futsu2.download.index', ['id' => $estimate->id, 'fileType' => 'pdf', 'keisyo' => $keisyo]) }}"
                            class="outline outline-[1px] outline-red-500 hover:bg-red-500 text-red-500 hover:text-white font-bold py-2 px-4 rounded shadow-lg">pdf</a>
                    @else
                        <a href="{{ route('futsu2.download.index', ['id' => $estimate->id, 'fileType' => 'excel', 'keisyo' => $keisyo]) }}"
                            class="outline outline-[1px] outline-green-500 hover:bg-green-500 text-green-500 hover:text-white font-bold py-2 px-4 rounded shadow-lg">excel</a>
                        <a class="bg-red-200 text-white font-bold py-2 px-4 rounded cursor-not-allowed">pdf</a>
                    @endif
                </div>
            </div>

            <table class="w-[900px] border-collapse bg-gray-200 text-left text-sm text-gray-500 leading-normal">
                <thead class="">
                    <tr class="">
                        <th class="px-5 py-3 w-1/4">
                            見積種類
                        </th>
                        <th class="px-5 py-3 w-1/4">
                            敬称
                        </th>
                        <th class="px-5 py-3 w-1/4">
                            ファイル
                        </th>
                        <th class="p-3 w-1/4">
                        </th>
                    </tr>
                </thead>
                <tbody class="w-screen">
                    @foreach ($estimateTypeList as $estimateType => $enableDisable)
                        <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-indigo-100">
                            <td class="p-3 w-1/4">
                                {{ $estimateType }}
                            </td>
                            <td class="p-3 w-1/4">
                                {{ $keisyo }}
                            </td>
                            <td class="p-3 w-1/4">
                                {{ $fileType }}
                            </td>
                            @if ($fileType == 'pdf')
                                @if ($enableDisable == 'disable')
                                    <td class="p-3 w-1/4">
                                        <button class="text-gray-500 cursor-not-allowed"><i
                                                class="fa fa-download"></i>ダウンロード</button>
                                    </td>
                                @else
                                    <td class="p-3 w-1/4">
                                        <form method="POST"
                                            action="{{ route('futsu2.download.type', ['id' => $estimate->id, 'category' => $estimateType, 'fileType' => $fileType, 'keisyo' => $keisyo]) }}">
                                            @csrf
                                            <button class="text-blue-500 hover:text-blue-800"><i
                                                    class="fa fa-download"></i>ダウンロード</button>
                                        </form>
                                    </td>
                                @endif
                            @else
                                @if ($enableDisable == 'disable')
                                    <td class="p-3 w-1/4">
                                        <button class="text-gray-500 cursor-not-allowed"><i
                                                class="fa fa-download"></i>ダウンロード</button>
                                    </td>
                                @else
                                    <td class="p-3 w-1/4">
                                        <form method="POST"
                                            action="{{ route('futsu2.download.type', ['id' => $estimate->id, 'category' => $estimateType, 'fileType' => $fileType, 'keisyo' => $keisyo]) }}">
                                            @csrf
                                            <button class="text-blue-500 hover:text-blue-800"><i
                                                    class="fa fa-download"></i>ダウンロード</button>
                                        </form>
                                    </td>
                                @endif
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-gray-600 w-full">
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
        </div>
    </main>
@endsection
