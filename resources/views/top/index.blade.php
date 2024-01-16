@extends('layouts.app')

@section('main')
    <main class="min-h-[800px]">
        <div class="flex flex-row space-x-5 justify-center p-6 md:w-[1000px] mx-auto">
            <a href="{{ route('estimate.create') }}"
                class="flex items-center w-1/2 border border-solid border-slate-400 shadow-md rounded-lg p-3 hover:bg-gray-200">
                <div
                    class="w-12 h-12 flex items-center justify-center rounded-full bg-indigo-100 text-indigo-500 flex-shrink-0">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="flex-grow ml-4">
                    <h2 class="text-gray-900 text-lg font-medium mb-1">普通火災共済見積書を作成</h2>
                    <p class="text-sm text-gray-600">普通火災共済見積書を作成します。</p>
                </div>
            </a>
            <a href="{{ route('futsu2.create') }}"
                class="flex items-center w-1/2 border border-solid border-slate-400 shadow-md rounded-lg p-3 hover:bg-gray-200">
                <div
                    class="w-12 h-12 flex items-center justify-center rounded-full bg-lime-100 text-lime-500 flex-shrink-0">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <div class="flex-grow ml-4">
                    <h2 class="text-gray-900 text-lg font-medium mb-1">普通火災共済Ⅱ見積書を作成</h2>
                    <p class="text-sm text-gray-600">普通火災共済Ⅱ見積書を作成します。</p>
                </div>
            </a>
        </div>

        <div class="flex flex-row items-center justify-center">
            @if ($estimateType == 'futsu')
                <a class="p-4 w-[480px] border border-gray-500 rounded-tl-lg bg-slate-300 shadow-inner">普通火災見積</a>
                <a href="{{ route('top.index', ['estimateType' => 'futsu2']) }}"
                    class="p-4 w-[480px] border border-black rounded-tr-lg bg-slate-100 hover:bg-slate-200">普通火災Ⅱ見積</a>
            @else
                <a href="{{ route('top.index', ['estimateType' => 'futsu']) }}"
                    class="p-4 w-[480px] border border-black rounded-tl-lg bg-slate-100 hover:bg-slate-200">普通火災見積</a>
                <a class="p-4 w-[480px] border border-gray-500 rounded-tr-lg bg-slate-300 shadow-inner">普通火災Ⅱ見積</a>
            @endif
        </div>

        <div class="container max-w-5xl px-4 mx-auto sm:px-8">
            {{-- 検索機能は保留 --}}
            {{-- <div class="flex flex-row justify-between w-full mb-3">
            <form action="" class="flex justify-center flex-row w-full space-x-3 space-y-0">
                <input type="text" name="search"
                    class="rounded-lg border border-gray-300 py-2 w-[400px] ps-4 bg-white text-gray-700 placeholder-gray-400 shadow-sm text-base focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                    placeholder="日付,所在地,契約者名,担当者名" />

                <button
                    class="flex-shrink-0 px-4 py-2 text-base font-semibold text-white bg-purple-600 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-purple-200"
                    type="submit">
                    検索
                </button>
            </form>
        </div> --}}

            <div
                class="min-w-full rounded-b-lg min-h-[694px] overflow-hidden shadow outline outline-1 outline-gray-300 bg-gray-100">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-600 text-white text-sm font-bold text-left">
                            <th class="px-5 py-3">
                                作成日
                            </th>
                            <th class="px-5 py-3">
                                所在地
                            </th>
                            <th class="px-5 py-3">
                                契約者名
                            </th>
                            <th class="px-5 py-3">
                                担当者
                            </th>
                            <th class="p-3 w-[200px]">
                            </th>
                        </tr>
                    </thead>
                    @if ($estimateType == 'futsu')
                        <tbody>
                            @foreach ($estimateList as $estimate)
                                <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-indigo-100">
                                    <td class="p-3 w-[180px]">
                                        {{ $estimate->created_at->isoFormat('YYYY年MM月DD日(ddd)') }}
                                    </td>
                                    <td class="p-3 w-36">
                                        {{ $estimate->location }}
                                    </td>
                                    <td class="p-3">
                                        {{ $estimate->contractee }}
                                    </td>
                                    <td class="p-3">
                                        {{ $estimate->staff_name }}
                                    </td>
                                    <td class="p-3 flex gap-10 w-[200px]">
                                        <form action="{{ route('estimate.edit', ['id' => $estimate->id]) }}" method="GET">
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 w-[30px]">
                                                <i class="fa-solid fa-pen-to-square inline-block"></i>
                                                編集
                                            </button>
                                        </form>
                                        <form
                                            action="{{ route('estimate.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 w-[30px]"
                                                onclick='return confirm("複製しますか？")'>
                                                <i class="fa-solid fa-clone"></i>
                                                複製
                                            </button>
                                        </form>
                                        <form
                                            action="{{ route('estimate.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 w-[30px]"
                                                onclick='return confirm("本当に削除しますか")'>
                                                <i class="fa-solid fa-trash"></i>
                                                削除
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @else
                        <tbody>
                            @foreach ($estimateList as $estimate)
                                <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-lime-100">
                                    <td class="p-3 w-[180px]">
                                        {{ $estimate->created_at->isoFormat('YYYY年MM月DD日(ddd)') }}
                                    </td>
                                    <td class="p-3 w-36">
                                        {{ $estimate->location }}
                                    </td>
                                    <td class="p-3">
                                        {{ $estimate->contractee }}
                                    </td>
                                    <td class="p-3">
                                        {{ $estimate->staff_name }}
                                    </td>
                                    <td class="p-3 flex gap-10 w-[200px]">
                                        <form action="{{ route('futsu2.edit', ['id' => $estimate->id]) }}" method="GET">
                                            <button type="submit" class="text-indigo-600 hover:text-indigo-900 w-[30px]">
                                                <i class="fa-solid fa-pen-to-square inline-block"></i>
                                                編集
                                            </button>
                                        </form>
                                        <form
                                            action="{{ route('futsu2.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu2']) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 w-[30px]"
                                                onclick='return confirm("複製しますか？")'>
                                                <i class="fa-solid fa-clone"></i>
                                                複製
                                            </button>
                                        </form>
                                        <form
                                            action="{{ route('futsu2.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu2']) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 w-[30px]"
                                                onclick='return confirm("本当に削除しますか")'>
                                                <i class="fa-solid fa-trash"></i>
                                                削除
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </div>
        <div class="flex flex-col items-center p-3 bg-white xs:flex-row xs:justify-between">
            <div class="flex items-center">
                {{ $estimateList->appends(request()->query())->links('layouts.pagination') }}
            </div>
        </div>

    </main>
@endsection
