@extends('futsu2.preview.layouts.app')
@section('地震普通2', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
  <h1 class="text-[26px] font-bold mb-2 -mt-3">地震普通Ⅱ</h1>
  @include('futsu2.preview.layouts.building-info')

  <div class="flex flex-row">

    @include('futsu2.preview.layouts.insurance-payout')

    <div>
      <div class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
        普通Ⅱ共済掛金
      </div>
      <div>
        <div class="flex flex-col items-center justify-center h-[120px] w-[200px] outline outline-1 outline-gray-500">
          <div>{{ yenUnit($standardPremium->getStandardPremium()) }}</div>
          @if ($estimate['pay_method'] == '分割払' && $standardPremium->getStandardPremium() < 1000)
            <div class="text-red-500 text-xs">掛金が1000円未満の場合<br>分割払いはできません</div>
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
    <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake1'])['合計掛金']) }}
    </div>
    <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake2'])['合計掛金']) }}
    </div>
    <div class="flex justify-center items-center h-20 w-[200px] outline outline-1 outline-gray-500">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake3'])['合計掛金']) }}
    </div>
  </div>
@endsection
