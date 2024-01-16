@extends('layouts.app')

@section('main')
    <main class="text-gray-600 w-[900px] mx-auto mt-5 text-xl">
        <div class="flex flex-col items-center">
            <div class="flex items-center justify-center mb-5">
                <div class="mr-10">
                    敬称選択
                    @if ($keisyo == '様')
                        <a class=" bg-gray-300 text-white font-bold py-2 px-4 rounded shadow-inner cursor-not-allowed">様</a>
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => '御中']) }}"
                            class="outline outline-[1px] outline-gray-500 hover:bg-gray-500 text-gray hover:text-white font-bold py-2 px-4 rounded shadow-lg">御中</a>
                    @else
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => $fileType, 'keisyo' => '様']) }}"
                            class="outline outline-[1px] outline-gray-500 hover:bg-gray-500 text-gray hover:text-white font-bold py-2 px-4 rounded shadow-lg">様</a>
                        <a class="bg-gray-300 text-white font-bold py-2 px-4 rounded cursor-not-allowed">御中</a>
                    @endif
                </div>
                <div class="mr-10">
                    ファイル選択
                    @if ($fileType == 'excel')
                        <a
                            class="bg-green-200 text-white font-bold py-2 px-4 rounded shadow-inner cursor-not-allowed">excel</a>
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => 'pdf', 'keisyo' => $keisyo]) }}"
                            class="outline outline-[1px] outline-red-500 hover:bg-red-500 text-red-500 hover:text-white font-bold py-2 px-4 rounded shadow-lg">pdf</a>
                    @else
                        <a href="{{ route('estimate.download.index', ['id' => $estimate->id, 'fileType' => 'excel', 'keisyo' => $keisyo]) }}"
                            class="outline outline-[1px] outline-green-500 hover:bg-green-500 text-green-500 hover:text-white font-bold py-2 px-4 rounded shadow-lg">excel</a>
                        <a class="bg-red-200 text-white font-bold py-2 px-4 rounded cursor-not-allowed">pdf</a>
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
