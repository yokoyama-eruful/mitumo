@extends('preview.layouts.app')
@section('地震普通', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
    <h1 class="text-[26px] font-bold mb-2 -mt-3">地震普通</h1>
    @include('preview.layouts.building-info')

    <div class="flex flex-row">

        @include('preview.layouts.insurance-payout')

        <div>
            <div class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
                普通共済金額
            </div>
            <div>
                <div class="flex items-center h-[120px] w-[200px] outline outline-1 outline-gray-500 pl-5">
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-row">
        <div class="flex justify-center items-center h-16 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
        </div>
        <div class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">

        </div>
        <div class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">

        </div>
        <div class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">

        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-20 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            共済金支払最大額
        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-20 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            地震掛金
        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>

    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-20 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            合計掛金
        </div>

        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">

        </div>
    </div>
@endsection
