<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Domains\BaseRate;
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
        $this->insuranceYear = old('insurance_year') ?? $estimate->insurance_year;
        $this->baseRate = old('base_rate') ?? $estimate->base_rate;

        $this->calcBuildingAge();
        $this->onChangePropertyType();
        $this->onChangeAssessItem();

    }

    public function render()
    {
        return view('estimate.livewire.form');
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
        $this->baseRate = $baseRateObj->findRate($this->propertyType, $this->buildingAge ?? '', $this->rank);
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
}
