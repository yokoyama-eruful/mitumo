@extends('preview.layouts.app')
@section('地震総合', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
    <h1 class="text-[26px] font-bold mb-2 -mt-3">地震総合</h1>
    @include('preview.layouts.building-info')

    <div class="flex flex-row">

        @include('preview.layouts.insurance-payout')

        <div>
            <div class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
                総合共済掛金
            </div>
            <div>
                <div class="flex items-center justify-center h-[120px] w-[200px] outline outline-1 outline-gray-500">
                    @if ($shortTerm->shortTermRate != 0)
                        -
                    @else
                        {{ yenUnit($comprehensivePremium->getComprehensivePremium()) }}
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-row">
        <div class="flex justify-center items-center h-16 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
        </div>
        <div class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ '支払縮小割合：' . percentUnit($estimate->EarthquakeList['earthquake1']) }}
        </div>
        <div class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ '支払縮小割合：' . percentUnit($estimate->EarthquakeList['earthquake2']) }}
        </div>
        <div class="flex justify-center items-center h-16 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ '支払縮小割合：' . percentUnit($estimate->EarthquakeList['earthquake3']) }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-20 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            共済金支払最大額
        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ thousandYenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake1'])['共済金支払最大額']) }}
        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ thousandYenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake2'])['共済金支払最大額']) }}
        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ thousandYenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake3'])['共済金支払最大額']) }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-20 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            地震掛金
        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake1'])['地震掛金']) }}
        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake2'])['地震掛金']) }}
        </div>
        <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake3'])['地震掛金']) }}
        </div>

    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-20 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            合計掛金
        </div>
        <div name="earth_reco" class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake1'])['合計掛金']) }}
        </div>
        <div name="earth_reco" class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake2'])['合計掛金']) }}
        </div>
        <div name="earth_reco" class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake3'])['合計掛金']) }}
        </div>
    </div>
@endsection
