@extends('layouts.app')

@section('main')
    <p class="text-xl font-bold px-3 pt-3 ml-10">新規</p>
    <main class="min-h-[800px] min-w-[1100px] flex flex-col overflow-y-auto overflow-x-hidden mt-5">
        <div class="flex flex-row w-[1000px] mx-auto">
            <div class="flex justify-center items-center w-full p-3">
                <a href="{{ route('estimate.create') }}"
                    class="transition bg-gray-200 border-2 border-gray-300 rounded shadow p-2 w-[500px] hover:bg-gray-100 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-lg">
                    <div class="flex flex-row items-center justify-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-blue-600"><i class="fa fa-file-text fa-2x fa-fw fa-inverse"></i></div>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-3xl">普通火災共済</h3>
                            <h5 class="font-bold uppercase text-gray-500">見積書を作成</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="flex justify-center items-center w-full p-3">
                <a href="{{ route('futsu2.create') }}"
                    class="transition bg-gray-200 border-2 border-gray-300 rounded shadow p-2 w-[500px] hover:bg-gray-100 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-lg">
                    <div class="flex flex-row items-center justify-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-green-600"><i class="fa fa-file-text fa-2x fa-fw fa-inverse"></i>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-3xl">普通火災共済Ⅱ</h3>
                            <h5 class="font-bold uppercase text-gray-500">見積書を作成</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="flex justify-center">
            <div class="w-screen border border-gray-300 mt-10 mb-5 mx-5"></div>
        </div>
        <div class="flex flex-row ml-10 space-x-5 text-xl">
            @if ($estimateType == 'futsu')
                <a
                    class="ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-blue-400 bg-blue-200">
                    <i class="fas fa-fire fa-lg text-blue-500 mr-2"></i>
                    普通火災
                </a>
                <a href="{{ route('top.index', ['estimateType' => 'futsu2']) }}"
                    class="ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:scale-110">
                    <i class="fas fa-fire fa-lg text-green-500 mr-2"></i>
                    普通火災Ⅱ
                </a>
            @else
                <a href="{{ route('top.index', ['estimateType' => 'futsu']) }}"
                    class="ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:scale-110">
                    <i class="fas fa-fire fa-lg text-blue-500 mr-2"></i>
                    普通火災
                </a>
                <a
                    class="ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-green-400 bg-green-200">
                    <i class="fas fa-fire fa-lg text-green-500 mr-2"></i>
                    普通火災Ⅱ
                </a>
            @endif
        </div>

        <div class="flex flex-col px-4 sm:px-10 my-5">
            <div class="min-h-[632px] bg-gray-200 overflow-hidden border border-gray-200 shadow-md">
                @if ($estimateType == 'futsu')
                    <table class="w-screen border-collapse bg-gray-200 text-left text-sm text-gray-500 leading-normal">
                        <thead class="bg-blue-300">
                            <tr class="">
                                <th class="px-5 py-3 w-1/5">
                                    作成日
                                </th>
                                <th class="px-5 py-3 w-1/5">
                                    所在地
                                </th>
                                <th class="px-5 py-3 w-1/5">
                                    契約者名
                                </th>
                                <th class="px-5 py-3 w-1/5">
                                    担当者
                                </th>
                                <th class="p-3 w-1/5">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="w-screen">
                            @foreach ($estimateList as $estimate)
                                <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-indigo-100">
                                    <td class="p-3 w-1/5">
                                        {{ $estimate->created_at->isoFormat('YYYY年MM月DD日') }}
                                    </td>
                                    <td class="p-3 w-1/5">
                                        {{ $estimate->location }}
                                    </td>
                                    <td class="p-3 w-1/5">
                                        {{ $estimate->contractee }}
                                    </td>
                                    <td class="p-3 w-1/5">
                                        {{ $estimate->staff_name }}
                                    </td>
                                    <td class="p-3 flex gap-10 w-1/5">
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
                    </table>
                @else
                    <table class="w-screen border-collapse bg-gray-200 text-left text-sm text-gray-500 leading-normal">
                        <thead class=" bg-green-300">
                            <tr class="">
                                <th class="px-5 py-3 w-1/5">
                                    作成日
                                </th>
                                <th class="px-5 py-3 w-1/5">
                                    所在地
                                </th>
                                <th class="px-5 py-3 w-1/5">
                                    契約者名
                                </th>
                                <th class="px-5 py-3 w-1/5">
                                    担当者
                                </th>
                                <th class="p-3  w-1/5">
                                </th>
                            </tr>
                        </thead>
                        <tbody class="w-screen">
                            @foreach ($estimateList as $estimate)
                                <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-lime-100">
                                    <td class="p-3  w-1/5">
                                        {{ $estimate->created_at->isoFormat('YYYY年MM月DD日') }}
                                    </td>
                                    <td class="p-3  w-1/5">
                                        {{ $estimate->location }}
                                    </td>
                                    <td class="p-3  w-1/5">
                                        {{ $estimate->contractee }}
                                    </td>
                                    <td class="p-3  w-1/5">
                                        {{ $estimate->staff_name }}
                                    </td>
                                    <td class="p-3 flex gap-10  w-1/5">
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
                    </table>
                @endif
            </div>
            <div class="flex flex-col items-center p-3 bg-white xs:flex-row xs:justify-between">
                <div class="flex items-center">
                    {{ $estimateList->appends(request()->query())->links('layouts.pagination') }}
                </div>
            </div>
        </div>
    </main>
@endsection
