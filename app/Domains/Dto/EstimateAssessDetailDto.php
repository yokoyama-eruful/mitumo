<?php

declare(strict_types=1);

namespace App\Domains\Dto;

final class EstimateAssessDetailDto
{
    public readonly array $params;

    public function __construct(?string $assess, array $params)
    {
        $this->params = match ($assess) {
            '通常' => $this->convertStandardParams($params),
            'ピロティー付' => $this->convertPilotiParams($params),
            '太陽光付' => $this->convertSolarPanelParams($params),
            '分譲マンション' => $this->convertCondominiumParams($params),
            '年次取得' => $this->convertAnnualAcquisitionParams($params),
            '大規模改装' => $this->convertRenovatedParams($params),

            default => [],
        };
    }

    private function convertStandardParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure','remarks',
            ]
        ));
    }

    private function convertPilotiParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'piloti_floor_area','remarks',
            ]
        ));
    }

    private function convertSolarPanelParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'installation_date', 'installation_cost','remarks',
            ]
        ));
    }

    private function convertCondominiumParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure','remarks',
            ]
        ));
    }

    private function convertAnnualAcquisitionParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'annual_price','remarks',
            ]
        ));
    }

    private function convertRenovatedParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'replacement_price', 'renovation_cost',
                'conventional_age', 'depreciation_rate', 'renovation_age',
                'renovation_depreciation_rate','remarks',
            ]
        ));
    }
}
