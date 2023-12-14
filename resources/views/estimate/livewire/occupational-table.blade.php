<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="w-full h-full bg-white m-8 rounded-lg">
        <button class="absolute top-2 right-12  hover:text-gray-700" wire:click='onClickCloseTable'>
            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="my-10 pb-10 px-14 h-screen overflow-y-scroll">
            <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg mb-10">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-4"></th>
                        <th class="px-6 py-4 w-32">標準コード</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->occupationalSurchargeCodeList as $item)
                        <tr class="transition-colors hover:bg-gray-100 cursor-pointer"
                            wire:click="onSelectedOccupationalCode('{{ $item['標準コード'] }}')">
                            <td class="border-y px-6 py-4">
                                <div class="text-lg font-semibold">{{ $item['小項目'] }}</div>
                                <div class="text-base ms-5"> {{ $item['備考'] }}</div>
                            </td>
                            <td class="border-y px-6 py-4">
                                {{ $item['標準コード'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
