<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\Assess\AssessAnnual;
use App\Domains\Assess\AssessCondo;
use App\Domains\Assess\AssessNormal;
use App\Domains\Assess\AssessPiloti;
use App\Domains\Assess\AssessRenovation;
use App\Domains\Assess\AssessSolarPanel;
use App\Domains\BuildingBaseRate;
use App\Domains\ChattelBaseRate;
use App\Domains\DiscountRisk;
use App\Domains\FactoryPropertyBasePremium;
use App\Domains\OccupationalSurcharge;
use App\Domains\WorkSurcharge;
use App\Models\Estimate;
use App\Utils\FullWidthDigitConverter;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EstimateForm extends Component
{
    public Estimate $estimate;

    //入力項目　プロパティ名：キャメルケース　nameタグ：スネークケース
    public ?string $company = null;

    public ?string $address = null;

    public ?string $phoneNumber = null;

    public ?string $staffName = null;

    public ?string $contractee = null;

    public ?string $location = null;

    public ?string $floorArea = null;

    public ?string $exclusiveArea = null;

    public ?string $structure = null;

    public ?string $otherStructure = null;

    public ?string $pillar = null;

    public ?string $otherPillar = null;

    public ?string $fireResistance = null;

    public ?string $otherFireResistance = null;

    public ?string $usage = null;

    public ?string $otherUsage = null;

    public ?string $floor = null;

    public ?string $building = null;

    public ?string $other = null;

    public ?string $propertyType = null;

    public ?string $rank = null;

    public ?string $startDate = null;

    public ?string $buildingYear = null;

    public ?string $buildingAge = null;

    public ?string $payMethod = null;

    public ?string $insuranceYear = null;

    public ?string $insuranceMonth = null;

    public ?string $insuranceDay = null;

    public ?string $recommend1 = null;

    public ?string $recommend2 = null;

    public ?string $recommend3 = null;

    public ?string $earthquake1 = null;

    public ?string $earthquake2 = null;

    public ?string $earthquake3 = null;

    /////物件情報/////////////////////////////

    public ?string $buildingBaseRate = null;

    public ?string $chattelBaseRate = null;

    public ?string $chattelSurcharge = null;

    public ?string $actualLossFactor = null;

    public ?string $otherDiscount = null;

    public ?string $discountFireExtinguisher = null;

    public ?string $discountSecurity = null;

    public ?string $discountPublic = null;

    public ?string $chattelSurchargeCategory = null;

    public ?string $occupationalSurchargeCode = null;

    public ?string $occupationalSurchargeRate = null;

    public ?string $occupationalSurchargeItem = null;

    public ?string $workSurchargeCode = null;

    public ?string $workSurchargeRate = null;

    public ?string $workSurchargeItem = null;

    public ?string $discountRiskCode = null;

    public ?string $discountRiskRate = null;

    public ?string $factoryBuildingCode = null;

    public ?string $buildingInsurancePayout = null;

    public ?string $householdInsurancePayout = null;

    public ?string $facilityInsurancePayout = null;

    public ?string $otherFacilityInsurancePayout = null;

    public ?string $itemInsurancePayout = null;

    public ?string $otherItemInsurancePayout = null;

    public ?string $selectFactory = null;

    /////評価項目/////////////////////////////
    public ?string $assessItem = null;

    public ?string $assessUsage = null;

    public ?string $assessStructure = null;

    public ?string $pilotiFloorArea = null;

    public ?string $replacementPrice = null;

    public ?string $renovationCost = null;

    public ?string $conventionalAge = null;

    public ?string $depreciationRate = null;

    public ?string $renovationAge = null;

    public ?string $renovationDepreciationRate = null;

    public ?string $installationDate = null;

    public ?string $installationCost = null;

    public ?string $annualPrice = null;

    public ?string $remarks = null;

    public ?string $reProcure80 = null;

    public ?string $reProcureA80 = null;

    public ?string $reProcureB80 = null;

    public ?string $reProcureC80 = null;

    public ?string $reProcure100 = null;

    public ?string $reProcureA100 = null;

    public ?string $reProcureB100 = null;

    public ?string $reProcureC100 = null;

    public ?string $reProcure100round = null;

    public ?string $reProcureA100round = null;

    public ?string $reProcureB100round = null;

    public ?string $reProcureC100round = null;

    public ?string $currentMarketPrice = null;

    public ?string $currentMarketPriceA = null;

    public ?string $currentMarketPriceB = null;

    public ?string $currentMarketPriceC = null;

    public ?string $currentMarketPriceRound = null;

    public ?string $currentMarketPriceARound = null;

    public ?string $currentMarketPriceBRound = null;

    public ?string $currentMarketPriceCRound = null;

    //セレクトボックス
    public array $structureList;

    public array $pillarList;

    public array $fireResistanceList;

    public array $usageList;

    public array $propertyTypeList;

    public array $rankList;

    public array $payMethodList;

    public array $chattelSurchargeCategoryList;

    public array $earthquakeList;

    public array $assessItemList;

    public array $assessUsageList;

    public array $assessStructureList;

    //テーブル情報の表示切り替え
    public bool $isViewFactoryRateTable = false;

    public string $factoryLargeCategory = 'すべて';

    public bool $isViewOccupationalTable = false;

    public string $occupationalSurchargeLargeCategory = 'すべて';

    public bool $isViewWorkTable = false;

    public string $workSurchargeLargeCategory = 'すべて';

    public bool $isViewDiscountRiskTable = false;

    public string $discountRiskCategory = '';

    //標準コード

    public array $factoryCodeList = [];

    public array $occupationalSurchargeCodeList = [];

    public array $workSurchargeCodeList = [];

    public array $discountRiskCodeList = [];

    public array $discountRiskLargeCodeList = [];

    public function mount($estimate)
    {
        $this->estimate = $estimate;
        $this->company = old('company', $estimate->company) != null ? old('company', $estimate->company) : '広島県中小企業共済協同組合';
        $this->address = old('address', $estimate->address) != null ? old('address', $estimate->address) : '広島市中区竹屋町4-17';
        $this->phoneNumber = old('phone_number', $estimate->phone_number) != null ? old('phone_number', $estimate->phone_number) : '082-243-4411';
        $this->staffName = old('staff_name', $estimate->staff_name) != null ? old('staff_name', $estimate->staff_name) : Auth::user()->name;
        $this->contractee = old('contractee', $estimate->contractee);
        $this->location = old('location', $estimate->location);

        $this->floorArea = old('floor_area', (string) $estimate->floor_area);
        $this->exclusiveArea = old('exclusive_area', (string) $estimate->exclusive_area);
        $this->structure = old('structure', $estimate->structure);
        $this->otherStructure = old('otherStructure', $estimate->other_structure);
        $this->pillar = old('pillar', $estimate->pillar);
        $this->otherPillar = old('other_pillar', $estimate->other_pillar);
        $this->fireResistance = old('fire_resistance', $estimate->fire_resistance);
        $this->otherFireResistance = old('other_fireResistance', $estimate->other_fire_resistance);
        $this->usage = old('usage', $estimate->usage);
        $this->otherUsage = old('other_usage', $estimate->other_usage);
        $this->floor = old('floor', (string) $estimate->floor);
        $this->building = old('building', (string) $estimate->building);
        $this->other = old('other', (string) $estimate->other);
        $this->propertyType = old('property_type', $estimate->property_type);

        $this->onChangePropertyType();

        $this->rank = old('rank', $estimate->rank);
        $this->startDate = old('start_date', $estimate->start_date?->format('Y-m'));
        $this->buildingYear = old('building_year', (string) $estimate->building_year);
        $this->buildingAge = old('building_age', (string) $estimate->building_age);
        $this->payMethod = old('pay_method', $estimate->pay_method);
        $this->insuranceYear = old('insurance_year', (string) $estimate->insurance_year);
        $this->insuranceMonth = old('insurance_month', (string) $estimate->insurance_month);
        $this->insuranceDay = old('insurance_day', (string) $estimate->insurance_day);

        $this->recommend1 = old('recommend1', (string) $estimate->recommendList['recommend1']);
        $this->recommend2 = old('recommend2', (string) $estimate->recommendList['recommend2']);
        $this->recommend3 = old('recommend3', (string) $estimate->recommendList['recommend3']);

        $this->earthquake1 = old('earthquake1', (string) $estimate->earthquakeList['earthquake1']);
        $this->earthquake2 = old('earthquake2', (string) $estimate->earthquakeList['earthquake2']);
        $this->earthquake3 = old('earthquake3', (string) $estimate->earthquakeList['earthquake3']);

        //物件情報
        $this->buildingBaseRate = old('building_base_rate', (string) $estimate->building_base_rate);
        $this->chattelBaseRate = old('chattel_base_rate', (string) $estimate->chattel_base_rate);
        $this->factoryBuildingCode = old('factory_building_code', $this->getValueByKey('factory_building_code', $estimate->property_detail));

        $this->chattelSurcharge = old('chattel_surcharge', $this->getValueByKey('chattel_surcharge', $estimate->property_detail));
        $this->actualLossFactor = old('actual_loss_factor', $this->getValueByKey('actual_loss_factor', $estimate->property_detail));
        $this->discountRiskCategory = old('discount_risk_category', $this->getValueByKey('discount_risk_category', $estimate->property_detail));
        $this->otherDiscount = old('other_discount', $this->getValueByKey('other_discount', $estimate->property_detail));
        $this->discountFireExtinguisher = old('discount_fire_extinguisher', $this->getValueByKey('discount_fire_extinguisher', $estimate->property_detail));
        $this->discountSecurity = old('discount_security', $this->getValueByKey('discount_security', $estimate->property_detail));
        $this->discountPublic = old('discount_public', $this->getValueByKey('discount_public', $estimate->property_detail));
        $this->chattelSurchargeCategory = old('chattel_surcharge_category', $this->getValueByKey('chattel_surcharge_category', $estimate->property_detail));

        $this->occupationalSurchargeCode = old('occupational_surcharge_code', $this->getValueByKey('occupational_surcharge_code', $estimate->property_detail));
        $this->occupationalSurchargeRate = old('occupational_surcharge_rate', $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail));
        $this->occupationalSurchargeItem = old('occupational_surcharge_item', $this->getValueByKey('occupational_surcharge_item', $estimate->property_detail));

        $this->workSurchargeCode = old('work_surcharge_code', $this->getValueByKey('work_surcharge_code', $estimate->property_detail));
        $this->workSurchargeRate = old('work_surcharge_rate', $this->getValueByKey('work_surcharge_rate', $estimate->property_detail));
        $this->workSurchargeItem = old('work_surcharge_item', $this->getValueByKey('work_surcharge_item', $estimate->property_detail));

        $this->discountRiskCode = old('discount_risk_code', $this->getValueByKey('discount_risk_code', $estimate->property_detail));
        $this->discountRiskRate = old('discount_risk_rate', $this->getValueByKey('discount_risk_rate', $estimate->property_detail));

        $this->buildingInsurancePayout = old('building_insurance_payout', $this->getValueByKey('building_insurance_payout', $estimate->property_detail));
        $this->householdInsurancePayout = old('household_insurance_payout', $this->getValueByKey('household_insurance_payout', $estimate->property_detail));
        $this->facilityInsurancePayout = old('facility_insurance_payout', $this->getValueByKey('facility_insurance_payout', $estimate->property_detail));
        $this->otherFacilityInsurancePayout = old('other_facility_insurance_payout', $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail));
        $this->itemInsurancePayout = old('item_insurance_payout', $this->getValueByKey('item_insurance_payout', $estimate->property_detail));
        $this->otherItemInsurancePayout = old('other_item_insurance_payout', $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail));

        //評価項目
        $this->assessItem = old('assess_item', $estimate->assess_item);
        $this->assessUsage = old('assess_usage', $this->getValueByKey('assess_usage', $estimate->assess_detail));
        $this->assessStructure = old('assess_structure', $this->getValueByKey('assess_structure', $estimate->assess_detail));
        $this->pilotiFloorArea = old('piloti_floor_area', $this->getValueByKey('piloti_floor_area', $estimate->assess_detail));
        $this->replacementPrice = old('replacement_price', $this->getValueByKey('replacement_price', $estimate->assess_detail));
        $this->renovationCost = old('renovation_cost', $this->getValueByKey('renovation_cost', $estimate->assess_detail));
        $this->conventionalAge = old('conventional_age', $this->getValueByKey('conventional_age', $estimate->assess_detail));
        $this->depreciationRate = old('depreciation_rate', $this->getValueByKey('depreciation_rate', $estimate->assess_detail));
        $this->renovationAge = old('renovation_age', $this->getValueByKey('renovation_age', $estimate->assess_detail));
        $this->renovationDepreciationRate = old('renovation_depreciation_rate', $this->getValueByKey('renovation_depreciation_rate', $estimate->assess_detail));
        $this->installationDate = old('installation_date', $this->getValueByKey('installation_date', $estimate->assess_detail));
        $this->installationCost = old('installation_cost', $this->getValueByKey('installation_cost', $estimate->assess_detail));
        $this->annualPrice = old('annual_price', $this->getValueByKey('annual_price', $estimate->assess_detail));
        $this->remarks = old('remarks', $this->getValueByKey('remarks', $estimate->assess_detail));

        //セレクトボックスの選択肢
        $this->structureList = $this->getStructureList();
        $this->pillarList = $this->getPillarList();
        $this->fireResistanceList = $this->getFireResistanceList();
        $this->usageList = $this->getUsageList();
        $this->propertyTypeList = $this->getPropertyTypeList();
        $this->payMethodList = $this->getPayMethodList();
        $this->chattelSurchargeCategoryList = $this->getChattelSurchargeCategoryList();
        $this->earthquakeList = $this->getEarthquakeList();
        $this->assessItemList = $this->getAssessItemList();
        $this->assessUsageList = $this->getAssessUsageList();
        $this->assessStructureList = $this->getAssessStructureList();
    }

    public function render()
    {
        if (is_null($this->buildingAge)) {
            $this->onChangeBuildingAge();
        }

        if (isset($this->estimate->id)) {
            $this->onChangeAssessItem();
        }

        if ($this->propertyType == '工場') {
            $this->findFactoryBuildingItem($this->factoryBuildingCode);
        }

        // if (in_array($this->propertyType, ['普通', '住宅'], true)) {
        //     $this->onChangeBaseRate();
        // }

        if ($this->occupationalSurchargeCode) {
            $this->findOccupationalItem();
        }

        if ($this->workSurchargeCode) {
            $this->findWorkSurchargeItem();
        }

        $this->onChangeDiscountRiskCode();

        return view('estimate.livewire.form');
    }

    /**
     * 物件情報に連動した級別セレクトボックスを設置する
     *
     * @return void
     */
    public function onChangePropertyType()
    {
        $this->buildingBaseRate = '';
        $this->rankList = $this->getRankList($this->propertyType);
    }

    /**
     * 基本率の自動算出
     *
     * @return void
     */
    public function onChangeBaseRate()
    {
        if (
            is_null($this->propertyType) ||
            is_null($this->rank)
        ) {
            $this->buildingBaseRate = null;
            $this->chattelBaseRate = null;
            $this->factoryBuildingCode = null;

            return;
        }

        if ($this->propertyType === '工場') {
            $this->onChangeFactoryBuildingCode();

            return;
        }

        if ($this->propertyType === '住宅') {
            $buildingAge = $this->buildingAge ?? '';
        } else {
            $buildingAge = '';
        }

        $buildingBaseRate = new BuildingBaseRate($this->estimate, 'rateTable/buildingBaseRateTable.csv');
        $this->buildingBaseRate = (string) $buildingBaseRate->findRate($this->propertyType, $buildingAge, $this->rank);
        $chattelBaseRate = new ChattelBaseRate($this->estimate, 'rateTable/chattelBaseTable.csv');
        $this->chattelBaseRate = (string) $chattelBaseRate->findRate($this->propertyType, $this->rank);

        if (isset($this->occupationalSurchargeCode)) {
            $this->onChangeOccupationalSurchargeCode();
        }

        if (isset($this->workSurchargeCode)) {
            $this->onChangeWorkSurchargeCode();
        }
    }

    /**
     * 築年数を算出する
     *
     * @return void
     */
    public function onChangeBuildingAge()
    {
        if (! $this->startDate || ! $this->buildingYear) {
            $this->buildingAge = null;

            return;
        }

        $this->buildingYear = (string) FullWidthDigitConverter::convert($this->buildingYear);

        $buildingDate = CarbonImmutable::createFromDate((int) $this->buildingYear, 1, 1);
        [$startYear,$startMonth] = explode('-', $this->startDate);

        $this->buildingAge = $buildingDate->year <= $startYear ? (string) $buildingDate->diffInYears($startYear) : null;

        $this->onChangeBaseRate();
    }

    /**
     * おすすめ契約期間指定の入力項目の表示トリガー
     *共済期間（年）
     * @return void
     */
    public function onChangeRecommend()
    {
        $this->insuranceMonth = null;
        $this->insuranceDay = null;
    }

    /**
     * 共済期間（月）
     */
    public function onChangeInsuranceMonth()
    {
        $this->insuranceYear = null;
    }

    /**
     * 共済期間（日）
     */
    public function onChangeInsuranceDay()
    {
        $this->insuranceYear = null;
    }

    /**
     * 評価項目によって評価用途の選択肢を変更するトリガー
     *
     * @return void
     */
    public function onChangeAssessItem()
    {
        if ($this->floorArea) {
            $this->floorArea = number_format((float) $this->floorArea, 2);
            $this->floorArea = str_replace(',', '', $this->floorArea);
        }

        if ($this->exclusiveArea) {
            $this->exclusiveArea = number_format((float) $this->exclusiveArea, 2);
            $this->exclusiveArea = str_replace(',', '', $this->exclusiveArea);
        }

        $this->assessUsageList = $this->getAssessUsageList();
        $this->assessStructureList = $this->getAssessStructureList();
        $this->onChangeAssessAmount();
    }

    /**
     * 工場物件建物基本率エラー
     */
    public function onChangeFactoryBuildingRate()
    {
        if ($this->buildingBaseRate > 100) {
            $this->dispatch('alert', '100以上の値は存在しません。');
        }
    }

    /**
     * 工場物件の建物基本率テーブルを開く
     *
     * @return void
     */
    public function onOpenFactoryRateTable()
    {
        if (is_null($this->rank) || $this->rank == '') {
            $this->dispatch('alert', '級別を指定してください。');

            return;
        }

        $this->onClickFactoryLargeCategory();

        $this->dispatch('disableScroll');
        $this->isViewFactoryRateTable = true;
    }

    public function onSelectedFactoryBuildingCode($code)
    {
        $this->factoryBuildingCode = $code;
        $this->onChangeFactoryBuildingCode();

        $this->onClickCloseTable();
    }

    public function onChangeFactoryBuildingCode()
    {
        if (empty($this->factoryBuildingCode)) {
            return;
        }

        $this->factoryBuildingCode = mb_convert_kana($this->factoryBuildingCode, 'n');

        $factoryPropertyBasePremium = new FactoryPropertyBasePremium();

        $factoryInfo = $factoryPropertyBasePremium->findCode($this->factoryBuildingCode, $this->rank);

        $this->buildingBaseRate = (string) $factoryInfo['基本率'];

        $this->chattelBaseRate = (string) $factoryInfo['基本率'];

        $this->chattelSurcharge = (string) $factoryInfo['動産割増'];

        $this->findFactoryBuildingItem($this->factoryBuildingCode);

    }

    public function findFactoryBuildingItem($code)
    {
        if (is_null($code)) {
            return;
        }

        $this->factoryBuildingCode = mb_convert_kana($code, 'n');

        $factoryPropertyBasePremium = new FactoryPropertyBasePremium();

        $this->selectFactory = (string) $factoryPropertyBasePremium->getFactoryPropertyBasePremiumItem($this->factoryBuildingCode, $this->rank);
    }

    public function onClickFactoryLargeCategory($category = 'すべて')
    {
        $this->factoryLargeCategory = $category;
        $factoryBase = new FactoryPropertyBasePremium();
        $rateList = $factoryBase->rateList;
        $rank = $this->rank;

        if ($category === 'すべて') {
            $this->factoryCodeList = array_filter($rateList, function ($element) use ($rank) {
                return is_array($element) && isset($element['級別']) && $element['級別'] === $rank;
            });
        } else {
            $this->factoryCodeList = array_filter($rateList, function ($element) use ($category, $rank) {
                return is_array($element) && isset($element['級別']) && $element['大項目'] == $category && $element['級別'] === $rank;
            });
        }
    }

    /**
     * 職業 テーブル情報から標準コードを参照する
     *
     * @return void
     */
    public function onClickOpenOccupationalSurchargeTable()
    {
        if (is_null($this->rank) || $this->rank == '') {
            $this->dispatch('alert', '級別を指定してください。');

            return;
        }

        $this->onClickOccupationalLargeCategory();

        $this->dispatch('disableScroll');
        $this->isViewOccupationalTable = true;
    }

    public function onClickOccupationalLargeCategory($category = 'すべて')
    {
        $this->occupationalSurchargeLargeCategory = $category;
        $occupationalSurcharge = new OccupationalSurcharge('surchargeTable/occupationalSurchargeTable.csv');
        $rateList = $occupationalSurcharge->rateList;
        $rank = $this->rank;

        if ($category === 'すべて') {
            $this->occupationalSurchargeCodeList = array_filter($rateList, function ($element) use ($rank) {
                return is_array($element) && isset($element['級別']) && $element['級別'] === $rank;
            });
        } else {
            $this->occupationalSurchargeCodeList = array_filter($rateList, function ($element) use ($category, $rank) {
                return is_array($element) && isset($element['級別']) && $element['大項目'] == $category && $element['級別'] === $rank;
            });
        }
    }

    public function onSelectedOccupationalCode($code)
    {
        $this->occupationalSurchargeCode = $code;
        $this->onChangeOccupationalSurchargeCode();

        $this->onClickCloseTable();
    }

    /**
     * 職業 標準コードから職業割増を算出する
     *
     * @return void
     */
    public function onChangeOccupationalSurchargeCode()
    {
        $this->occupationalSurchargeCode = mb_convert_kana($this->occupationalSurchargeCode, 'n');

        $occupationalSurcharge = new OccupationalSurcharge('surchargeTable/occupationalSurchargeTable.csv');

        $this->occupationalSurchargeRate = (string) $occupationalSurcharge->findCode($this->occupationalSurchargeCode, $this->rank);

        $this->occupationalSurchargeItem = (string) $occupationalSurcharge->getOccupationalSurchargeItem($this->occupationalSurchargeCode, $this->rank);

        $this->onChangeOccupationalSurchargeRate();
    }

    public function findOccupationalItem()
    {
        $this->occupationalSurchargeCode = mb_convert_kana($this->occupationalSurchargeCode, 'n');

        $occupationalSurcharge = new OccupationalSurcharge('surchargeTable/occupationalSurchargeTable.csv');

        $this->occupationalSurchargeItem = (string) $occupationalSurcharge->getOccupationalSurchargeItem($this->occupationalSurchargeCode, $this->rank);
    }

    public function onChangeOccupationalSurchargeRate()
    {
        if ($this->workSurchargeRate) {
            $this->dispatch('alert', '職業割増、作業割増は一方しか適用できません。作業割増を削除します。');
        }
        $this->workSurchargeCode = null;
        $this->workSurchargeRate = null;
        $this->workSurchargeItem = null;
    }

    /**
     * 職業 テーブル情報から作業工程コードを参照する
     *
     * @return void
     */
    public function onClickOpenWorkSurchargeTable()
    {
        if (is_null($this->rank) || $this->rank == '') {
            $this->dispatch('alert', '級別を指定してください。');

            return;
        }
        $this->onClickWorkLargeCategory();
        $this->dispatch('disableScroll');

        $this->isViewWorkTable = true;
    }

    public function onClickWorkLargeCategory($category = 'すべて')
    {
        $this->workSurchargeLargeCategory = $category;
        $workSurcharge = new WorkSurcharge('surchargeTable/workSurchargeTable.csv');
        $rateList = $workSurcharge->rateList;
        $rank = $this->rank;

        if ($category === 'すべて') {
            $this->workSurchargeCodeList = array_filter($rateList, function ($element) use ($rank) {
                return is_array($element) && isset($element['級別']) && $element['級別'] === $rank;
            });
        } else {
            $this->workSurchargeCodeList = array_filter($rateList, function ($element) use ($category, $rank) {
                return is_array($element) && isset($element['級別']) && $element['大項目'] == $category && $element['級別'] === $rank;
            });
        }
    }

    /**
     * 作業 作業工程テーブル情報から作業工程コードを参照する
     *
     * @return void
     */
    public function onSelectedWorkCode($code)
    {
        $this->workSurchargeCode = $code;
        $this->onChangeWorkSurchargeCode();
        $this->onClickCloseTable();
    }

    /**
     * 作業工程コードから作業割増を算出する
     *
     * @return void
     */
    public function onChangeWorkSurchargeCode()
    {
        $this->workSurchargeCode = mb_convert_kana($this->workSurchargeCode, 'n');

        $workSurcharge = new WorkSurcharge('surchargeTable/workSurchargeTable.csv');

        $this->workSurchargeRate = (string) $workSurcharge->findCode($this->workSurchargeCode, $this->rank);

        $this->workSurchargeItem = (string) $workSurcharge->getWorkSurchargeItem($this->workSurchargeCode, $this->rank);

        $this->onChangeWorkSurchargeRate();
    }

    public function findWorkSurchargeItem()
    {
        $this->workSurchargeCode = mb_convert_kana($this->workSurchargeCode, 'n');

        $workSurcharge = new WorkSurcharge('surchargeTable/workSurchargeTable.csv');

        $this->workSurchargeItem = (string) $workSurcharge->getWorkSurchargeItem($this->workSurchargeCode, $this->rank);
    }

    public function onChangeWorkSurchargeRate()
    {
        if ($this->occupationalSurchargeRate) {
            $this->dispatch('alert', '職業割増、作業割増は一方しか適用できません。職業割増を削除します。');
        }
        $this->occupationalSurchargeCode = null;
        $this->occupationalSurchargeRate = null;
        $this->occupationalSurchargeItem = null;
    }

    /**
     * リスク評価のための表を参照
     *
     * @return void
     */
    public function onClickDiscountRiskTable()
    {
        $this->onFindDiscountRiskCategory();

        $this->dispatch('disableScroll');
        $this->isViewDiscountRiskTable = true;
    }

    public function onFindDiscountRiskCategory()
    {

        $discountRisk = new DiscountRisk();
        $rateList = $discountRisk->rateList;
        $this->discountRiskCodeList = array_filter($rateList, function ($element) {
            return is_array($element);
        });

        $this->discountRiskLargeCodeList = ['Ⅰ.危険分散', 'Ⅱ.消火設備', 'Ⅲ.セキュリティ', 'Ⅳ.火気管理', 'Ⅴ.ロス履歴'];
    }

    public function onSelectDiscountRiskCode($code)
    {
        $this->discountRiskCode = $code;
        $this->onChangeDiscountRiskCode();
        $this->onClickCloseTable();
    }

    public function onChangeDiscountRiskCode()
    {

        if (empty($this->discountRiskCode)) {
            return;
        }

        $this->discountRiskCode = mb_convert_kana($this->discountRiskCode, 'n');

        $discountRisk = new DiscountRisk();

        $this->discountRiskRate = (string) $discountRisk->findCode($this->discountRiskCode);

        $this->discountRiskCategory = (string) $discountRisk->getDiscountRiskItem($this->discountRiskCode);
    }

    public function onChangeDiscountRiskRate()
    {
        $this->discountRiskCode = '';

        if (empty($this->discountRiskRate)) {
            $this->discountRiskRate = '';
            $this->discountRiskCode = '';
            $this->discountRiskCategory = '';
        }
    }

    /**
     * 職業 テーブル情報から標準コードを参照する
     *
     * @return void
     */
    public function onClickCloseTable()
    {
        $this->isViewFactoryRateTable = false;
        $this->isViewOccupationalTable = false;
        $this->isViewWorkTable = false;
        $this->isViewDiscountRiskTable = false;

        $this->dispatch('enableScroll');
    }

    /**
     * 再調達価額、時価額を参照
     *
     * @return void
     */
    public function onChangeAssessAmount()
    {
        $this->reProcure80 = '';
        $this->reProcureA80 = '';
        $this->reProcureB80 = '';
        $this->reProcureC80 = '';
        $this->reProcure100 = '';
        $this->reProcureA100 = '';
        $this->reProcureB100 = '';
        $this->reProcureC100 = '';
        $this->reProcure100round = '';
        $this->reProcureA100round = '';
        $this->reProcureB100round = '';
        $this->reProcureC100round = '';
        $this->currentMarketPrice = '';
        $this->currentMarketPriceA = '';
        $this->currentMarketPriceB = '';
        $this->currentMarketPriceC = '';
        $this->currentMarketPriceRound = '';
        $this->currentMarketPriceARound = '';
        $this->currentMarketPriceBRound = '';
        $this->currentMarketPriceCRound = '';

        if (is_null($this->assessUsage) || is_null($this->assessStructure)) {
            return;
        }

        if ($this->assessItem == '選択しない' || is_null($this->assessItem)) {
            return;
        }

        if (is_null($this->buildingAge) || $this->buildingAge == '') {
            $this->dispatch('alert', '評価する場合は、築年数を入力してください。');

            return;
        }

        switch ($this->assessItem) {
            case '通常':
                $assessDomain = new AssessNormal($this->estimate);

                $this->reProcureA80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'A')['80'];
                $this->reProcureB80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'B')['80'];
                $this->reProcureC80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'C')['80'];

                $this->reProcureA100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'A')['100'];
                $this->reProcureB100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'B')['100'];
                $this->reProcureC100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'C')['100'];

                $this->reProcureA100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'A')['100まるめ'];
                $this->reProcureB100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'B')['100まるめ'];
                $this->reProcureC100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'C')['100まるめ'];

                $this->currentMarketPriceA = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'A')['通常'];
                $this->currentMarketPriceB = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'B')['通常'];
                $this->currentMarketPriceC = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'C')['通常'];

                $this->currentMarketPriceARound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'A')['まるめ'];
                $this->currentMarketPriceBRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'B')['まるめ'];
                $this->currentMarketPriceCRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'C')['まるめ'];
                break;
            case 'ピロティー付':
                $assessDomain = new AssessPiloti($this->estimate);

                $this->reProcureA80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'A')['80'];
                $this->reProcureB80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'B')['80'];
                $this->reProcureC80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'C')['80'];

                $this->reProcureA100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'A')['100'];
                $this->reProcureB100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'B')['100'];
                $this->reProcureC100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'C')['100'];

                $this->reProcureA100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'A')['100まるめ'];
                $this->reProcureB100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'B')['100まるめ'];
                $this->reProcureC100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, 'C')['100まるめ'];

                $this->currentMarketPriceA = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, $this->buildingAge, 'A')['通常'];
                $this->currentMarketPriceB = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, $this->buildingAge, 'B')['通常'];
                $this->currentMarketPriceC = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, $this->buildingAge, 'C')['通常'];

                $this->currentMarketPriceARound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, $this->buildingAge, 'A')['まるめ'];
                $this->currentMarketPriceBRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, $this->buildingAge, 'B')['まるめ'];
                $this->currentMarketPriceCRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->pilotiFloorArea, $this->buildingAge, 'C')['まるめ'];
                break;
            case '太陽光付':
                $assessDomain = new AssessSolarPanel($this->estimate);

                $this->reProcureA80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'A')['80'];
                $this->reProcureB80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'B')['80'];
                $this->reProcureC80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'C')['80'];

                $this->reProcureA100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'A')['100'];
                $this->reProcureB100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'B')['100'];
                $this->reProcureC100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'C')['100'];

                $this->reProcureA100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'A')['100まるめ'];
                $this->reProcureB100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'B')['100まるめ'];
                $this->reProcureC100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->floorArea, 'C')['100まるめ'];

                $this->currentMarketPriceA = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'A')['通常'];
                $this->currentMarketPriceB = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'B')['通常'];
                $this->currentMarketPriceC = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'C')['通常'];

                $this->currentMarketPriceARound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'A')['まるめ'];
                $this->currentMarketPriceBRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'B')['まるめ'];
                $this->currentMarketPriceCRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->floorArea, $this->buildingAge, 'C')['まるめ'];
                break;
            case '分譲マンション':
                $assessDomain = new AssessCondo($this->estimate);

                $this->reProcureA80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'A')['80'];
                $this->reProcureB80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'B')['80'];
                $this->reProcureC80 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'C')['80'];

                $this->reProcureA100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'A')['100'];
                $this->reProcureB100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'B')['100'];
                $this->reProcureC100 = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'C')['100'];

                $this->reProcureA100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'A')['100まるめ'];
                $this->reProcureB100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'B')['100まるめ'];
                $this->reProcureC100round = $assessDomain->viewReProcure($this->assessUsage, $this->assessStructure, $this->exclusiveArea, 'C')['100まるめ'];

                $this->currentMarketPriceA = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->exclusiveArea, $this->buildingAge, 'A')['通常'];
                $this->currentMarketPriceB = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->exclusiveArea, $this->buildingAge, 'B')['通常'];
                $this->currentMarketPriceC = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->exclusiveArea, $this->buildingAge, 'C')['通常'];

                $this->currentMarketPriceARound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->exclusiveArea, $this->buildingAge, 'A')['まるめ'];
                $this->currentMarketPriceBRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->exclusiveArea, $this->buildingAge, 'B')['まるめ'];
                $this->currentMarketPriceCRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->exclusiveArea, $this->buildingAge, 'C')['まるめ'];
                break;
            case '年次取得':
                if ($this->buildingAge > 48) {
                    $this->dispatch('alert', 'この築年数は評価できません。');
                }

                $assessDomain = new AssessAnnual($this->estimate);

                $this->reProcure80 = $assessDomain->viewReProcure($this->rank, $this->annualPrice, $this->buildingAge)['80'];
                $this->reProcure100 = $assessDomain->viewReProcure($this->rank, $this->annualPrice, $this->buildingAge)['100'];
                $this->reProcure100round = $assessDomain->viewReProcure($this->rank, $this->annualPrice, $this->buildingAge)['100まるめ'];

                $this->currentMarketPriceA = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->rank, $this->annualPrice, $this->buildingAge, 'A')['通常'];
                $this->currentMarketPriceB = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->rank, $this->annualPrice, $this->buildingAge, 'B')['通常'];
                $this->currentMarketPriceC = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->rank, $this->annualPrice, $this->buildingAge, 'C')['通常'];

                $this->currentMarketPriceARound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->rank, $this->annualPrice, $this->buildingAge, 'A')['まるめ'];
                $this->currentMarketPriceBRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->rank, $this->annualPrice, $this->buildingAge, 'B')['まるめ'];
                $this->currentMarketPriceCRound = $assessDomain->viewCurrentMarketPrice($this->assessUsage, $this->assessStructure, $this->rank, $this->annualPrice, $this->buildingAge, 'C')['まるめ'];
                break;
            case '大規模改装':

                if (! is_numeric($this->replacementPrice) ||
                ! is_numeric($this->renovationCost) ||
                ! is_numeric($this->conventionalAge) ||
                ! is_numeric($this->depreciationRate) ||
                ! is_numeric($this->renovationAge) ||
                ! is_numeric($this->renovationDepreciationRate)) {
                    break;
                }

                $assessDomain = new AssessRenovation($this->estimate);

                $this->currentMarketPrice = $assessDomain->viewCurrentMarketPrice($this->replacementPrice, $this->renovationCost, $this->conventionalAge, $this->depreciationRate, $this->renovationAge, $this->renovationDepreciationRate)['通常'];
                $this->currentMarketPriceRound = $assessDomain->viewCurrentMarketPrice($this->replacementPrice, $this->renovationCost, $this->conventionalAge, $this->depreciationRate, $this->renovationAge, $this->renovationDepreciationRate)['まるめ'];
                break;

        }

    }

    //選択肢のデータ格納用メソッド
    private function getStructureList()
    {
        return [
            'コンクリート造等',
            '耐火被覆鉄骨造',
            '鉄骨造',
            '木造',
            '土蔵造',
            '軽量鉄骨',
            'その他',
        ];
    }

    private function getPillarList()
    {
        return [
            '',
            '木造枠組壁（２×４）',
            'コンクリート系プレハブ',
            '鉄骨系プレハブ',
            '木質系プレハブ',
        ];
    }

    private function getFireResistanceList()
    {
        return [
            '',
            '耐火建築物',
            '準耐火建築物',
            '省令準耐火建物',
            'その他',
        ];
    }

    private function getUsageList()
    {
        return [
            '専用住宅（1戸建）',
            '専用住宅（共同住宅）',
            '併用住宅',
            '店舗',
            '事務所',
            '工場',
            '作業場',
            '倉庫',
            'その他',
        ];
    }

    private function getPropertyTypeList()
    {
        return [
            '住宅',
            '普通',
            '工場',
        ];
    }

    private function getRankList($propertyType)
    {
        $this->rank = null;

        return match ($propertyType) {
            '普通', '工場' => ['1級', '2級', '3級'],
            '住宅' => ['M', 'T', 'H'],
            default => [],
        };
    }

    private function getPayMethodList()
    {
        return [
            '一時払',
            '長期年払',
            '分割払',
        ];
    }

    private function getChattelSurchargeCategoryList()
    {
        return [
            '-',
            '1級品',
            '2級品',
        ];
    }

    private function getEarthquakeList()
    {
        return [15, 30, 50];
    }

    private function getAssessItemList()
    {
        return ['選択しない', '通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得', '大規模改装'];
    }

    private function getAssessUsageList()
    {
        return match ($this->assessItem) {
            '通常', 'ピロティー付', '太陽光付', '年次取得' => ['専用住宅', '併用住宅', '専用店舗', '工場・倉庫', 'プレハブ'],
            '分譲マンション' => ['分譲マンション', '分譲マンション以外'],
            default => [],
        };
    }

    private function getAssessStructureList()
    {
        return match ($this->assessItem) {
            '通常', 'ピロティー付', '太陽光付', '年次取得' => ['木造', '鉄骨', '鉄筋（RC）', '枠組（2×4）', 'プレハブ造', '軽量鉄骨'],
            '分譲マンション' => ['上塗', '壁真'],
            default => [],
        };
    }

    /**
     * 配列から安全に中身を取得するUtils関数
     */
    private function getValueByKey(string $key, ?array $array): string
    {
        if (is_null($array)) {
            return '';
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return $array[$key];
        }

        return '';
    }
}
