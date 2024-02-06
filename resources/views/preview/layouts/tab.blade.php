<div class="grid grid-cols-9">
    <a href="{{ route('estimate.preview.standard', ['id' => $estimate->id]) }}"
        class="rounded-t-lg outline outline-1 outline-gray-200 col-span-1 hover:bg-gray-300 text-center @yield('普通')">
        普通
    </a>

    @if ($estimate->property_type != '工場' && empty($shortTerm->shortTermRate))
        <a href="{{ route('estimate.preview.comprehensive', ['id' => $estimate->id]) }}"
            class="rounded-t-lg outline outline-1 outline-gray-200 hover:bg-gray-300 text-center @yield('総合')">
            総合
        </a>
    @endif

    @if ($estimate->property_type != '工場' && empty($shortTerm->shortTermRate))
        <a href="{{ route('estimate.preview.standardAndComprehensive', ['id' => $estimate->id]) }}"
            class="rounded-t-lg outline outline-1 outline-gray-200 hover:bg-gray-300 text-center @yield('普通＆総合')">
            普通&総合
        </a>
    @endif

    <a href="{{ route('estimate.preview.standardOfRecommend', ['id' => $estimate->id]) }}"
        class="rounded-t-lg outline outline-1 outline-gray-200 hover:bg-gray-300 text-center @yield('おすすめ普通')">
        おすすめ普通
    </a>

    @if ($estimate->property_type != '工場' && empty($shortTerm->shortTermRate))
        <a href="{{ route('estimate.preview.comprehensiveOfRecommend', ['id' => $estimate->id]) }}"
            class="rounded-t-lg outline outline-1 outline-gray-200 hover:bg-gray-300 text-center @yield('おすすめ総合')">
            おすすめ総合
        </a>
    @endif

    @if ($estimate->property_type != '工場' && empty($shortTerm->shortTermRate))
        <a href="{{ route('estimate.preview.standardOfRecommendAndComprehensiveOfRecommend', ['id' => $estimate->id]) }}"
            class="rounded-t-lg outline outline-1 outline-gray-200 hover:bg-gray-300 text-center @yield('おすすめ普&総')">
            おすすめ普&総
        </a>
    @endif

    <a href="{{ route('estimate.preview.standardOfEarthquake', ['id' => $estimate->id]) }}"
        class="rounded-t-lg outline outline-1 outline-gray-200 hover:bg-gray-300 text-center @yield('地震普通')">
        地震普通
    </a>

    @if ($estimate->property_type != '工場' && empty($shortTerm->shortTermRate))
        <a href="{{ route('estimate.preview.comprehensiveOfEarthquake', ['id' => $estimate->id]) }}"
            class="rounded-t-lg outline outline-1 outline-gray-200 hover:bg-gray-300 text-center @yield('地震総合')">
            地震総合
        </a>
    @endif

    <a href="{{ route('estimate.preview.calculation', ['id' => $estimate->id]) }}"
        class="rounded-t-lg outline outline-1 outline-gray-200 hover:bg-gray-300 text-center @yield('計算')">
        計算
    </a>
</div>
