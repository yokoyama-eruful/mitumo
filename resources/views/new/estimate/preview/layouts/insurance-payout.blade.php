<div class="pb-5">
    <div class="flex flex-row">
        <div class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            共済の目的
        </div>
        <div class="flex justify-center items-center h-10 w-[175px] bg-green-200 outline outline-1 outline-gray-500">
            共済金額
        </div>
        <div class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
            共済の目的
        </div>
        <div class="flex justify-center items-center h-10 w-[175px] bg-green-200 outline outline-1 outline-gray-500">
            共済金額
        </div>
    </div>
    <div class="flex flex-row">
        <div>
            <div class="flex flex-row">
                <div
                    class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
                    建物
                </div>
                <div class="flex items-center justify-end pr-3 h-10 w-[175px] outline outline-1 outline-gray-500 pl-5">
                    @if (isset($estimate['property_detail']['building_insurance_payout']))
                        {{ thousandYenUnit($estimate['property_detail']['building_insurance_payout']) }}
                    @endif
                </div>
                <div
                    class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
                    家財
                </div>
                <div class="flex items-center justify-end pr-3 h-10 w-[175px] outline outline-1 outline-gray-500 pl-5">
                    @if (isset($estimate['property_detail']['household_insurance_payout']))
                        {{ thousandYenUnit($estimate['property_detail']['household_insurance_payout']) }}
                    @endif
                </div>
            </div>
            <div class="flex flex-row">
                <div
                    class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
                    設備・什器等
                </div>

                <div class="flex items-center justify-end pr-3 h-10 w-[175px] outline outline-1 outline-gray-500 pl-5">
                    @if (isset($estimate['property_detail']['facility_insurance_payout']))
                        {{ thousandYenUnit($estimate['property_detail']['facility_insurance_payout']) }}
                    @endif
                </div>

                <div
                    class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
                    商品・製品等
                </div>

                <div class="flex items-center justify-end pr-3 h-10 w-[175px] outline outline-1 outline-gray-500 pl-5">
                    @if (isset($estimate['property_detail']['item_insurance_payout']))
                        {{ thousandYenUnit($estimate['property_detail']['item_insurance_payout']) }}
                    @endif
                </div>

            </div>
            <div class="flex flex-row">
                <div
                    class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
                    その他
                </div>

                <div class="flex items-center justify-end pr-3 h-10 w-[175px] outline outline-1 outline-gray-500 pl-5">
                    @if (isset($estimate['property_detail']['other_facility_insurance_payout']))
                        {{ thousandYenUnit($estimate['property_detail']['other_facility_insurance_payout']) }}
                    @endif
                </div>

                <div
                    class="flex justify-center items-center h-10 w-[100px] bg-green-200 outline outline-1 outline-gray-500">
                    その他
                </div>

                <div class="flex items-center justify-end pr-3 h-10 w-[175px] outline outline-1 outline-gray-500 pl-5">
                    @if (isset($estimate['property_detail']['other_item_insurance_payout']))
                        {{ thousandYenUnit($estimate['property_detail']['other_item_insurance_payout']) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
