<main class="w-[800px] m-auto text-black bg-white">
    @if (Session::has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ Session::get('message') }}</span>
        </div>
    @endif

    <form id="estimateFormId" action="{{ route('estimate.save', ['id' => $estimate->id ?? 'new']) }}"
        onsubmit="return false;" method="POST" class="flex flex-col container p-2 mt-2">
        @csrf
        <div class=" estimate-input-box">
            <label class="estimate-label" for="company">
                取扱代理店名
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="company" name="company" class="estimate-input" wire:model="company" required>
        </div>

        <div class="estimate-input-box">
            <label class="estimate-label" for="address">住所
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="address" name="address" class="estimate-input" wire:model="address" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="phone_number">電話番号
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="phone_number" name="phone_number" class="estimate-input" wire:model="phoneNumber" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="staff_name">担当者
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="staff_name" name="staff_name" class="estimate-input" wire:model="staffName" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="contractee">ご契約者名
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="contractee" name="contractee" class="estimate-input" wire:model="contractee" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="location">所在地
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="location" name="location" class="estimate-input" wire:model="location" required>
        </div>

        <div class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600">
            <label class="p-3">共済の目的およびこれを収容する建物の構造・用法</label>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label for="floor_area" class="estimate-label border">延床面積</label>
                <input id="floor_area" name="floor_area" type="text" class="estimate-input" wire:model="floorArea">
                <span class="my-auto mx-1">m²</span>
            </div>
            <div class="estimate-input-box">
                <label for="exclusive_area" class="estimate-label">専有面積</label>
                <input id="exclusive_area" name="exclusive_area" type="text" class="estimate-input"
                    wire:model="exclusiveArea">
                <span class="my-auto mx-1">m²</span>
            </div>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label class="estimate-label" for="structure">構造
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="structure" name="structure" wire:model="structure" class="estimate-input" required>
                    <option value="" hidden></option>
                    @foreach ($structureList as $item)
                        <option value={{ $item }} @if ($item === $structure) selected @endif>
                            {{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_structure">その他構造</label>
                <input id="other_structure" name="other_structure" class="estimate-input" wire:model="otherStructure">
                <!-- 任意・常時 -->
            </div>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label class="estimate-label" for="pillar">柱無時</label>
                <select id="pillar" name="pillar" wire:model="pillar" class="estimate-input">
                    <option value="" hidden></option>
                    @foreach ($pillarList as $item)
                        <option value={{ $item }} @if ($item === $pillar) selected @endif>
                            {{ $item }}</option>
                    @endforeach
                </select>
                <!-- 任意・常時 -->
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_pillar">その他柱無時</label>
                <input id="other_pillar" name="other_pillar" class="estimate-input" wire:model="otherPillar">
                <!-- 任意・常時 -->
            </div>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label class="estimate-label" for="fire_resistance">耐火基準</label>
                <select id="fire_resistance" name="fire_resistance" wire:model="fireResistance"
                    class="estimate-input">
                    <option value="" hidden>選択してください</option>
                    @foreach ($fireResistanceList as $item)
                        <option value={{ $item }} @if ($item === $fireResistance) selected @endif>
                            {{ $item }}</option>
                    @endforeach
                </select>
                <!-- 任意・常時 -->
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_fire_resistance">その他耐火基準</label>
                <input id="other_fire_resistance" name="other_fire_resistance" class="estimate-input"
                    wire:model="otherFireResistance">
                <!-- 任意・常時 -->
            </div>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label class="estimate-label" for="usage">用法
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="usage" name="usage" wire:model="usage" class="estimate-input" required>
                    <option value="" hidden>選択してください</option>
                    @foreach ($usageList as $item)
                        <option value={{ $item }} @if ($item === $usage) selected @endif>
                            {{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_usage">その他用法</label>
                <input id="other_usage" name="other_usage" class="estimate-input"
                    wire:model="otherUsage"><!-- 任意・常時 -->
            </div>
        </div>


        <div class="estimate-input-box">
            <label class="estimate-label" for="floor">階
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="floor" name="floor" class="estimate-input" wire:model="floor" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="building">棟
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="building" name="building" class="estimate-input" wire:model="building" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="other">その他</label>
            <input id="other" name="other" class="estimate-input" wire:model="other">
            <!-- 任意・常時 -->
        </div>

        <div class="estimate-input-box mt-5">
            <div class="flex">
                <label class="estimate-label" for="property_type">物件
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="property_type" name="property_type" class="estimate-input w-44"
                    wire:model="propertyType" wire:change="onChangePropertyType" required>
                    <option value="" hidden>選択してください</option>
                    @foreach ($propertyTypeList as $item)
                        <option value={{ $item }} @if ($item === $usage) selected @endif>
                            {{ $item }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex">
                <label class="estimate-label" for="rank">級別
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="rank" name="rank" class="estimate-input w-44" wire:model="rank"
                    wire:change="onChangeBaseRate" required>
                    <option value="" hidden>選択してください</option>
                    @foreach ($rankList as $item)
                        <option value={{ $item }} @if ($item === $rank) selected @endif>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="start_date">始期日
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="start_date" name="start_date" type="month" class="estimate-input text-right"
                placeholder="西暦を入力してください" wire:model="startDate" wire:change="onChangeBuildingAge" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="building_year">建築年
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="building_year" name="building_year" class="estimate-input text-right"
                placeholder="西暦を入力してください" wire:model="buildingYear" wire:change="onChangeBuildingAge" required>
            <span class="my-auto mx-1">年</span>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="building_age">築年数
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="building_age" name="building_age"
                class="estimate-input hover:bg-white focus:bg-white text-right" placeholder="自動で算出されます"
                wire:model="buildingAge" required readonly>
            <span class="my-auto mx-1">年</span>

        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="pay_method">払込方法
                <div class="estimate-input-required">※必須</div>
            </label>
            <select id="pay_method" name="pay_method" wire:model="payMethod" wire:change="onChangeRecommend"
                class="estimate-input text-right" required>
                <option value="" hidden>選択してください</option>
                @foreach ($payMethodList as $item)
                    <option value={{ $item }} @if ($item === $rank) selected @endif>
                        {{ $item }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="estimate-input-box mb-5 flex">
            <label class="estimate-label" for="insurance_year">共済期間</label>
            <div class="flex flex-row" class="estimate-input">
                <div class="w-1/3 flex flex-row">
                    <input id="insurance_year" name="insurance_year" wire:model="insuranceYear"
                        wire:change="onChangeRecommend" class="text-right focus:bg-blue-100">
                    <span class="my-auto mx-1">年</span>
                </div>
                <div class="w-1/3 flex flex-row">
                    <input id="insurance_month" name="insurance_month" wire:model="insuranceMonth"
                        class="text-right focus:bg-blue-100">
                    <span class="w-16 p-1 flex items-center">ヶ月</span>
                </div>
                <div class="w-1/3 flex flex-row">
                    <input id="insurance_day" name="insurance_day" class="text-right focus:bg-blue-100"
                        wire:model="insuranceDay">
                    <span class="my-auto mx-1">日</span>
                    <!-- 任意・常時 -->
                </div>
            </div>
        </div>

        <!-- 変動のある項目 -->
        @if ($payMethod === '一時払' && $insuranceYear == 1)
            <div class="my-5">
                <div
                    class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600">
                    <label class="p-3">おすすめ契約期間指定（年数）</label>
                </div>
                <div class="estimate-input-box">
                    <input id="recommend1" name="recommend1" class="estimate-input text-right"
                        wire:model="recommend1">
                    <span class="my-auto mx-1">年</span>
                    <input id="recommend2" name="recommend2" class="estimate-input text-right"
                        wire:model="recommend2">
                    <span class="my-auto mx-1">年</span>
                    <input id="recommend3" name="recommend3" class="estimate-input text-right"
                        wire:model="recommend3">
                    <span class="my-auto mx-1">年</span>
                </div>
            </div>
        @endif

        @if (in_array($propertyType, ['普通', '工場'], true) && $insuranceYear == 1)
            <div class="my-5">
                <div
                    class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600">
                    <label class="p-3">地震特約縮小支払割合指定</label>
                </div>
                <div class="estimate-input-box">
                    <select id="earthquake1" name="earthquake1" wire:model="earthquake1" class="estimate-input">
                        @foreach ($earthquakeList as $item)
                            <option value={{ $item }} @if ($item === 15) selected @endif>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                    <span class="my-auto mx-1">%</span>
                    <select id="earthquake2" name="earthquake2" wire:model="earthquake2" class="estimate-input">
                        @foreach ($earthquakeList as $item)
                            <option value={{ $item }} @if ($item === 30) selected @endif>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                    <span class="my-auto mx-1">%</span>
                    <select id="earthquake3" name="earthquake3" wire:model="earthquake3" class="estimate-input">
                        @foreach ($earthquakeList as $item)
                            <option value={{ $item }} @if ($item === 50) selected @endif>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                    <span class="my-auto mx-1">%</span>
                </div>
            </div>
        @endif

        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="base_rate">建物基本率
                    <div class="estimate-input-required">※必須</div>
                </label>
                @if (in_array($propertyType, ['住宅', '普通'], true))
                    <input id="base_rate" name="base_rate" class="estimate-input"
                        placeholder="物件が「住宅」か「普通」の時は、自動算出されます" wire:model="baseRate" readonly>
                @else
                    <input id="base_rate" name="base_rate" class="estimate-input" wire:model="baseRate">
                @endif
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="chattel_surcharge">動産割増
                    @if ($propertyType === '工場')
                        <div class="estimate-input-required">※必須</div>
                    @endif
                </label>
                <input id="chattel_surcharge" name="chattel_surcharge" class="estimate-input"
                    wire:model="chattelSurcharge">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="chattel_surcharge_category">動産割増（カテゴリ）
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="chattel_surcharge_category" name="chattel_surcharge_category" class="estimate-input"
                    wire:model="chattelSurchargeCategory">
            </div>
        @endif
        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="actual_loss_factor">実損払係数
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="actual_loss_factor" name="actual_loss_factor" class="estimate-input"
                    wire:model="actualLossFactor">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_discount">その他割引
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="other_discount" name="other_discount" class="estimate-input" wire:model="otherDiscount">
            </div>
        @endif
        @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box mt-5 w-auto">
                <label class="estimate-label h-auto">職業割増</label>
                <div class="w-auto">
                    <div class="flex">
                        <label class="estimate-label w-32" for="occupational_surcharge_code">標準コード</label>
                        <button type="button" wire:click="onClickOpenOccupationalSurchargeTable"
                            class="mx-2 my-1 px-2 h-8 w-64 bg-gray-700 hover:bg-gray-600 text-white rounded">コードを参照する</button>
                        <input id="occupational_surcharge_code" name="occupational_surcharge_code"
                            class="estimate-input" wire:model="occupationalSurchargeCode"
                            wire:change="onChangeOccupationalSurchargeCode">
                    </div>
                    <div class="flex">
                        <label class="estimate-label w-32" for="occupational_surcharge_rate">職業割増</label>
                        <div class="mx-2 w-64 text-sm">※調整は,手動でお願いします</div>
                        <input id="occupational_surcharge_rate" name="occupational_surcharge_rate"
                            class="estimate-input" wire:model="occupationalSurchargeRate">
                    </div>
                </div>
            </div>
        @endif
        @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box w-auto">
                <label class="estimate-label h-auto">作業割増</label>
                <div class="w-auto">
                    <div class="flex">
                        <label class="estimate-label w-32" for="work_surcharge_code">作業工程コード</label>
                        <button type="button" wire:click="onClickOpenWorkSurchargeTable"
                            class="mx-2 my-1 px-2 h-8 w-64 bg-gray-700 hover:bg-gray-600 text-white rounded">コードを参照する</button>
                        <input id="work_surcharge_code" name="work_surcharge_code" class="estimate-input"
                            wire:model="workSurchargeCode" wire:change="onChangeWorkSurchargeCode">
                    </div>
                    <div class=" flex">
                        <label class="estimate-label w-32" for="work_surcharge_rate">作業割増</label>
                        <div class="mx-2 w-64 text-sm">※調整は,手動でお願いします</div>
                        <input id="work_surcharge_rate" name="work_surcharge_rate" class="estimate-input"
                            wire:model="workSurchargeRate">
                    </div>
                </div>
            </div>
        @endif
        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div class="estimate-input-box mt-5 flex justify-between">
                <label class="estimate-label w-1/3 bg-indigo-500 outline outline-1">共済の目的</label>
                <label class="estimate-label w-1/3 bg-indigo-500 outline outline-1">共済金額</label>
                <label class="estimate-label w-1/3 bg-indigo-500 outline outline-1">共済金額合計</label>
            </div>
        @endif
        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label w-1/3" for="building_insurance_payout">建物共済金額</label>
                <input id="building_insurance_payout" name="building_insurance_payout" class="estimate-input  w-1/3"
                    wire:model="buildingInsurancePayout">
                <input class="estimate-input  w-1/3">
            </div>
        @endif
        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label w-1/3" for="household_insurance_payout">家財共済金額</label>
                <input id="household_insurance_payout" name="household_insurance_payout" class="estimate-input w-1/3"
                    wire:model="householdInsurancePayout">
                <input class="estimate-input w-1/3">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="facility_insurance_payout">
                    設備共済金額
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="facility_insurance_payout" name="facility_insurance_payout" class="estimate-input"
                    wire:model="facilityInsurancePayout">
                <input class="estimate-input">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_facility_insurance_payout">その他設備共済金額
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="other_facility_insurance_payout" name="other_facility_insurance_payout"
                    class="estimate-input" wire:model="otherFacilityInsurancePayout">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="item_insurance_payout">商品共済金額</label>
                <input id="item_insurance_payout" name="item_insurance_payout" class="estimate-input"
                    wire:model="itemInsurancePayout">
                <input class="estimate-input">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_item_insurance_payout">その他商品共済金額</label>
                <input id="other_item_insurance_payout" name="other_item_insurance_payout" class="estimate-input"
                    wire:model="otherItemInsurancePayout">
            </div>
        @endif

        <div class="estimate-input-box mt-5">
            <label class="estimate-label" for="assess_item">評価項目</label>
            <select id="assess_item" name="assess_item" wire:model="assessItem" wire:change="onChangeAssessItem"
                class="estimate-input">
                <option value="" hidden>選択してください</option>
                @foreach ($assessItemList as $item)
                    <option value="{{ $item }}" @if ($item === $assessItem) selected @endif>
                        {{ $item }}</option>
                @endforeach
            </select>
        </div>
        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="assess_usage">用途
                    <div class="estimate-input-required">※必須</div>
                </label>

                <select id="assess_usage" name="assess_usage" wire:model="assessUsage" class="estimate-input">
                    <option value="" hidden>選択してください</option>
                    @foreach ($assessUsageList as $item)
                        <option value="{{ $item }}" @if ($item === $assessUsage) selected @endif>
                            {{ $item }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="assess_structure">構造
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="assess_structure" name="assess_structure" wire:model="assessStructure"
                    class="estimate-input">
                    <option value="" hidden>選択してください</option>
                    @foreach ($assessStructureList as $item)
                        <option value="{{ $item }}" @if ($item === $assessStructure) selected @endif>
                            {{ $item }}</option>
                    @endforeach
                </select>
            </div>
        @endif
        @if (in_array($assessItem, ['ピロティー付'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="piloti_floor_area">ピロティーの延床面積
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="piloti_floor_area" name="piloti_floor_area" class="estimate-input"
                    wire:model="pilotiFloorArea">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div class="estimate-input-box">
                <label class="estimate-label text-sm" for="replacement_price">従来建物の<br>再調達価格
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="replacement_price" name="replacement_price" class="estimate-input"
                    wire:model="replacementPrice">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="renovation_cost">改装費用
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="renovation_cost" name="renovation_cost" class="estimate-input"
                    wire:model="renovationCost">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="conventional_age">従来建物の築年
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="conventional_age" name="conventional_age" class="estimate-input"
                    wire:model="conventionalAge">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="depreciation_rate">従来建物の償却率
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="depreciation_rate" name="depreciation_rate" class="estimate-input"
                    wire:model="depreciationRate">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="renovation_age">改装部分の築年
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="renovation_age" name="renovation_age" class="estimate-input" wire:model="renovationAge">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="renovation_depreciation_rate">改装部分の償却率
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="renovation_depreciation_rate" name="renovation_depreciation_rate" class="estimate-input"
                    wire:model="renovationDepreciationRate">
            </div>
        @endif
        @if (in_array($assessItem, ['太陽光付'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="installation_date">設置日
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="installation_date" type="date" name="installation_date" class="estimate-input"
                    wire:model="installationDate">
            </div>
        @endif
        @if (in_array($assessItem, ['太陽光付'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="installation_cost">設置金額
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="installation_cost" name="installation_cost" class="estimate-input"
                    wire:model="installationCost">
            </div>
        @endif
        @if (in_array($assessItem, ['年次取得'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="annual_price">年次価額
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="annual_price" name="annual_price" class="estimate-input" wire:model="annualPrice">
            </div>
        @endif

        <button type="button" onclick="submitForm(this)" name="save"
            class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col w-full mt-5">
            <div class="flex items-center mb-3">
                <div
                    class="w-8 h-8 mr-2 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                    <i class="fa-solid fa-floppy-disk"></i>
                </div>
                <h2 class="text-lg font-medium">保存</h2>
            </div>
            <p class="text-left pl-5">入力された内容を保存します。<br>保存したのちプレビューが可能になります。</p>
        </button>

        @if ($estimate->id)
            <div class="grid grid-cols-3 gap-4 mb-6">
                <button type="button" onclick="submitForm(this)" value="preview"
                    class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col w-full mt-5">
                    <div class="flex items-center mt-2 mb-5">
                        <div
                            class="w-8 h-8 mr-2 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                            <i class="fa-solid fa-floppy-disk"></i>
                        </div>
                        <div class="text-lg font-medium">プレビュー</div>
                    </div>
                    <p class="text-left pl-5">入力した内容で見積書をプレビューする。</p>
                </button>

                <button type="button" onclick="submitForm(this)" value="assessSheet"
                    class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col w-full mt-5">
                    <div class="flex items-center mb-2">
                        <div
                            class="w-8 h-8 mr-2 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                            <i class="fa-solid fa-ranking-star"></i>
                        </div>
                        <div class="text-lg font-medium">評価シートを<br>ダウンロード</div>
                    </div>
                    <p class="text-left pl-5">評価シートをダウンロード方法を選択します。</p>
                </button>

                <button type="button" onclick="submitForm(this)" value="download"
                    class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col w-full mt-5">
                    <div class="flex items-center mb-2">
                        <div
                            class="w-8 h-8 mr-2 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div class="text-lg font-medium">ダウンロードファイル選択</div>
                    </div>
                    <p class="text-left pl-5">ダウンロードする見積書を選択する画面に移動します。</p>
                </button>
            </div>
        @endif
    </form>
    @if ($isViewOccupationalTable)
        @include('estimate.livewire.occupational-table')
    @endif

    @if ($isViewWorkTable)
        @include('estimate.livewire.work-table')
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('disableScroll', function() {
                document.documentElement.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
            });
            Livewire.on('enableScroll', function() {
                document.documentElement.style.overflow = 'auto';
                document.body.style.overflow = 'auto';
            });

            Livewire.on('alert', function(message) {
                alert(message)
            });
        });
    </script>

    <script>
        function submitForm(button) {
            const submitInput = document.createElement("input");
            submitInput.setAttribute("name", "submit_type");
            submitInput.setAttribute("value", button.value);

            document.getElementById('estimateFormId').appendChild(submitInput);
            document.getElementById('estimateFormId').submit();
        }
    </script>
</main>
