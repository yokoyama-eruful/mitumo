<main class="m-auto w-full bg-white text-black">

  @if (Session::has('message'))
    <div class="relative rounded border border-green-400 bg-green-100 px-4 py-3 text-green-700" role="alert">
      <span class="block sm:inline">{{ Session::get('message') }}</span>
    </div>
  @endif

  <form class="mt-2 flex flex-col justify-center p-2" id="estimateFormId"
    action="{{ route('futsu2.save', ['id' => $estimate->id ?? 'new']) }}" onsubmit="return false;" method="POST">
    @csrf
    <div class="mx-10 block border-2 bg-lime-50 p-5">

      <div class="flex flex-wrap">
        <div class="w-1/2 px-2">
          <div class="futsu2-title">
            <label class="p-3">取扱代理所情報</label>
          </div>

          <div class="estimate-input-box">
            <label class="futsu2-label" for="company">
              取扱代理所名
            </label>
            <input class="estimate-input" id="company" name="company" wire:model="company" required>
          </div>

          <div class="estimate-input-box">
            <label class="futsu2-label" for="address">住所
            </label>
            <input class="estimate-input" id="address" name="address" wire:model="address" required>
          </div>
          <div class="estimate-input-box">
            <label class="futsu2-label" for="phone_number">電話番号
            </label>
            <input class="estimate-input" id="phone_number" name="phone_number" wire:model="phoneNumber" required>
          </div>
          <div class="estimate-input-box">
            <label class="futsu2-label" for="staff_name">担当者
            </label>
            <input class="estimate-input" id="staff_name" name="staff_name" wire:model="staffName" required>
          </div>
        </div>
        <div class="w-1/2 px-2">
          <div class="futsu2-title">
            <label class="p-3">共済契約者情報</label>
          </div>
          <div class="estimate-input-box flex">
            <label class="futsu2-label" for="certificate_number">証書番号
            </label>
            <input class="estimate-input" id="certificate_number" name="certificate_number"
              wire:model="certificateNumber" required>
            <button
              class="mx-2 h-8 whitespace-nowrap rounded bg-gray-700 px-2 text-[0.6rem] text-white hover:bg-gray-600 sm:text-base"
              type="button" wire:click="onClickCertificateNumber">証書番号参照</button>
          </div>
          <div class="estimate-input-box">
            <label class="futsu2-label" for="contractee">ご契約者名
            </label>
            <input class="estimate-input" id="contractee" name="contractee" wire:model="contractee" required>
          </div>
          <div class="estimate-input-box">
            <label class="futsu2-label" for="location">所在地
            </label>
            <input class="estimate-input" id="location" name="location" wire:model="location" required>
          </div>
        </div>
      </div>

      <div class="flex flex-wrap">
        <div class="w-1/2 px-2">
          <div class="futsu2-title">
            <label class="p-3">共済の目的およびこれを収容する建物の構造・用法</label>
          </div>

          <div class="flex flex-row">
            <div class="estimate-input-box">
              <label class="futsu2-label border" for="floor_area">延床面積</label>
              <input class="estimate-input text-right" id="floor_area" name="floor_area" type="text"
                wire:model="floorArea"wire:change="onChangeAssessItem">
              <span class="unit-display">m²</span>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="exclusive_area">専有面積</label>
              <input class="estimate-input text-right" id="exclusive_area" name="exclusive_area" type="text"
                wire:model="exclusiveArea"wire:change="onChangeAssessItem">
              <span class="unit-display">m²</span>
            </div>
          </div>

          <div class="flex flex-row">
            <div class="estimate-input-box">
              <label class="futsu2-label" for="structure">構造
                {{-- <div class="estimate-input-required">※必須</div> --}}
              </label>
              <select class="estimate-input" id="structure" name="structure" wire:model="structure" required>
                <option value="" hidden></option>
                @foreach ($structureList as $item)
                  <option value={{ $item }} @if ($item === $structure) selected @endif>
                    {{ $item }}</option>
                @endforeach
              </select>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="other_structure">その他構造</label>
              <input class="estimate-input" id="other_structure" name="other_structure" wire:model="otherStructure">
              <!-- 任意・常時 -->
            </div>
          </div>

          <div class="flex flex-row">
            <div class="estimate-input-box">
              <label class="futsu2-label" for="pillar">柱無時</label>
              <select class="estimate-input" id="pillar" name="pillar" wire:model="pillar">
                <option value="" hidden></option>
                @foreach ($pillarList as $item)
                  <option value={{ $item }} @if ($item === $pillar) selected @endif>
                    {{ $item }}</option>
                @endforeach
              </select>
              <!-- 任意・常時 -->
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="other_pillar">その他柱無時</label>
              <input class="estimate-input" id="other_pillar" name="other_pillar" wire:model="otherPillar">
              <!-- 任意・常時 -->
            </div>
          </div>

          <div class="flex flex-row">
            <div class="estimate-input-box">
              <label class="futsu2-label" for="fire_resistance">耐火基準</label>
              <select class="estimate-input" id="fire_resistance" name="fire_resistance"
                wire:model="fireResistance">
                <option value="" hidden></option>
                @foreach ($fireResistanceList as $item)
                  <option value={{ $item }} @if ($item === $fireResistance) selected @endif>
                    {{ $item }}</option>
                @endforeach
              </select>
              <!-- 任意・常時 -->
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="other_fire_resistance">その他耐火基準</label>
              <input class="estimate-input" id="other_fire_resistance" name="other_fire_resistance"
                wire:model="otherFireResistance">
              <!-- 任意・常時 -->
            </div>
          </div>

          <div class="flex flex-row">
            <div class="estimate-input-box">
              <label class="futsu2-label" for="usage">用法
                {{-- <div class="estimate-input-required">※必須</div> --}}
              </label>
              <select class="estimate-input" id="usage" name="usage" wire:model="usage" required>
                <option value="" hidden></option>
                @foreach ($usageList as $item)
                  <option value={{ $item }} @if ($item === $usage) selected @endif>
                    {{ $item }}</option>
                @endforeach
              </select>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="other_usage">その他用法</label>
              <input class="estimate-input" id="other_usage" name="other_usage"
                wire:model="otherUsage"><!-- 任意・常時 -->
            </div>
          </div>

          <div class="flex flex-row">
            <div class="estimate-input-box">
              <label class="futsu2-label" for="floor">階
              </label>
              <input class="estimate-input" id="floor" name="floor" wire:model="floor" required>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="building">棟
              </label>
              <input class="estimate-input" id="building" name="building" wire:model="building" required>
            </div>
          </div>
          <div class="estimate-input-box">
            <label class="futsu2-label" for="other">備考</label>
            <input class="estimate-input" id="other" name="other" wire:model="other">
            <!-- 任意・常時 -->
          </div>
          <div class="estimate-input-box">
            <div class="flex">
              <label class="futsu2-label" for="property_type">物件
                <div class="estimate-input-required">※必須</div>
              </label>
              <select class="estimate-input" id="property_type" name="property_type" wire:model="propertyType"
                wire:change="onChangePropertyType" required>
                <option value="" hidden>選択してください</option>
                @foreach ($propertyTypeList as $item)
                  <option value={{ $item }} @if ($item === $usage) selected @endif>
                    {{ $item }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="flex">
              <label class="futsu2-label" for="rank">級別
                <div class="estimate-input-required">※必須</div>
              </label>
              <select class="estimate-input" id="rank" name="rank" wire:model="rank"
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
          @if (is_null($buildingInsurancePayout) || $buildingInsurancePayout == '')
            <div class="estimate-input-box">
              <label class="futsu2-label" for="start_date">始期日
              </label>
              <input class="estimate-input text-right" id="start_date" name="start_date" type="month"
                placeholder="西暦を入力してください" wire:model="startDate" wire:change="onChangeBuildingAge" required>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="building_year">建築年
              </label>
              <div class="estimate-input"></div>
              <select class="text-right" id="building_year" name="building_year_era"
                wire:change="onChangeBuildingAge" placeholder="和暦" wire:model="buildingYearEra" required>
                <option value="西暦">西暦</option>
                <option value="令和">令和</option>
                <option value="平成">平成</option>
                <option value="昭和">昭和</option>
                <option value="大正">大正</option>
                <option value="明治">明治</option>
              </select>
              <input class="estimate-input w-20 text-right" id="building_year" name="building_year" placeholder=""
                wire:model="buildingYear" wire:change="onChangeBuildingAge" required>
              <span class="unit-display">年</span>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="building_age">築年数
              </label>
              <input class="estimate-input text-right hover:bg-white focus:bg-white" id="building_age"
                name="building_age" wire:model="buildingAge"wire:change="onChangeAssessItem" required readonly>
              <span class="unit-display">年</span>
            </div>
          @else
            <div class="estimate-input-box">
              <label class="futsu2-label" for="start_date">始期日
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="start_date" name="start_date" type="month"
                placeholder="西暦を入力してください" wire:model="startDate" wire:change="onChangeBuildingAge" required>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="building_year">建築年
              </label>
              <div class="estimate-input"></div>
              <select class="text-right" id="building_year" name="building_year_era"
                wire:change="onChangeBuildingAge" placeholder="和暦" wire:model="buildingYearEra" required>
                <option value="西暦">西暦</option>
                <option value="令和">令和</option>
                <option value="平成">平成</option>
                <option value="昭和">昭和</option>
                <option value="大正">大正</option>
                <option value="明治">明治</option>
              </select>
              <input class="estimate-input w-20 text-right" id="building_year" name="building_year" placeholder=""
                wire:model="buildingYear" wire:change="onChangeBuildingAge" required>
              <span class="unit-display">年</span>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="building_age">築年数
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right hover:bg-white focus:bg-white" id="building_age"
                name="building_age" wire:model="buildingAge" required readonly>
              <span class="unit-display">年</span>
            </div>
          @endif
        </div>

        <div class="w-1/2 px-2">
          <div class="futsu2-title">
            <label class="p-3">払込方法・共済期間</label>
          </div>
          <div class="estimate-input-box">
            <label class="futsu2-label" for="pay_method">払込方法
              <div class="estimate-input-required">※必須</div>
            </label>
            <select class="estimate-input text-right" id="pay_method" name="pay_method" wire:model="payMethod"
              wire:change="onChangeRecommend" required>
              <option value="" hidden>選択してください</option>
              @foreach ($payMethodList as $item)
                <option value={{ $item }} @if ($item === $rank) selected @endif>
                  {{ $item }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="estimate-input-box mb-5 flex">
            <label class="futsu2-label" for="insurance_year">共済期間
              <div class="estimate-input-required">※必須</div>
            </label>
            @if (!empty($insuranceMonth) || !empty($insuranceDay))
              <div class="flex w-1/3 flex-row">
                <input class="estimate-input bg-gray-300 text-right hover:bg-gray-300" id="insurance_year"
                  name="insurance_year" wire:model="insuranceYear" wire:change="onChangeRecommend" readonly>
                <span class="unit-display">年</span>
              </div>
            @else
              <div class="flex w-1/3 flex-row">
                <input class="estimate-input text-right" id="insurance_year" name="insurance_year"
                  wire:model="insuranceYear" wire:change="onChangeRecommend">
                <span class="unit-display">年</span>
              </div>
            @endif
            @if (!empty($insuranceYear))
              <div class="flex w-1/3 flex-row">
                <input class="estimate-input bg-gray-300 text-right hover:bg-gray-300" id="insurance_month"
                  name="insurance_month" wire:model="insuranceMonth" wire:change="onChangeInsuranceMonth" readonly>
                <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">ヶ月</span>
              </div>
              <div class="flex w-1/3 flex-row">
                <input class="estimate-input bg-gray-300 text-right hover:bg-gray-300" id="insurance_day"
                  name="insurance_day" wire:change="onChangeInsuranceDay" wire:model="insuranceDay" readonly>
                <span class="unit-display">日</span>
                <!-- 任意・常時 -->
              </div>
            @else
              <div class="flex w-1/3 flex-row">
                <input class="estimate-input text-right" id="insurance_month" name="insurance_month"
                  wire:model="insuranceMonth" wire:change="onChangeInsuranceMonth">
                <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">ヶ月</span>
              </div>
              <div class="flex w-1/3 flex-row">
                <input class="estimate-input text-right" id="insurance_day" name="insurance_day"
                  wire:change="onChangeInsuranceDay" wire:model="insuranceDay">
                <span class="unit-display">日</span>
                <!-- 任意・常時 -->
              </div>
            @endif
          </div>

          <!-- 変動のある項目 -->
          @if ($payMethod === '一時払' && $insuranceYear == 1)
            <div class="my-5">
              <div class="futsu2-title">
                <label class="p-3">おすすめ契約期間指定（年数）</label>
              </div>
              <div class="estimate-input-box">
                <input class="estimate-input text-right" id="recommend1" name="recommend1" wire:model="recommend1">
                <span class="unit-display">年</span>
                <input class="estimate-input text-right" id="recommend2" name="recommend2" wire:model="recommend2">
                <span class="unit-display">年</span>
                <input class="estimate-input text-right" id="recommend3" name="recommend3" wire:model="recommend3">
                <span class="unit-display">年</span>
              </div>
            </div>
          @else
            <div class="my-5">
              <div class="futsu2-title bg-gray-300">
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

          @if (in_array($propertyType, ['普通'], true) && $insuranceYear == 1)
            <div class="my-5">
              <div class="futsu2-title">
                <label class="p-3">地震特約縮小支払割合指定</label>
              </div>
              <div class="estimate-input-box">
                <select class="estimate-input text-right" id="earthquake1" name="earthquake1"
                  wire:model="earthquake1">
                  <option value="15">
                    15
                  </option>
                  <option value="選択しない">
                    選択しない
                  </option>
                </select>
                <span class="unit-display">%</span>
                <select class="estimate-input text-right" id="earthquake2" name="earthquake2"
                  wire:model="earthquake2">
                  <option value="30">
                    30
                  </option>
                  <option value="選択しない">
                    選択しない
                  </option>
                </select>
                <span class="unit-display">%</span>
                <select class="estimate-input text-right" id="earthquake3" name="earthquake3"
                  wire:model="earthquake3">
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
              <div class="futsu2-title bg-gray-300">
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
            <div class="futsu2-title">
              <label class="p-3">料率・係数等</label>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="building_base_rate">建物基本率
                @if (!empty($buildingInsurancePayout))
                  <div class="estimate-input-required">※必須</div>
                @endif
              </label>
              <input class="estimate-input" id="building_base_rate" name="building_base_rate"
                placeholder="物件が「住宅」か「普通」の時は、自動算出されます" wire:model="buildingBaseRate" readonly>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="chattel_base_rate">動産基本率
                @if (
                    !empty($householdInsurancePayout) ||
                        !empty($facilityInsurancePayout) ||
                        !empty($otherFacilityInsurancePayout) ||
                        !empty($itemInsurancePayout) ||
                        !empty($otherItemInsurancePayout))
                  <div class="estimate-input-required">※必須</div>
                @endif
              </label>
              <input class="estimate-input" id="chattel_base_rate" name="chattel_base_rate"
                placeholder="物件が「住宅」か「普通」の時は、自動算出されます" wire:model="chattelBaseRate" readonly>
            </div>
            <div class="estimate-input-box">
              <div class="futsu2-label flex flex-row" for="chattel_surcharge">
                <label> 損保９掛</label>
                <input class="ms-2 h-6 w-6 text-xl" name="is_sonpo_base_rate" type="checkbox" value="true"
                  wire:model.lazy="isSonpoBaseRate">
              </div>
              @if ($isSonpoBaseRate)
                <label class="futsu2-label h-auto" for="sonpo_building_base_rate">
                  建物
                </label>
                <input class="estimate-input" id="sonpo_building_base_rate" name="sonpo_building_base_rate"
                  wire:model="sonpoBuildingBaseRate">

                <label class="futsu2-label" for="sonpo_chattel_base_rate">
                  動産
                </label>
                <input class="estimate-input" id="sonpo_chattel_base_rate" name="sonpo_chattel_base_rate"
                  wire:model="sonpoChattelBaseRate">
              @endif
            </div>
          @endif
          @if ($propertyType == '普通')
            <div class="estimate-input-box">
              <label class="futsu2-label" for="chattel_surcharge_category">動産割増
              </label>
              <select class="estimate-input" id="chattel_surcharge_category" name="chattel_surcharge_category"
                wire:model="chattelSurchargeCategory">
                @foreach ($chattelSurchargeCategoryList as $item)
                  <option value="{{ $item }}" @if ($item === $chattelSurchargeCategory) selected @endif>
                    {{ $item }}</option>
                @endforeach
              </select>
            </div>
          @endif
          @if (in_array($propertyType, ['住宅', '普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="actual_loss_factor">実損払係数
              </label>
              <input class="estimate-input" id="actual_loss_factor" name="actual_loss_factor"
                wire:model="actualLossFactor">
            </div>
          @endif
          @if (in_array($propertyType, ['住宅', '普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="discount_fire_extinguisher">消火設備割引
              </label>
              <input class="estimate-input text-right" id="discount_fire_extinguisher"
                name="discount_fire_extinguisher" type="number" min="0" max="100"
                wire:model="discountFireExtinguisher">
              <span class="unit-display">%</span>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="discount_security">セキュリティ割引
              </label>
              <input class="estimate-input text-right" id="discount_security" name="discount_security"
                type="number" min="0" max="100" wire:model="discountSecurity">
              <span class="unit-display">%</span>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" id="discount_public" for="discount_public">公有物件割引
              </label>
              <input class="estimate-input text-right" id="discount_public" name="discount_public" type="number"
                min="0" max="100" wire:model="discountPublic">
              <span class="unit-display">%</span>
            </div>
            <div class="estimate-input-box w-auto">
              <label class="futsu2-label h-auto">リスク評価割引</label>
              <div class="w-11/12">
                <div class="estimate-input-box flex">
                  <label class="futsu2-label" for="discount_risk_code"></label>
                  <button
                    class="mx-2 h-8 rounded bg-gray-700 px-2 text-[0.6rem] text-white hover:bg-gray-600 sm:text-base"
                    type="button" wire:click="onClickDiscountRiskTable">評価項目表を参照</button>
                  <input class="estimate-input" id="discount_risk_code" name="discount_risk_code"
                    wire:model="discountRiskCode" wire:change="onChangeDiscountRiskCode" hidden>
                </div>
                <div class="flex">
                  <label class="futsu2-label" for="discount_risk_rate">割引率</label>
                  <input class="estimate-input text-right" id="discount_risk_rate" name="discount_risk_rate"
                    type="number" wire:model="discountRiskRate"wire:change="onChangeDiscountRiskRate" min="0"
                    max="100">
                  <span class="unit-display">%</span>
                </div>
              </div>
            </div>
          @else
            <div>
            </div>
          @endif
          @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="other_discount">その他割引
              </label>
              <input class="estimate-input text-right" id="other_discount" name="other_discount" type="number"
                wire:model="otherDiscount" min="0" max="50">
              <span class="unit-display">%</span>
            </div>
          @endif
          @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box mt-5">
              <label class="futsu2-label h-auto">職業割増</label>
              <div class="w-11/12">
                <div class="estimate-input-box flex">
                  <label class="futsu2-label"></label>
                  <button class="mx-2 rounded bg-gray-700 px-2 text-[0.6rem] text-white hover:bg-gray-600 sm:text-base"
                    type="button" wire:click="onClickOpenOccupationalSurchargeTable">コードを参照する</button>
                </div>
                <div class="estimate-input-box flex">
                  <label class="futsu2-label" for="occupational_surcharge_code">標準コード</label>
                  <input class="estimate-input" id="occupational_surcharge_code" name="occupational_surcharge_code"
                    wire:model="occupationalSurchargeCode" wire:change="onChangeOccupationalSurchargeCode">
                </div>
                <div class="estimate-input-box flex">
                  <label class="futsu2-label">選択項目</label>
                  <input class="estimate-input outline-none" name="occupational_surcharge_item"
                    wire:model="occupationalSurchargeItem" readonly>
                </div>
                <div class="flex">
                  <label class="futsu2-label" for="occupational_surcharge_rate">率</label>
                  <input class="estimate-input" id="occupational_surcharge_rate" name="occupational_surcharge_rate"
                    wire:model="occupationalSurchargeRate" wire:change="onChangeOccupationalSurchargeRate">
                </div>
              </div>
            </div>
          @endif
          @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label h-auto">作業割増</label>
              <div class="w-11/12">
                <div class="estimate-input-box flex">
                  <label class="futsu2-label"></label>
                  <button class="mx-2 rounded bg-gray-700 px-2 text-[0.6rem] text-white hover:bg-gray-600 sm:text-base"
                    type="button" wire:click="onClickOpenWorkSurchargeTable">コードを参照する</button>
                </div>
                <div class="estimate-input-box flex">
                  <label class="futsu2-label" for="work_surcharge_code">作業工程コード</label>
                  <input class="estimate-input" id="work_surcharge_code" name="work_surcharge_code"
                    wire:model="workSurchargeCode" wire:change="onChangeWorkSurchargeCode">
                </div>
                <div class="estimate-input-box flex">
                  <label class="futsu2-label">選択項目</label>
                  <input class="estimate-input outline-none" name="work_surcharge_item"
                    wire:model="workSurchargeItem">
                </div>
                <div class="estimate-input-box flex">
                  <label class="futsu2-label" for="work_surcharge_item">作業人数</label>
                  <select class="estimate-input" name="worker_count_class" wire:model="workerCountClass"
                    wire:change="onChangeWorkSurchargeCode">
                    <option value="">未入力</option>
                    <option value="1">5人未満</option>
                    <option value="2">50人未満</option>
                    <option value="3">50人以上</option>
                  </select>
                </div>
                <div class="flex">
                  <label class="futsu2-label" for="work_surcharge_rate">率</label>
                  <input class="estimate-input" id="work_surcharge_rate" name="work_surcharge_rate"
                    wire:model="workSurchargeRate" wire:change="onChangeWorkSurchargeCode">
                </div>
              </div>
            </div>
          @endif
        </div>

        <div class="w-1/2 px-2">
          @if (in_array($propertyType, ['住宅', '普通'], true))
            <div class="futsu2-title">
              <label class="p-3">共済金額</label>
            </div>
          @endif
          @if (in_array($propertyType, ['住宅', '普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label w-1/2" for="building_insurance_payout">建物</label>
              <input class="estimate-input w-1/2 text-right" id="building_insurance_payout"
                name="building_insurance_payout" wire:model="buildingInsurancePayout">
              <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">千円</span>

            </div>
          @endif
          @if (in_array($propertyType, ['住宅', '普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label w-1/2" for="household_insurance_payout">家財</label>
              <input class="estimate-input w-1/2 text-right" id="household_insurance_payout"
                name="household_insurance_payout" wire:model="householdInsurancePayout">
              <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">千円</span>
            </div>
          @endif
          @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label w-1/2" for="facility_insurance_payout">
                設備・什器等
              </label>
              <input class="estimate-input w-1/2 text-right" id="facility_insurance_payout"
                name="facility_insurance_payout" wire:model="facilityInsurancePayout">
              <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">千円</span>
            </div>
          @endif
          @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label w-1/2" for="other_facility_insurance_payout">その他設備
              </label>
              <input class="estimate-input w-1/2 text-right" id="other_facility_insurance_payout"
                name="other_facility_insurance_payout" wire:model="otherFacilityInsurancePayout">
              <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">千円</span>
            </div>
          @endif
          @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label w-1/2" for="item_insurance_payout">商品・製品等</label>
              <input class="estimate-input w-1/2 text-right" id="item_insurance_payout" name="item_insurance_payout"
                wire:model="itemInsurancePayout">
              <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">千円</span>
            </div>
          @endif
          @if (in_array($propertyType, ['普通'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label w-1/2" for="other_item_insurance_payout">その他商品</label>
              <input class="estimate-input w-1/2 text-right" id="other_item_insurance_payout"
                name="other_item_insurance_payout" wire:model="otherItemInsurancePayout">
              <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">千円</span>
            </div>
          @endif

          @if ($estimate->id)
            <div class="futsu2-title">
              <label class="p-3">評価</label>
            </div>
            <div class="estimate-input-box">
              <label class="futsu2-label" for="assess_item">評価項目</label>
              <select class="estimate-input" id="assess_item" name="assess_item" wire:model="assessItem"
                wire:change="onChangeAssessItem">
                <option value="" hidden>選択してください</option>
                @foreach ($assessItemList as $item)
                  <option value="{{ $item }}" @if ($item === $assessItem) selected @endif>
                    {{ $item }}</option>
                @endforeach
              </select>
            </div>
          @else
            <div class="estimate-input-box bg-rose-400 p-2 text-lg text-white">
              評価機能は、見積もり情報を保存することで利用できます。
            </div>
          @endif

          @if (in_array(
                  $assessItem,
                  ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'],
                  true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="assess_usage">用途
                <div class="estimate-input-required">※必須</div>
              </label>

              <select class="estimate-input" id="assess_usage" name="assess_usage" wire:model="assessUsage"
                wire:change="onChangeAssessItem">
                <option value="" hidden>選択してください</option>
                @foreach ($assessUsageList as $item)
                  <option value="{{ $item }}" @if ($item === $assessUsage) selected @endif>
                    {{ $item }}</option>
                @endforeach
              </select>
            </div>
          @endif
          @if (in_array(
                  $assessItem,
                  ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'],
                  true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="assess_structure">構造
                <div class="estimate-input-required">※必須</div>
              </label>
              <select class="estimate-input" id="assess_structure" name="assess_structure"
                wire:model="assessStructure" wire:change="onChangeAssessItem">
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
              <label class="futsu2-label sm:text-[0.6rem]" for="piloti_floor_area">ピロティーの延床面積
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input" id="piloti_floor_area" name="piloti_floor_area"
                wire:model="pilotiFloorArea" wire:change="onChangeAssessItem">
              <span class="unit-display">m²</span>
            </div>
          @endif
          @if (in_array($assessItem, ['大規模改装'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label sm:text-[0.6rem]" for="replacement_price">従来建物の再調達価格
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="replacement_price" name="replacement_price"
                wire:model="replacementPrice" wire:change="onChangeAssessItem">
              <span class="unit-display">円</span>
            </div>
          @endif
          @if (in_array($assessItem, ['大規模改装'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="renovation_cost">改装費用
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="renovation_cost" name="renovation_cost"
                wire:model="renovationCost" wire:change="onChangeAssessItem">
              <span class="unit-display">円</span>
            </div>
          @endif
          @if (in_array($assessItem, ['大規模改装'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label sm:text-[0.6rem]" for="conventional_age">従来建物の築年
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="conventional_age" name="conventional_age"
                wire:model="conventionalAge" wire:change="onChangeAssessItem">
              <span class="unit-display">年</span>
            </div>
          @endif
          @if (in_array($assessItem, ['大規模改装'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label sm:text-[0.6rem]" for="depreciation_rate">従来建物の償却率
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="depreciation_rate" name="depreciation_rate"
                wire:model="depreciationRate" wire:change="onChangeAssessItem">
              <span class="unit-display">%</span>
            </div>
          @endif
          @if (in_array($assessItem, ['大規模改装'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label sm:text-[0.6rem]" for="renovation_age">改装部分の築年
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="renovation_age" name="renovation_age"
                wire:model="renovationAge" wire:change="onChangeAssessItem">
              <span class="unit-display">年</span>
            </div>
          @endif
          @if (in_array($assessItem, ['大規模改装'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label sm:text-[0.6rem]" for="renovation_depreciation_rate">改装部分の償却率
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="renovation_depreciation_rate"
                name="renovation_depreciation_rate" wire:model="renovationDepreciationRate"
                wire:change="onChangeAssessItem">
              <span class="unit-display">%</span>
            </div>
          @endif
          @if (in_array($assessItem, ['太陽光付'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="installation_date">設置日
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input" id="installation_date" name="installation_date" type="date"
                wire:model="installationDate" wire:change="onChangeAssessItem">
            </div>
          @endif
          @if (in_array($assessItem, ['太陽光付'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="installation_cost">設置金額
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="installation_cost" name="installation_cost"
                wire:model="installationCost" wire:change="onChangeAssessItem">
              <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">千円</span>
            </div>
          @endif
          @if (in_array($assessItem, ['年次取得'], true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="annual_price">取得価額
                <div class="estimate-input-required">※必須</div>
              </label>
              <input class="estimate-input text-right" id="annual_price" name="annual_price"
                wire:model="annualPrice" wire:change="onChangeAssessItem">
              <span class="unit-display sm:flex sm:w-16 sm:items-center sm:p-1">千円</span>
            </div>
          @endif
          @if (in_array(
                  $assessItem,
                  ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得', '大規模改装'],
                  true))
            <div class="estimate-input-box">
              <label class="futsu2-label" for="remarks">備考
                <div class="estimate-input-required"></div>
              </label>
              <input class="estimate-input text-right" id="remarks" name="remarks" wire:model="remarks"
                wire:change="onChangeAssessItem">
            </div>
          @endif

          <br>

          <div class="flex flex-col justify-center">
            @if (in_array($assessItem, ['年次取得'], true))
              <div class="flex w-full flex-row">
                <label class="assess-label"></label>
                <label class="assess-label">80%
                </label>
                <label class="assess-label">100%
                </label>
                <label class="assess-label">100%まるめ
                </label>
              </div>
              <div class="flex w-full flex-row">
                <label class="assess-label">再調達価額
                </label>
                <input class="assess-input" wire:model="reProcure80" readonly>
                <input class="assess-input" wire:model="reProcure100" readonly>
                <input class="assess-input" wire:model="reProcure100round" readonly>
              </div>
            @endif
          </div>

          <div class="flex flex-col justify-center">
            @if (in_array($assessItem, ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション'], true))
              <div class="flex w-full flex-row">
                <label class="assess-label">
                  再調達
                </label>
                <label class="assess-label">80%
                </label>
                <label class="assess-label">100%
                </label>
                <label class="assess-label">100%まるめ
                </label>
              </div>
              <div class="flex w-full flex-row">
                <label class="assess-label">
                  {{ $assessStructure == 'プレハブ造' ? '木質系' : 'A' }}
                </label>

                <input class="assess-input" wire:model="reProcureA80" readonly>
                <input class="assess-input" wire:model="reProcureA100" readonly>
                <input class="assess-input" wire:model="reProcureA100round" readonly>
              </div>
            @endif

            @if (in_array($assessItem, ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション'], true))
              <div class="flex w-full flex-row">
                <label class="assess-label">
                  {{ $assessStructure == 'プレハブ造' ? '鉄骨系' : 'B' }}
                </label>
                <input class="assess-input" wire:model="reProcureB80" readonly>
                <input class="assess-input" wire:model="reProcureB100" readonly>
                <input class="assess-input" wire:model="reProcureB100round" readonly>
              </div>
            @endif

            @if (in_array($assessItem, ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション'], true))
              <div class="flex w-full flex-row">
                <label class="assess-label">
                  {{ $assessStructure == 'プレハブ造' ? 'RC系' : 'C' }}
                </label>
                <input class="assess-input" wire:model="reProcureC80" readonly>
                <input class="assess-input" wire:model="reProcureC100" readonly>
                <input class="assess-input" wire:model="reProcureC100round" readonly>
              </div>
            @endif
          </div>

          <div class="mt-5 flex flex-col justify-center">
            @if (in_array($assessItem, ['大規模改装'], true))
              <div class="flex w-full flex-row">
                <label class="market-price-label"></label>
                <label class="market-price-label">通常
                </label>
                <label class="market-price-label">まるめ
                </label>
              </div>
              <div class="flex w-full flex-row">
                <label class="market-price-label">時価額
                </label>
                <input class="market-price-input" wire:model="currentMarketPrice" readonly>
                <input class="market-price-input" wire:model="currentMarketPriceRound" readonly>
              </div>
            @endif
          </div>

          <div class="mt-5 flex flex-col justify-center">
            @if (in_array(
                    $assessItem,
                    ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'],
                    true))
              <div class="flex w-full flex-row">
                <label class="market-price-label">
                  時価額
                </label>
                <label class="market-price-label">通常
                </label>
                <label class="market-price-label">まるめ
                </label>
              </div>
              <div class="flex w-full flex-row">
                <label class="market-price-label">
                  {{ $assessStructure == 'プレハブ造' ? '木質系' : 'A' }}
                </label>
                <input class="market-price-input" wire:model="currentMarketPriceA" readonly>
                <input class="market-price-input" wire:model="currentMarketPriceARound" readonly>
              </div>
            @endif

            @if (in_array(
                    $assessItem,
                    ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'],
                    true))
              <div class="flex w-full flex-row">
                <label class="market-price-label">
                  {{ $assessStructure == 'プレハブ造' ? '鉄骨系' : 'B' }}
                </label>
                <input class="market-price-input" wire:model="currentMarketPriceB" readonly>
                <input class="market-price-input" wire:model="currentMarketPriceBRound" readonly>
              </div>
            @endif

            @if (in_array(
                    $assessItem,
                    ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得'],
                    true))
              <div class="flex w-full flex-row">
                <label class="market-price-label">
                  {{ $assessStructure == 'プレハブ造' ? 'RC系' : 'C' }}
                </label>
                <input class="market-price-input" wire:model="currentMarketPriceC" readonly>
                <input class="market-price-input" wire:model="currentMarketPriceCRound" readonly>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <button
      class="mx-48 mt-5 flex h-full flex-col rounded-lg bg-gray-100 p-8 shadow-md outline outline-1 outline-slate-300 hover:bg-gray-300"
      name="save" type="button" onclick="submitForm(this)">
      <div class="mb-3 flex items-center">
        <div
          class="mr-2 inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-500 text-white">
          <i class="fa-solid fa-floppy-disk"></i>
        </div>
        <h2 class="text-lg font-medium">保存</h2>
      </div>
      <p class="pl-5 text-left">入力された内容を保存します。<br>保存したのちプレビューが可能になります。</p>
    </button>

    @if ($estimate->id)
      <div class="mx-48 mb-6 grid grid-rows-3 gap-4 sm:grid-cols-3">
        <button
          class="mt-5 flex h-full w-full flex-col rounded-lg bg-gray-100 p-8 shadow-md outline outline-1 outline-slate-300 hover:bg-gray-300"
          type="button" value="preview" onclick="submitForm(this)">
          <div class="mb-5 mt-2 flex items-center">
            <div
              class="mr-2 inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-500 text-white">
              <i class="fa-solid fa-floppy-disk"></i>
            </div>
            <div class="text-lg font-medium">プレビュー</div>
          </div>
          <p class="pl-5 text-left">入力した内容で見積書をプレビューする。</p>
        </button>

        @if (in_array(
                $assessItem,
                ['通常(延床面積)', '通常(専有面積)', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得', '大規模改装'],
                true))
          <button
            class="mt-5 flex h-full w-full flex-col rounded-lg bg-gray-100 p-8 shadow-md outline outline-1 outline-slate-300 hover:bg-gray-300"
            type="button" value="assessSheet" onclick="submitForm(this)">
            <div class="mb-2 flex items-center">
              <div
                class="mr-2 inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-500 text-white">
                <i class="fa-solid fa-ranking-star"></i>
              </div>
              <div class="text-lg font-medium">評価シートを<br>ダウンロード</div>
            </div>
            <p class="pl-5 text-left">評価シートをダウンロード方法を選択します。</p>
          </button>
        @endif

        <button
          class="mt-5 flex h-full w-full flex-col rounded-lg bg-gray-100 p-8 shadow-md outline outline-1 outline-slate-300 hover:bg-gray-300"
          type="button" value="download" onclick="submitForm(this)">
          <div class="mb-2 flex items-center">
            <div
              class="mr-2 inline-flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full bg-indigo-500 text-white">
              <i class="fa-solid fa-eye"></i>
            </div>
            <div class="text-lg font-medium">ダウンロードファイル選択</div>
          </div>
          <p class="pl-5 text-left">ダウンロードする見積書を選択する画面に移動します。</p>
        </button>
      </div>
    @endif
  </form>
  @if ($isViewOccupationalTable)
    @include('futsu2.livewire.occupational-table')
  @endif

  @if ($isViewWorkTable)
    @include('futsu2.livewire.work-table')
  @endif

  @if ($isViewDiscountRiskTable)
    @include('futsu2.livewire.discount-risk-table')
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
