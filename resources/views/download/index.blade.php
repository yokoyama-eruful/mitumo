@extends('layouts.app')

@section('main')
    <main class="text-gray-600 w-[900px] mx-auto mt-5 text-xl">
        <div class="flex flex-col items-center">
            <div class="flex items-center justify-center mb-5">
                <div class="mr-10">
                    敬称選択
                    @if ($keisyo == '様')
                        <a href="" class=" bg-gray-300 text-white font-bold py-2 px-4 rounded shadow-inner">様</a>
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => '御中']) }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow-md">御中</a>
                    @else
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => '様']) }}"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">様</a>
                        <a href="" class="bg-gray-300 text-white font-bold py-2 px-4 rounded">御中</a>
                    @endif
                </div>
                <div class="mr-10">
                    ファイル選択
                    @if ($fileType == 'excel')
                        <a href="" class="bg-green-300 text-white font-bold py-2 px-4 rounded shadow-inner">excel</a>
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => 'pdf', 'keisyo' => $keisyo]) }}"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">pdf</a>
                    @else
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => 'excel', 'keisyo' => $keisyo]) }}"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-md">excel</a>
                        <a href="" class="bg-red-300 text-white font-bold py-2 px-4 rounded">pdf</a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-3 gap-10 mt-5">
                @foreach ($estimateTypeList as $estimateType => $enableDisable)
                    @if ($fileType == 'pdf')
                        @if ($enableDisable == 'disable')
                            <button
                                class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl bg-slate-100 text-gray-400 cursor-not-allowed">
                                {{ $estimateType }}
                            </button>
                        @else
                            <form method="POST"
                                action="{{ route('estimate.download.type', ['id' => $estimate->id, 'category' => $estimateType, 'fileType' => $fileType, 'keisyo' => $keisyo]) }}"
                                class="bg-red-50 hover:bg-red-100">
                                @csrf
                                <button
                                    class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                                    {{ $estimateType }}
                                </button>
                            </form>
                        @endif
                    @else
                        @if ($enableDisable == 'disable')
                            <div>
                                <button
                                    class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl bg-slate-100 text-gray-400 cursor-not-allowed">
                                    {{ $estimateType }}
                                </button>
                            </div>
                        @else
                            <form method="POST"
                                action="{{ route('estimate.download.type', ['id' => $estimate->id, 'category' => $estimateType, 'fileType' => $fileType, 'keisyo' => $keisyo]) }}"
                                class="bg-green-50 hover:bg-green-100">
                                @csrf
                                <button
                                    class="h-44 w-44 outline outline-1 outline-slate-300 shadow-md rounded text-center text-xl">
                                    {{ $estimateType }}
                                </button>
                            </form>
                        @endif
                    @endif
                @endforeach
            </div>

            <div class="text-gray-600 w-full">
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
