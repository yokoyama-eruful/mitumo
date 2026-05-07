<div class="new-ref-overlay">
    <div class="new-ref-modal">
        <button class="new-ref-close" wire:click='onClickCloseTable' type="button">×</button>
        <div class="new-ref-body">
            <table class="new-ref-table">
                <tbody>
                    @foreach ($this->discountRiskLargeCodeList as $LargeItem)
                        <tr>
                            <td class="new-ref-section px-6 py-4 text-lg font-bold">{{ $LargeItem }}</td>
                            <td class="new-ref-section px-6 py-4"></td>
                        </tr>
                        @foreach ($this->discountRiskCodeList as $shortItem)
                            @if ($LargeItem == $shortItem['大分類'])
                                <tr class="new-ref-row"
                                    wire:click="onSelectDiscountRiskCode('{{ $shortItem['項目番号'] }}')">
                                    <td class="px-6 py-4">
                                        <div class="text-base font-semibold">{{ $shortItem['小分類'] }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">{{ $shortItem['割引率'] . '%' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
