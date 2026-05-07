<div class="grid grid-cols-9">
  <a class="@yield('普通') col-span-1 rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
    href="{{ route('estimate.preview.standard', ['id' => $estimate->id]) }}">
    普通
  </a>

  @if ($estimate->property_type != '工場')
    <a class="@yield('総合') rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
      href="{{ route('estimate.preview.comprehensive', ['id' => $estimate->id]) }}">
      総合
    </a>
  @endif

  @if ($estimate->property_type != '工場')
    <a class="@yield('普通＆総合') rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
      href="{{ route('estimate.preview.standardAndComprehensive', ['id' => $estimate->id]) }}">
      普通&総合
    </a>
  @endif

  @if (array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail))
    <a class="@yield('おすすめ普通') rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
      href="{{ route('estimate.preview.standardOfRecommend', ['id' => $estimate->id]) }}">
      おすすめ普通
    </a>
  @endif

  @if (
      $estimate->property_type != '工場' &&
          array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail))
    <a class="@yield('おすすめ総合') rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
      href="{{ route('estimate.preview.comprehensiveOfRecommend', ['id' => $estimate->id]) }}">
      おすすめ総合
    </a>
  @endif

  @if (
      $estimate->property_type != '工場' &&
          array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail))
    <a class="@yield('おすすめ普&総') rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
      href="{{ route('estimate.preview.standardOfRecommendAndComprehensiveOfRecommend', ['id' => $estimate->id]) }}">
      おすすめ普&総
    </a>
  @endif

  @if (array_keys_exists(['earthquake1', 'earthquake2', 'earthquake3'], $estimate->property_detail))
    <a class="@yield('地震普通') rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
      href="{{ route('estimate.preview.standardOfEarthquake', ['id' => $estimate->id]) }}">
      地震普通
    </a>
  @endif

  @if (
      $estimate->property_type != '工場' &&
          array_keys_exists(['earthquake1', 'earthquake2', 'earthquake3'], $estimate->property_detail))
    <a class="@yield('地震総合') rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
      href="{{ route('estimate.preview.comprehensiveOfEarthquake', ['id' => $estimate->id]) }}">
      地震総合
    </a>
  @endif

  <a class="@yield('計算') rounded-t-lg text-center outline outline-1 outline-gray-200 hover:bg-gray-300"
    href="{{ route('estimate.preview.calculation', ['id' => $estimate->id]) }}">
    計算
  </a>
</div>
