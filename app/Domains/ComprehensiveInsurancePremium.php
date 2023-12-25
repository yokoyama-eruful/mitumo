<?php

declare(strict_types=1);

namespace App\Domains;

use App\Models\Estimate;

final class ComprehensiveInsurancePremium
{
    public readonly Estimate $estimate;

    public readonly float $chattelSurcharge;

    public readonly float $occupationalSurchargeRate;

    public readonly float $workSurchargeRate;

    public readonly string $payMethod;

    public readonly array $building;

    public readonly array $houseHold;

    public readonly array $facility;

    public readonly array $item;

    public readonly float $comprehensive1;

    public readonly float $comprehensive2;

    public readonly float $comprehensiveDiv;

    public readonly bool $payByLongTermFlag;

    public readonly float $comprehensiveLongTerm;

    public function __construct($estimate)
    {
        $this->estimate = $estimate;
        $this->payMethod = $estimate->pay_method;
        $this->chattelSurcharge = $this->getChattelSurcharge($estimate);
        $this->occupationalSurchargeRate = $this->getFloatValueByKey('occupational_surcharge_rate', $estimate->property_detail);
        $this->workSurchargeRate = $this->getFloatValueByKey('work_surcharge_rate', $estimate->property_detail);

        $this->building = $this->calcBuilding();
        $this->houseHold = $this->calcHouseHold();
        $this->facility = $this->calcFacility();
        $this->item = $this->calcItem();

        //総合合計１
        $this->comprehensive1 = $this->calcComprehensive1();

        //総合合計２
        $this->comprehensive2 = $this->calcComprehensive2();

        //総合（分割）
        $this->comprehensiveDiv = $this->calcComprehensiveDiv();

        //長期年払フラグ
        $this->payByLongTermFlag = $this->getPayByLongTermFlag();

        //長期年払いを考慮した総合合計
        $this->comprehensiveLongTerm = $this->calcComprehensiveLongTerm();
    }

    private function calcComprehensive1()
    {
        return round($this->building['掛金'] + $this->houseHold['掛金'] + $this->facility['掛金'] + $this->item['掛金'], -1);
    }

    private function calcComprehensive2()
    {
        if (in_array($this->payMethod, ['一時払', '長期年払'], true)) {
            return $this->comprehensive1;
        }

        if ($this->comprehensive1 >= 100000) {
            $coefficient = 1.0;
        } else {
            $coefficient = 1.1;
        }

        $comprehensiveMonth = $this->comprehensive1 * $coefficient / 12;

        return round(ceil($comprehensiveMonth), -1);
    }

    private function calcComprehensiveDiv()
    {
        if (in_array($this->payMethod, ['一時払', '長期年払'])) {
            return 0;
        }

        if ($this->comprehensive2 >= 180) {
            return $this->comprehensive2;
        }

        return 180;
    }

    private function getPayByLongTermFlag()
    {
        $estimate = $this->estimate;

        return
         $this->payMethod == '長期年払' &&
        $estimate->insurance_year >= 5 &&
        $estimate->property_detail['other_discount'];

    }

    private function calcComprehensiveLongTerm()
    {
        if ($this->comprehensive2 > 2050) {
            return round(ceil($this->comprehensive2 * 0.97), -1);
        }

        if ($this->comprehensive2 >= 2000) {
            return $this->comprehensive2;
        }

        return 2000;
    }

    public function getComprehensivePremium()
    {
        if ($this->payMethod == '分割払') {
            return $this->comprehensiveDiv;
        }

        if ($this->comprehensive2 == 0) {
            return 0;
        }

        if ($this->payByLongTermFlag) {
            return $this->comprehensiveLongTerm;
        }

        if ($this->comprehensive2 > 2000) {
            return $this->comprehensive2;
        }

        return 2000;
    }

    private function calcBuilding()
    {
        $estimate = $this->estimate;

        $propertyType = $estimate->property_type;

        if ($estimate->property_type === '住宅') {
            $buildingAge = $estimate->building_age ?? '';
        } else {
            $buildingAge = '';
        }

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            $baseRate = new BuildingBaseRate($estimate);
            $buildingBaseRate = $baseRate->findRate($propertyType, $buildingAge, $estimate->rank);
        } else {
            $buildingBaseRate = $estimate->building_base_rate;
        }
        //その他割引

        $calcOtherDiscount = function ($estimate, $buildingBaseRate) {

            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 0);
            if ($otherDiscount == 0) {
                $otherDiscount = 1;
            } else {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($buildingBaseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate, $buildingBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('建物');

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(($otherDiscount + $comprehensivePremium) * $actualLossFactor, 2) * $longTermRate, 2);

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = $buildingInsurancePayout * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $buildingBaseRate,
            '総合割増' => $comprehensivePremium,
            '長期率' => $longTermRate,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
    }

    private function calcHouseHold()
    {
        $estimate = $this->estimate;

        $propertyType = $estimate->property_type;

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            $baseRate = new ChattelBaseRate($estimate);
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
        }

        //その他割引

        $calcOtherDiscount = function ($estimate, $chattelBaseRate) {

            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 0);
            if ($otherDiscount == 0) {
                $otherDiscount = 1;
            } else {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($chattelBaseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate, $chattelBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('家財');

        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = round(($otherDiscount + $comprehensivePremium) * $longTermRate, 2);

        //掛け金
        $householdInsurancePayout = $this->getValueByKey('household_insurance_payout', $estimate->property_detail);
        $premium = $householdInsurancePayout * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $chattelBaseRate,
            '総合割増' => $comprehensivePremium,
            '長期率' => $longTermRate,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
    }

    private function calcFacility()
    {
        $estimate = $this->estimate;

        $propertyType = $estimate->property_type;

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            $baseRate = new ChattelBaseRate($estimate);
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
        }

        //その他割引

        $calcOtherDiscount = function ($estimate, $chattelBaseRate) {

            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 0);
            if ($otherDiscount == 0) {
                $otherDiscount = 1;
            } else {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($chattelBaseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate, $chattelBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('動産2');

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(($otherDiscount + $comprehensivePremium) * $actualLossFactor, 2) * $longTermRate, 2);

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = ($facilityInsurancePayout + $otherFacilityInsurancePayout) * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $chattelBaseRate,
            '総合割増' => $comprehensivePremium,
            '長期率' => $longTermRate,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
    }

    private function calcItem()
    {
        $estimate = $this->estimate;

        $propertyType = $estimate->property_type;

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            $baseRate = new ChattelBaseRate($estimate);
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
        }
        //その他割引

        $calcOtherDiscount = function ($estimate, $chattelBaseRate) {

            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 0);
            if ($otherDiscount == 0) {
                $otherDiscount = 1;
            } else {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($chattelBaseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate, $chattelBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('動産3');

        $longTermRate = $mutualAidPremium->longTerRate;

        $applyRate = round(($otherDiscount + $comprehensivePremium + $this->chattelSurcharge) * $longTermRate, 2);

        //掛け金
        $itemInsurancePayout = $this->getValueByKey('item_insurance_payout', $estimate->property_detail);
        $otherItemInsurancePayout = $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail);
        $premium = ($itemInsurancePayout + $otherItemInsurancePayout) * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $chattelBaseRate,
            '総合割増' => $comprehensivePremium,
            '長期率' => $longTermRate,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
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
