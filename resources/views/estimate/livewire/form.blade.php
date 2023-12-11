<main class="w-[800px]  m-auto text-black bg-white">
    <form id="estimateFormId" action="{{ route('estimate.save', ['id' => $estimate->id ?? 'new']) }}"
        onsubmit="return false;" method="POST" class="flex flex-col container p-2 mt-2">
        @csrf
        <div class="estimate-input-box">
            <label class="estimate-label" for="company">
                取扱代理店名
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="company" name="company" class="estimate-input" value="{{ old('company', $estimate->company) }}"
                required>
        </div>

        <div class="estimate-input-box">
            <label class="estimate-label" for="address">住所
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="address" name="address" class="estimate-input" value="{{ old('address', $estimate->address) }}"
                required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="phone_number">電話番号
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="phone_number" name="phone_number" class="estimate-input"
                value="{{ old('phone_number', $estimate->phone_number) }}" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="staff_name">担当者
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="staff_name" name="staff_name" class="estimate-input"
                value="{{ old('staff_name', $estimate->staff_name) }}" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="contractee">ご契約者名
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="contractee" name="contractee" class="estimate-input"
                value="{{ old('contractee', $estimate->contractee) }}" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="location">所在地
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="location" name="location" class="estimate-input"
                value="{{ old('location', $estimate->location) }}" required>
        </div>

        <div
            class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600 font-bold">
            <label class="p-3">共済の目的およびこれを収容する建物の構造・用法</label>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label for="floor_area" class="estimate-label border">延床面積</label>
                <input id="floor_area" name="floor_area" type="text" class="estimate-input"
                    value="{{ old('floor_area', $estimate->floor_area) }}">
                <span class="my-auto mx-1">m²</span>
            </div>
            <div class="estimate-input-box">
                <label for="exclusive_area" class="estimate-label">専有面積</label>
                <input id="exclusive_area" name="exclusive_area" type="text" class="estimate-input"
                    value="{{ old('exclusive_area', $estimate->exclusive_area) }}">
                <span class="my-auto mx-1">m²</span>
            </div>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label class="estimate-label" for="structure">構造
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="structure" name="structure" class="estimate-input" required>
                    <option value="" hidden>選択してください</option>
                    <option value="コンクリート造等" @if (old('structure', $estimate->structure) === 'コンクリート造等') selected @endif>コンクリート造等</option>
                    <option value="耐火被覆鉄骨造"@if (old('structure', $estimate->structure) === '耐火被覆鉄骨造') selected @endif>耐火被覆鉄骨造</option>
                    <option value="鉄骨造"@if (old('structure', $estimate->structure) === '鉄骨造') selected @endif>鉄骨造</option>
                    <option value="木造"@if (old('structure', $estimate->structure) === '木造') selected @endif>木造</option>
                    <option value="土蔵造"@if (old('structure', $estimate->structure) === '土蔵造') selected @endif>土蔵造</option>
                    <option value="その他"@if (old('structure', $estimate->structure) === 'その他') selected @endif>その他</option>
                </select>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_structure">その他構造</label>
                <input id="other_structure" name="other_structure" class="estimate-input"
                    value="{{ old('other_structure', $estimate->other_structure) }}"><!-- 任意・常時 -->
            </div>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label class="estimate-label" for="pillar">柱無時</label>
                <select id="pillar" name="pillar" class="estimate-input">
                    <option value="" hidden>選択してください</option>
                    <option value="木造枠組壁（２×４）"@if (old('pillar', $estimate->pillar) === '木造枠組壁（２×４）') selected @endif>木造枠組壁（２×４）</option>
                    <option value="コンクリート系プレハブ"@if (old('pillar', $estimate->pillar) === 'コンクリート系プレハブ') selected @endif>コンクリート系プレハブ
                    </option>
                    <option value="鉄骨系プレハブ"@if (old('pillar', $estimate->pillar) === '鉄骨系プレハブ') selected @endif>鉄骨系プレハブ</option>
                    <option value="木質系プレハブ"@if (old('pillar', $estimate->pillar) === '耐火被覆鉄骨造') selected @endif>木質系プレハブ</option>
                </select>
                <!-- 任意・常時 -->
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_pillar">その他柱無時</label>
                <input id="other_pillar" name="other_pillar" class="estimate-input"
                    value="{{ old('other_pillar', $estimate->other_pillar) }}"><!-- 任意・常時 -->
            </div>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label class="estimate-label" for="fire_resistance">耐火基準</label>
                <select id="fire_resistance" name="fire_resistance" class="estimate-input">
                    <option value="" hidden>選択してください</option>
                    <option value="耐火建築物"@if (old('fire_resistance', $estimate->fire_resistance) === '耐火建築物') selected @endif>耐火建築物</option>
                    <option value="準耐火建築物"@if (old('fire_resistance', $estimate->fire_resistance) === '準耐火建築物') selected @endif>準耐火建築物</option>
                    <option value="省令準耐火建物"@if (old('fire_resistance', $estimate->fire_resistance) === '省令準耐火建物') selected @endif>省令準耐火建物</option>
                    <option value="その他"@if (old('fire_resistance', $estimate->fire_resistance) === 'その他') selected @endif>その他</option>
                </select>
                <!-- 任意・常時 -->
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_fire_resistance">その他耐火基準</label>
                <input id="other_fire_resistance" name="other_fire_resistance" class="estimate-input"
                    value="{{ old('other_fire_resistance', $estimate->other_fire_resistance) }}">
                <!-- 任意・常時 -->
            </div>
        </div>

        <div class="flex flex-row">
            <div class="estimate-input-box">
                <label class="estimate-label" for="usage">用法
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="usage" name="usage" class="estimate-input" required>
                    <option value="" hidden>選択してください</option>
                    <option value="専用住宅（1戸建）"@if (old('usage', $estimate->usage) === '専用住宅（1戸建）') selected @endif>専用住宅（1戸建）</option>
                    <option value="専用住宅（共同住宅）"@if (old('usage', $estimate->usage) === '専用住宅（共同住宅）') selected @endif>専用住宅（共同住宅）
                    </option>
                    <option value="併用住宅"@if (old('usage', $estimate->usage) === '併用住宅') selected @endif>併用住宅</option>
                    <option value="店舗"@if (old('usage', $estimate->usage) === '店舗') selected @endif>店舗</option>
                    <option value="事務所"@if (old('usage', $estimate->usage) === '事務所') selected @endif>事務所</option>
                    <option value="工場"@if (old('usage', $estimate->usage) === '工場') selected @endif>工場</option>
                    <option value="作業場"@if (old('usage', $estimate->usage) === '作業場') selected @endif>作業場</option>
                    <option value="倉庫"@if (old('usage', $estimate->usage) === '倉庫') selected @endif>倉庫</option>
                    <option value="その他"@if (old('usage', $estimate->usage) === 'その他') selected @endif>その他</option>
                </select>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_usage">その他用法</label>
                <input id="other_usage" name="other_usage" class="estimate-input"
                    value="{{ old('other_usage', $estimate->other_usage) }}"><!-- 任意・常時 -->
            </div>
        </div>


        <div class="estimate-input-box">
            <label class="estimate-label" for="floor">階
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="floor" name="floor" class="estimate-input"
                value="{{ old('floor', $estimate->floor) }}" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="building">棟
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="building" name="building" class="estimate-input"
                value="{{ old('building', $estimate->building) }}" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="other">その他</label>
            <input id="other" name="other" class="estimate-input"
                value="{{ old('other', $estimate->other) }}"><!-- 任意・常時 -->
        </div>

        <div class="estimate-input-box mt-5">
            <div class="flex">
                <label class="estimate-label" for="property_type">物件
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="property_type" name="property_type" class="estimate-input w-44"
                    wire:model="propertyType" wire:change="onChangePropertyType" required>
                    <option value="" hidden>選択してください</option>
                    <option value="住宅"@if (old('property_type', $estimate->property_type) === '住宅') selected @endif>住宅</option>
                    <option value="普通"@if (old('property_type', $estimate->property_type) === '普通') selected @endif>普通</option>
                    <option value="工場"@if (old('property_type', $estimate->property_type) === '工場') selected @endif>工場</option>
                </select>
            </div>
            <div class="flex">
                <label class="estimate-label" for="rank">級別
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="rank" name="rank" class="estimate-input w-44" wire:model="rank"
                    wire:change="onChangeBaseRate" required>
                    <option value="" hidden>選択してください</option>
                    @foreach ($baseRateList as $rate)
                        <option value={{ $rate }}@if (old('rank', $estimate->base_rate ?? null) === $rate) selected @endif>
                            {{ $rate }}
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
                placeholder="西暦を入力してください" value="{{ old('start_date', $estimate->start_date?->format('Y-m')) }}"
                wire:model="startDate" wire:change="calcBuildingAge" required>
        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="building_year">建築年
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="building_year" name="building_year" class="estimate-input text-right"
                placeholder="西暦を入力してください" value="{{ old('building_year', $estimate->building_year) }}"
                wire:model="buildingYear" wire:change="calcBuildingAge"required>
            <span class="my-auto mx-1">年</span>

        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="building_age">築年数
                <div class="estimate-input-required">※必須</div>
            </label>
            <input id="building_age" name="building_age"
                class="estimate-input hover:bg-white focus:bg-white text-right" placeholder="自動で算出されます"
                value="{{ $buildingAge ?? old('building_age', $estimate->building_age) }}" wire:model="buildingAge"
                required readonly>
            <span class="my-auto mx-1">年</span>

        </div>
        <div class="estimate-input-box">
            <label class="estimate-label" for="pay_method">払込方法
                <div class="estimate-input-required">※必須</div>
            </label>
            <select id="pay_method" name="pay_method" wire:model="payMethod" wire:change="onChangeRecommend"
                class="estimate-input text-right" required>
                <option value="" hidden>選択してください</option>
                <option value="一時払" @if (old('pay_method', $estimate->pay_method) === '一時払') selected @endif>一時払</option>
                <option value="長期年払" @if (old('pay_method', $estimate->pay_method) === '長期年払') selected @endif>長期年払</option>
                <option value="分割払" @if (old('pay_method', $estimate->pay_method) === '分割払') selected @endif>分割払</option>
            </select>
        </div>
        <div class="estimate-input-box mb-5 flex">
            <label class="estimate-label" for="insurance_year">共済期間</label>
            <div class="flex flex-row" class="estimate-input">
                <div class="w-1/3 flex flex-row">
                    <input id="insurance_year" name="insurance_year"
                        wire:model="insuranceYear"wire:change="onChangeRecommend" class="text-right focus:bg-blue-100"
                        value="{{ old('insurance_year', $estimate->insurance_year) }}">
                    <span class="my-auto mx-1">年</span>
                </div>
                <div class="w-1/3 flex flex-row">
                    <input id="insurance_month" name="insurance_month"class="text-right focus:bg-blue-100"
                        value="{{ old('insurance_month', $estimate->insurance_year) }}">
                    <span class="w-16 p-1 flex items-center">ヶ月</span>
                </div>
                <div class="w-1/3 flex flex-row">
                    <input id="insurance_day" class="text-right focus:bg-blue-100"
                        value="{{ old('insurance_day', $estimate->insurance_year) }}">
                    <span class="my-auto mx-1">日</span>
                    <!-- 任意・常時 -->
                </div>
            </div>
        </div>

        <!-- 変動のある項目 -->
        @if ($payMethod === '一時払' && $insuranceYear == 1)
            <div id="recommend-view-container" class="my-5">
                <div
                    class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600 font-bold">
                    <label class="p-3">おすすめ契約期間指定（年数）</label>
                </div>
                <div class="estimate-input-box">
                    <input id="recommend1" name="recommend1" class="estimate-input text-right"
                        value="{{ old('recommend1', $estimate->property_detail['recommend1'] ?? null) }}">
                    <span class="my-auto mx-1">年</span>
                    <input id="recommend2" name="recommend2" class="estimate-input text-right"
                        value="{{ old('recommend2', $estimate->property_detail['recommend2'] ?? null) }}">
                    <span class="my-auto mx-1">年</span>
                    <input id="recommend3" name="recommend3" class="estimate-input text-right"
                        value="{{ old('recommend3', $estimate->property_detail['recommend3'] ?? null) }}">
                    <span class="my-auto mx-1">年</span>
                </div>
            </div>
        @endif

        @if (in_array($propertyType, ['普通', '工場'], true) && $insuranceYear == 1)
            <div id="earthquake-view-container" class="my-5">
                <div
                    class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600 font-bold">
                    <label class="p-3">地震特約縮小支払割合指定</label>
                </div>
                <div class="estimate-input-box">
                    <select id="earthquake1" name="earthquake1" class="estimate-input">
                        <option value="15" selected>15</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                    </select>
                    <span class="my-auto mx-1">%</span>
                    <select id="earthquake2" name="earthquake2" class="estimate-input">
                        <option value="15">15</option>
                        <option value="30" selected>30</option>
                        <option value="50">50</option>
                    </select>
                    <span class="my-auto mx-1">%</span>
                    <select id="earthquake3" name="earthquake3" class="estimate-input">
                        <option value="15">15</option>
                        <option value="30">30</option>
                        <option value="50" selected>50</option>
                    </select>
                    <span class="my-auto mx-1">%</span>
                </div>
            </div>
        @endif

        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div class="estimate-input-box">
                <label class="estimate-label" for="base_rate">基本率
                    <div class="estimate-input-required">※必須</div>
                </label>
                @if (in_array($propertyType, ['住宅', '普通'], true))
                    <input id="base_rate" name="base_rate" class="estimate-input"
                        placeholder="物件が「住宅」か「普通」の時は、自動算出されます" wire:model="baseRate"
                        value="{{ old('base_rate', $estimate->property_detail['base_rate'] ?? null) }}" readonly>
                @else
                    <input id="base_rate" name="base_rate" class="estimate-input" wire:model="baseRate"
                        value="{{ old('base_rate', $estimate->property_detail['base_rate'] ?? null) }}">
                @endif
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div id="chattel_surcharge-view-container" class="estimate-input-box">
                <label class="estimate-label" for="chattel_surcharge">動産割増</label>
                <input id="chattel_surcharge" name="chattel_surcharge" class="estimate-input"
                    value="{{ old('chattel_surcharge', $estimate->property_detail['chattel_surcharge'] ?? null) }}">
            </div>
        @endif
        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div id="actual_loss_factor-view-container" class="estimate-input-box">
                <label class="estimate-label" for="actual_loss_factor">実損払係数</label>
                <input id="actual_loss_factor" name="actual_loss_factor" class="estimate-input"
                    value="{{ old('actual_loss_factor', $estimate->property_detail['actual_loss_factor'] ?? null) }}">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div id="other_discount-view-container" class="estimate-input-box">
                <label class="estimate-label" for="other_discount">その他割引</label>
                <input id="other_discount" name="other_discount" class="estimate-input"
                    value="{{ old('other_discount', $estimate->property_detail['other_discount'] ?? null) }}">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div id="chattel_surcharge_category-view-container" class="estimate-input-box">
                <label class="estimate-label" for="chattel_surcharge_category">動産割増（カテゴリ）
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="chattel_surcharge_category" name="chattel_surcharge_category" class="estimate-input"
                    value="{{ old('chattel_surcharge_category', $estimate->property_detail['chattel_surcharge_category'] ?? null) }}">
            </div>
        @endif
        @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box mt-5 w-auto">
                <label class="estimate-label h-auto">職業割増</label>
                <div class="w-auto">
                    <div class="flex">
                        <label class="estimate-label w-32" for="occupational_surcharge_code">標準コード</label>
                        <button type="button"
                            class="mx-2 my-1 px-2 h-8 w-64 bg-gray-700 hover:bg-gray-600 text-white rounded">コードを参照する</button>
                        <input id="occupational_surcharge_code" name="occupational_surcharge_code"
                            class="estimate-input" wire:model="occupationalSurchargeCode"
                            wire:change="onChangeOccupationalSurchargeCode"
                            value="{{ old('occupational_surcharge_code', $estimate->property_detail['occupational_surcharge_code'] ?? null) }}">
                    </div>
                    <div class="flex">
                        <label class="estimate-label w-32" for="occupational_surcharge_rate">職業割増</label>
                        <div class="mx-2 w-64 text-sm">※調整は,手動でお願いします</div>
                        <input id="occupational_surcharge_rate" name="occupational_surcharge_rate"
                            class="estimate-input" wire:model="occupationalSurchargeRate"
                            value="{{ old('occupational_surcharge_rate', $estimate->property_detail['occupational_surcharge_rate'] ?? null) }}">
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
                        <button type="button"
                            class="mx-2 my-1 px-2 h-8 w-64 bg-gray-700 hover:bg-gray-600 text-white rounded">コードを参照する</button>
                        <input id="work_surcharge_code" name="work_surcharge_code" class="estimate-input"
                            wire:model="workSurchargeCode" wire:change="onChangeWorkSurchargeCode"
                            value="{{ old('work_surcharge_code', $estimate->property_detail['work_surcharge_code'] ?? null) }}">
                    </div>
                    <div class="flex">
                        <label class="estimate-label w-32" for="work_surcharge_rate">職業割増</label>
                        <div class="mx-2 w-64 text-sm">※調整は,手動でお願いします</div>
                        <input id="work_surcharge_surcharge_rate" name="work_surcharge_rate" class="estimate-input"
                            wire:model="workSurchargeRate"
                            value="{{ old('work_surcharge_rate', $estimate->property_detail['work_surcharge_rate'] ?? null) }}">
                    </div>
                </div>
            </div>
        @endif
        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div id="insurance_purpose-view-container" class="estimate-input-box mt-5">
                <label class="estimate-label bg-indigo-500 outline outline-1">共済の目的</label>
                <label class="estimate-label bg-indigo-500 outline outline-1">共済金額</label>
                <label class="estimate-label bg-indigo-500 outline outline-1">共済金額合計</label>
            </div>
        @endif
        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div id="building_insurance_payout-view-container" class="estimate-input-box">
                <label class="estimate-label" for="building_insurance_payout">建物共済金額</label>
                <input id="building_insurance_payout" name="building_insurance_payout" class="estimate-input"
                    value="{{ old('building_insurance_payout', $estimate->property_detail['building_insurance_payout'] ?? null) }}">
                <input class="estimate-input">
            </div>
        @endif
        @if (in_array($propertyType, ['住宅', '普通', '工場'], true))
            <div id="household_insurance_payout-view-container" class="estimate-input-box">
                <label class="estimate-label" for="household_insurance_payout">家財共済金額</label>
                <input id="household_insurance_payout" name="household_insurance_payout" class="estimate-input"
                    value="{{ old('household_insurance_payout', $estimate->property_detail['household_insurance_payout'] ?? null) }}">
                <input class="estimate-input">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div id="facility_insurance_payout-view-container" class="estimate-input-box">
                <label class="estimate-label" for="facility_insurance_payout">
                    設備共済金額
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="facility_insurance_payout" name="facility_insurance_payout" class="estimate-input"
                    value="{{ old('facility_insurance_payout', $estimate->property_detail['facility_insurance_payout'] ?? null) }}">
                <input class="estimate-input">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div id="other_facility_insurance_payout-view-container" class="estimate-input-box">
                <label class="estimate-label" for="other_facility_insurance_payout">その他設備共済金額
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="other_facility_insurance_payout" name="other_facility_insurance_payout"
                    class="estimate-input"
                    value="{{ old('other_facility_insurance_payout', $estimate->property_detail['other_facility_insurance_payout'] ?? null) }}">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div id="item_insurance_payout-view-container" class="estimate-input-box">
                <label class="estimate-label" for="item_insurance_payout">商品共済金額</label>
                <input id="item_insurance_payout" name="item_insurance_payout" class="estimate-input"
                    value="{{ old('item_insurance_payout', $estimate->property_detail['item_insurance_payout'] ?? null) }}">
                <input class="estimate-input">
            </div>
        @endif
        @if (in_array($propertyType, ['普通', '工場'], true))
            <div id="other_item_insurance_payout-view-container" class="estimate-input-box">
                <label class="estimate-label" for="other_item_insurance_payout">その他商品共済金額</label>
                <input id="other_item_insurance_payout" name="other_item_insurance_payout" class="estimate-input"
                    value="{{ old('other_item_insurance_payout', $estimate->property_detail['other_item_insurance_payout'] ?? null) }}">
            </div>
        @endif

        <div class="estimate-input-box mt-5">
            <label class="estimate-label" for="assess_item">評価項目</label>
            <select id="assess_item" name="assess_item" wire:model="assessItem" wire:change="onChangeAssessItem"
                class="estimate-input">
                <option value="" hidden>選択してください</option>
                <option value=""@if (old('assess_item', $estimate->assess_item) === '') selected @endif>選択しない</option>
                <option value="通常"@if (old('assess_item', $estimate->assess_item) === '通常') selected @endif>通常</option>
                <option value="ピロティー付"@if (old('assess_item', $estimate->assess_item) === 'ピロティー付') selected @endif>ピロティー付</option>
                <option value="太陽光付"@if (old('assess_item', $estimate->assess_item) === '太陽光付') selected @endif>太陽光付</option>
                <option value="分譲マンション"@if (old('assess_item', $estimate->assess_item) === '分譲マンション') selected @endif>分譲マンション</option>
                <option value="年次取得"@if (old('assess_item', $estimate->assess_item) === '年次取得') selected @endif>年次取得</option>
                <option value="改装物件"@if (old('assess_item', $estimate->assess_item) === '改装物件') selected @endif>改装物件の評価計算式</option>
            </select>
        </div>
        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'], true))
            <div id="assess_usage-view-container" class="estimate-input-box">
                <label class="estimate-label" for="assess_usage">用途
                    <div class="estimate-input-required">※必須</div>
                </label>

                <select id="assess_usage" name="assess_usage" class="estimate-input">
                    <option value="" hidden>選択してください</option>
                    @foreach ($assessUsageList as $usage)
                        <option value={{ $usage }}@if (old('assess_usage', $estimate->assess_detail['assess_usage'] ?? null) === $usage) selected @endif>
                            {{ $usage }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
        @if (in_array($assessItem, ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'], true))
            <div id="assess_structure-view-container" class="estimate-input-box">
                <label class="estimate-label" for="assess_structure">構造
                    <div class="estimate-input-required">※必須</div>
                </label>
                <select id="assess_structure" name="assess_structure" class="estimate-input">
                    <option value="" hidden>選択してください</option>
                    <option value="木造" @if (old('assess_structure', $estimate->assess_detail['assess_structure'] ?? null) === '木造') selected @endif>木造</option>
                    <option value="鉄骨"@if (old('assess_structure', $estimate->assess_detail['assess_structure'] ?? null) === '鉄骨') selected @endif>鉄骨</option>
                    <option value="鉄筋（RC）"@if (old('assess_structure', $estimate->assess_detail['assess_structure'] ?? null) === '鉄筋（RC）') selected @endif>鉄筋（RC）</option>
                    <option value="枠組（2×4）"@if (old('assess_structure', $estimate->assess_detail['assess_structure'] ?? null) === '枠組（2×4）') selected @endif>枠組（2×4）</option>
                    <option value="プレハブ造"@if (old('assess_structure', $estimate->assess_detail['assess_structure'] ?? null) === 'プレハブ造') selected @endif>プレハブ造</option>
                    <option value="軽量鉄骨"@if (old('assess_structure', $estimate->assess_detail['assess_structure'] ?? null) === '軽量鉄骨') selected @endif>軽量鉄骨</option>
                </select>
            </div>
        @endif
        @if (in_array($assessItem, ['ピロティー付'], true))
            <div id="piloti_floor_area-view-container" class="estimate-input-box">
                <label class="estimate-label" for="piloti_floor_area">ピロティーの延床面積
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="piloti_floor_area" name="piloti_floor_area" class="estimate-input"
                    value="{{ old('piloti_floor_area', $estimate->assess_detail['piloti_floor_area'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div id="replacement_price-view-container" class="estimate-input-box">
                <label class="estimate-label text-sm" for="replacement_price">従来建物の<br>再調達価格
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="replacement_price" name="replacement_price" class="estimate-input"
                    value="{{ old('replacement_price', $estimate->assess_detail['replacement_price'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div id="renovation_cost-view-container" class="estimate-input-box">
                <label class="estimate-label" for="renovation_cost">改装費用
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="renovation_cost" name="renovation_cost" class="estimate-input"
                    value="{{ old('renovation_cost', $estimate->assess_detail['renovation_cost'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div id="conventional_age-view-container" class="estimate-input-box">
                <label class="estimate-label" for="conventional_age">従来建物の築年
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="conventional_age" name="conventional_age" class="estimate-input"
                    value="{{ old('conventional_age', $estimate->assess_detail['conventional_age'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div id="depreciation_rate-view-container" class="estimate-input-box">
                <label class="estimate-label" for="depreciation_rate">従来建物の償却率
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="depreciation_rate" name="depreciation_rate" class="estimate-input"
                    value="{{ old('depreciation_rate', $estimate->assess_detail['depreciation_rate'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div id="renovation_age-view-container" class="estimate-input-box">
                <label class="estimate-label" for="renovation_age">改装部分の築年
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="renovation_age" name="renovation_age" class="estimate-input"
                    value="{{ old('renovation_age', $estimate->assess_detail['renovation_age'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['改装物件'], true))
            <div id="renovation_depreciation_rate-view-container" class="estimate-input-box">
                <label class="estimate-label" for="renovation_depreciation_rate">改装部分の償却率
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="renovation_depreciation_rate" name="renovation_depreciation_rate" class="estimate-input"
                    value="{{ old('renovation_depreciation_rate', $estimate->assess_detail['renovation_depreciation_rate'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['太陽光付'], true))
            <div id="installation_date-view-container" class="estimate-input-box">
                <label class="estimate-label" for="installation_date">設置日
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="installation_date" name="installation_date" class="estimate-input"
                    value="{{ old('installation_date', $estimate->assess_detail['installation_date'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['太陽光付'], true))
            <div id="installation_cost-view-container" class="estimate-input-box">
                <label class="estimate-label" for="installation_cost">設置金額
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="installation_cost" name="installation_cost" class="estimate-input"
                    value="{{ old('installation_cost', $estimate->assess_detail['installation_cost'] ?? null) }}">
            </div>
        @endif
        @if (in_array($assessItem, ['年次取得'], true))
            <div id="annual_price-view-container" class="estimate-input-box">
                <label class="estimate-label" for="annual_price">年次価額
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="annual_price" name="annual_price" class="estimate-input"
                    value="{{ old('annual_price', $estimate->assess_detail['annual_price'] ?? null) }}">
            </div>
        @endif

        <button type="button" onclick="submitForm(this)" name="save"
            class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col w-full mt-5">
            <div class="flex items-center mb-3">
                <div
                    class="w-8 h-8 mr-2 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                    <i class="fa-solid fa-floppy-disk"></i>
                </div>
                <h2 class="text-lg title-font font-medium">保存</h2>
            </div>
            <p class="text-left pl-5">入力された内容を保存します。<br>保存したのちプレビューが可能になります。</p>
        </button>

        @if ($estimate->id)
            <div class="grid grid-cols-2 gap-4 mb-6">
                <button type="button" onclick="submitForm(this)" value="preview"
                    class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col w-full mt-5">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-8 h-8 mr-2 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                            <i class="fa-solid fa-floppy-disk"></i>
                        </div>
                        <h2 class="text-lg title-font font-medium">プレビュー</h2>
                    </div>
                    <p class="text-left pl-5">入力した内容で見積書、評価シートのプレビューを表示します。</p>
                </button>

                <button type="button" onclick="submitForm(this)" value="download"
                    class="flex rounded-lg h-full outline outline-1 outline-slate-300 shadow-md bg-gray-100 hover:bg-gray-300 p-8 flex-col w-full mt-5">
                    <div class="flex items-center mb-3">
                        <div
                            class="w-8 h-8 mr-2 inline-flex items-center justify-center rounded-full bg-indigo-500 text-white flex-shrink-0">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <h2 class="text-lg title-font font-medium">ダウンロードファイル選択</h2>
                    </div>
                    <p class="text-left pl-5">ダウンロードする見積書を選択する画面に移行します。</p>
                </button>
            </div>
        @endif
    </form>
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
