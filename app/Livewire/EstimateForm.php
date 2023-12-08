<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Estimate;
use Carbon\Carbon;
use Livewire\Component;

class EstimateForm extends Component
{
    public Estimate $estimate;

    public ?string $propertyType = null;

    public ?string $startDate = null;

    public ?string $buildingYear = null;

    public ?int $buildingAge = null;

    public ?string $payMethod = null;

    public ?int $insuranceYear = null;

    public ?string $assessItem = null;

    public array $assessUsageList = [];

    public function mount($estimate)
    {
        $this->estimate = $estimate;
        $this->startDate = $estimate->start_date?->format('Y-m');
        $this->buildingYear = (string) $estimate->building_year;
        $this->propertyType = $estimate->property_type;
        $this->assessItem = $estimate->assess_item;

        $this->payMethod = $estimate->pay_method;
        $this->insuranceYear = $estimate->insurance_year;

        $this->calcBuildingAge();
        $this->onChangeAssessItem();
    }

    public function render()
    {
        return view('estimate.livewire.form');
    }

    public function onChangePropertyType()
    {

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
    }

    private function convertToHalfWidthIfNumeric($value)
    {
        $convertedValue = mb_convert_kana($value, 'n');

        return is_numeric($convertedValue) ? $convertedValue : '';
    }
}
