@extends('preview.layouts.app')
@section('総合', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
  <h1 class="-mt-3 mb-2 text-[26px] font-bold">総合</h1>
  @include('preview.layouts.building-info')
  <div class="flex flex-row">
    @include('preview.layouts.insurance-payout')

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

  <div class="pb-5">
    <div class="flex flex-row">
      <div class="flex h-6 w-[275px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
        補償内容
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
        総合火災
      </div>
      <div class="flex h-6 w-[275px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
        費用
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
        総合火災
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        火災
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->coverageOptions['火災'] }}
      </div>
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        残存物取り片付け費用
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->paymentOptions['残存物取り片付け費用'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        落雷
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->coverageOptions['落雷'] }}
      </div>
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        失火見舞費用
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->paymentOptions['失火見舞費用'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        破裂または爆発
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->coverageOptions['破裂または爆発'] }}
      </div>
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        地震火災費用
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->paymentOptions['地震火災費用'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        風災・雪災
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->coverageOptions['風災・雪災'] }}
      </div>
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        修理付帯費用
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->paymentOptions['修理付帯費用'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        物体の落下・衝突
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->coverageOptions['物体の落下・衝突'] }}
      </div>
      <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
        損害防止費用
      </div>
      <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
        {{ $comprehensiveCompensation->paymentOptions['損害防止費用'] }}
      </div>
    </div>

    <div class="flex flex-row">
      <div>
        <div class="flex flex-row">
          <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
            騒じょう・労働争議
          </div>
          <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
            {{ $comprehensiveCompensation->coverageOptions['騒じょう・労働争議'] }}
          </div>
        </div>
        <div class="flex flex-row">
          <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
            水ぬれ
          </div>
          <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
            {{ $comprehensiveCompensation->coverageOptions['水ぬれ'] }}
          </div>
        </div>
        <div class="flex flex-row">
          <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
            盗難
          </div>
          <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
            {{ $comprehensiveCompensation->coverageOptions['盗難'] }}
          </div>
        </div>
        <div class="flex flex-row">
          <div class="flex h-6 w-[275px] items-center justify-center outline outline-1 outline-gray-500">
            水害
          </div>
          <div class="flex h-6 w-[100px] items-center justify-center outline outline-1 outline-gray-500">
            {{ $comprehensiveCompensation->coverageOptions['水害'] }}
          </div>
        </div>
      </div>
      <div class="h-24 w-[375px] bg-green-200 outline outline-1 outline-gray-500">
      </div>
    </div>
  </div>
@endsection
