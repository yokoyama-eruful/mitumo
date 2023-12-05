<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>mitumo</title>
    @vite(['resources/css/app.css'])
</head>

<body class=" min-h-screen flex flex-col justify-between">
    <header class="sticky top-0 z-10 bg-white shadow-md">
        <div class="container mx-auto flex items-center justify-between py-4 px-6">
            <a href="{{ route('top.index') }}" class="flex items-center text-gray-900 hover:text-gray-700">
                <div class="text-2xl"><i class="fa-solid fa-file-invoice"></i></div>
                <span class="ml-3 text-xl font-medium">mitumo</span>
            </a>
            <nav class="flex space-x-6">
                <a href="{{ route('top.index') }}"
                    class="text-gray-600 hover:text-gray-900 transition duration-300">トップに戻る</a>
                <a href="#" class="text-gray-600 hover:text-gray-900 transition duration-300">一覧から探す</a>
            </nav>
            <form action="{{ route('auth.logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="bg-red-100 hover:bg-red-200 transition duration-300 rounded-full py-2 px-6 text-sm text-red-600 font-medium focus:outline-none">
                    <span class="pr-1">ログアウト</span>
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <main class="w-[800px] flex m-auto text-black body-font bg-white">
        <div class="flex flex-col container p-2 pt-20">
            <div class="estimate-input-box">
                <label class="estimate-label" for="company">
                    取扱代理店名
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="company" name="company" class="estimate-input" required>
            </div>

            <div class="estimate-input-box">
                <label class="estimate-label" for="address">住所
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="address" name="address" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="phone_number">TEL
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="phone_number" name="phone_number" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="staff_name">担当者
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="staff_name" name="staff_name" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="contractee">ご契約者名
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="contractee" name="contractee" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="location">所在地
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="location" name="location" class="estimate-input" required>
            </div>

            <div
                class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600 font-bold">
                <label class="p-3">共済の目的およびこれを収容する建物の構造・用法</label>
            </div>

            <div class="flex flex-row">
                <div class="estimate-input-box">
                    <label for="floor_area" class="estimate-label border">延床面積</label>
                    <input id="floor_area" name="floor_area" type="text" class="estimate-input">
                    <span class="my-auto mx-1">m²</span>
                </div>
                <div class="estimate-input-box">
                    <label for="exclusive_area" class="estimate-label">専有面積</label>
                    <input id="exclusive_area" name="exclusive_area" type="text" class="estimate-input">
                    <span class="my-auto mx-1">m²</span>
                </div>
            </div>

            <div class="flex flex-row">
                <div class="estimate-input-box">
                    <label class="estimate-label" for="structure">構造
                        <div class="estimate-input-required">※必須</div>
                    </label>
                    <select id="structure" name="structure" class="estimate-input" required>
                        <option value="コンクリート造等">コンクリート造等</option>
                        <option value="耐火被覆鉄骨造">耐火被覆鉄骨造</option>
                        <option value="鉄骨造">鉄骨造</option>
                        <option value="木造">木造</option>
                        <option value="土蔵造">土蔵造</option>
                        <option value="その他">その他</option>
                    </select>{{-- セレクトボックス --}}
                </div>
                <div class="estimate-input-box">
                    <label class="estimate-label" for="other_structure">その他構造</label>
                    <input id="other_structure" name="other_structure" class="estimate-input"><!-- 任意・常時 -->
                </div>
            </div>

            <div class="flex flex-row">
                <div class="estimate-input-box">
                    <label class="estimate-label" for="pillar">柱無時</label>
                    <input id="pillar" name="pillar" class="estimate-input">
                </div><!-- 任意・常時 -->{{-- セレクトボックス --}}
                <div class="estimate-input-box">
                    <label class="estimate-label" for="other_pillar">その他柱無時</label>
                    <input id="other_pillar" name="other_pillar" class="estimate-input"><!-- 任意・常時 -->
                </div>
            </div>

            <div class="flex flex-row">
                <div class="estimate-input-box">
                    <label class="estimate-label" for="fire_resistance">耐火基準</label>
                    <input id="fire_resistance" name="fire_resistance" class="estimate-input">
                </div>
                <!-- 任意・常時 -->{{-- セレクトボックス --}}
                <div class="estimate-input-box">
                    <label class="estimate-label" for="other_fire_resistance">その他耐火基準</label>
                    <input id="other_fire_resistance" name="other_fire_resistance" class="estimate-input">
                    <!-- 任意・常時 -->
                </div>
            </div>

            <div class="flex flex-row">
                <div class="estimate-input-box">
                    <label class="estimate-label" for="usage">用法
                        <div class="estimate-input-required">※必須</div>
                    </label>
                    <input id="usage" name="usage" class="estimate-input" required>{{-- セレクトボックス --}}
                </div>
                <div class="estimate-input-box">
                    <label class="estimate-label" for="other_usage">その他用法</label>
                    <input id="other_usage" name="other_usage" class="estimate-input"><!-- 任意・常時 -->
                </div>
            </div>


            <div class="estimate-input-box">
                <label class="estimate-label" for="floor">階
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="floor" name="floor" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="building">棟
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="building" name="building" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other">その他</label>
                <input id="other" name="other" class="estimate-input"><!-- 任意・常時 -->
            </div>

            <div class="estimate-input-box mt-5">
                <div class="flex">
                    <label class="estimate-label" for="property_type">物件
                        <div class="estimate-input-required">※必須</div>
                    </label>
                    <select id="property_type" name="property_type" class="estimate-input w-44" required>
                        <option value="住宅">住宅</option>
                        <option value="普通">普通</option>
                        <option value="工場">工場</option>
                    </select>
                </div>
                <div class="flex">
                    <label class="estimate-label" for="rank">級別
                        <div class="estimate-input-required">※必須</div>
                    </label>
                    <select id="rank" name="rank" class="estimate-input w-44" required>
                        <option value="1級">1級</option>
                        <option value="2級">2級</option>
                        <option value="3級">3級</option>
                        <option value="M">M</option>
                        <option value="T">T</option>
                        <option value="H">H</option>
                    </select>{{-- セレクトボックス --}}
                </div>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="start_date">始期日
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="start_date" name="start_date" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="building_year">建築年
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="building_year" name="building_year" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="building_age">築年数
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="building_age" name="building_age" class="estimate-input" required>
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="pay_method">払込方法
                    <div class="estimate-input-required">※必須</div>
                </label>
                <input id="pay_method" name="pay_method" class="estimate-input" required>{{-- セレクトボックス --}}
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="insurance_year">共済期間</label>
                <input id="insurance_year" name="insurance_year" class="estimate-input">
                <span class="my-auto mx-1">年</span>
                <input id="insurance_month" name="insurance_month" class="estimate-input">
                <span class="my-auto mx-1">ヶ月</span>
                <input id="insurance_day" name="insurance_day" class="estimate-input">
                <span class="my-auto mx-1">日</span>
                <!-- 任意・常時 -->
            </div>


            <!-- 変動のある項目 -->
            <div
                class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600 font-bold mt-5">
                <label class="p-3">おすすめ契約期間指定（年数）</label>
            </div>
            <div class="estimate-input-box">
                <input id="recommend1" name="recommend1" class="estimate-input">
                <span class="my-auto mx-1">年</span>
                <input id="recommend2" name="recommend2" class="estimate-input">
                <span class="my-auto mx-1">年</span>
                <input id="recommend3" name="recommend3" class="estimate-input">
                <span class="my-auto mx-1">年</span>
            </div>

            <div
                class="h-14 w-full flex flex-col items-center justify-center bg-indigo-300 border border-gray-600 font-bold mt-5">
                <label class="p-3">地震特約縮小支払割合指定</label>
            </div>
            <div class="estimate-input-box">
                <input id="earthquake1" name="earthquake1" class="estimate-input">{{-- セレクトボックス --}}
                <span class="my-auto mx-1">%</span>
                <input id="earthquake2" name="earthquake2" class="estimate-input">{{-- セレクトボックス --}}
                <span class="my-auto mx-1">%</span>
                <input id="earthquake3" name="earthquake3" class="estimate-input">{{-- セレクトボックス --}}
                <span class="my-auto mx-1">%</span>
            </div>



            <div class="estimate-input-box mt-5">
                <label class="estimate-label" for="base_rate">基本率</label>
                <input id="base_rate" name="base_rate" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="chattel_surcharge">動産割増</label>
                <input id="chattel_surcharge" name="chattel_surcharge" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="actual_loss_factor">実損払係数</label>
                <input id="actual_loss_factor" name="actual_loss_factor" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_discount">その他割引</label>
                <input id="other_discount" name="other_discount" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="chattel_surcharge_category">動産割増（カテゴリ）</label>
                <input id="chattel_surcharge_category" name="chattel_surcharge_category"
                    class="estimate-input">{{-- セレクトボックス --}}
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="occupational _surcharge">職業割増</label>
                <input id="occupational_surcharge" name="occupational_surcharge" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="work_surcharge">作業割増</label>
                <input id="work_surcharge" name="work_surcharge" class="estimate-input">
            </div>

            <div class="estimate-input-box mt-5">
                <label class="estimate-label bg-indigo-500" for="building_insurance_payout">共済の目的</label>
                <label class="estimate-label bg-indigo-500" for="building_insurance_payout">共済金額</label>
                <label class="estimate-label bg-indigo-500" for="building_insurance_payout">共済金額合計</label>
            </div>

            <div class="estimate-input-box">
                <label class="estimate-label" for="building_insurance_payout">建物共済金額</label>
                <input id="building_insurance_payout" name="building_insurance_payout" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="household_insurance_payout">家財共済金額</label>
                <input id="household_insurance_payout" name="household_insurance_payout" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="facility_insurance_payout">設備共済金額</label>
                <input id="facility_insurance_payout" name="facility_insurance_payout" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_facility_insurance_payout">その他設備共済金額</label>
                <input id="other_facility_insurance_payout" name="other_facility_insurance_payout"
                    class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="item_insurance_payout">商品共済金額</label>
                <input id="item_insurance_payout" name="item_insurance_payout" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="other_item_insurance_payout">その他商品共済金額</label>
                <input id="other_item_insurance_payout" name="other_item_insurance_payout" class="estimate-input">
            </div>

            <div class="estimate-input-box mt-5">
                <label class="estimate-label" for="assess_item">評価項目</label>
                <input id="assess_item" name="assess_item" class="estimate-input">
                <!-- 任意・常時 -->{{-- セレクトボックス --}}
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="assess_usage">用途</label>
                <input id="assess_usage" name="assess_usage" class="estimate-input">{{-- セレクトボックス --}}
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="assess_structure">構造</label>
                <input id="assess_structure" name="assess_structure" class="estimate-input">{{-- セレクトボックス --}}
            </div>

            <div class="estimate-input-box">
                <label class="estimate-label" for="piloti_floor_area">ピロティーの延床面積</label>
                <input id="piloti_floor_area" name="piloti_floor_area" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="replacement_price">従来建物の<br>再調達価格</label>
                <input id="replacement_price" name="replacement_price" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="renovation_cost">改装費用</label>
                <input id="renovation_cost" name="renovation_cost" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="conventional_age">従来建物の築年</label>
                <input id="conventional_age" name="conventional_age" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="depreciation_rate">従来建物の償却率</label>
                <input id="depreciation_rate" name="depreciation_rate" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="renovation_age">改装部分の築年</label>
                <input id="renovation_age" name="renovation_age" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="renovation_depreciation_rate">改装部分の償却率</label>
                <input id="renovation_depreciation_rate" name="renovation_depreciation_rate" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="installation_date">設置日</label>
                <input id="installation_date" name="installation_date" class="estimate-input">
            </div>
            <div class="estimate-input-box">
                <label class="estimate-label" for="installation_cost">設置金額</label>
                <input id="installation_cost" name="installation_cost" class="estimate-input">
            </div>
        </div>

    </main>

    <footer class="bg-gray-200 py-4">
        <div class="container mx-auto text-center text-gray-600 text-sm">
            &copy; 2023 mitumo. All Rights Reserved.
        </div>
    </footer>
</body>

<script>
    // 物件のタイプをトリガーとした表示・非表示
    const propertyTypeSelect = document.getElementById('property_type');

    propertyTypeSelect.addEventListener('change', function() {
        const selectedValue = propertyTypeSelect.value;
        console.log(`Selected property type: ${selectedValue}`);
    });
</script>


</html>
