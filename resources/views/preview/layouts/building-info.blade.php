<div class="pb-5">
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            所在地
        </div>
        <div class="flex items-center h-10 w-[650px] outline outline-1 outline-gray-500 pl-5">
            {{ $estimate->location }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-20 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            建物の構造
        </div>
        <div class=" h-20 w-[325px] outline outline-1 outline-gray-500 pl-5 pt-2 overflow-x-auto">
            {{ $estimate->buildingStructure }}
            <div>
                {{ $estimate->floorWithFloor }}
                {{ $estimate->BuildingWithFloor }}
                {{ $estimate->FloorAreaWithFloor }}
                {{ $estimate->ExclusiveAreaWithFloor }}
            </div>
        </div>
        <div class="flex justify-center items-center h-20 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            物件
        </div>
        <div class="flex justify-center items-center text-2xl h-20 w-[225px] outline outline-1 outline-gray-500">
            {{ $estimate->property_type }}
        </div>
    </div>
</div>

<div class="pb-5">
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            構造級別
        </div>
        <div class="flex items-center h-10 w-[275px] outline outline-1 outline-gray-500 pl-5">
            {{ $estimate->rank }}
        </div>
        <div class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            払込方法
        </div>
        <div class="flex items-center h-10 w-[275px] outline outline-1 outline-gray-500 pl-5">
            {{ $estimate->pay_method }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            地域(等地)
        </div>
        <div class="flex items-center h-10 w-[275px] outline outline-1 outline-gray-500 pl-5">
        </div>
        <div class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            共済期間
        </div>
        <div class="flex items-center h-10 w-[275px] outline outline-1 outline-gray-500 pl-5">
            {{ $estimate->InsurancePeriod }}
        </div>
    </div>
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            その他
        </div>
        <div class="flex items-center h-10 w-[650px] outline outline-1 outline-gray-500 pl-5">
            {{ $estimate->OtherItem }}
        </div>
    </div>
</div>
