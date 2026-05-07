<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="w-full h-full bg-white m-8 rounded-lg">
        <button class="absolute top-2 right-12  hover:text-gray-700" wire:click='onClickCloseTable'>
            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="my-10 pb-10 px-5 h-screen overflow-y-scroll">
            <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">
                <tbody>
                    @foreach ($this->discountRiskLargeCodeList as $LargeItem)
                        <tr class="mt-5">
                            <td class="border-y px-6 py-4 bg-blue-200">
                                <div class="text-xl font-bold">{{ $LargeItem }}</div>
                            </td>
                            <td class="border-y px-6 py-4 bg-blue-200">
                            </td>
                        </tr>
                        @foreach ($this->discountRiskCodeList as $shortItem)
                            @if ($LargeItem == $shortItem['大分類'])
                                <tr class="transition-colors hover:bg-gray-100 cursor-pointer --}}"
                                    wire:click="onSelectDiscountRiskCode('{{ $shortItem['項目番号'] }}')">
                                    <td class="border-y px-6 py-4">
                                        <div class="text-lg font-semibold">{{ $shortItem['小分類'] }}</div>
                                    </td>
                                    <td class="border-y px-6 py-4">
                                        {{ $shortItem['割引率'] . '%' }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
