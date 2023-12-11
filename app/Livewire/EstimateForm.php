<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\BaseRate;
use App\Domains\OccupationalSurcharge;
use App\Domains\WorkSurcharge;
use App\Models\Estimate;
use Carbon\Carbon;
use Livewire\Component;

use function old;

class EstimateForm extends Component
{
    public Estimate $estimate;

    public ?string $propertyType = null;

    public ?string $rank = null;

    public array $baseRateList = [];

    public ?string $startDate = null;

    public ?string $buildingYear = null;

    public ?int $buildingAge = null;

    public ?string $payMethod = null;

    public ?int $insuranceYear = null;

    public ?float $baseRate = null;

    //職業割増
    public ?string $occupationalSurchargeCode = null;

    public ?float $occupationalSurchargeRate = null;

    //作業割増
    public ?string $workSurchargeCode = null;

    public ?float $workSurchargeRate = null;

    //評価項目
    public ?string $assessItem = null;

    public array $assessUsageList = [];

    public function mount($estimate)
    {
        $this->estimate = $estimate;
        $this->startDate = old('start_date') ?? $estimate->start_date?->format('Y-m');
        $this->buildingYear = old('building_year') ?? $estimate->building_year ? (string) $estimate->building_year : null;
        $this->propertyType = old('property_type') ?? $estimate->property_type;
        $this->rank = old('rank') ?? $estimate->rank;
        $this->assessItem = old('assess_item') ?? $estimate->assess_item;

        $this->payMethod = old('payMethod') ?? $estimate->pay_method;
        $this->insuranceYear = old('insurance_year') ?? $estimate->insurance_year ? (int) $estimate->insurance_year : null;

        $this->baseRate = old('base_rate') ?? $estimate->base_rate ? (float) $estimate->base_rate : null;

        //職業割増
        $this->occupationalSurchargeCode =
         old('occupational_surcharge_code')
         ?? array_key_exists('occupational_surcharge_code', $estimate->property_detail ?? [])
         ? $estimate->property_detail['occupational_surcharge_code'] : null;
        $this->occupationalSurchargeRate =
         old('occupational_surcharge_rate')
         ?? array_key_exists('occupational_surcharge_rate', $estimate->property_detail ?? [])
         ? (float) $estimate->property_detail['occupational_surcharge_rate'] : null;

        //作業割増
        $this->workSurchargeCode =
         old('work_surcharge_code')
         ?? array_key_exists('work_surcharge_code', $estimate->property_detail ?? [])
         ? $estimate->property_detail['work_surcharge_code'] : null;
        $this->workSurchargeRate =
         old('work_surcharge_rate')
         ?? array_key_exists('work_surcharge_rate', $estimate->property_detail ?? [])
         ? (float) $estimate->property_detail['work_surcharge_rate'] : null;

        $this->calcBuildingAge();
        $this->initBaseRateList();
        $this->onChangeAssessItem();
    }

    public function render()
    {
        return view('estimate.livewire.form');
    }

    public function initBaseRateList()
    {
        if (in_array($this->propertyType, ['普通', '工場'], true)) {
            $this->baseRateList = ['1級', '2級', '3級'];
        } elseif ($this->propertyType === '住宅') {
            $this->baseRateList = ['M', 'T', 'H'];
        } else {
            $this->baseRateList = [];
        }
        $this->onChangeBaseRate();
    }

    public function onChangePropertyType()
    {
        if (in_array($this->propertyType, ['普通', '工場'], true)) {
            $this->baseRateList = ['1級', '2級', '3級'];
        } elseif ($this->propertyType === '住宅') {
            $this->baseRateList = ['M', 'T', 'H'];
        } else {
            $this->baseRateList = [];
        }
        $this->rank = null;
        $this->onChangeBaseRate();
    }

    public function onChangeBaseRate()
    {
        if ($this->propertyType === '工場' || is_null($this->propertyType) || is_null($this->rank)) {
            $this->baseRate = null;

            return;
        }

        if ($this->propertyType === '住宅') {
            $buildingAge = $this->buildingAge;
        } else {
            $buildingAge = '';
        }

        if (is_null($buildingAge)) {
            return;
        }

        $baseRateObj = new BaseRate();

        $this->baseRate = $baseRateObj->findRate($this->propertyType, $buildingAge, $this->rank);
    }

    public function onChangeAssessItem()
    {
        if (in_array($this->assessItem, ['通常', 'ピロティー付', '太陽光付', '年次取得'], true)) {
            $this->assessUsageList = ['専用住宅', '併用住宅', '専用店舗', '工場・倉庫', 'プレハブ'];
        } elseif ($this->assessItem == '分譲マンション') {
            $this->assessUsageList = ['分譲マンション'];
        } else {
            $this->assessUsageList = [];
        }
    }

    public function onChangeRecommend()
    {

    }

    public function calcBuildingAge()
    {
        $this->buildingYear = $this->convertToHalfWidthIfNumeric($this->buildingYear);

        if (is_null($this->startDate) || is_null($this->buildingYear)) {
            $this->buildingAge = null;

            return;
        }

        $startDate = Carbon::createFromDate((int) $this->buildingYear, 1, 1);
        $endDate = $this->startDate;

        $this->buildingAge = $startDate <= $endDate ? $startDate->diffInYears($endDate) : null;

        $this->onChangeBaseRate();
    }

    private function convertToHalfWidthIfNumeric($value)
    {
        if (is_null($value)) {
            return;
        }
        $convertedValue = mb_convert_kana($value, 'n');

        return is_numeric($convertedValue) ? $convertedValue : '';
    }

    //職業割増の標準コード
    public function onChangeOccupationalSurchargeCode()
    {
        $this->occupationalSurchargeCode = mb_convert_kana($this->occupationalSurchargeCode, 'n');

        $occupationalSurcharge = new OccupationalSurcharge();

        $this->occupationalSurchargeRate = $occupationalSurcharge->findCode($this->occupationalSurchargeCode);
    }

    //作業割増の作業工程コード
    public function onChangeWorkSurchargeCode()
    {
        $this->workSurchargeCode = mb_convert_kana($this->workSurchargeCode, 'n');

        $workSurcharge = new WorkSurcharge();

        $this->workSurchargeRate = $workSurcharge->findCode($this->workSurchargeCode);
    }
}
