@extends('preview.layouts.app')
@section('おすすめ普&総', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
    <h1 class="text-[26px] font-bold mb-2 -mt-3">おすすめ普通&総合</h1>
    @include('preview.layouts.building-info')

    <div class="flex flex-row">

        @include('preview.layouts.insurance-payout')

        <div>
            <div class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
                総合共済掛金
            </div>
            <div>
                <div class="flex items-center h-10 w-[200px] outline outline-1 outline-gray-500 pl-5">
                    {{ yenUnit($comprehensivePremium->getComprehensivePremium()) }}
                </div>
            </div>
            <div class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
                普通共済掛金
            </div>
            <div>
                <div class="flex items-center h-10 w-[200px] outline outline-1 outline-gray-500 pl-5">
                    {{ yenUnit($standardPremium->getStandardPremium()) }}
                </div>
            </div>
        </div>
    </div>

    <div>★ おすすめ契約 ① ★　（総合火災共済の場合）</div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
        </div>
        <div name="reco_c"
            class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ yearUnit($estimate->RecommendList['recommend1']) }}
        </div>
        <div name="reco2_c"
            class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ yearUnit($estimate->RecommendList['recommend2']) }}
        </div>
        <div name="reco3_c"
            class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ yearUnit($estimate->RecommendList['recommend3']) }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            共済掛金
        </div>
        <div name="reco_c" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend1'])['共済掛金']) }}
        </div>
        <div name="reco2_c" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend2'])['共済掛金']) }}
        </div>
        <div name="reco3_c" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend3'])['共済掛金']) }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            １年更新の場合より
        </div>
        <div name="reco_c" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend1'])['１年更新の場合より']) . 'お得' }}
        </div>
        <div name="reco2_c" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend2'])['１年更新の場合より']) . 'お得' }}
        </div>
        <div name="reco3_c" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendComprehensivePremium->getRecommendPremium((int) $estimate->RecommendList['recommend3'])['１年更新の場合より']) . 'お得' }}
        </div>
    </div>
    <div class="pt-2">★ おすすめ契約 ②　★　（普通火災共済の場合）</div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
        </div>
        <div name="reco"
            class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ yenUnit(yearUnit($estimate->RecommendList['recommend1'])) }}
        </div>
        <div name="reco2"
            class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ yenUnit(yearUnit($estimate->RecommendList['recommend2'])) }}
        </div>
        <div name="reco3"
            class="flex justify-center items-center h-10 w-[200px] bg-green-200 outline outline-1 outline-gray-500">
            {{ yenUnit(yearUnit($estimate->RecommendList['recommend3'])) }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            共済掛金
        </div>
        <div name="reco" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendStandardPremium->getRecommendPremium((int) $estimate->RecommendList['recommend1'])['共済掛金']) }}
        </div>
        <div name="reco2" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendStandardPremium->getRecommendPremium((int) $estimate->RecommendList['recommend2'])['共済掛金']) }}
        </div>
        <div name="reco3" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendStandardPremium->getRecommendPremium((int) $estimate->RecommendList['recommend3'])['共済掛金']) }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[150px] bg-green-200 outline outline-1 outline-gray-500">
            １年更新の場合より
        </div>
        <div name="reco" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendStandardPremium->getRecommendPremium((int) $estimate->RecommendList['recommend1'])['１年更新の場合より']) . 'お得' }}
        </div>
        <div name="reco2" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendStandardPremium->getRecommendPremium((int) $estimate->RecommendList['recommend2'])['１年更新の場合より']) . 'お得' }}
        </div>
        <div name="reco3" class="flex justify-center items-center h-10 w-[200px] outline outline-1 outline-gray-500">
            {{ yenUnit($recommendStandardPremium->getRecommendPremium((int) $estimate->RecommendList['recommend3'])['１年更新の場合より']) . 'お得' }}
        </div>
    </div>
@endsection
