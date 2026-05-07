@extends('old.layouts.app')

@section('main')
    <main class="min-h-[800px] min-w-[1100px] flex flex-col overflow-y-auto overflow-x-hidden mt-5">
        <div class="flex flex-row w-[1000px] mx-auto">
            <div class="flex justify-center items-center w-full p-3">
                <a href="{{ route('account.create') }}"
                    class="transition bg-gray-200 border-2 border-gray-300 rounded shadow p-2 w-[500px] hover:bg-gray-100 hover:-translate-y-1 hover:-translate-x-1 hover:shadow-lg">
                    <div class="flex flex-row items-center justify-center">
                        <div class="flex-shrink pr-4">
                            <div class="rounded p-3 bg-blue-600"><i class="fa fa-user fa-2x fa-fw fa-inverse"></i></div>
                        </div>
                        <div class="text-center">
                            <h3 class="font-bold text-3xl">ユーザー追加</h3>
                            <h5 class="font-bold uppercase text-gray-500">新規ユーザーを作成</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="flex justify-center">
            <div class="w-screen border border-gray-300 mt-10 mb-5 mx-5"></div>
        </div>

        <div class="flex flex-col px-4 sm:px-10 my-5">
            <div class="min-h-[632px] bg-gray-200 overflow-hidden border border-gray-200 shadow-md">
                <table class="w-screen border-collapse bg-gray-200 text-left text-sm text-gray-500 leading-normal">
                    <thead class="bg-blue-300">
                        <tr class="">
                            <th class="px-5 py-3 w-1/5">
                                職員番号
                            </th>
                            <th class="px-5 py-3 w-1/5">
                                名前
                            </th>
                            <th class="p-3 w-1/5">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="w-screen">
                        @foreach ($userList as $user)
                            <tr class="text-sm text-black bg-white border-b border-gray-200 hover:bg-indigo-100">
                                <td class="p-3 w-1/5">
                                    {{ $user->staff_id }}
                                </td>
                                <td class="p-3 w-1/5">
                                    {{ $user->name }}
                                </td>
                                <td class="p-3 flex gap-10 w-1/5">
                                    <form action="{{ route('account.edit', ['id' => $user->id]) }}" method="GET">
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 w-[30px]">
                                            <i class="fa-solid fa-pen-to-square inline-block"></i>
                                            編集
                                        </button>
                                    </form>
                                    @if ($user->id === Auth::user()->id)
                                        <div
                                            class="bg-green-300 animate-pulse flex justify-center items-center rounded-full">
                                            active
                                        </div>
                                    @else
                                        <form action="{{ route('account.destroy', ['id' => $user->id]) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 w-[30px]"
                                                onclick='return confirm("本当に削除しますか")'>
                                                <i class="fa-solid fa-trash"></i>
                                                削除
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center p-3 bg-white xs:flex-row xs:justify-between">
                <div class="flex items-center">
                    {{ $userList->appends(request()->query())->links('old.layouts.pagination') }}
                </div>
            </div>
        </div>
    </main>
@endsection
