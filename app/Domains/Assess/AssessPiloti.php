<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessPiloti
{
    public readonly float $floorArea;

    public readonly float $pilotiFloorArea;

    public readonly float $exceptForPilotiFloorArea;

    public readonly int $buildingAge;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    private StandardAmount $standardAmount;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->floorArea = $estimate->floor_area ? $estimate->floor_area : 0;
        $this->pilotiFloorArea = (float) $estimate->assess_detail['piloti_floor_area'];
        $this->exceptForPilotiFloorArea = $this->floorArea - $this->pilotiFloorArea;
        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->assessUsage = $estimate->assess_detail['assess_usage'];
        $this->assessStructure = $estimate->assess_detail['assess_structure'];

        $this->standardAmount = new StandardAmount($this->assessUsage, $this->assessStructure);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    public function getExceptForPiloti($assessRank)
    {
        $standardAmount = $this->standardAmount->findStandardAmount($assessRank);
        $reProcure = $standardAmount * $this->exceptForPilotiFloorArea;

        return [
            '標準単価' => $standardAmount,
            'ピロティー以外80%' => $reProcure * 0.8,
            'ピロティー以外80%まるめ' => ceil($reProcure * 0.8 / 1000) * 1000,
            'ピロティー以外100%' => $reProcure,
            'ピロティー以外100%まるめ' => ceil($reProcure / 1000) * 1000,
            'ピロティー以外120%' => $reProcure * 1.2,

        ];
    }

    public function getPiloti()
    {
        $standardAmount = 60;
        $reProcure = $standardAmount * $this->pilotiFloorArea;

        return [
            '標準単価' => $standardAmount,
            'ピロティー80%' => $reProcure * 0.8,
            'ピロティー80%まるめ' => ceil($reProcure * 0.8 / 1000) * 1000,
            'ピロティー100%' => $reProcure,
            'ピロティー100%まるめ' => ceil($reProcure / 1000) * 1000,
        ];
    }

    public function getAllFloorAreaReProcure($assessRank)
    {
        $reProcure = $this->getExceptForPiloti($assessRank)['ピロティー以外100%'] + $this->getPiloti()['ピロティー100%'];

        return [
            '80%' => $reProcure * 0.8,
            '80%まるめ' => ceil($reProcure * 0.8 / 1000) * 1000,
            '100%' => $reProcure,
            '100%まるめ' => ceil($reProcure / 1000) * 1000,
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
        $currentMarketPrice = $this->getExceptForPiloti($assessRank)['ピロティー以外100%'] * $this->getResidualValue($assessRank)['残価率最終'];

        return [
            '時価額' => $currentMarketPrice,
            '時価額まるめ' => ceil($currentMarketPrice / 1000) * 1000,
        ];
    }
}
