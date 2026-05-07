@extends('new.estimate.preview.layouts.app')
@section('地震総合', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
  <h1 class="-mt-3 mb-2 text-[26px] font-bold">地震総合</h1>
  @include('new.estimate.preview.layouts.building-info')

  <div class="flex flex-row">

    @include('new.estimate.preview.layouts.insurance-payout')
    <div>
      <div class="flex h-10 w-[200px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
        総合共済掛金
      </div>
      <div>
        <div class="flex flex-col h-[120px] w-[200px] items-center justify-center outline outline-1 outline-gray-500">
          <div>{{ yenUnit($comprehensivePremium->getComprehensivePremium()) }}</div>
          @if ($estimate['pay_method'] == '分割払' && $comprehensivePremium->getComprehensivePremium() < 1000)
            <div class="text-red-500 text-xs">掛金が1000円未満の場合<br>分割払いはできません</div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="flex flex-row">
    <div class="flex h-16 w-[150px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
    </div>
    <div class="flex h-16 w-[200px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
      {{ '支払縮小割合：' . percentUnit($estimate->EarthquakeList['earthquake1']) }}
    </div>
    <div class="flex h-16 w-[200px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
      {{ '支払縮小割合：' . percentUnit($estimate->EarthquakeList['earthquake2']) }}
    </div>
    <div class="flex h-16 w-[200px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
      {{ '支払縮小割合：' . percentUnit($estimate->EarthquakeList['earthquake3']) }}
    </div>
  </div>
  <div class="flex flex-row">
    <div class="flex h-20 w-[150px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
      共済金支払最大額
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500">
      {{ thousandYenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake1'])['共済金支払最大額']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500">
      {{ thousandYenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake2'])['共済金支払最大額']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500">
      {{ thousandYenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake3'])['共済金支払最大額']) }}
    </div>
  </div>
  <div class="flex flex-row">
    <div class="flex h-20 w-[150px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
      地震掛金
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake1'])['地震掛金']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake2'])['地震掛金']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake3'])['地震掛金']) }}
    </div>

  </div>
  <div class="flex flex-row">
    <div class="flex h-20 w-[150px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
      合計掛金
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="earth_reco">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake1'])['合計掛金']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="earth_reco">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake2'])['合計掛金']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="earth_reco">
      {{ yenUnit($earthquakePremium->getEarthquakePremium($estimate->EarthquakeList['earthquake3'])['合計掛金']) }}
    </div>
  </div>
@endsection
