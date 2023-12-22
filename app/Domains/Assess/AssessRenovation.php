<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessRenovation
{
    public readonly int $replacementPrice;

    public readonly int $renovationCost;

    public readonly int $conventionalAge;

    public readonly float $depreciationRate;

    public readonly int $renovationAge;

    public readonly float $renovationDepreciationRate;

    public readonly array $conventionalDepreciationRate;

    public readonly array $renovationCostRate;

    public readonly float $totalDepreciationRate;

    public readonly float $residualValue;

    public readonly array $currentMarketPrice;

    public function __construct($estimate)
    {
        $this->replacementPrice = $this->getIntValueByKey('replacement_price', $estimate->assess_detail);
        $this->renovationCost = $this->getIntValueByKey('renovation_cost', $estimate->assess_detail);
        $this->conventionalAge = $this->getIntValueByKey('conventional_age', $estimate->assess_detail);
        $this->depreciationRate = $this->getFloatValueByKey('depreciation_rate', $estimate->assess_detail);
        $this->renovationAge = $this->getIntValueByKey('renovation_age', $estimate->assess_detail);
        $this->renovationDepreciationRate = $this->getFloatValueByKey('renovation_depreciation_rate', $estimate->assess_detail);

        $this->conventionalDepreciationRate = $this->getConventionalDepreciationRate();
        $this->renovationCostRate = $this->getRenovationCostRate();
        $this->totalDepreciationRate = $this->getTotalDepreciationRate();
        $this->residualValue = $this->getResidualValue();
        $this->currentMarketPrice = $this->getCurrentMarketPrice();
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewCurrentMarketPrice($replacementPrice, $renovationCost, $conventionalAge, $depreciationRate, $renovationAge, $renovationDepreciationRate): string
    {
        if (! $replacementPrice) {
            return '0';
        }

        $priceStructureRatio = ((int) $replacementPrice - (int) $renovationCost) / $replacementPrice;

        $depreciationRate = $conventionalAge * $depreciationRate;

        if ($depreciationRate >= 0.5) {
            $depreciationRate = 0.5;
        }

        $conventionalDepreciationRate = round(round($priceStructureRatio, 2) * $depreciationRate, 3);

        $priceStructureRatio = $renovationCost / $replacementPrice;

        $depreciationRate = $renovationAge * $renovationDepreciationRate;

        if ($depreciationRate >= 0.5) {
            $depreciationRate = 0.5;
        }

        $renovationCostRate = round(round($priceStructureRatio, 2) * $depreciationRate, 3);

        $marketPrice = $replacementPrice * (1 - $conventionalDepreciationRate + $renovationCostRate);

        return (string) $marketPrice;
    }

    private function getConventionalDepreciationRate()
    {
        if ($this->replacementPrice == 0) {
            return [
                '価額構成割合' => 0,
                '価額構成割合まるめ' => 0,
                '減価率' => 0,
                '減価率まるめ' => 0,
            ];
        }

        $priceStructureRatio = ($this->replacementPrice - $this->renovationCost) / $this->replacementPrice;

        $depreciationRate = $this->conventionalAge * $this->depreciationRate;

        if ($depreciationRate >= 0.5) {
            $depreciationRate = 0.5;
        }

        return [
            '価額構成割合' => $priceStructureRatio,
            '価額構成割合まるめ' => round($priceStructureRatio, 2),
            '減価率' => round($priceStructureRatio, 2) * $depreciationRate,
            '減価率まるめ' => round(round($priceStructureRatio, 2) * $depreciationRate, 3),
        ];
    }

    private function getRenovationCostRate()
    {
        if ($this->replacementPrice == 0) {
            return [
                '価額構成割合' => 0,
                '価額構成割合まるめ' => 0,
                '減価率' => 0,
                '減価率まるめ' => 0,
            ];
        }

        $priceStructureRatio = $this->renovationCost / $this->replacementPrice;

        $depreciationRate = $this->renovationAge * $this->renovationDepreciationRate;

        if ($depreciationRate >= 0.5) {
            $depreciationRate = 0.5;
        }

        return [
            '価額構成割合' => $priceStructureRatio,
            '価額構成割合まるめ' => round($priceStructureRatio, 2),
            '減価率' => round($priceStructureRatio, 2) * $depreciationRate,
            '減価率まるめ' => round(round($priceStructureRatio, 2) * $depreciationRate, 3),
        ];
    }

    private function getTotalDepreciationRate()
    {
        return $this->conventionalDepreciationRate['減価率まるめ'] + $this->renovationCostRate['減価率まるめ'];
    }

    private function getResidualValue()
    {
        return 1 - $this->totalDepreciationRate;
    }

    private function getCurrentMarketPrice()
    {
        $currentMarketPrice = $this->replacementPrice * $this->residualValue;

        return [
            '時価額' => $currentMarketPrice,
            '時価額まるめ' => ceil($currentMarketPrice / 100000) * 100000,
        ];
    }

    private function getIntValueByKey(string $key, array $array, int $defaultValue = 0): int
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return (int) $array[$key];
        }

        return $defaultValue;
    }

    private function getFloatValueByKey(string $key, array $array, float $defaultValue = 0): float
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return (float) $array[$key];
        }

        return $defaultValue;
    }
}
