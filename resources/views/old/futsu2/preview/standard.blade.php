@extends('futsu2.preview.layouts.app')
@section('普通2', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
  <h1 class="text-[26px] font-bold mb-2 -mt-3">普通火災Ⅱ</h1>
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

  <div class="pb-5">
    <div class="flex flex-row">
      <div class="flex justify-center items-center h-6 w-[650px] bg-green-200 outline outline-1 outline-gray-500">
        補償内容
      </div>
      <div class="flex justify-center items-center h-6 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
        普通火災Ⅱ
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
        火災
      </div>
      <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
        {{ $standardCompensation->coverageOptions['火災'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
        落雷
      </div>
      <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
        {{ $standardCompensation->coverageOptions['落雷'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
        破裂または爆発
      </div>
      <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
        {{ $standardCompensation->coverageOptions['破裂または爆発'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
        風災・雪災
      </div>
      <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
        {{ $standardCompensation->coverageOptions['風災・雪災'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
        物体の落下・衝突
      </div>
      <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
        {{ $standardCompensation->coverageOptions['物体の落下・衝突'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div>
        <div class="flex flex-row">
          <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
            騒じょう・労働争議
          </div>
          <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
            {{ $standardCompensation->coverageOptions['騒じょう・労働争議'] }}
          </div>
        </div>
        <div class="flex flex-row">
          <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
            水ぬれ
          </div>
          <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
            {{ $standardCompensation->coverageOptions['水ぬれ'] }}
          </div>
        </div>
        <div class="flex flex-row">
          <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
            盗難
          </div>
          <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
            {{ $standardCompensation->coverageOptions['盗難'] }}
          </div>
        </div>
        <div class="flex flex-row">
          <div class="flex justify-center items-center h-6 w-[650px] outline outline-1 outline-gray-500">
            水害
          </div>
          <div class="flex justify-center items-center h-6 w-[100px] outline outline-1 outline-gray-500">
            {{ $standardCompensation->coverageOptions['水害'] }}
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
