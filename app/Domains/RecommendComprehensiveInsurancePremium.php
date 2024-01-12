<?php

declare(strict_types=1);

namespace App\Domains;

use App\Models\Estimate;

final class RecommendComprehensiveInsurancePremium
{
    public readonly Estimate $estimate;

    public readonly float $standardPremium;

    public function __construct($estimate, $standardPremium)
    {
        $this->estimate = $estimate;
        $this->standardPremium = $standardPremium;
    }

    public function getRecommendPremium($recommendYear)
    {
        if ($recommendYear == '' || $recommendYear == 0) {
            return [
                '共済掛金' => '',
                '１年更新の場合より' => '',
            ];
        }

        return [
            '共済掛金' => $this->setPremium($recommendYear),
            '１年更新の場合より' => $this->setDiscountAmount($recommendYear),
        ];
    }

    private function setPremium($recommendYear)
    {
        if ($this->standardPremium == 0) {
            return 0;
        }

        $buildingPremium = $this->calcBuilding($recommendYear);
        $houseHoldPremium = $this->calcHouseHold($recommendYear);
        $facilityPremium = $this->calcFacility($recommendYear);
        $itemPremium = $this->calcItem($recommendYear);

        return round($buildingPremium + $houseHoldPremium + $facilityPremium + $itemPremium, -1);
    }

    private function setDiscountAmount($recommendYear)
    {
        if ($this->standardPremium == 0) {
            return '';
        }

        return $this->standardPremium * $recommendYear - $this->setPremium($recommendYear);
    }

    private function calcBuilding($recommendYear)
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $occupationalSurchargeRate = $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail);
            $workSurchargeRate = $this->getValueByKey('work_surcharge_rate', $estimate->property_detail);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 0);
            if ($otherDiscount == 0) {
                $otherDiscount = 1;
            } else {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($estimate->building_base_rate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear);
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('建物');

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = ($otherDiscount + $comprehensivePremium) * $actualLossFactor * $longTermRate;

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = $buildingInsurancePayout * $applyRate;

        return $premium;
    }

    private function calcHouseHold($recommendYear)
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                if ($estimate->getTable() == 'futsu2') {
                    $baseRate = new ChattelBaseRate($estimate, 'futsu2/rateTable/chattelBaseTable.csv');
                } else {
                    $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
                }
                $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            } else {
                $chattelBaseRate = $estimate->building_base_rate;
            }

            $occupationalSurchargeRate = $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail);
            $workSurchargeRate = $this->getValueByKey('work_surcharge_rate', $estimate->property_detail);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 0);
            if ($otherDiscount == 0) {
                $otherDiscount = 1;
            } else {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear);
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('家財');

        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = ($otherDiscount + $comprehensivePremium) * $longTermRate;

        //掛け金
        $householdInsurancePayout = $this->getValueByKey('household_insurance_payout', $estimate->property_detail);
        $premium = $householdInsurancePayout * $applyRate;

        return $premium;
    }

    private function calcFacility($recommendYear)
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                if ($estimate->getTable() == 'futsu2') {
                    $baseRate = new ChattelBaseRate($estimate, 'futsu2/rateTable/chattelBaseTable.csv');
                } else {
                    $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
                }
                $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            } else {
                $chattelBaseRate = $estimate->building_base_rate;
            }

            $occupationalSurchargeRate = $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail);
            $workSurchargeRate = $this->getValueByKey('work_surcharge_rate', $estimate->property_detail);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 0);
            if ($otherDiscount == 0) {
                $otherDiscount = 1;
            } else {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear);
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('動産2');

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = ($otherDiscount + $comprehensivePremium) * $actualLossFactor * $longTermRate;

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = ($facilityInsurancePayout + $otherFacilityInsurancePayout) * $applyRate;

        return $premium;
    }

    private function calcItem($recommendYear)
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                if ($estimate->getTable() == 'futsu2') {
                    $baseRate = new ChattelBaseRate($estimate, 'futsu2/rateTable/chattelBaseTable.csv');
                } else {
                    $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
                }
                $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            } else {
                $chattelBaseRate = $estimate->building_base_rate;
            }

            $occupationalSurchargeRate = $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail, 1);
            $workSurchargeRate = $this->getValueByKey('work_surcharge_rate', $estimate->property_detail);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 0);
            if ($otherDiscount == 0) {
                $otherDiscount = 1;
            } else {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return ($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount / 100;
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear);
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('動産3');

        $longTermRate = $mutualAidPremium->longTerRate;

        $chattelSurchargeRate = match ($estimate->property_type) {
            '住宅' => 0,
            '普通' => 0.2, //動産割増（カテゴリ）が１級でも２級でも料率は0.2
            '工場' => $estimate->property_detail['chattel_surcharge'],
        };

        $applyRate = ($otherDiscount + $comprehensivePremium + $chattelSurchargeRate) * $longTermRate;

        //掛け金
        $itemInsurancePayout = $this->getValueByKey('item_insurance_payout', $estimate->property_detail);
        $otherItemInsurancePayout = $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail);
        $premium = ($itemInsurancePayout + $otherItemInsurancePayout) * $applyRate;

        return $premium;
    }

    /**
     * 配列から安全に中身を取得するUtils関数
     */
    private function getValueByKey(string $key, array $array, int $defaultValue = 0): int
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return intval($array[$key]);
        }

        return $defaultValue;
    }
}
