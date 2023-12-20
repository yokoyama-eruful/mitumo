<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessAnnual
{
    public readonly string $rank;

    public readonly float $floorArea;

    public readonly int $buildingAge;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly int $annualPrice;

    private AnnualCoefficient $annualCoefficient;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->rank = $estimate->rank;
        $this->floorArea = $estimate->floor_area ? $estimate->floor_area : 0;
        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->assessUsage = $estimate->assess_detail['assess_usage'];
        $this->assessStructure = $estimate->assess_detail['assess_structure'];
        $this->annualPrice = (int) $estimate->assess_detail['annual_price'];

        $this->annualCoefficient = new AnnualCoefficient($this->rank, $estimate->building_year);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    public function getReProcure()
    {
        $annualCoefficient = $this->annualCoefficient->annualCoefficient;
        $reProcure = $this->annualPrice * $annualCoefficient;

        return [
            '年次係数' => $annualCoefficient,
            '80%' => $reProcure * 0.8,
            '80%まるめ' => ceil($reProcure * 0.8 / 1000) * 1000,
            '100%' => $reProcure,
            '100%まるめ' => ceil($reProcure / 1000) * 1000,
            '120%' => $reProcure * 1.2,
        ];
    }

    public function getResidualValue($assessRank)
    {
        $agingDepreciation = $this->agingDepreciation->findAgingDepreciation($assessRank);
        $residualValue = $agingDepreciation * $this->buildingAge;

        if ($residualValue >= 0.5) {
            $finalResidualValue = 0.5;
        } else {
            $finalResidualValue = 1 - $residualValue;
        }

        return [
            '経年減価率' => $agingDepreciation,
            '残価率' => $residualValue,
            '残価率最終' => $finalResidualValue,
        ];
    }

    public function getCurrentMarketPrice($assessRank)
    {
        $currentMarketPrice = $this->getReProcure()['100%'] * $this->getResidualValue($assessRank)['残価率最終'];

        return [
            '時価額' => $currentMarketPrice,
            '時価額まるめ' => ceil($currentMarketPrice / 1000) * 1000,
        ];
    }
}
