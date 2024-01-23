@extends('layouts.app')

@section('main')
    @if ($errors->any())
        <div id="alert"
            class="relative flex flex-col sm:flex-row bg-red-200 shadow rounded-md py-5 pl-6 pr-8 sm:pr-6 mb-5">
            <div class="flex flex-row items-center border-b sm:border-b-0 w-full sm:w-auto pb-4 sm:pb-0">
                <div class="text-red-500">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
                <div class="text-sm font-medium ml-3">エラー</div>
            </div>
            <div class="text-sm tracking-wide text-gray-500 mt-4 sm:mt-0 sm:ml-4">
                @foreach ($errors->all() as $error)
                    <li class="">{{ $error }}</li>
                @endforeach
            </div>
        </div>
    @endif
    <main class="min-h-[800px] flex justify-center pt-10">
        <form action="{{ route('account.save', ['id' => isset($user) ? $user->id : 'new']) }}" method="POST">
            @csrf
            <div class="relative z-0 w-[800px] mb-6 group">
                <input type="text" name="staff_id"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none   focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    value="{{ isset($user) ? $user->staff_id : old('staff_id') }}" placeholder=" " required />
                <label
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    職員番号</label>
            </div>
            <div class="relative z-0 w-[800px] mb-6 group">
                <input type="text" name="name"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none   focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    value="{{ isset($user) ? $user->name : old('name') }}" placeholder=" " required />
                <label
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    ユーザー名</label>
            </div>
            <div class="relative z-0 w-[800px] mb-6 group">
                <input type="text" name="email"
                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none   focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                    value="{{ isset($user) ? $user->email : old('email') }}" placeholder=" " required />
                <label
                    class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                    メールアドレス</label>
            </div>
            @if (isset($user))
                <p class="text-red-500 text-xl font-bold">※パスワードは変更が必要な場合のみ入力</p>
                <div class="relative z-0 w-[800px] mb-6 group">
                    <input type="password" name="password"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none   focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " />
                    <label
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">パスワード</label>
                </div>
                <div class="relative z-0 w-[800px] mb-6 group">
                    <input type="password" name="password_confirmation" id="floating_repeat_password"
                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none   focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                        placeholder=" " />
                    <label
                        class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">パスワード確認
                    </label>
                @else
                    <div class="relative z-0 w-[800px] mb-6 group">
                        <input type="password" name="password"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none   focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">パスワード</label>
                    </div>
                    <div class="relative z-0 w-[800px] mb-6 group">
                        <input type="password" name="password_confirmation" id="floating_repeat_password"
                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none   focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " required />
                        <label
                            class="peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:left-0 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">パスワード確認
                        </label>
                    </div>
            @endif

            {{-- <div class="flex flex-wrap justify-center items-center space-x-5 mb-5">
                <div class="flex items-center pl-4 border border-gray-200 rounded">
                    <input checked id="slave" type="radio" value="" name="privilege"
                        class="w-[200px] h-4 bg-gray-100 border-gray-300">
                    <label class="w-full py-4 ml-2 text-sm font-medium text-gray-900">一般</label>
                </div>
                <div class="flex items-center pl-4 border border-gray-200 rounded">
                    <input id="master" type="radio" value="管理者" name="privilege"
                        class="p-10 w-[200px] h-4 bg-gray-100 border-gray-300">
                    <label class="w-full py-4 ml-2 text-sm font-medium text-gray-900">管理者</label>
                </div>
            </div> --}}

            <button type="submit"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center">登録</button>
    </main>
@endsection
