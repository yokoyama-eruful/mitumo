<div class="flex flex-row">
    <a href="{{ route('futsu2.preview.standard', ['id' => $estimate->id]) }}"
        class="rounded-t-lg outline outline-1 outline-gray-200 w-[400px] hover:bg-gray-300 text-center @yield('普通2')">
        普通Ⅱ
    </a>

    <a href="{{ route('futsu2.preview.standardOfRecommend', ['id' => $estimate->id]) }}"
        class="rounded-t-lg outline outline-1 outline-gray-200 w-[400px] hover:bg-gray-300 text-center @yield('おすすめ普通2')">
        おすすめ普通Ⅱ
    </a>

    <a href="{{ route('futsu2.preview.standardOfEarthquake', ['id' => $estimate->id]) }}"
        class="rounded-t-lg outline outline-1 outline-gray-200 w-[400px] hover:bg-gray-300 text-center @yield('地震普通2')">
        地震普通Ⅱ
    </a>

    <a href="{{ route('futsu2.preview.calculation', ['id' => $estimate->id]) }}"
        class="rounded-t-lg outline outline-1 outline-gray-200 w-[400px] hover:bg-gray-300 text-center @yield('計算2')">
        計算
    </a>
</div>
