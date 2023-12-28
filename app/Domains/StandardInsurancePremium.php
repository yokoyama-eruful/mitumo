<?php

declare(strict_types=1);

namespace App\Domains;

use App\Models\Estimate;

final class StandardInsurancePremium
{
    public readonly Estimate $estimate;

    public readonly float $occupationalSurchargeRate;

    public readonly float $workSurchargeRate;

    public readonly float $chattelSurcharge;

    public readonly float $otherDiscount;

    public readonly float $actualLossFactor;

    public readonly string $payMethod;

    public readonly array $building;

    public readonly array $houseHold;

    public readonly array $facility;

    public readonly array $item;

    public readonly float $standard1;

    public readonly float $standard2;

    public readonly float $standardDiv;

    public readonly bool $payByLongTermFlag;

    public readonly float $standardLongTerm;

    public function __construct($estimate)
    {
        $this->estimate = $estimate;
        $this->occupationalSurchargeRate = $this->getFloatValueByKey('occupational_surcharge_rate', $estimate->property_detail);
        $this->workSurchargeRate = $this->getFloatValueByKey('work_surcharge_rate', $estimate->property_detail);
        $this->chattelSurcharge = $this->getChattelSurcharge($estimate);
        $this->otherDiscount = $this->getFloatValueByKey('other_discount', $estimate->property_detail);
        $this->actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail);
        $this->payMethod = $estimate->pay_method;

        $this->building = $this->calcBuilding();
        $this->houseHold = $this->calcHouseHold();
        $this->facility = $this->calcFacility();
        $this->item = $this->calcItem();

        //普通合計１
        $this->standard1 = $this->calcStandard1();

        //普通合計２
        $this->standard2 = $this->calcStandard2();

        //普通（分割）
        $this->standardDiv = $this->calcStandardDiv();

        //長期年払フラグ
        $this->payByLongTermFlag = $this->getPayByLongTermFlag();

        //長期年払いを考慮した普通合計
        $this->standardLongTerm = $this->calcStandardLongTerm();
    }

    private function calcStandard1()
    {
        return round($this->building['掛金'] + $this->houseHold['掛金'] + $this->facility['掛金'] + $this->item['掛金'], -1);
    }

    private function calcStandard2()
    {
        if (in_array($this->payMethod, ['一時払', '長期年払'], true)) {
            return $this->standard1;
        }

        if ($this->standard1 >= 100000) {
            $coefficient = 1.0;
        } else {
            $coefficient = 1.1;
        }

        $standardMonth = $this->standard1 * $coefficient / 12;

        return round(ceil($standardMonth), -1);
    }

    private function calcStandardDiv()
    {
        if (in_array($this->payMethod, ['一時払', '長期年払'])) {
            return 0;
        }

        if ($this->standard2 >= 180) {
            return $this->standard2;
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

    private function calcStandardLongTerm()
    {
        if ($this->standard2 > 2050) {
            return round(ceil($this->standard2 * 0.97), -1);
        }

        if ($this->standard2 >= 2000) {
            return $this->standard2;
        }

        return 2000;
    }

    public function getStandardPremium()
    {
        if ($this->payMethod == '分割払') {
            return $this->standardDiv;
        }

        if ($this->standard2 == 0) {
            return 0;
        }

        if ($this->payByLongTermFlag) {
            return $this->standardLongTerm;
        }

        if ($this->standard2 > 2000) {
            return $this->standard2;
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
        //その他割引

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            $baseRate = new BuildingBaseRate($estimate);
            $buildingBaseRate = $baseRate->findRate($estimate->property_type, $buildingAge, $estimate->rank);
        } else {
            $buildingBaseRate = $estimate->building_base_rate;
        }

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

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $calcShortTermRate = $mutualAidPremium->shortTermRate;

        if ($calcShortTermRate == 0) {
            $calcShortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(round($otherDiscount * $actualLossFactor, 2) * $longTermRate, 2) * $calcShortTermRate, 2);

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = $buildingInsurancePayout * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $buildingBaseRate,
            '短期率' => $shortTermRate == 0 ? '' : $shortTermRate * 100,
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

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $calcShortTermRate = $mutualAidPremium->shortTermRate;

        if ($calcShortTermRate == 0) {
            $calcShortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = round(round($otherDiscount * $longTermRate, 2) * $calcShortTermRate, 2);

        //掛け金
        $householdInsurancePayout = $this->getValueByKey('household_insurance_payout', $estimate->property_detail);
        $premium = $householdInsurancePayout * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $chattelBaseRate,
            '短期率' => $shortTermRate == 0 ? '' : $shortTermRate * 100,
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

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $calcShortTermRate = $mutualAidPremium->shortTermRate;

        if ($calcShortTermRate == 0) {
            $calcShortTermRate = 1;
        }
        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(round($otherDiscount * $actualLossFactor, 2) * $longTermRate, 2) * $calcShortTermRate, 2);

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = ($facilityInsurancePayout + $otherFacilityInsurancePayout) * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $chattelBaseRate,
            '短期率' => $shortTermRate == 0 ? '' : $shortTermRate * 100,
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

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $calcShortTermRate = $mutualAidPremium->shortTermRate;

        if ($calcShortTermRate == 0) {
            $calcShortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;

        $applyRate = round(round(($otherDiscount + $this->chattelSurcharge) * $longTermRate, 2) * $calcShortTermRate, 2);

        //掛け金
        $itemInsurancePayout = $this->getValueByKey('item_insurance_payout', $estimate->property_detail);
        $otherItemInsurancePayout = $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail);
        $premium = ($itemInsurancePayout + $otherItemInsurancePayout) * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $chattelBaseRate,
            '短期率' => $shortTermRate == 0 ? '' : $shortTermRate * 100,
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
