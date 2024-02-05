<?php

declare(strict_types=1);

namespace App\Domains\Dto;

final class EstimateStandardInfoDto
{
    public readonly array $params;

    public function __construct(array $params)
    {
        $tmpParams = array_intersect_key($params, array_flip([
            'company', 'address', 'phone_number', 'staff_name',
            'contractee', 'certificate_number', 'location', 'floor_area', 'exclusive_area',
            'structure', 'other_structure', 'pillar', 'other_pillar',
            'fire_resistance', 'other_fire_resistance', 'usage',
            'other_usage', 'floor', 'building', 'other', 'property_type',
            'rank', 'start_date', 'building_year', 'building_age',
            'pay_method', 'insurance_year', 'insurance_month',
            'insurance_day', 'assess_item', 'building_base_rate',
            'chattel_base_rate',
        ]));

        if (isset($tmpParams['floor']) && preg_match('/^[０-９]*$/', $tmpParams['floor']) == 1) {
            $tmpParams['floor'] = mb_convert_kana($tmpParams['floor'], 'n');
        }

        if (isset($tmpParams['building']) && preg_match('/^[０-９]*$/', $tmpParams['building']) == 1) {
            $tmpParams['building'] = mb_convert_kana($tmpParams['building'], 'n');
        }

        if (isset($tmpParams['start_date'])) {
            $tmpParams['start_date'] = $params['start_date'].'-01';
        }

        $this->params = $tmpParams;
    }
}
