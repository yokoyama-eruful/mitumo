<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company',
        'address',
        'phone_number',
        'staff_name',
        'certificate_number',
        'contractee',
        'location',

        'floor_area',
        'exclusive_area',
        'structure',
        'other_structure',
        'pillar',
        'other_pillar',
        'fire_resistance',
        'other_fire_resistance',
        'usage',
        'other_usage',
        'floor',
        'building',
        'other',

        'property_type',
        'rank',
        'start_date',
        'building_year',
        'building_age',
        'pay_method',
        'insurance_year',
        'insurance_month',
        'insurance_day',
        'assess_item',
        'building_base_rate',
        'chattel_base_rate',

        'property_detail',
        'assess_detail',
    ];

    protected $casts = [
        'start_date' => 'date',
        'property_detail' => 'json',
        'assess_detail' => 'json',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getBuildingStructureAttribute()
    {
        $buildingStructure = [
            $this->structure, $this->other_structure, $this->pillar,
            $this->other_pillar, $this->fire_resistance, $this->other_fire_resistance,
            $this->usage, $this->other_usage, '築'.$this->building_age.'年', '西暦'.$this->building_year.'年築',
        ];
        $buildingStructure = array_filter($buildingStructure);

        return implode(' ', $buildingStructure);
    }

    public function getOtherBuildingStructureAttribute()
    {
        $otherBuildingStructure = [
            $this->floor ? $this->floor.'階建' : '',
            $this->building ? $this->building.'棟' : '',
            $this->floor_area ? '延床:' . $this->floor_area . 'm²' : '',
            $this->exclusive_area ? '専有:' . $this->exclusive_area . 'm² ' : '',
            $this->other ? $this->other : '',
        ];
        $otherBuildingStructure = array_filter($otherBuildingStructure);

        return implode(' ', $otherBuildingStructure);
    }

    public function getInsurancePeriodAttribute()
    {
        $insurancePeriod = [
            $this->insurance_year ? $this->insurance_year . '年' : '',
            $this->insurance_month ? $this->insurance_month . 'ヶ月' : '',
            $this->insurance_day ? $this->insurance_day . '日' : '',
        ];
        $insurancePeriod = array_filter($insurancePeriod);

        return implode(' ', $insurancePeriod);
    }

    public function getOtherItemAttribute()
    {
        $otherItem = [
            (isset($this->property_detail['chattel_surcharge_category']) && $this->property_detail['chattel_surcharge_category'] != '-' && ($this->property_detail['item_insurance_payout'] || $this->property_detail['other_item_insurance_payout'])) ? '動産割増' : '',
            (isset($this->property_detail['chattel_surcharge']) && ($this->property_detail['item_insurance_payout'] || $this->property_detail['other_item_insurance_payout'])) ? '動産割増' : '',
            isset($this->property_detail['occupational_surcharge_rate']) ? '職業割増' : '',
            isset($this->property_detail['work_surcharge_rate']) ? '作業割増' : '',
            isset($this->property_detail['other_discount']) ? 'その他割引' : '',
            isset($this->property_detail['actual_loss_factor']) ? '実損払係数あり' : '',
        ];

        $otherItem = array_filter($otherItem);

        return implode(' ', $otherItem);
    }

    public function getRecommendListAttribute()
    {
        $recommendations = [];

        for ($i = 1; $i <= 3; $i++) {
            $key = "recommend{$i}";
            $recommendations[$key]
                = isset($this->property_detail[$key]) ? $this->property_detail[$key] : 0;
        }

        return $recommendations;
    }

    public function getEarthquakeListAttribute()
    {
        $earthquakeList = [];

        for ($i = 1; $i <= 3; $i++) {
            $key = "earthquake{$i}";
            $earthquakeList[$key]
                = isset($this->property_detail[$key]) ? $this->property_detail[$key] : 0;
        }

        return $earthquakeList;
    }

    //単位をつけるメソッド
    public function getFloorWithFloorAttribute()
    {
        return $this->floor ? $this->floor.'階建' : '';
    }

    public function getBuildingWithFloorAttribute()
    {
        return $this->building ? $this->building.'棟' : '';
    }

    public function getFloorAreaWithFloorAttribute()
    {
        return $this->floor_area ? '延床:' . $this->floor_area . 'm²' : '';
    }

    public function getExclusiveAreaWithFloorAttribute()
    {
        return $this->exclusive_area ? '専有:' . $this->exclusive_area . 'm² ' : '';
    }
}
