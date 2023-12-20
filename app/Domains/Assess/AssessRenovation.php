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
        $this->replacementPrice = (int) $estimate->assess_detail['replacement_price'];
        $this->renovationCost = (int) $estimate->assess_detail['renovation_cost'];
        $this->conventionalAge = (int) $estimate->assess_detail['conventional_age'];
        $this->depreciationRate = (float) $estimate->assess_detail['depreciation_rate'];
        $this->renovationAge = (int) $estimate->assess_detail['renovation_age'];
        $this->renovationDepreciationRate = (float) $estimate->assess_detail['renovation_depreciation_rate'];

        $this->conventionalDepreciationRate = $this->getConventionalDepreciationRate();
        $this->renovationCostRate = $this->getRenovationCostRate();
        $this->totalDepreciationRate = $this->getTotalDepreciationRate();
        $this->residualValue = $this->getResidualValue();
        $this->currentMarketPrice = $this->getCurrentMarketPrice();
    }

    private function getConventionalDepreciationRate()
    {
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
}
