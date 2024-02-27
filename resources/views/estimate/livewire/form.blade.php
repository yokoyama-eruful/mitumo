<main class="w-full m-auto text-black bg-white">
    @if (Session::has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ Session::get('message') }}</span>
        </div>
    @endif

    <form id="estimateFormId" action="{{ route('estimate.save', ['id' => $estimate->id ?? 'new']) }}"
        onsubmit="return false;" method="POST" class="flex flex-col justify-center">
        @csrf
        <div class="block border-2 p-5 bg-blue-50 mx-10">

            <div class="flex flex-wrap">
                <div class="w-1/2 px-2">
                    <div class="estimate-title">
                        <label class="p-3">取扱代理所情報</label>
                    </div>
                    <div class=" estimate-input-box">
                        <label class="estimate-label" for="company">
                            取扱代理所名
                        </label>
                        <input id="company" name="company" class="estimate-input" wire:model="company" required>
                    </div>

                    <div class="estimate-input-box">
                        <label class="estimate-label" for="address">住所
                        </label>
                        <input id="address" name="address" class="estimate-input" wire:model="address" required>
                    </div>
                    <div class="estimate-input-box">
                        <label class="estimate-label" for="phone_number">電話番号
                        </label>
                        <input id="phone_number" name="phone_number" class="estimate-input" wire:model="phoneNumber"
                            required>
                    </div>
                    <div class="estimate-input-box">
                        <label class="estimate-label" for="staff_name">担当者
                        </label>
                        <input id="staff_name" name="staff_name" class="estimate-input" wire:model="staffName" required>
                    </div>
                </div>
                <div class="w-1/2 px-2">
                    <div class="estimate-title">
                        <label class="p-3">共済契約者情報</label>
                    </div>
                    <div class="estimate-input-box flex">
                        <label class="estimate-label" for="certificate_number">証書番号
                        </label>
                        <input id="certificate_number" name="certificate_number" class="estimate-input"
                            wire:model="certificateNumber" required>
                        <button type="button" wire:click="onClickCertificateNumber"
                            class="mx-2 px-2 h-8 sm:text-base text-[0.6rem] bg-gray-700 hover:bg-gray-600 text-white rounded whitespace-nowrap">証書番号参照</button>
                    </div>
                    <div class="estimate-input-box">
                        <label class="estimate-label" for="contractee">ご契約者名
                        </label>
                        <input id="contractee" name="contractee" class="estimate-input" wire:model="contractee"
                            required>
                    </div>
                    <div class="estimate-input-box">
                        <label class="estimate-label" for="location">所在地
                        </label>
                        <input id="location" name="location" class="estimate-input" wire:model="location" required>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-1/2 px-2">
                    <div class="estimate-title">
                        <label class="p-3">共済の目的およびこれを収容する建物の構造・用法</label>
                    </div>
                    <div class="flex flex-row">
                        <div class="estimate-input-box">
                            <label for="floor_area" class="estimate-label">延床面積</label>
                            <input id="floor_area" name="floor_area" type="text" class="estimate-input text-right"
                                wire:model="floorArea"wire:change="onChangeAssessItem">
                            <span class="unit-display">m²</span>
                        </div>
                        <div class="estimate-input-box">
                            <label for="exclusive_area" class="estimate-label">専有面積</label>
                            <input id="exclusive_area" name="exclusive_area" type="text"
                                class="estimate-input text-right"
                                wire:model="exclusiveArea"wire:change="onChangeAssessItem">
                            <span class="unit-display">m²</span>
                        </div>
                    </div>
                    <div class="flex flex-row">
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="structure">構造
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <select id="structure" name="structure" wire:model="structure" class="estimate-input"
                                required>
                                <option value="" hidden></option>
                                @foreach ($structureList as $item)
                                    <option value={{ $item }} @if ($item === $structure) selected @endif>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="other_structure">その他構造</label>
                            <input id="other_structure" name="other_structure" class="estimate-input"
                                wire:model="otherStructure">
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
                            <input id="other_pillar" name="other_pillar" class="estimate-input"
                                wire:model="otherPillar">
                            <!-- 任意・常時 -->
                        </div>
                    </div>
                    <div class="flex flex-row">
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="fire_resistance">耐火基準</label>
                            <select id="fire_resistance" name="fire_resistance" wire:model="fireResistance"
                                class="estimate-input">
                                <option value="" hidden></option>
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
                            <select id="usage" name="usage" wire:model="usage" class="estimate-input"
                                required>
                                <option value="" hidden></option>
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
                    <div class="flex flex-row">
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="floor">階
                            </label>
                            <input id="floor" name="floor" class="estimate-input" wire:model="floor" required>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="building">棟
                            </label>
                            <input id="building" name="building" class="estimate-input" wire:model="building"
                                required>
                        </div>
                    </div>
                    <div class="estimate-input-box">
                        <label class="estimate-label" for="other">その他</label>
                        <input id="other" name="other" class="estimate-input" wire:model="other">
                        <!-- 任意・常時 -->
                    </div>
                    <div class="estimate-input-box">
                        <label class="estimate-label" for="property_type">物件
                            <div class="estimate-input-required">※必須</div>
                        </label>
                        <select id="property_type" name="property_type" class="estimate-input"
                            wire:change="onChangePropertyType" required>
                            <option value="" hidden>選択してください</option>
                            @foreach ($propertyTypeList as $item)
                                <option value={{ $item }} @if ($item === $usage) selected @endif>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                        <label class="estimate-label" for="rank">級別
                            <div class="estimate-input-required">※必須</div>
                        </label>
                        <select id="rank" name="rank" class="estimate-input" wire:model="rank"
                            wire:change="onChangeBaseRate" required>
                            <option value="" hidden>選択してください</option>
                            @foreach ($rankList as $item)
                                <option value={{ $item }} @if ($item === $rank) selected @endif>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if (is_null($buildingInsurancePayout) || $buildingInsurancePayout == '')
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="start_date">始期日
                            </label>
                            <input id="start_date" name="start_date" type="month"
                                class="estimate-input text-right" placeholder="西暦を入力してください" wire:model="startDate"
                                wire:change="onChangeBuildingAge" required>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="building_year">建築年
                            </label>
                            <input id="building_year" name="building_year" class="estimate-input text-right"
                                placeholder="西暦を入力してください" wire:model="buildingYear" wire:change="onChangeBuildingAge"
                                required>
                            <span class="unit-display">年</span>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="building_age">築年数
                            </label>
                            <input id="building_age" name="building_age"
                                class="estimate-input hover:bg-white focus:bg-white text-right"
                                wire:model="buildingAge"wire:change="onChangeAssessItem" required readonly>
                            <span class="unit-display">年</span>
                        </div>
                    @else
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="start_date">始期日
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="start_date" name="start_date" type="month"
                                class="estimate-input text-right" placeholder="西暦を入力してください" wire:model="startDate"
                                wire:change="onChangeBuildingAge" required>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="building_year">建築年
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="building_year" name="building_year" class="estimate-input text-right"
                                placeholder="西暦を入力してください" wire:model="buildingYear" wire:change="onChangeBuildingAge"
                                required>
                            <span class="unit-display">年</span>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="building_age">築年数
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="building_age" name="building_age"
                                class="estimate-input hover:bg-white focus:bg-white text-right"
                                wire:model="buildingAge" required readonly>
                            <span class="unit-display">年</span>
                        </div>
                    @endif
                </div>

                <div class="w-1/2 px-2">
                    <div class="estimate-title">
                        <label class="p-3">払込方法・共済期間</label>
                    </div>
                    <div class="estimate-input-box">
                        <label class="estimate-label" for="pay_method">払込方法
                            <div class="estimate-input-required">※必須</div>
                        </label>
                        <select id="pay_method" name="pay_method" wire:model="payMethod"
                            wire:change="onChangeRecommend" class="estimate-input text-right" required>
                            <option value="" hidden>選択してください</option>
                            @foreach ($payMethodList as $item)
                                <option value={{ $item }} @if ($item === $rank) selected @endif>
                                    {{ $item }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="estimate-input-box mb-5 flex">
                        <label class="estimate-label" for="insurance_year">共済期間
                            <div class="estimate-input-required">※必須</div>
                        </label>
                        <div class="flex flex-row" class="estimate-input">
                            @if (!empty($insuranceMonth) || !empty($insuranceDay))
                                <div class="w-1/3 flex flex-row">
                                    <input id="insurance_year" name="insurance_year"
                                        class="text-right estimate-input bg-gray-300 hover:bg-gray-300"
                                        wire:model="insuranceYear" wire:change="onChangeRecommend" readonly>
                                    <span class="unit-display">年</span>
                                </div>
                            @else
                                <div class="w-1/3 flex flex-row">
                                    <input id="insurance_year" name="insurance_year" wire:model="insuranceYear"
                                        wire:change="onChangeRecommend" class="text-right estimate-input">
                                    <span class="unit-display">年</span>
                                </div>
                            @endif
                            @if (!empty($insuranceYear))
                                <div class="w-1/3 flex flex-row">
                                    <input id="insurance_month" name="insurance_month"
                                        class="text-right estimate-input bg-gray-300 hover:bg-gray-300"
                                        wire:model="insuranceMonth" wire:change="onChangeInsuranceMonth" readonly>
                                    <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">ヶ月</span>
                                </div>
                                <div class="w-1/3 flex flex-row">
                                    <input id="insurance_day" name="insurance_day"
                                        class="text-right estimate-input bg-gray-300 hover:bg-gray-300"
                                        wire:change="onChangeInsuranceDay" wire:model="insuranceDay" readonly>
                                    <span class="unit-display">日</span>
                                    <!-- 任意・常時 -->
                                </div>
                            @else
                                <div class="w-1/3 flex flex-row">
                                    <input id="insurance_month" name="insurance_month" wire:model="insuranceMonth"
                                        wire:change="onChangeInsuranceMonth" class="text-right estimate-input">
                                    <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">ヶ月</span>
                                </div>
                                <div class="w-1/3 flex flex-row">
                                    <input id="insurance_day" name="insurance_day" class="text-right estimate-input"
                                        wire:change="onChangeInsuranceDay" wire:model="insuranceDay">
                                    <span class="unit-display">日</span>
                                    <!-- 任意・常時 -->
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- 変動のある項目 -->
                    @if ($payMethod === '一時払' && $insuranceYear == 1)
                        <div class="my-5">
                            <div class="estimate-title">
                                <label class="p-3">おすすめ契約期間指定（年数）</label>
                            </div>
                            <div class="estimate-input-box">
                                <input id="recommend1" name="recommend1" class="estimate-input text-right"
                                    wire:model="recommend1" wire:change="onChangeRecommend1">
                                <span class="unit-display">年</span>
                                <input id="recommend2" name="recommend2" class="estimate-input text-right"
                                    wire:model="recommend2" wire:change="onChangeRecommend2">
                                <span class="unit-display">年</span>
                                <input id="recommend3" name="recommend3" class="estimate-input text-right"
                                    wire:model="recommend3" wire:change="onChangeRecommend3">
                                <span class="unit-display">年</span>
                            </div>
                        </div>
                    @else
                        <div class="my-5">
                            <div class="estimate-title bg-gray-300">
                                <label class="p-3">おすすめ契約期間指定（年数）</label>
                            </div>
                            <div class="estimate-input-box bg-gray-300">
                                <div class="estimate-input text-right"></div>
                                <span class="unit-display">年</span>
                                <div class="estimate-input text-right"></div>
                                <span class="unit-display">年</span>
                                <div class="estimate-input text-right"></div>
                                <span class="unit-display">年</span>
                            </div>
                        </div>
                    @endif

                    @if (in_array($propertyType, ['普通', '工場'], true) && $insuranceYear == 1)
                        <div class="my-5">
                            <div class="estimate-title">
                                <label class="p-3">地震特約縮小支払割合指定</label>
                            </div>
                            <div class="estimate-input-box">
                                <select id="earthquake1" name="earthquake1" wire:model="earthquake1"
                                    class="estimate-input text-right">
                                    <option value="15">
                                        15
                                    </option>
                                    <option value="選択しない">
                                        選択しない
                                    </option>
                                </select>
                                <span class="unit-display">%</span>
                                <select id="earthquake2" name="earthquake2" wire:model="earthquake2"
                                    class="estimate-input text-right">
                                    <option value="30">
                                        30
                                    </option>
                                    <option value="選択しない">
                                        選択しない
                                    </option>
                                </select>
                                <span class="unit-display">%</span>
                                <select id="earthquake3" name="earthquake3" wire:model="earthquake3"
                                    class="estimate-input text-right">
                                    <option value="50">
                                        50
                                    </option>
                                    <option value="選択しない">
                                        選択しない
                                    </option>
                                </select>
                                <span class="unit-display">%</span>
                            </div>
                        </div>
                    @else
                        <div class="my-5">
                            <div class="estimate-title bg-gray-300">
                                <label class="p-3">地震特約縮小支払割合指定</label>
                            </div>
                            <div class="estimate-input-box bg-gray-300">
                                <div class="estimate-input text-right"></div>
                                <span class="unit-display">%</span>
                                <div class="estimate-input text-right"></div>
                                <span class="unit-display">%</span>
                                <div class="estimate-input text-right"></div>
                                <span class="unit-display">%</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>


            <div class="flex flex-wrap">
                <div class="w-1/2 px-2">
                    @if (in_array($propertyType, ['住宅', '普通'], true))
                        <div class="estimate-title">
                            <label class="p-3">料率・係数等</label>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="building_base_rate">建物基本率
                                @if (!empty($buildingInsurancePayout))
                                    <div class="estimate-input-required">※必須</div>
                                @endif
                            </label>
                            <input id="building_base_rate" name="building_base_rate" class="estimate-input"
                                placeholder="物件が「住宅」か「普通」の時は、自動算出されます" wire:model="buildingBaseRate" readonly>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="chattel_base_rate">動産基本率
                                @if (
                                    !empty($householdInsurancePayout) ||
                                        !empty($facilityInsurancePayout) ||
                                        !empty($otherFacilityInsurancePayout) ||
                                        !empty($itemInsurancePayout) ||
                                        !empty($otherItemInsurancePayout))
                                    <div class="estimate-input-required">※必須</div>
                                @endif
                            </label>
                            <input id="chattel_base_rate" name="chattel_base_rate" class="estimate-input"
                                placeholder="物件が「住宅」か「普通」の時は、自動算出されます" wire:model="chattelBaseRate" readonly>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['工場'], true))
                        <div class="estimate-title">
                            <label class="p-3">料率・係数等</label>
                        </div>
                        <div class="estimate-input-box w-auto">
                            <label class="estimate-label h-auto">建物基本率
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <div class="w-11/12">
                                <div class="flex">
                                    <label class="estimate-label"></label>
                                    <button type="button" wire:click="onOpenFactoryRateTable"
                                        class="mx-2 px-2 sm:text-base text-[0.6rem] bg-gray-700 hover:bg-gray-600 text-white rounded">コードを参照する</button>
                                </div>
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for="work_surcharge_code">作業工程コード</label>
                                    <input id="factory_building_code" name="factory_building_code"
                                        class="estimate-input" wire:model="factoryBuildingCode"
                                        wire:change="onChangeFactoryBuildingCode">
                                </div>
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for="">備考</label>
                                    <div class="text-sm"></div>
                                    <input class="estimate-input" wire:model="selectFactory">
                                </div>
                                <div class="flex">
                                    <label class="estimate-label" for="building_base_rate">率</label>
                                    <input id="building_base_rate" name="building_base_rate" class="estimate-input"
                                        wire:model="buildingBaseRate" wire:change="onChangeFactoryBuildingRate">
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($propertyType == '工場')
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="chattel_base_rate">動産基本率
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="chattel_base_rate" name="chattel_base_rate" class="estimate-input"
                                placeholder="自動算出されます" wire:model="chattelBaseRate" readonly>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="chattel_surcharge">動産割増
                            </label>
                            <input id="chattel_surcharge" name="chattel_surcharge" class="estimate-input"
                                wire:model="chattelSurcharge">
                        </div>
                    @endif
                    @if ($propertyType == '普通')
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="chattel_surcharge_category">動産割増
                            </label>
                            <select id="chattel_surcharge_category" name="chattel_surcharge_category"
                                wire:model="chattelSurchargeCategory" class="estimate-input">
                                @foreach ($chattelSurchargeCategoryList as $item)
                                    <option value="{{ $item }}"
                                        @if ($item === $chattelSurchargeCategory) selected @endif>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="actual_loss_factor">実損払係数
                            </label>
                            <input id="actual_loss_factor" name="actual_loss_factor" class="estimate-input"
                                wire:model="actualLossFactor">
                        </div>
                    @endif
                    @if ($propertyType == '工場')
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="discount_fire_extinguisher">消火設備割引
                            </label>
                            <input id="discount_fire_extinguisher" name="discount_fire_extinguisher"
                                class="estimate-input text-right" type="number" min="0" max="100"
                                wire:model="discountFireExtinguisher">
                            <span class="unit-display">%</span>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="discount_security">セキュリティ割引
                            </label>
                            <input id="discount_security" name="discount_security" class="estimate-input text-right"
                                type="number" min="0" max="100" wire:model="discountSecurity">
                            <span class="unit-display">%</span>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label h-auto">リスク評価割引</label>
                            <div class="w-11/12">
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for="discount_risk_code"></label>
                                    <button type="button" wire:click="onClickDiscountRiskTable"
                                        class="mx-2 px-2 h-8 sm:text-base text-[0.6rem] bg-gray-700 hover:bg-gray-600 text-white rounded">評価項目表を参照</button>
                                    <input id="discount_risk_code" name="discount_risk_code" class="estimate-input"
                                        wire:model="discountRiskCode" wire:change="onChangeDiscountRiskCode" hidden>
                                </div>
                                <div class="flex">
                                    <label class="estimate-label" for="discount_risk_rate">割引率</label>
                                    <input id="discount_risk_rate" name="discount_risk_rate"
                                        class="estimate-input text-right" wire:model="discountRiskRate"
                                        wire:change="onChangeDiscountRiskRate" type="number" min="0"
                                        max="40">
                                    <span class="unit-display">%</span>
                                </div>
                            </div>
                        </div>
                    @elseif(in_array($propertyType, ['普通', '住宅'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="discount_fire_extinguisher">消火設備割引
                            </label>
                            <input id="discount_fire_extinguisher" name="discount_fire_extinguisher"
                                class="estimate-input text-right" type="number" min="0" max="100"
                                wire:model="discountFireExtinguisher">
                            <span class="unit-display">%</span>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="discount_security">セキュリティ割引
                            </label>
                            <input id="discount_security" name="discount_security" class="estimate-input text-right"
                                type="number" min="0" max="100" wire:model="discountSecurity">
                            <span class="unit-display">%</span>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label" id="discount_public" for="discount_public">公有物件割引
                            </label>
                            <input id="discount_public" name="discount_public" class="estimate-input text-right"
                                type="number" min="0" max="100" wire:model="discountPublic">
                            <span class="unit-display">%</span>
                        </div>
                        <div class="estimate-input-box">
                            <label class="estimate-label h-auto">リスク評価割引</label>
                            <div class="w-11/12">
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for="discount_risk_code"></label>
                                    <button type="button" wire:click="onClickDiscountRiskTable"
                                        class="mx-2 px-2 h-8 sm:text-base text-[0.6rem] bg-gray-700 hover:bg-gray-600 text-white rounded">評価項目表を参照</button>
                                    <input id="discount_risk_code" name="discount_risk_code" class="estimate-input"
                                        wire:model="discountRiskCode" wire:change="onChangeDiscountRiskCode" hidden>
                                </div>
                                <div class=" flex">
                                    <label class="estimate-label" for="discount_risk_rate">割引率</label>
                                    <input id="discount_risk_rate" name="discount_risk_rate"
                                        class="estimate-input text-right"
                                        wire:model="discountRiskRate"wire:change="onChangeDiscountRiskRate"
                                        type="number" min="0" max="100">
                                    <span class="unit-display">%</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['普通', '工場', '住宅'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="other_discount">その他割引
                            </label>
                            <input id="other_discount" name="other_discount" class="estimate-input text-right"
                                wire:model="otherDiscount" type="number" min="0" max="100">
                            <span class="unit-display">%</span>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['普通'], true))
                        <div class="estimate-input-box mt-5">
                            <label class="estimate-label h-auto">職業割増</label>
                            <div class="w-11/12">
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label"></label>
                                    <button type="button" wire:click="onClickOpenOccupationalSurchargeTable"
                                        class="mx-2 px-2 sm:text-base text-[0.6rem] bg-gray-700 hover:bg-gray-600 text-white rounded">コードを参照する</button>
                                </div>
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for="occupational_surcharge_code">標準コード</label>
                                    <input id="occupational_surcharge_code" name="occupational_surcharge_code"
                                        class="estimate-input" wire:model="occupationalSurchargeCode"
                                        wire:change="onChangeOccupationalSurchargeCode">
                                </div>
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for="occupational_surcharge_item">選択項目</label>
                                    <input wire:model="occupationalSurchargeItem" name="occupational_surcharge_item"
                                        class="estimate-input" readonly>
                                </div>
                                <div class="flex">
                                    <label class="estimate-label" for="occupational_surcharge_rate">率</label>
                                    <input id="occupational_surcharge_rate" name="occupational_surcharge_rate"
                                        class="estimate-input" wire:model="occupationalSurchargeRate"
                                        wire:change="onChangeOccupationalSurchargeRate">
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row">
                            <label class="estimate-label h-auto">作業割増</label>
                            <div class="w-11/12">
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for=""></label>
                                    <button type="button" wire:click="onClickOpenWorkSurchargeTable"
                                        class="mx-2 px-2 sm:text-base text-[0.6rem] bg-gray-700 hover:bg-gray-600 text-white rounded">コードを参照する</button>
                                </div>
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for="work_surcharge_code">作業工程コード</label>
                                    <input id="work_surcharge_code" name="work_surcharge_code" class="estimate-input"
                                        wire:model="workSurchargeCode" wire:change="onChangeWorkSurchargeCode">
                                </div>
                                <div class="estimate-input-box flex">
                                    <label class="estimate-label" for="work_surcharge_item">選択項目</label>
                                    <input wire:model="workSurchargeItem" name="work_surcharge_item"
                                        class="estimate-input">
                                </div>
                                <div class="flex">
                                    <label class="estimate-label" for="work_surcharge_rate">率</label>
                                    <input id="work_surcharge_rate" name="work_surcharge_rate" class="estimate-input"
                                        wire:model="workSurchargeRate" wire:change="onChangeWorkSurchargeRate">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="w-1/2 px-2">
                    @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
                        <div class="estimate-title">
                            <label class="p-3">共済金額</label>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label w-1/2" for="building_insurance_payout">建物</label>
                            <input id="building_insurance_payout" name="building_insurance_payout"
                                class="estimate-input  w-1/2 text-right" wire:model="buildingInsurancePayout">
                            <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">千円</span>

                        </div>
                    @endif
                    @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label w-1/2" for="household_insurance_payout">家財</label>
                            <input id="household_insurance_payout" name="household_insurance_payout"
                                class="estimate-input w-1/2 text-right" wire:model="householdInsurancePayout">
                            <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">千円</span>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['普通', '工場'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label w-1/2" for="facility_insurance_payout">
                                設備・什器等
                            </label>
                            <input id="facility_insurance_payout" name="facility_insurance_payout"
                                class="estimate-input w-1/2 text-right" wire:model="facilityInsurancePayout">
                            <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">千円</span>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['普通', '工場'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label w-1/2" for="other_facility_insurance_payout">その他設備
                            </label>
                            <input id="other_facility_insurance_payout" name="other_facility_insurance_payout"
                                class="estimate-input w-1/2 text-right" wire:model="otherFacilityInsurancePayout">
                            <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">千円</span>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['普通', '工場'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label w-1/2" for="item_insurance_payout">商品・製品等</label>
                            <input id="item_insurance_payout" name="item_insurance_payout"
                                class="estimate-input w-1/2 text-right" wire:model="itemInsurancePayout">
                            <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">千円</span>
                        </div>
                    @endif
                    @if (in_array($propertyType, ['普通', '工場'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label w-1/2" for="other_item_insurance_payout">その他商品</label>
                            <input id="other_item_insurance_payout" name="other_item_insurance_payout"
                                class="estimate-input w-1/2 text-right" wire:model="otherItemInsurancePayout">
                            <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">千円</span>
                        </div>
                    @endif

                    <div class="estimate-title">
                        <label class="p-3">評価</label>
                    </div>
                    <div class="estimate-input-box">
                        <label class="estimate-label" for="assess_item">評価項目</label>
                        <select id="assess_item" name="assess_item" wire:model="assessItem"
                            wire:change="onChangeAssessItem" class="estimate-input">
                            <option value="" hidden>選択してください</option>
                            @foreach ($assessItemList as $item)
                                <option value="{{ $item }}"
                                    @if ($item === $assessItem) selected @endif>
                                    {{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="assess_usage">用途
                                <div class="estimate-input-required">※必須</div>
                            </label>

                            <select id="assess_usage" name="assess_usage" wire:model="assessUsage"
                                wire:change="onChangeAssessItem" class="estimate-input">
                                <option value="" hidden>選択してください</option>
                                @foreach ($assessUsageList as $item)
                                    <option value="{{ $item }}"
                                        @if ($item === $assessUsage) selected @endif>
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
                                wire:change="onChangeAssessItem" class="estimate-input">
                                <option value="" hidden>選択してください</option>
                                @foreach ($assessStructureList as $item)
                                    <option value="{{ $item }}"
                                        @if ($item === $assessStructure) selected @endif>
                                        {{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['ピロティー付'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label sm:text-[0.6rem]" for="piloti_floor_area">ピロティーの延床面積
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="piloti_floor_area" name="piloti_floor_area" class="estimate-input"
                                wire:model="pilotiFloorArea" wire:change="onChangeAssessItem">
                            <span class="unit-display">m²</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['大規模改装'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label sm:text-[0.6rem]" for="replacement_price">従来建物の再調達価格
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="replacement_price" name="replacement_price" class="estimate-input text-right"
                                wire:model="replacementPrice" wire:change="onChangeAssessItem">
                            <span class="unit-display">円</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['大規模改装'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="renovation_cost">改装費用
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="renovation_cost" name="renovation_cost" class="estimate-input text-right"
                                wire:model="renovationCost" wire:change="onChangeAssessItem">
                            <span class="unit-display">円</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['大規模改装'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label sm:text-[0.6rem]" for="conventional_age">従来建物の築年
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="conventional_age" name="conventional_age" class="estimate-input text-right"
                                wire:model="conventionalAge" wire:change="onChangeAssessItem">
                            <span class="unit-display">年</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['大規模改装'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label sm:text-[0.6rem]" for="depreciation_rate">従来建物の償却率
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="depreciation_rate" name="depreciation_rate" class="estimate-input text-right"
                                wire:model="depreciationRate" wire:change="onChangeAssessItem">
                            <span class="unit-display">%</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['大規模改装'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label sm:text-[0.6rem]" for="renovation_age">改装部分の築年
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="renovation_age" name="renovation_age" class="estimate-input text-right"
                                wire:model="renovationAge" wire:change="onChangeAssessItem">
                            <span class="unit-display">年</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['大規模改装'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label sm:text-[0.6rem]" for="renovation_depreciation_rate">改装部分の償却率
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="renovation_depreciation_rate" name="renovation_depreciation_rate"
                                class="estimate-input text-right" wire:model="renovationDepreciationRate"
                                wire:change="onChangeAssessItem">
                            <span class="unit-display">%</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['太陽光付'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="installation_date">設置日
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="installation_date" type="date" name="installation_date"
                                class="estimate-input" wire:model="installationDate"
                                wire:change="onChangeAssessItem">
                        </div>
                    @endif
                    @if (in_array($assessItem, ['太陽光付'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="installation_cost">設置金額
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="installation_cost" name="installation_cost" class="estimate-input"
                                wire:model="installationCost" wire:change="onChangeAssessItem">
                            <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">千円</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['年次取得'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="annual_price">取得価額
                                <div class="estimate-input-required">※必須</div>
                            </label>
                            <input id="annual_price" name="annual_price" class="estimate-input text-right"
                                wire:model="annualPrice" wire:change="onChangeAssessItem">
                            <span class="sm:w-16 sm:p-1 sm:flex sm:items-center unit-display">千円</span>
                        </div>
                    @endif
                    @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得', '大規模改装'], true))
                        <div class="estimate-input-box">
                            <label class="estimate-label" for="remarks">備考
                                <div class="estimate-input-required"></div>
                            </label>
                            <input id="remarks" name="remarks" class="estimate-input text-right"
                                wire:model="remarks" wire:change="onChangeAssessItem">
                        </div>
                    @endif

                    <br>

                    <div class="flex flex-col justify-center">
                        @if (in_array($assessItem, ['年次取得'], true))
                            <div class="w-full flex flex-row">
                                <label class="assess-label"></label>
                                <label class="assess-label">80%
                                </label>
                                <label class="assess-label">100%
                                </label>
                                <label class="assess-label">100%まるめ
                                </label>
                            </div>
                            <div class="w-full flex flex-row">
                                <label class="assess-label">再調達価額
                                </label>
                                <input class="assess-input" wire:model="reProcure80" readonly>
                                <input class="assess-input" wire:model="reProcure100" readonly>
                                <input class="assess-input" wire:model="reProcure100round" readonly>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col justify-center">
                        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション'], true))
                            <div class="w-full flex flex-row">
                                <label class="assess-label"></label>
                                <label class="assess-label">80%
                                </label>
                                <label class="assess-label">100%
                                </label>
                                <label class="assess-label">100%まるめ
                                </label>
                            </div>
                            <div class="w-full flex flex-row">
                                <label class="assess-label">再調達A</label>

                                <input class="assess-input" wire:model="reProcureA80" readonly>
                                <input class="assess-input" wire:model="reProcureA100" readonly>
                                <input class="assess-input" wire:model="reProcureA100round" readonly>
                            </div>
                        @endif

                        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション'], true))
                            <div class="w-full flex flex-row">
                                <label class="assess-label">再調達B</label>
                                <input class="assess-input" wire:model="reProcureB80" readonly>
                                <input class="assess-input" wire:model="reProcureB100" readonly>
                                <input class="assess-input" wire:model="reProcureB100round" readonly>
                            </div>
                        @endif

                        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション'], true))
                            <div class="w-full flex flex-row">
                                <label class="assess-label">再調達C</label>
                                <input class="assess-input" wire:model="reProcureC80" readonly>
                                <input class="assess-input" wire:model="reProcureC100" readonly>
                                <input class="assess-input" wire:model="reProcureC100round" readonly>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col justify-center mt-5">
                        @if (in_array($assessItem, ['大規模改装'], true))
                            <div class="w-full flex flex-row">
                                <label class="market-price-label"></label>
                                <label class="market-price-label">通常
                                </label>
                                <label class="market-price-label">まるめ
                                </label>
                            </div>
                            <div class="w-full flex flex-row">
                                <label class="market-price-label">時価額
                                </label>
                                <input class="market-price-input" wire:model="currentMarketPrice" readonly>
                                <input class="market-price-input" wire:model="currentMarketPriceRound" readonly>
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col justify-center mt-5">
                        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'], true))
                            <div class="w-full flex flex-row">
                                <label class="market-price-label"></label>
                                <label class="market-price-label">通常
                                </label>
                                <label class="market-price-label">まるめ
                                </label>
                            </div>
                            <div class="w-full flex flex-row">
                                <label class="market-price-label">時価額A
                                </label>
                                <input class="market-price-input" wire:model="currentMarketPriceA" readonly>
                                <input class="market-price-input" wire:model="currentMarketPriceARound" readonly>
                            </div>
                        @endif

                        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'], true))
                            <div class="w-full flex flex-row">
                                <label class="market-price-label">時価額B
                                </label>
                                <input class="market-price-input" wire:model="currentMarketPriceB" readonly>
                                <input class="market-price-input" wire:model="currentMarketPriceBRound" readonly>
                            </div>
                        @endif

                        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'], true))
                            <div class="w-full flex flex-row">
                                <label class="market-price-label">時価額C
                                </label>
                                <input class="market-price-input" wire:model="currentMarketPriceC" readonly>
                                <input class="market-price-input" wire:model="currentMarketPriceCRound" readonly>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        <button type="button" onclick="submitForm(this)" name="save"
            class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col mx-48 mt-5">
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
            <div class="grid sm:grid-cols-3 grid-rows-3 gap-4 mx-48 mb-6">
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

                @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得', '大規模改装'], true))
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
                @endif

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
    @if ($isViewFactoryRateTable)
        @include('estimate.livewire.factory-rate-table')
    @endif
    @if ($isViewOccupationalTable)
        @include('estimate.livewire.occupational-table')
    @endif

    @if ($isViewWorkTable)
        @include('estimate.livewire.work-table')
    @endif
    @if ($isViewDiscountRiskTable)
        @include('estimate.livewire.discount-risk-table')
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
