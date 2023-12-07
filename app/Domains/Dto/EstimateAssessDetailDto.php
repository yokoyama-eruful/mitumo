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
            '改装物件' => $this->convertRenovatedParams($params),

            default => [],
        };
    }

    private function convertStandardParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure',
            ]
        ));
    }

    private function convertPilotiParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'piloti_floor_area',
            ]
        ));
    }

    private function convertSolarPanelParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'installation_date', 'installation_cost',
            ]
        ));
    }

    private function convertCondominiumParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure',
            ]
        ));
    }

    private function convertAnnualAcquisitionParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'annual_price',
            ]
        ));
    }

    private function convertRenovatedParams(array $params): array
    {
        return array_intersect_key($params, array_flip(
            [
                'replacement_price', 'renovation_cost',
                'conventional_age', 'depreciation_rate', 'renovation_age',
                'renovation_depreciation_rate',
            ]
        ));
    }
}
