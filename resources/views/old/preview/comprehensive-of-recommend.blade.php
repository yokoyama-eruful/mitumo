@extends('preview.layouts.app')
@section('おすすめ総合', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
  <h1 class="-mt-3 mb-2 text-[26px] font-bold">おすすめ総合</h1>
  @include('preview.layouts.building-info')

  <div class="flex flex-row">

    @include('preview.layouts.insurance-payout')

    <div>
      <div class="flex h-10 w-[200px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
        総合共済掛金
      </div>
      <div>
        <div class="flex h-[120px] w-[200px] items-center justify-center outline outline-1 outline-gray-500">
          {{ yenUnit($comprehensivePremium->getComprehensivePremium()) }}
        </div>
      </div>
    </div>
  </div>

  <div class="flex flex-row">
    <div class="flex h-16 w-[150px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
    </div>
    <div class="flex h-16 w-[200px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500"
      name="reco_c">
      {{ yearUnit($estimate->RecommendList['recommend1']) }}
    </div>
    <div class="flex h-16 w-[200px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500"
      name="reco2_c">
      {{ yearUnit($estimate->RecommendList['recommend2']) }}
    </div>
    <div class="flex h-16 w-[200px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500"
      name="reco3_c">
      {{ yearUnit($estimate->RecommendList['recommend3']) }}
    </div>
  </div>
  <div class="flex flex-row">
    <div class="flex h-20 w-[150px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
      共済掛金
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="reco_c">
      {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend1'])['共済掛金']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="reco2_c">
      {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend2'])['共済掛金']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="reco3_c">
      {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend3'])['共済掛金']) }}
    </div>
  </div>
  <div class="flex flex-row">
    <div class="flex h-20 w-[150px] items-center justify-center bg-green-200 outline outline-1 outline-gray-500">
      １年更新の場合より
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="reco_c">
      {{ gainUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend1'])['１年更新の場合より']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="reco2_c">
      {{ gainUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend2'])['１年更新の場合より']) }}
    </div>
    <div class="flex h-20 w-[200px] items-center justify-center outline outline-1 outline-gray-500" name="reco3_c">
      {{ gainUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend3'])['１年更新の場合より']) }}
    </div>
  </div>
@endsection
