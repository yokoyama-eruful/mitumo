<?php

declare(strict_types=1);

namespace App\Domains\Dto;

final class EstimatePropertyDetailDto
{
    public readonly array $params;

    public function __construct(string $property_type, array $params)
    {
        $this->params = match ($property_type) {
            '住宅' => $this->convertHouseParams($params),
            '普通' => $this->convertStandardParams($params),
            '工場' => $this->convertFactoryParams($params),
            default => [],
        };
    }

    private function convertHouseParams(array $params): array
    {
        return array_intersect_key($params, array_flip([
            'actual_loss_factor', 'building_insurance_payout',
            'household_insurance_payout', 'insurance_purpose',
            'recommend1', 'recommend2', 'recommend3', //おすすめ
            'earthquake1', 'earthquake2', 'earthquake3', //地震
        ]));
    }

    private function convertStandardParams(array $params): array
    {
        return array_intersect_key($params, array_flip([
            'actual_loss_factor', 'other_discount', 'chattel_surcharge_category',
            'occupational_surcharge_code', 'occupational_surcharge_rate',
            'work_surcharge_code', 'work_surcharge_rate',
            'building_insurance_payout', 'household_insurance_payout', 'facility_insurance_payout',
            'other_facility_insurance_payout',
            'item_insurance_payout', 'other_item_insurance_payout', 'insurance_purpose',
            'recommend1', 'recommend2', 'recommend3', //おすすめ
            'earthquake1', 'earthquake2', 'earthquake3', //地震
        ]));
    }

    private function convertFactoryParams(array $params): array
    {
        return array_intersect_key($params, array_flip([
            'chattel_surcharge', 'actual_loss_factor', 'other_discount',
            'factory_building_code',
            'building_insurance_payout', 'household_insurance_payout', 'facility_insurance_payout',
            'other_facility_insurance_payout', 'item_insurance_payout',
            'other_item_insurance_payout',
            'insurance_purpose',
            'recommend1', 'recommend2', 'recommend3', //おすすめ
            'earthquake1', 'earthquake2', 'earthquake3', //地震
        ]));
    }
}
