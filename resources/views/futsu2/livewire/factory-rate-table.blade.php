<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="w-full h-full bg-white m-8 rounded-lg">
        <button class="absolute top-2 right-12  hover:text-gray-700" wire:click='onClickCloseTable'>
            <svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="my-10 pb-10 px-5 h-screen overflow-y-scroll">
            <div>
                <div class="flex pt-4 text-sm lg:text-lg font-bold">
                    <div class="flex-1 p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == 'すべて') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('すべて')">
                        すべて
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '金属および機械器具工場') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('金属および機械器具工場')">
                        金属および<br>機械器具工場
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '電気業') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('電気業')">
                        電気業
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '化学工場') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('化学工場')">
                        化学工場
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == 'パルプおよび製紙工場') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('パルプおよび製紙工場')">
                        パルプおよび製紙工場
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '窯業および土石工場') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('窯業および土石工場')">
                        窯業および土石工場
                    </div>
                </div>
                <div class="flex pt-1 text-sm lg:text-lg font-bold">
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '紡織工場') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('紡織工場')">
                        紡織工場
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '製材および木工業') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('製材および木工業')">
                        製材および木工業
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '食料品工場') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('食料品工場')">
                        食料品工場
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '印刷および紙工業') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('印刷および紙工業')">
                        印刷および紙工業
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '石油工業') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('石油工業')">
                        石油工業
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == 'その他の工業') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('その他の工業')">
                        その他の工業
                    </div>
                </div>
                <div class="flex pt-1 text-sm lg:text-lg font-bold">
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '準作業施設') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('準作業施設')">
                        準作業施設
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '貯蔵施設') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('貯蔵施設')">
                        貯蔵施設
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '付属施設') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('付属施設')">
                        付属施設
                    </div>
                    <div class="flex-1 p-2 md:p-3 text-center bg-gray-200 hover:bg-gray-400 @if ($this->workSurchargeLargeCategory == '特殊物件') bg-blue-300 hover:bg-blue-300 @endif rounded-t-md cursor-pointer"
                        wire:click="onClickFactoryLargeCategory('特殊物件')">
                        特殊物件
                    </div>
                </div>
            </div>
            <table class="min-w-full bg-white border border-gray-300 shadow-md rounded-lg overflow-hidden">
                <tbody>
                    @foreach ($this->factoryCodeList as $item)
                        <tr class="transition-colors hover:bg-gray-100 cursor-pointer"
                            wire:click="onSelectedFactoryBuildingCode('{{ $item['作業工程コード'] }}')">
                            <td class="border-y px-6 py-4">
                                <div class="text-lg font-semibold">{{ $item['中項目'] . $item['小項目'] }}</div>
                                <div class="text-base ms-5"> {{ $item['項目'] }}</div>
                            </td>
                            <td class="border-y px-6 py-4">
                                {{ $item['作業工程コード'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
