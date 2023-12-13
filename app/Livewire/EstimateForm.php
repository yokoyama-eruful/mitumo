<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\BuildingBaseRate;
use App\Domains\OccupationalSurcharge;
use App\Domains\WorkSurcharge;
use App\Models\Estimate;
use App\Utils\FullWidthDigitConverter;
use Carbon\CarbonImmutable;
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

    public ?string $baseRate = null;

    public ?string $chattelSurcharge = null;

    public ?string $actualLossFactor = null;

    public ?string $otherDiscount = null;

    public ?string $chattelSurchargeCategory = null;

    public ?string $occupationalSurchargeCode = null;

    public ?string $occupationalSurchargeRate = null;

    public ?string $workSurchargeCode = null;

    public ?string $workSurchargeRate = null;

    public ?string $buildingInsurancePayout = null;

    public ?string $householdInsurancePayout = null;

    public ?string $facilityInsurancePayout = null;

    public ?string $otherFacilityInsurancePayout = null;

    public ?string $itemInsurancePayout = null;

    public ?string $otherItemInsurancePayout = null;

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

    //セレクトボックス
    public array $structureList;

    public array $pillarList;

    public array $fireResistanceList;

    public array $usageList;

    public array $propertyTypeList;

    public array $rankList;

    public array $payMethodList;

    public array $earthquakeList;

    public array $assessItemList;

    public array $assessUsageList;

    public array $assessStructureList;

    //テーブル情報の表示切り替え
    public bool $isViewOccupationalTable = false;

    public bool $isViewWorkTable = false;

    //標準コード
    private OccupationalSurcharge $occupationalSurcharge;

    public array $occupationalSurchargeCodeList = [];

    private WorkSurcharge $workSurcharge;

    public array $workSurchargeCodeList = [];

    public function mount($estimate)
    {
        $this->estimate = $estimate;

        $this->company = old('company', $estimate->company);
        $this->address = old('address', $estimate->address);
        $this->phoneNumber = old('phone_number', $estimate->phone_number);
        $this->staffName = old('staff_name', $estimate->staff_name);
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
        $this->baseRate = old('base_rate', (string) $estimate->base_rate);
        $this->chattelSurcharge = old('chattel_surcharge', $this->getValueByKey('chattel_surcharge', $estimate->property_detail));
        $this->actualLossFactor = old('actual_loss_factor', $this->getValueByKey('actual_loss_factor', $estimate->property_detail));
        $this->otherDiscount = old('other_discount', $this->getValueByKey('other_discount', $estimate->property_detail));
        $this->chattelSurchargeCategory = old('chattel_surcharge_category', $this->getValueByKey('chattel_surcharge_category', $estimate->property_detail));

        $this->occupationalSurchargeCode = old('occupational_surcharge_code', $this->getValueByKey('occupational_surcharge_code', $estimate->property_detail));
        $this->occupationalSurchargeRate = old('occupational_surcharge_rate', $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail));

        $this->workSurchargeCode = old('work_surcharge_code', $this->getValueByKey('work_surcharge_code', $estimate->property_detail));
        $this->workSurchargeRate = old('work_surcharge_rate', $this->getValueByKey('work_surcharge_rate', $estimate->property_detail));

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

        //セレクトボックスの選択肢
        $this->structureList = $this->getStructureList();
        $this->pillarList = $this->getPillarList();
        $this->fireResistanceList = $this->getFireResistanceList();
        $this->usageList = $this->getUsageList();
        $this->propertyTypeList = $this->getPropertyTypeList();
        $this->payMethodList = $this->getPayMethodList();
        $this->earthquakeList = $this->getEarthquakeList();
        $this->assessItemList = $this->getAssessItemList();
        $this->assessUsageList = $this->getAssessUsageList();
        $this->assessStructureList = $this->getAssessStructureList();

        $this->occupationalSurcharge = new OccupationalSurcharge();
        $this->occupationalSurchargeCodeList = $this->occupationalSurcharge->rateList;
        $this->workSurcharge = new WorkSurcharge();
        $this->workSurchargeCodeList = $this->workSurcharge->rateList;
    }

    public function render()
    {
        return view('estimate.livewire.form');
    }

    /**
     * 物件情報に連動した級別セレクトボックスを設置する
     *
     * @return void
     */
    public function onChangePropertyType()
    {
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
            $this->baseRate = null;

            return;
        }

        if ($this->propertyType == '工場') {
            return;
        }

        if ($this->propertyType === '住宅') {
            $buildingAge = $this->buildingAge ?? '';
        } else {
            $buildingAge = '';
        }

        $baseRate = new BuildingBaseRate();

        $this->baseRate = (string) $baseRate->findRate($this->propertyType, $buildingAge, $this->rank);
    }

    /**
     * 築年数を算出する
     *
     * @return void
     */
    public function onChangeBuildingAge()
    {
        $this->buildingYear = (string) FullWidthDigitConverter::convert($this->buildingYear);

        if (! $this->startDate || ! $this->buildingYear) {
            $this->buildingAge = null;

            return;
        }

        $buildingDate = CarbonImmutable::createFromDate((int) $this->buildingYear, 1, 1);
        $startDate = $this->startDate;

        $this->buildingAge = $buildingDate <= $startDate ? (string) $buildingDate->diffInYears($startDate) : null;

        $this->onChangeBaseRate();
    }

    /**
     * おすすめ契約期間指定の入力項目の表示トリガー
     *
     * @return void
     */
    public function onChangeRecommend()
    {

    }

    /**
     * 評価項目によって評価用途の選択肢を変更するトリガー
     *
     * @return void
     */
    public function onChangeAssessItem()
    {
        $this->assessUsageList = $this->getAssessUsageList();
    }

    /**
     * 職業 テーブル情報から標準コードを参照する
     *
     * @return void
     */
    public function onClickOpenOccupationalSurchargeTable()
    {
        $this->dispatch('disableScroll');
        $this->isViewOccupationalTable = true;
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

        $occupationalSurcharge = new OccupationalSurcharge();

        $this->occupationalSurchargeRate = (string) $occupationalSurcharge->findCode($this->occupationalSurchargeCode);
    }

    /**
     * 職業 テーブル情報から作業工程コードを参照する
     *
     * @return void
     */
    public function onClickOpenWorkSurchargeTable()
    {
        $this->dispatch('disableScroll');

        $this->isViewWorkTable = true;
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

        $workSurcharge = new WorkSurcharge();

        $this->workSurchargeRate = (string) $workSurcharge->findCode($this->workSurchargeCode);
    }

    /**
     * 職業 テーブル情報から標準コードを参照する
     *
     * @return void
     */
    public function onClickCloseTable()
    {
        $this->isViewOccupationalTable = false;
        $this->isViewWorkTable = false;

        $this->dispatch('enableScroll');
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
            'その他',
        ];
    }

    private function getPillarList()
    {
        return [
            '木造枠組壁（２×４）',
            'コンクリート系プレハブ',
            '鉄骨系プレハブ',
            '木質系プレハブ',
        ];
    }

    private function getFireResistanceList()
    {
        return [
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

    private function getEarthquakeList()
    {
        return [15, 30, 50];
    }

    private function getAssessItemList()
    {
        return ['通常', 'ピロティー付', '太陽光付', '分譲マンション', '年次取得', '改装物件'];
    }

    private function getAssessUsageList()
    {
        return match ($this->assessItem) {
            '通常', 'ピロティー付', '太陽光付', '年次取得' => ['専用住宅', '併用住宅', '専用店舗', '工場・倉庫', 'プレハブ'],
            '分譲マンション' => ['分譲マンション'],
            default => [],
        };
    }

    private function getAssessStructureList()
    {
        return [
            '木造', '鉄骨', '鉄筋（RC）', '枠組（2×4）', 'プレハブ造', '軽量鉄骨',
        ];
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
