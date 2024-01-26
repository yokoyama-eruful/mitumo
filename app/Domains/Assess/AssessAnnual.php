<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessAnnual
{
    public readonly string $rank;

    public readonly float $floorArea;

    public readonly int $buildingAge;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly int $annualPrice;

    public readonly string $remarks;

    private AnnualCoefficient $annualCoefficient;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->rank = $estimate->rank ?? '';
        $this->floorArea = $estimate->floor_area ? $estimate->floor_area : 0;
        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->buildingYear = $estimate->building_year ? $estimate->building_year : 0;
        $this->assessUsage = $this->getStringValueByKey('assess_usage', $estimate->assess_detail);
        $this->assessStructure = $this->getStringValueByKey('assess_structure', $estimate->assess_detail);
        $this->annualPrice = $this->getIntValueByKey('annual_price', $estimate->assess_detail);
        $this->remarks = $this->getStringValueByKey('remarks', $estimate->assess_detail);

        $this->annualCoefficient = new AnnualCoefficient($this->rank, $this->buildingAge);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    /*
      ** LiveFireで使用する評価額
      */
    public function viewReProcure($rank, $annualPrice, $buildingAge)
    {
        if ($rank == '' || $annualPrice == '' || $buildingAge == '') {
            return [
                '80' => '',
                '100' => '',
                '100まるめ' => '',
            ];
        }

        $annualCoefficient = new AnnualCoefficient($rank, $buildingAge);

        $annualCoefficient = $annualCoefficient->annualCoefficient;
        $reProcure = (float) $annualPrice * $annualCoefficient;

        return [
            '80' => (string) thousandYenUnit(floor($reProcure * 0.8)),
            '100' => (string) thousandYenUnit($reProcure),
            '100まるめ' => (string) thousandYenUnit(ceil($reProcure / 1000) * 1000),
        ];
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewCurrentMarketPrice($assessUsage, $assessStructure, $rank, $annualPrice, $buildingAge, $assessRank = '')
    {
        $annualCoefficient = new AnnualCoefficient($rank, $buildingAge);
        $agingDepreciation = new AgingDepreciation($assessUsage, $assessStructure);

        if ($assessRank == '') {
            return [
                '通常' => '',
                'まるめ' => '',
            ];
        }
        $annualCoefficient = $annualCoefficient->annualCoefficient;
        $reProcure = (float) $annualPrice * $annualCoefficient;

        $agingDepreciation = $agingDepreciation->findAgingDepreciation($assessRank);
        $residualValue = $agingDepreciation * $this->buildingAge;

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

    private function getIntValueByKey(string $key, ?array $array, int $defaultValue = 0): int
    {
        if (is_null($array)) {
            return $defaultValue;
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return (int) $array[$key];
        }

        return $defaultValue;
    }
}
