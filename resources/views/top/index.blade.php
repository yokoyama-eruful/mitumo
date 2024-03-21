@extends('layouts.app')

@section('main')
    <main class="min-h-[800px] flex flex-col overflow-y-auto overflow-x-hidden">
        <div class="flex flex-row justify-center space-x-10 w-screen mt-3">
            <div class="flex justify-center">
                <a href="{{ route('estimate.create') }}"
                    class="transition px-14 bg-gray-200 border-2 border-gray-300 rounded shadow p-2 hover:bg-gray-100 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-lg">
                    <div class="flex flex-row items-center justify-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-blue-600"><i class="fa fa-file-text fa-2x fa-fw fa-inverse"></i></div>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-3xl">普通・総合火災共済</h3>
                            <h5 class="font-bold uppercase text-gray-500">見積書を作成</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="flex justify-center">
                <a href="{{ route('futsu2.create') }}"
                    class="transition px-14 bg-gray-200 border-2 border-gray-300 rounded shadow p-2 hover:bg-gray-100 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-lg">
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
            <div class="w-screen border border-gray-300 mt-5 mb-5 mx-5"></div>
        </div>
        <div class="flex flex-row ml-10 space-x-5 text-xl">
            @if ($estimateType == 'futsu')
                <a
                    class="w-48 text-center ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-blue-400 bg-blue-200">
                    <i class="fas fa-fire fa-lg text-blue-500 mr-1"></i>
                    普通・総合火災
                </a>
                <a href="{{ route('top.index', ['estimateType' => 'futsu2']) }}"
                    class="w-48 text-center ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:scale-110">
                    <i class="fas fa-fire fa-lg text-green-500 mr-1"></i>
                    普通火災Ⅱ
                </a>
            @else
                <a href="{{ route('top.index', ['estimateType' => 'futsu']) }}"
                    class="w-48 text-center ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white hover:scale-110">
                    <i class="fas fa-fire fa-lg text-blue-500 mr-1"></i>
                    普通・総合火災
                </a>
                <a
                    class="w-48 text-center ease-in transition duration-400 block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-green-400 bg-green-200">
                    <i class="fas fa-fire fa-lg text-green-500 mr-1"></i>
                    普通火災Ⅱ
                </a>
            @endif
        </div>

        <div class="flex flex-col px-4 sm:px-10 mb-5">
            <div class="min-h-[522px] w-full bg-gray-200 overflow-hidden border border-gray-200 shadow-md">
                <table class="w-full border-collapse bg-gray-200 text-left text-sm text-gray-500 leading-normal">
                    <thead class="{{ $estimateType == 'futsu' ? 'bg-blue-300' : 'bg-green-300' }}">
                        <tr class="">
                            <th class="px-2 py-1">
                                作成日
                            </th>
                            <th class="px-5 py-1">
                                所在地
                            </th>
                            <th class="px-2 py-1">
                                物件
                            </th>
                            <th class="px-2 py-1">
                                級別
                            </th>
                            <th class="px-2 py-1 ">
                                共済期間（年）
                            </th>
                            <th class="px-2 py-1">
                                振込方法
                            </th>
                            <th class="px-2 py-1">
                                備考
                            </th>
                            <th class="px-5 py-1">
                                契約者名
                            </th>
                            <th class="px-5 py-1">
                                担当者
                            </th>
                            <th class="w-32">
                            </th>
                            <th class="w-16">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="w-screen">
                        @foreach ($estimateList as $estimate)
                            <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-indigo-100">
                                <td class="p-1 w-20">
                                    {{ $estimate->created_at->isoFormat('YYYY') }}
                                    <br>
                                    {{ $estimate->created_at->isoFormat('MM/DD') }}
                                </td>
                                <td class="p-1">
                                    {{ $estimate->location }}
                                </td>
                                <td class="p-1">
                                    {{ $estimate->property_type }}
                                </td>
                                <td class="p-1">
                                    {{ $estimate->rank }}
                                </td>
                                <td class="p-1">
                                    {{ $estimate->insurance_year }}
                                </td>
                                <td class="p-1">
                                    {{ $estimate->pay_method }}
                                </td>
                                <td class="p-1">
                                    {{ $estimate->other }}
                                </td>
                                <td class="p-1">
                                    {{ $estimate->contractee }}
                                </td>
                                <td class="p-1 w-28">
                                    {{ $estimate->staff_name }}
                                </td>
                                <td class="flex gap-1">
                                    @if ($estimateType == 'futsu')
                                        <form action="{{ route('estimate.edit', ['id' => $estimate->id]) }}"
                                            method="GET">
                                            <button type="submit"
                                                class="p-1 rounded text-indigo-600 hover:text-white hover:bg-indigo-600">
                                                <i class="fa-solid fa-pen-to-square inline-block"></i>
                                                編集
                                            </button>
                                        </form>
                                        <form
                                            action="{{ route('estimate.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="p-1 rounded text-green-600 hover:text-white hover:bg-green-600"
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
                                            <button type="submit"
                                                class="p-1 rounded text-red-600 hover:text-white hover:bg-red-600"
                                                onclick='return confirm("本当に削除しますか")'>
                                                <i class="fa-solid fa-trash me-1"></i>
                                                削除
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('futsu2.edit', ['id' => $estimate->id]) }}" method="GET">
                                            <button type="submit"
                                                class="p-1 rounded text-indigo-600 hover:text-white hover:bg-indigo-600">
                                                <i class="fa-solid fa-pen-to-square inline-block"></i>
                                                編集
                                            </button>
                                        </form>
                                        <form
                                            action="{{ route('futsu2.duplication', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="p-1 rounded text-green-600 hover:text-white hover:bg-green-600"
                                                onclick='return confirm("複製しますか？")'>
                                                <i class="fa-solid fa-clone"></i>
                                                複製
                                            </button>
                                        </form>
                                        <form
                                            action="{{ route('futsu2.destroy', ['id' => $estimate->id, 'estimateType' => 'futsu']) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-1 rounded text-red-600 hover:text-white hover:bg-red-600"
                                                onclick='return confirm("本当に削除しますか")'>
                                                <i class="fa-solid fa-trash me-1"></i>
                                                削除
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td class="p-1">
                                    {{ $versionDomain->Comparative($estimate->updated_at) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
            <div class="flex flex-col items-center p-3 bg-white xs:flex-row xs:justify-between">
                <div class="flex items-center">
                    {{ $estimateList->appends(request()->query())->links('layouts.pagination') }}
                </div>
            </div>
        </div>
    </main>
@endsection
