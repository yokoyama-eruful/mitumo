<div class="new-preview-tabs {{ $estimate->property_type == '工場' ? 'md:grid-cols-6' : 'md:grid-cols-9' }}">
  <a class="new-preview-tab @yield('普通')"
    href="{{ route('new.estimate.preview.standard', ['id' => $estimate->id]) }}">
    普通
  </a>

  @if ($estimate->property_type != '工場')
    <a class="new-preview-tab @yield('総合')"
      href="{{ route('new.estimate.preview.comprehensive', ['id' => $estimate->id]) }}">
      総合
    </a>
  @endif

  @if ($estimate->property_type != '工場')
    <a class="new-preview-tab @yield('普通＆総合')"
      href="{{ route('new.estimate.preview.standardAndComprehensive', ['id' => $estimate->id]) }}">
      普通&総合
    </a>
  @endif

  @if (array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail))
    <a class="new-preview-tab @yield('おすすめ普通')"
      href="{{ route('new.estimate.preview.standardOfRecommend', ['id' => $estimate->id]) }}">
      おすすめ普通
    </a>
  @endif

  @if (
      $estimate->property_type != '工場' &&
          array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail))
    <a class="new-preview-tab @yield('おすすめ総合')"
      href="{{ route('new.estimate.preview.comprehensiveOfRecommend', ['id' => $estimate->id]) }}">
      おすすめ総合
    </a>
  @endif

  @if (
      $estimate->property_type != '工場' &&
          array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail))
    <a class="new-preview-tab @yield('おすすめ普&総')"
      href="{{ route('new.estimate.preview.standardOfRecommendAndComprehensiveOfRecommend', ['id' => $estimate->id]) }}">
      おすすめ普&総
    </a>
  @endif

  @if (array_keys_exists(['earthquake1', 'earthquake2', 'earthquake3'], $estimate->property_detail))
    <a class="new-preview-tab @yield('地震普通')"
      href="{{ route('new.estimate.preview.standardOfEarthquake', ['id' => $estimate->id]) }}">
      地震普通
    </a>
  @endif

  @if (
      $estimate->property_type != '工場' &&
          array_keys_exists(['earthquake1', 'earthquake2', 'earthquake3'], $estimate->property_detail))
    <a class="new-preview-tab @yield('地震総合')"
      href="{{ route('new.estimate.preview.comprehensiveOfEarthquake', ['id' => $estimate->id]) }}">
      地震総合
    </a>
  @endif

  <a class="new-preview-tab @yield('計算')"
    href="{{ route('new.estimate.preview.calculation', ['id' => $estimate->id]) }}">
    計算
  </a>
</div>
