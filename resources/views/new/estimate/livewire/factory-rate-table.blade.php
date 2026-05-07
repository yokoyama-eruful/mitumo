<div class="new-ref-overlay">
    <div class="new-ref-modal">
        <button class="new-ref-close" wire:click='onClickCloseTable' type="button">×</button>
        <div class="new-ref-body">
            <div class="new-ref-tabs md:grid-cols-3 xl:grid-cols-6">
                @foreach (['すべて', '金属および機械器具工場', '電気業', '化学工場', 'パルプおよび製紙工場', '窯業および土石工場', '紡織工場', '製材および木工業', '食料品工場', '印刷および紙工業', '石油工業', 'その他の工業', '準作業施設', '貯蔵施設', '付属施設', '特殊物件'] as $category)
                    <button type="button" @class([
                        'new-ref-tab',
                        'new-ref-tab-active' => $this->workSurchargeLargeCategory == $category,
                    ]) wire:click="onClickFactoryLargeCategory('{{ $category }}')">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
            <table class="new-ref-table">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em]">項目</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em]">作業工程コード</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em]">基本率</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-[0.18em]">動産割増</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->factoryCodeList as $item)
                        <tr class="new-ref-row" wire:click="onSelectedFactoryBuildingCode('{{ $item['作業工程コード'] }}')">
                            <td class="px-6 py-4">
                                <div class="text-base font-semibold">{{ $item['中項目'] . $item['小項目'] }}</div>
                                <div class="mt-1 text-sm" style="color: var(--new-muted);">{{ $item['項目'] }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $item['作業工程コード'] }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item['基本率'] }}</td>
                            <td class="px-6 py-4 text-sm">{{ $item['動産割増'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
