<div class="new-ref-overlay">
    <div class="new-ref-modal">
        <button class="new-ref-close" wire:click='onClickCloseTable' type="button">×</button>
        <div class="new-ref-body">
            <div class="new-ref-tabs md:grid-cols-4 xl:grid-cols-8">
                @foreach (['すべて', '商業（店舗）物件', '事務所物件', '公益事業物件', '文教用物件', 'サービス業物件', 'その他の物件', '森林物件'] as $category)
                    <button type="button" @class([
                        'new-ref-tab',
                        'new-ref-tab-active' => $this->occupationalSurchargeLargeCategory == $category,
                    ]) wire:click="onClickOccupationalLargeCategory('{{ $category }}')">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
            <table class="new-ref-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em]">項目</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em]">標準コード</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em]">割増率</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->occupationalSurchargeCodeList as $item)
                        <tr class="new-ref-row" wire:click="onSelectedOccupationalCode('{{ $item['標準コード'] }}')">
                            <td class="px-6 py-4">
                                <div class="text-base font-semibold">{{ $item['中項目'] . $item['小項目'] }}</div>
                                <div class="mt-1 text-sm" style="color: var(--new-muted);">{{ $item['備考'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $item['標準コード'] }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item['割増率'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
