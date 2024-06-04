<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessCondo
{
    public readonly float $exclusiveArea;

    public readonly int $buildingAge;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly string $remarks;

    private StandardAmount $standardAmount;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->exclusiveArea = $estimate->exclusive_area ? $estimate->exclusive_area : 0;
        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->buildingYear = $estimate->building_year ? $estimate->building_year : 0;
        $this->assessUsage = $this->getValueByKey('assess_usage', $estimate->assess_detail);
        $this->assessStructure = $this->getValueByKey('assess_structure', $estimate->assess_detail);
        $this->remarks = $this->getValueByKey('remarks', $estimate->assess_detail);

        $this->standardAmount = new StandardAmount($this->assessUsage, $this->assessStructure);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    /*
       ** LiveFireで使用する評価額
       */
    public function viewReProcure($assessUsage, $assessStructure, $exclusiveArea, $assessRank = '')
    {
        if ($assessRank == '' || $assessUsage == '' ||
        $assessStructure == '' || $exclusiveArea == '') {
            return [
                '80' => '',
                '100' => '',
                '100まるめ' => '',
            ];
        }

        $standardAmount = new StandardAmount($assessUsage, $assessStructure);
        $amount = $standardAmount->findStandardAmount($assessRank);
        $reProcure = $amount * $exclusiveArea;

        return [
            '80' => thousandYenUnit(floor($reProcure * 0.8)),
            '100' => thousandYenUnit(floor($reProcure)),
            '100まるめ' => thousandYenUnit(ceil(floor($reProcure) / 1000) * 1000),
        ];
    }

    /*
    ** Livewireで使用する評価額
    */
    public function viewCurrentMarketPrice($assessUsage, $assessStructure, $exclusiveArea, $buildingAge, $assessRank = '')
    {
        if ($assessRank == '' || $assessUsage == '' ||
        $assessStructure == '' || $exclusiveArea == '' ||
        $buildingAge == '') {
            return [
                '通常' => '',
                'まるめ' => '',
            ];
        }

        $agingDepreciation = new AgingDepreciation($assessUsage, $assessStructure);
        $standardAmount = new StandardAmount($assessUsage, $assessStructure);

        $amount = $standardAmount->findStandardAmount($assessRank);
        $reProcure = $amount * $exclusiveArea;

        $agingDepreciation = $agingDepreciation->findAgingDepreciation($assessRank);

        $residualValue = $agingDepreciation * $buildingAge;

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

    public function getReProcure($assessRank = '')
    {
        $standardAmount = $this->standardAmount->findStandardAmount($assessRank);
        $reProcure = $standardAmount * $this->exclusiveArea;

        return [
            '標準単価' => $standardAmount,
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
        $currentMarketPrice = $this->getReProcure($assessRank)['100%'] * $this->getResidualValue($assessRank)['残価率最終'];

        return [
            '時価額' => $currentMarketPrice,
            '時価額まるめ' => ceil(floor($currentMarketPrice) / 1000) * 1000,
        ];
    }

    private function getValueByKey(string $key, ?array $array, string $defaultValue = ''): string
    {
        if (is_null($array)) {
            return $defaultValue;
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return (string) $array[$key];
        }

        return $defaultValue;
    }
}
