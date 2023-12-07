<?php

declare(strict_types=1);

namespace App\Domains\Dto;

final class EstimateAssessDetailDto
{
    public array $params;

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
        $standardParams = array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure',
            ]
        ));

        return $standardParams;
    }

    private function convertPilotiParams(array $params): array
    {
        $standardParams = array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'piloti_floor_area',
            ]
        ));

        return $standardParams;
    }

    private function convertSolarPanelParams(array $params): array
    {
        $standardParams = array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'installation_date', 'installation_cost',
            ]
        ));

        return $standardParams;
    }

    private function convertCondominiumParams(array $params): array
    {
        $standardParams = array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure',
            ]
        ));

        return $standardParams;
    }

    private function convertAnnualAcquisitionParams(array $params): array
    {
        $standardParams = array_intersect_key($params, array_flip(
            [
                'assess_usage', 'assess_structure', 'annual_price',
            ]
        ));

        return $standardParams;
    }

    private function convertRenovatedParams(array $params): array
    {
        $standardParams = array_intersect_key($params, array_flip(
            [
                'replacement_price', 'renovation_cost',
                'conventional_age', 'depreciation_rate', 'renovation_age',
                'renovation_depreciation_rate',
            ]
        ));

        return $standardParams;
    }
}
