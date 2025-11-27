<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Futsu2 extends Model
{
    use HasFactory;

    protected $table = 'estimate_futsu2';

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
        'building_year_era',
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

        'is_sonpo_base_rate',
        'sonpo_building_base_rate',
        'sonpo_chattel_base_rate',

        'worker_count_class',
    ];

    protected $casts = [
        'start_date' => 'date',
        'property_detail' => 'json',
        'assess_detail' => 'json',
        'is_sonpo_base_rate' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getBuildingStructureAttribute()
    {
        if ($this->building_year_era == '西暦') {
            $buildingYear = $this->building_year.'年築';
        } else {
            $buildingYear = $this->building_year_era.' '.$this->building_year.'年築';
        }

        $buildingStructure = [
            $this->structure, $this->other_structure, $this->pillar,
            $this->other_pillar, $this->fire_resistance, $this->other_fire_resistance,
            $this->usage, $this->other_usage, $this->building_age ? '築'.$this->building_age.'年' : '',
            $this->building_year ? '('.$buildingYear.')' : '',
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
        // $dosanWarimashi = $this->getDosanWarimashi();
        // $waribikiUtiwakeList = $this->getWaribikiUtiwakeList();

        // $otherItem = [
        //     $dosanWarimashi,
        //     ...$waribikiUtiwakeList,
        //     isset($this->property_detail['occupational_surcharge_rate']) ? '職業割増' : '',
        //     isset($this->property_detail['work_surcharge_rate']) ? '作業割増' : '',
        //     isset($this->property_detail['other_discount']) ? 'その他割引' : '',
        //     isset($this->property_detail['actual_loss_factor']) ? '実損払係数あり' : '',
        // ];

        // $otherItem = array_filter($otherItem);

        // return implode(' ', $otherItem);

        //表示ロジックは残すが非表示。復活する可能性がある。
        return '';
    }

    private function getWaribikiUtiwakeList()
    {
        $risk = $this->property_detail['discount_risk_rate'] ? 'リスク評価割引' : '';
        $fire = $this->property_detail['discount_fire_extinguisher'] ? '消火設備割引' : '';
        $security = $this->property_detail['discount_security'] ? 'セキュリティ割引' : '';
        $public = $this->property_detail['discount_public'] ? '公有物件等割引 ' : '';
        $longTerm = $this->getLongTermFlag() ? '長期年払割引' : '';

        $items = array_filter([
            $risk,
            $fire,
            $security,
            $public,
            $longTerm,
        ]);

        return $items;
    }

    private function getDosanWarimashi()
    {
        if (
            isset($this->property_detail['chattel_surcharge_category']) &&
            $this->property_detail['chattel_surcharge_category'] != '-' &&
            ($this->property_detail['item_insurance_payout'] || $this->property_detail['other_item_insurance_payout'])
        ) {
            return '動産割増';
        }

        if (
            isset($this->property_detail['chattel_surcharge']) &&
            ($this->property_detail['item_insurance_payout'] || $this->property_detail['other_item_insurance_payout'])
        ) {
            return '動産割増';
        }

        return '';
    }

    private function getLongTermFlag()
    {
        if ($this->getValueByKey('discount_risk_rate', $this->property_detail, 0) > 0) {
            return false;
        }

        if (
            $this->getValueByKey('discount_fire_extinguisher', $this->property_detail) > 0
         || $this->getValueByKey('discount_security', $this->property_detail) > 0
         || $this->getValueByKey('discount_public', $this->property_detail) > 0) {
            return
                $this->pay_method == '長期年払' &&
                $this->insurance_year >= 5 &&
                is_null($this->property_detail['actual_loss_factor']) &&
                $this->getValueByKey('discount_fire_extinguisher', $this->property_detail) + $this->getValueByKey('discount_security', $this->property_detail) < 10;
        }

        return
            $this->pay_method == '長期年払' &&
            $this->insurance_year >= 5 &&
            is_null($this->property_detail['actual_loss_factor']) &&
            $this->getValueByKey('other_discount', $this->property_detail) < 10;
    }

    /**
     * 配列から安全に中身を取得するUtils関数
     */
    private function getValueByKey(string $key, array $array, int $defaultValue = 0): int
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return intval($array[$key]);
        }

        return $defaultValue;
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

    public function workerCountClass(): string
    {
        return match ($this->worker_count_class) {
            1 => '5人未満',
            2 => '50人未満',
            3 => '50人以上',
            default => '未入力',
        };
    }
}
