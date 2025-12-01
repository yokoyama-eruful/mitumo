<?php

declare(strict_types=1);

namespace App\Domains\Assess;

use App\Utils\JapaneseEraConverter;

final class AssessAnnual
{
    public string $buildingYearEra;

    public readonly string $rank;

    public readonly float $floorArea;

    public readonly int $buildingAge;

    public readonly int $originalBuildYear;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly int $annualPrice;

    public readonly string $remarks;

    private AnnualCoefficient $annualCoefficient;

    private AgingDepreciation $agingDepreciation;

    public function __construct($buildingYearEra, $rank, $floorArea, $buildingAge, $buildingYear,$assessUsage,$assessStructure,$annualPrice,$remarks)
    {
        $this->buildingYearEra = $buildingYearEra;
        $this->rank = $rank ?? '';
        $this->floorArea = (float)$floorArea ? (float)$floorArea: 0;
        $this->buildingAge = (int)$buildingAge ? (int)$buildingAge : 0;
        $this->originalBuildYear = (int)$buildingYear ? (int)$buildingYear : 0;
        $this->buildingYear = $this->changeWarekiToSeireki();
        $this->assessUsage = $assessUsage;
        $this->assessStructure = $assessStructure;
        $this->annualPrice = (int)$annualPrice;
        $this->remarks = $remarks;
        $this->annualCoefficient = new AnnualCoefficient($this->rank, $this->buildingYear);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    private function changeWarekiToSeireki()
    {
        $westernYear = JapaneseEraConverter::getJapaneseEraStartYear($this->buildingYearEra);
        $this->buildingYearEra = $this->buildingYearEra == '' ? '西暦' : $this->buildingYearEra;

        if ($this->buildingYearEra !== '西暦') {
            $buildingYear = $westernYear + $this->originalBuildYear - 1;
        } else {
            $buildingYear = $this->originalBuildYear;
        }

        return $buildingYear;
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

        $annualCoefficient = new AnnualCoefficient($rank, $this->buildingYear);

        $annualCoefficient = $annualCoefficient->annualCoefficient;
        $reProcure = (float) $annualPrice * $annualCoefficient;

        return [
            '80' => thousandYenUnit(floor($reProcure * 0.8)),
            '100' => thousandYenUnit(floor($reProcure)),
            '100まるめ' => thousandYenUnit(ceil(floor($reProcure) / 1000) * 1000),
        ];
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewCurrentMarketPrice($assessUsage, $assessStructure, $rank, $annualPrice, $buildingAge, $assessRank = '')
    {
        $annualCoefficient = new AnnualCoefficient($rank, $this->buildingYear);
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
            '80%まるめ' => ceil(floor($reProcure * 0.8) / 1000) * 1000,
            '100%' => $reProcure,
            '100%まるめ' => ceil(floor($reProcure) / 1000) * 1000,
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
            '時価額まるめ' => ceil(floor($currentMarketPrice)  / 1000) * 1000,
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
