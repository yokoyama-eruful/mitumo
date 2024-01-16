<?php

declare(strict_types=1);

namespace App\Domains;

use App\Models\Estimate;

final class RecommendComprehensiveInsurancePremium
{
    public readonly Estimate $estimate;

    public readonly float $comprehensivePremium;

    public readonly float $chattelSurcharge;

    public readonly float $occupationalSurchargeRate;

    public readonly float $workSurchargeRate;

    public function __construct($estimate, $comprehensivePremium)
    {
        $this->estimate = $estimate;
        $this->comprehensivePremium = $comprehensivePremium;
        $this->chattelSurcharge = $this->getChattelSurcharge($estimate);
        $this->occupationalSurchargeRate = $this->getFloatValueByKey('occupational_surcharge_rate', $estimate->property_detail);
        $this->workSurchargeRate = $this->getFloatValueByKey('work_surcharge_rate', $estimate->property_detail);
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
        if ($this->comprehensivePremium == 0) {
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
        if ($this->comprehensivePremium == 0) {
            return '';
        }

        return $this->comprehensivePremium * $recommendYear - $this->setPremium($recommendYear);
    }

    private function calcBuilding($recommendYear)
    {
        $estimate = $this->estimate;
        $propertyType = $estimate->property_type;
        if ($estimate->property_type === '住宅') {
            $buildingAge = $estimate->building_age ?? '';
        } else {
            $buildingAge = '';
        }
        //その他割引

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new BuildingBaseRate($estimate, 'estimateFutsu2/rateTable/buildingBaseRateTable.csv');
            } else {
                $baseRate = new BuildingBaseRate($estimate, 'rateTable/buildingBaseRateTable.csv');
            }
            $buildingBaseRate = $baseRate->findRate($estimate->property_type, $buildingAge, $estimate->rank);
        } else {
            $buildingBaseRate = $estimate->building_base_rate;
        }

        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('建物');

        $otherDiscount = $this->getOtherDiscount($buildingBaseRate, 0, $comprehensivePremium);
        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear);

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(($otherDiscount) * $actualLossFactor, 2) * $longTermRate, 2);

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor($buildingInsurancePayout * $applyRate * 10) / 10);

        return $premium;
    }

    private function calcHouseHold($recommendYear)
    {
        $estimate = $this->estimate;
        $propertyType = $estimate->property_type;

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new ChattelBaseRate($estimate, 'estimateFutsu2/rateTable/chattelBaseTable.csv');
            } else {
                $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
            }
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
        }

        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('家財');
        //その他割引
        $otherDiscount = $this->getOtherDiscount($chattelBaseRate, $this->chattelSurcharge, $comprehensivePremium);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear);

        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = round(($otherDiscount) * $longTermRate, 2);

        //掛け金
        $householdInsurancePayout = $this->getValueByKey('household_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor($householdInsurancePayout * $applyRate * 10) / 10);

        return $premium;
    }

    private function calcFacility($recommendYear)
    {
        $estimate = $this->estimate;
        $propertyType = $estimate->property_type;

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new ChattelBaseRate($estimate, 'estimateFutsu2/rateTable/chattelBaseTable.csv');
            } else {
                $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
            }
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
        }
        //その他割引
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('動産2');
        $otherDiscount = $this->getOtherDiscount($chattelBaseRate, $this->chattelSurcharge, $comprehensivePremium);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear);

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(($otherDiscount) * $actualLossFactor, 2) * $longTermRate, 2);

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor(($facilityInsurancePayout + $otherFacilityInsurancePayout) * $applyRate * 10) / 10);

        return $premium;
    }

    private function calcItem($recommendYear)
    {
        $estimate = $this->estimate;
        $propertyType = $estimate->property_type;

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new ChattelBaseRate($estimate, 'estimateFutsu2/rateTable/chattelBaseTable.csv');
            } else {
                $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
            }
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
        }
        //その他割引
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('動産3');
        $otherDiscount = $this->getOtherDiscount($chattelBaseRate, $this->chattelSurcharge, $comprehensivePremium);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear);

        $longTermRate = $mutualAidPremium->longTerRate;

        $applyRate = round(($otherDiscount + $comprehensivePremium) * $longTermRate, 2);

        //掛け金
        $itemInsurancePayout = $this->getValueByKey('item_insurance_payout', $estimate->property_detail);
        $otherItemInsurancePayout = $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor(($itemInsurancePayout + $otherItemInsurancePayout) * $applyRate * 10) / 10);

        return $premium;
    }

    public function getOtherDiscount($baseRate, $chattelPremium = 0, $comprehensivePremium = 0)
    {
        $discountCategoryRate = 0;
        $discountCategory = array_key_exists('discount_category', $this->estimate->property_detail) ? $this->estimate->property_detail['discount_category'] : '';

        switch ($discountCategory) {
            case 'リスク評価割引':
                $discountRiskRate = $this->getFloatValueByKey('discount_risk_rate', $this->estimate->property_detail, 1);
                $discountCategoryRate = round((($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) + $chattelPremium + $comprehensivePremium) * (1 - $discountRiskRate / 100), 2);
                break;
            case '公有物件等割引':
                $discountFireExtinguisher = $this->getFloatValueByKey('discount_fire_extinguisher', $this->estimate->property_detail);
                $discountFireExtinguisher = $discountFireExtinguisher == 0 ? 0 : $discountFireExtinguisher / 100;
                $discountSecurity = $this->getFloatValueByKey('discount_security', $this->estimate->property_detail);
                $discountSecurity = $discountSecurity == 0 ? 0 : $discountSecurity / 100;
                $discount = $discountFireExtinguisher + $discountSecurity == 0 ? 1 : $discountFireExtinguisher + $discountSecurity;
                $discountCategoryRate = (($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $discount + $chattelPremium + $comprehensivePremium) * (1 - 0.25);
                break;

            default:
                $otherDiscount = $this->getValueByKey('other_discount', $this->estimate->property_detail, 0);
                if ($otherDiscount == 0) {
                    $otherDiscount = 1;
                } else {
                    $otherDiscount = 1 - $otherDiscount / 100;
                }
                $discountCategoryRate = round(($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount + $chattelPremium + $comprehensivePremium, 2);
                break;
        }

        return $discountCategoryRate;
    }

    private function getChattelSurcharge($estimate)
    {
        return match ($estimate->property_type) {
            '住宅' => 0,
            '普通' => 0.2, //動産割増（カテゴリ）が１級でも２級でも料率は0.2
            '工場' => $this->getFloatValueByKey('chattel_surcharge', $estimate->property_detail),
        };
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

    private function getFloatValueByKey(string $key, array $array, int $defaultValue = 0): float
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return (float) $array[$key];
        }

        return $defaultValue;
    }
}
