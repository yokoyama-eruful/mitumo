<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="w-full h-full bg-white m-8 rounded-lg">
        <button class="absolute top-2 right-12  hover:text-gray-700" wire:click='onClickCloseTable'>
            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <div class="my-10 pb-10 px-5 h-screen overflow-y-scroll">
            <div class="flex pt-4 text-sm lg:text-lg font-bold">
                <div class="flex-1 p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->occupationalSurchargeLargeCategory == 'すべて') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                    wire:click="onClickOccupationalLargeCategory('すべて')">
                    すべて
                </div>
                <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->occupationalSurchargeLargeCategory == '商業（店舗）物件') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                    wire:click="onClickOccupationalLargeCategory('商業（店舗）物件')">
                    商業（店舗）物件
                </div>
                <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->occupationalSurchargeLargeCategory == '事務所物件') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                    wire:click="onClickOccupationalLargeCategory('事務所物件')">
                    事務所物件
                </div>
                <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->occupationalSurchargeLargeCategory == '公益事業物件') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                    wire:click="onClickOccupationalLargeCategory('公益事業物件')">
                    公益事業物件
                </div>
                <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->occupationalSurchargeLargeCategory == '文教用物件') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                    wire:click="onClickOccupationalLargeCategory('文教用物件')">
                    文教用物件
                </div>
                <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->occupationalSurchargeLargeCategory == 'サービス業物件') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                    wire:click="onClickOccupationalLargeCategory('サービス業物件')">
                    サービス業物件
                </div>
                <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->occupationalSurchargeLargeCategory == 'その他の物件') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                    wire:click="onClickOccupationalLargeCategory('その他の物件')">
                    その他の物件
                </div>
                <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->occupationalSurchargeLargeCategory == '森林物件') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                    wire:click="onClickOccupationalLargeCategory('森林物件')">
                    森林物件
                </div>
            </div>
            <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg mb-10">
                <tbody>
                    @foreach ($this->occupationalSurchargeCodeList as $item)
                        <tr class="transition-colors hover:bg-gray-100 cursor-pointer"
                            wire:click="onSelectedOccupationalCode('{{ $item['標準コード'] }}')">
                            <td class="border-y px-6 py-4">
                                <div class="text-lg font-semibold">{{ $item['中項目'] . $item['小項目'] }}</div>
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
