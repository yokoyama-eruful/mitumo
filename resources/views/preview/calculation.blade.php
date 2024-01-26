@extends('preview.layouts.app')
@section('計算', 'text-white bg-blue-400 hover:bg-blue-400')

@section('contents')
    <h1 class="text-[26px] font-bold mb-2 -mt-3">計算</h1>

    <div class="my-5">
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-24"></div>
            <div class="flex justify-center items-center h-10 w-16 outline-gray-500"></div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                基本率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                総合割増
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                長期率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                適用率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                掛金
            </div>
        </div>
        <div class="flex flex-row h-20">
            <div class="flex justify-center items-center w-24 outline outline-1 outline-gray-500">建物</div>
            <div class="flex flex-col justify-center items-center w-16 outline-gray-500">
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">普通</div>
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">総合</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->building['基本率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->building['基本率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    <div class="absolute w-28 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->building['総合割増'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->building['長期率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->building['長期率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->building['適用率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->building['適用率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->building['掛金'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->building['掛金'] }}</div>
            </div>
        </div>
    </div>

    <div class="my-5">
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-24"></div>
            <div class="flex justify-center items-center h-10 w-16 outline-gray-500"></div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                基本率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                総合割増
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                長期率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                適用率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                掛金
            </div>
        </div>
        <div class="flex flex-row h-20">
            <div class="flex justify-center items-center w-24 outline outline-1 outline-gray-500">家財</div>
            <div class="flex flex-col justify-center items-center w-16 outline-gray-500">
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">普通</div>
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">総合</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->houseHold['基本率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->houseHold['基本率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    <div class="absolute w-28 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->houseHold['総合割増'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->houseHold['長期率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->houseHold['長期率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->houseHold['適用率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->houseHold['適用率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->houseHold['掛金'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->houseHold['掛金'] }}</div>
            </div>
        </div>
    </div>

    <div class="my-5">
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-24"></div>
            <div class="flex justify-center items-center h-10 w-16 outline-gray-500"></div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                基本率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                総合割増
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                長期率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                適用率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                掛金
            </div>
        </div>
        <div class="flex flex-row h-20">
            <div class="flex justify-center items-center w-24 outline outline-1 outline-gray-500">設備・什器等</div>
            <div class="flex flex-col justify-center items-center w-16 outline-gray-500">
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">普通</div>
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">総合</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->facility['基本率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->facility['基本率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    <div class="absolute w-28 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->facility['総合割増'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->facility['長期率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->facility['長期率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->facility['適用率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->facility['適用率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->facility['掛金'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->facility['掛金'] }}</div>
            </div>
        </div>
    </div>

    <div class="my-5">
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-24"></div>
            <div class="flex justify-center items-center h-10 w-16 outline-gray-500"></div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                基本率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                総合割増
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                長期率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                適用率
            </div>
            <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                掛金
            </div>
        </div>
        <div class="flex flex-row h-20">
            <div class="flex justify-center items-center w-24 outline outline-1 outline-gray-500">商品・製品等</div>
            <div class="flex flex-col justify-center items-center w-16 outline-gray-500">
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">普通</div>
                <div class="flex justify-center items-center w-16 h-10 outline outline-1 outline-gray-500">総合</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->item['基本率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->item['基本率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    <div class="absolute w-28 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->item['総合割増'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->item['長期率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->item['長期率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->item['適用率'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->item['適用率'] }}</div>
            </div>
            <div class="flex flex-col justify-center items-center w-28 outline-gray-500">
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $standard->item['掛金'] }}</div>
                <div class="flex justify-center items-center w-28 h-10 outline outline-1 outline-gray-500">
                    {{ $comprehensive->item['掛金'] }}</div>
            </div>
        </div>
    </div>

    <div class="flex">
        <div class="">
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 outline outline-1 outline-gray-500">
                    <div class="absolute w-32 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                    料率
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    基本率（建物）
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ $standard->building['基本率'] }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    基本率（動産）
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ $standard->houseHold['基本率'] }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    職業割増
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ $standard->occupationalSurchargeRate }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    作業割増
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ $standard->workSurchargeRate }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    動産割増
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ $standard->chattelSurcharge }}
                </div>
            </div>

        </div>
        <div class="ms-10">
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 outline outline-1 outline-gray-500">
                    <div class="absolute w-32 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                    料率
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    リスク評価割引
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ percentUnit($standard->discountRiskRate) }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    消火設備割引
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ percentUnit($standard->discountFireExtinguisher) }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    セキュリティ割引
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ percentUnit($standard->discountSecurity) }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    公有物件等割引
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ percentUnit($standard->discountPublicProperty) }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    その他割引
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ percentUnit($standard->otherDiscount) }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    長期年払割引
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    @if ($standard->getPayByLongTermFlag() == true)
                        3%
                    @else
                    @endif
                </div>
            </div>
        </div>
        <div class="ms-10">
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 outline outline-1 outline-gray-500">
                    <div class="absolute w-32 h-[2px] transform -skew-y-[18deg] bg-gray-500"></div>
                </div>
                <div class="flex justify-center items-center h-10 w-28 bg-green-200 outline outline-1 outline-gray-500">
                    料率
                </div>
            </div>

            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    短期係数
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ percentUnit($standard->building['短期率']) }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    長期係数
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ $standard->building['長期率'] }}
                </div>
            </div>
            <div class="flex flex-row">
                <div class="flex justify-center items-center h-10 w-32 bg-green-200 outline outline-1 outline-gray-500">
                    実損払係数
                </div>
                <div class="flex items-center h-10 w-28 outline outline-1 outline-gray-500 pl-5">
                    {{ $standard->actualLossFactor }}
                </div>
            </div>

        </div>
    </div>
    @if (in_array($estimate->property_type, ['普通'], true))
        <div class="flex flex-row mt-5">
            <div class="flex justify-center items-center h-10 w-48 bg-green-200 outline outline-1 outline-gray-500">
                標準コード:{{ array_key_exists('occupational_surcharge_code', $estimate->property_detail) ? $estimate->property_detail['occupational_surcharge_code'] : '' }}
            </div>
            <div class="flex items-center h-10 w-full outline outline-1 outline-gray-500 pl-5">
                {{ $occupationalSurchargeItem->getOccupationalSurchargeItem(array_key_exists('occupational_surcharge_code', $estimate->property_detail) ? $estimate->property_detail['occupational_surcharge_code'] : '', $estimate->rank) }}
            </div>
        </div>
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-48 bg-green-200 outline outline-1 outline-gray-500">
                作業工程コード:{{ array_key_exists('work_surcharge_code', $estimate->property_detail) ? $estimate->property_detail['work_surcharge_code'] : '' }}
            </div>
            <div class="flex items-center h-10 w-full outline outline-1 outline-gray-500 pl-5">
                {{ $workSurchargeItem->getWorkSurchargeItem(array_key_exists('work_surcharge_code', $estimate->property_detail) ? $estimate->property_detail['work_surcharge_code'] : '', $estimate->rank) }}
            </div>
        </div>
    @endif
    @if (in_array($estimate->property_type, ['工場'], true))
        <div class="flex flex-row">
            <div class="flex justify-center items-center h-10 w-48 bg-green-200 outline outline-1 outline-gray-500">
                作業工程コード:{{ array_key_exists('factory_building_code', $estimate->property_detail) ? $estimate->property_detail['factory_building_code'] : '' }}
            </div>
            <div class="flex items-center h-10 w-full outline outline-1 outline-gray-500 pl-5">
                {{ $factoryPropertyBasePremiumItem->getFactoryPropertyBasePremiumItem(array_key_exists('factory_building_code', $estimate->property_detail) ? $estimate->property_detail['factory_building_code'] : '', $estimate->rank) }}
            </div>
        </div>
    @endif

@endsection
