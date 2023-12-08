<div class="flex flex-row">
    <a href="{{ route('estimate.preview.standard', ['id' => $estimate->id]) }}"
        class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300">
        <button class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300 @yield('普通')"
            value="normal">普通</button>
    </a>

    <a href="{{ route('estimate.preview.comprehensive', ['id' => $estimate->id]) }}">
        <button class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300 @yield('総合')"
            value="">総合</button>
    </a>

    <a href="{{ route('estimate.preview.standardAndComprehensive', ['id' => $estimate->id]) }}">
        <button class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300 @yield('普通＆総合')"
            value="">普通&総合</button>
    </a>

    <a href="{{ route('estimate.preview.standardOfRecommend', ['id' => $estimate->id]) }}">
        <button class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300 @yield('おすすめ普通')"
            value="">おすすめ普通</button>
    </a>

    <a href="{{ route('estimate.preview.comprehensiveOfRecommend', ['id' => $estimate->id]) }}">
        <button class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300 @yield('おすすめ総合')"
            value="">おすすめ総合</button>
    </a>

    <a href="{{ route('estimate.preview.standardOfRecommendAndComprehensiveOfRecommend', ['id' => $estimate->id]) }}">
        <button class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300 @yield('おすすめ普&総')"
            value="">おすすめ普&総</button>
    </a>

    <a href="{{ route('estimate.preview.standardOfEarthquake', ['id' => $estimate->id]) }}">
        <button class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300 @yield('地震普通')"
            value="">地震普通</button>
    </a>

    <a href="{{ route('estimate.preview.comprehensiveOfEarthquake', ['id' => $estimate->id]) }}">
        <button class="rounded-b-lg outline outline-1 outline-gray-200 w-28 hover:bg-gray-300 @yield('地震総合')"
            value="">地震総合</button>
    </a>
</div>
