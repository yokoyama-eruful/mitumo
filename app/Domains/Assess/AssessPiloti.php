<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessPiloti
{
    public readonly float $floorArea;

    public readonly float $pilotiFloorArea;

    public readonly float $exceptForPilotiFloorArea;

    public readonly int $buildingAge;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly string $remarks;

    private StandardAmount $standardAmount;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->floorArea = $estimate->floor_area ? $estimate->floor_area : 0;
        $this->pilotiFloorArea = $this->getFloatValueByKey('piloti_floor_area', $estimate->assess_detail);
        $this->exceptForPilotiFloorArea = $this->floorArea - $this->pilotiFloorArea;
        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->buildingYear = $estimate->building_year ? $estimate->building_year : 0;
        $this->assessUsage = $this->getStringValueByKey('assess_usage', $estimate->assess_detail);
        $this->assessStructure = $this->getStringValueByKey('assess_structure', $estimate->assess_detail);
        $this->remarks = $this->getStringValueByKey('remarks', $estimate->assess_detail);

        $this->standardAmount = new StandardAmount($this->assessUsage, $this->assessStructure);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewReProcure($assessUsage, $assessStructure, $floorArea, $pilotiFloorArea, $assessRank = '')
    {
        if ($assessRank == '' || $assessUsage == '' ||
        $assessStructure == '' || $floorArea == '') {
            return [
                '80' => '',
                '100' => '',
                '100まるめ' => '',
            ];
        }

        $standardAmount = $this->standardAmount = new StandardAmount($assessUsage, $assessStructure);

        $standardAmount = $this->standardAmount->findStandardAmount($assessRank);

        $reProcure = $standardAmount * ((float) $floorArea - (float) $pilotiFloorArea);

        return [
            '80' => (string) thousandYenUnit(floor($reProcure * 0.8)),
            '100' => thousandYenUnit(floor($reProcure)),
            '100まるめ' => thousandYenUnit(ceil(floor($reProcure) / 1000) * 1000),
        ];
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewCurrentMarketPrice($assessUsage, $assessStructure, $floorArea, $pilotiFloorArea, $buildingAge, $assessRank = '')
    {
        if ($assessRank == '' || $assessUsage == '' ||
        $assessStructure == '' || $floorArea == '' ||
        $pilotiFloorArea == '' || $buildingAge == '') {
            return [
                '通常' => '',
                'まるめ' => '',
            ];
        }

        $standardAmount = $this->standardAmount = new StandardAmount($assessUsage, $assessStructure);
        $agingDepreciation = $this->agingDepreciation = new AgingDepreciation($assessUsage, $assessStructure);

        $standardAmount = $this->standardAmount->findStandardAmount($assessRank);
        $reProcure = $standardAmount * ((float) $floorArea - (float) $pilotiFloorArea);

        $agingDepreciation = $this->agingDepreciation->findAgingDepreciation($assessRank);

        $residualValue = $agingDepreciation * (int) $buildingAge;

        if ($residualValue >= 0.5) {
            $finalResidualValue = 0.5;
        } else {
            $finalResidualValue = 1 - $residualValue;
        }

        $marketPrice = thousandYenUnit(floor($reProcure * $finalResidualValue));

        return [
            '通常' => (string) $marketPrice,
            'まるめ' => (string) thousandYenUnit(ceil(floor($reProcure * $finalResidualValue) / 1000) * 1000),
        ];
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
            '80%まるめ' => ceil(floor($reProcure * 0.8) / 1000) * 1000,
            '100%' => $reProcure,
            '100%まるめ' => ceil(floor($reProcure) / 1000) * 1000,
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

    private function getStringValueByKey(string $key, ?array $array, string $defaultValue = ''): string
    {
        if (is_null($array)) {
            return $defaultValue;
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return (string) $array[$key];
        }

        return $defaultValue;
    }

    private function getFloatValueByKey(string $key, ?array $array, float $defaultValue = 0): float
    {
        if (is_null($array)) {
            return $defaultValue;
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return (float) $array[$key];
        }

        return $defaultValue;
    }
}
