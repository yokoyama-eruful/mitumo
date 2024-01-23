<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessSolarPanel
{
    public readonly float $floorArea;

    public readonly int $buildingAge;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly string $installationDate;

    public readonly int $installationCost;

    public readonly string $remarks;

    private StandardAmount $standardAmount;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->floorArea = $estimate->floor_area ? $estimate->floor_area : 0;
        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->buildingYear = $estimate->building_year ? $estimate->building_year : 0;
        $this->assessUsage = $this->getStringValueByKey('assess_usage', $estimate->assess_detail);
        $this->assessStructure = $this->getStringValueByKey('assess_structure', $estimate->assess_detail);
        $this->installationDate = $this->getStringValueByKey('installation_date', $estimate->assess_detail);
        $this->installationCost = $this->getIntValueByKey('installation_cost', $estimate->assess_detail);
        $this->remarks = $this->getStringValueByKey('remarks', $estimate->assess_detail);

        $this->standardAmount = new StandardAmount($this->assessUsage, $this->assessStructure);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    /*
        ** LiveFireで使用する評価額
        */
    public function viewReProcure($assessUsage, $assessStructure, $floorArea, $assessRank = ''): string
    {
        if ($assessRank == '' || $assessUsage == '' ||
        $assessStructure == '' || $floorArea == '') {
            return '';
        }

        $standardAmount = new StandardAmount($assessUsage, $assessStructure);
        $amount = $standardAmount->findStandardAmount($assessRank);
        $reProcure = $amount * $floorArea;

        return thousandYenUnit(floor($reProcure * 0.8)).'~'.thousandYenUnit(floor($reProcure * 1.2));
    }

    /*
    ** Livewireで使用する評価額
    */
    public function viewCurrentMarketPrice($assessUsage, $assessStructure, $floorArea, $buildingAge, $assessRank = ''): string
    {
        if ($assessRank == '' || $assessUsage == '' ||
        $assessStructure == '' || $floorArea == '' ||
        $buildingAge == '') {
            return '';
        }

        $agingDepreciation = new AgingDepreciation($assessUsage, $assessStructure);
        $standardAmount = new StandardAmount($assessUsage, $assessStructure);

        $amount = $standardAmount->findStandardAmount($assessRank);
        $reProcure = $amount * $floorArea;

        $agingDepreciation = $agingDepreciation->findAgingDepreciation($assessRank);

        $residualValue = $agingDepreciation * $buildingAge;

        if ($residualValue >= 0.5) {
            $finalResidualValue = 0.5;
        } else {
            $finalResidualValue = 1 - $residualValue;
        }

        $marketPrice = thousandYenUnit(floor($reProcure * $finalResidualValue));

        return (string) $marketPrice;
    }

    public function getSolarPanelInfo()
    {
        $agingDepreciation = 0.1;
        $progressYear = now()->diffInYears($this->installationDate);
        $residualValue = $progressYear * $agingDepreciation;

        if ($residualValue >= 0.5) {
            $finalResidualValue = 0.5;
        } else {
            $finalResidualValue = 1 - $residualValue;
        }

        return [
            '経過年数' => $progressYear,
            '経年減価率' => $agingDepreciation,
            '残価率' => $residualValue,
            '残価率最終' => $finalResidualValue,
            '時価額' => $this->installationCost * $finalResidualValue,
        ];
    }

    public function getReProcure($assessRank = '')
    {
        $standardAmount = $this->standardAmount->findStandardAmount($assessRank);
        $reProcure = $standardAmount * $this->floorArea;

        return [
            '標準単価' => $standardAmount,
            '80%' => $reProcure * 0.8,
            '80%まるめ' => ceil($reProcure * 0.8 / 1000) * 1000,
            '太陽光付き80%まるめ' => ceil($reProcure * 0.8 / 1000) * 1000 + $this->installationCost,
            '100%' => $reProcure,
            '100%まるめ' => ceil($reProcure / 1000) * 1000,
            '太陽光付き100%まるめ' => ceil($reProcure / 1000) * 1000 + $this->installationCost,
            '120%' => $reProcure * 1.2,

        ];
    }

    public function getResidualValue($assessRank = '')
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

    public function getCurrentMarketPrice($assessRank = '')
    {
        $currentMarketPrice = $this->getReProcure($assessRank)['100%'] * $this->getResidualValue($assessRank)['残価率最終'];

        return [
            '時価額' => $currentMarketPrice,
            '時価額まるめ' => ceil($currentMarketPrice / 1000) * 1000,
            '太陽光付まるめ' => ceil($currentMarketPrice / 1000) * 1000 + $this->getSolarPanelInfo()['時価額'],
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
