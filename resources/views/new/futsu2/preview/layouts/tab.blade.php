<div class="new-preview-tabs md:grid-cols-4">
  <a href="{{ route('new.futsu2.preview.standard', ['id' => $estimate->id]) }}"
    class="new-preview-tab @yield('普通2')">
    普通Ⅱ
  </a>

  @if (array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail))
    <a href="{{ route('new.futsu2.preview.standardOfRecommend', ['id' => $estimate->id]) }}"
      class="new-preview-tab @yield('おすすめ普通2')">
      おすすめ普通Ⅱ
    </a>
  @endif

  @if (array_keys_exists(['earthquake1', 'earthquake2', 'earthquake3'], $estimate->property_detail))
    <a href="{{ route('new.futsu2.preview.standardOfEarthquake', ['id' => $estimate->id]) }}"
      class="new-preview-tab @yield('地震普通2')">
      地震普通Ⅱ
    </a>
  @endif

  <a href="{{ route('new.futsu2.preview.calculation', ['id' => $estimate->id]) }}"
    class="new-preview-tab @yield('計算')">
    計算
  </a>
</div>
