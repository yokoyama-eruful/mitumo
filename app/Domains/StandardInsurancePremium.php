<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Database\Eloquent\Model;

final class StandardInsurancePremium
{
    public readonly Model $estimate;

    public readonly float $occupationalSurchargeRate;

    public readonly float $workSurchargeRate;

    public readonly float $chattelSurcharge;

    public readonly float $otherDiscount;

    public readonly float $actualLossFactor;

    public readonly int $discountRiskRate;

    public readonly int $discountFireExtinguisher;

    public readonly int $discountSecurity;

    public readonly int $discountPublicProperty;

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
        $this->discountRiskRate = $this->getValueByKey('discount_risk_rate', $this->estimate->property_detail, 0);
        $this->discountFireExtinguisher = $this->getValueByKey('discount_fire_extinguisher', $this->estimate->property_detail);
        $this->discountSecurity = $this->getValueByKey('discount_security', $this->estimate->property_detail);
        $this->discountPublicProperty = $this->getValueByKey('discount_public', $this->estimate->property_detail);

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

        if ($this->standard1 >= 100000 ) {
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

        if ($this->standard1 >= 180) {
            if ($this->standard1 >= 100000 ) {
                $coefficient = 1.0;
            } else {
                $coefficient = 1.1;
            }

            return round(ceil($this->standard1 * $coefficient / 12), -1);
        }

        return 180;
    }

    public function getPayByLongTermFlag()
    {
        $estimate = $this->estimate;

        if ($this->discountRiskRate > 0) {
            return false;
        }

        if ($this->discountPublicProperty > 0) {
            return false;

        }

        if ($this->discountFireExtinguisher > 0 || $this->discountSecurity > 0 || $this->discountPublicProperty > 0) {
            return $this->payMethod == '長期年払' &&
            $estimate->insurance_year >= 5 &&
            (is_null($estimate->property_detail['actual_loss_factor']) || $estimate->property_detail['actual_loss_factor'] == '0') &&
            $this->discountFireExtinguisher + $this->discountSecurity < 10;
        }

        return $this->payMethod == '長期年払' &&
        $estimate->insurance_year >= 5 &&
        (is_null($estimate->property_detail['actual_loss_factor']) || $estimate->property_detail['actual_loss_factor'] == '0') &&
        $this->otherDiscount < 10;

        // return
        // $this->payMethod == '長期年払' &&
        // $estimate->insurance_year >= 5 &&
        // is_null($estimate->property_detail['actual_loss_factor']) &&
        // (is_null($estimate->property_detail['other_discount']) ||
        // $estimate->property_detail['other_discount'] < 10);

    }

    private function calcStandardLongTerm()
    {
        if ($this->standard2 >= 2060) {
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

        $buildingAge = $estimate->building_age ?? '';
        //その他割引

        $startDate = $estimate->start_date ?? null;

        if (in_array($propertyType, ['住宅', '普通'], true)) {
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new BuildingBaseRate($estimate, 'estimateFutsu2/rateTable/buildingBaseRateTable.csv');
            } else {
                $baseRate = new BuildingBaseRate($estimate, 'rateTable/buildingBaseRateTable.csv');
            }

            $buildingBaseRate = $baseRate->findRate($estimate->property_type, $buildingAge, $estimate->rank,$startDate);
        } else {
            $buildingBaseRate = $estimate->building_base_rate;

        }

        //損保９割のロジック（building_base_rate）
        if ($estimate->is_sonpo_base_rate) {
            $buildingBaseRate = $estimate->sonpo_building_base_rate;
        }

        $otherDiscount = $this->getOtherDiscount($buildingBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $calcShortTermRate = $mutualAidPremium->shortTermRate;

        if ($calcShortTermRate == 0) {
            $calcShortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(round($otherDiscount * $actualLossFactor, 2) * $longTermRate, 2) * $calcShortTermRate, 2);

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor($buildingInsurancePayout * $applyRate * 10) / 10);

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
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new ChattelBaseRate($estimate, 'estimateFutsu2/rateTable/chattelBaseTable.csv');
            } else {
                $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
            }
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            //損保９割のロジック（chattel_base_rate）
            if ($estimate->is_sonpo_base_rate) {
                $chattelBaseRate = $estimate->sonpo_chattel_base_rate;
            }
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
            //損保９割のロジック（building_base_rate）
            if ($estimate->is_sonpo_base_rate) {
                $chattelBaseRate = $estimate->sonpo_building_base_rate;
            }
        }
        //その他割引
        $otherDiscount = $this->getOtherDiscount($chattelBaseRate);

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
        $premium = ceil(floor($householdInsurancePayout * $applyRate * 10) / 10);

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
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new ChattelBaseRate($estimate, 'estimateFutsu2/rateTable/chattelBaseTable.csv');
            } else {
                $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
            }
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            //損保９割のロジック（chattel_base_rate）
            if ($estimate->is_sonpo_base_rate) {
                $chattelBaseRate = $estimate->sonpo_chattel_base_rate;
            }
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
            //損保９割のロジック（building_base_rate）
            if ($estimate->is_sonpo_base_rate) {
                $chattelBaseRate = $estimate->sonpo_building_base_rate;
            }
        }
        //その他割引

        $otherDiscount = $this->getOtherDiscount($chattelBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $calcShortTermRate = $mutualAidPremium->shortTermRate;

        if ($calcShortTermRate == 0) {
            $calcShortTermRate = 1;
        }
        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(round($otherDiscount * $actualLossFactor, 2) * $longTermRate, 2) * $calcShortTermRate, 2);

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor(($facilityInsurancePayout + $otherFacilityInsurancePayout) * $applyRate * 10) / 10);

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
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new ChattelBaseRate($estimate, 'estimateFutsu2/rateTable/chattelBaseTable.csv');
            } else {
                $baseRate = new ChattelBaseRate($estimate, 'rateTable/chattelBaseTable.csv');
            }
            $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            //損保９割のロジック（chattel_base_rate）
            if ($estimate->is_sonpo_base_rate) {
                $chattelBaseRate = $estimate->sonpo_chattel_base_rate;
            }
        } else {
            $chattelBaseRate = $estimate->building_base_rate;
            //損保９割のロジック（building_base_rate）
            if ($estimate->is_sonpo_base_rate) {
                $chattelBaseRate = $estimate->sonpo_building_base_rate;
            }
        }
        //その他割引

        $otherDiscount = $this->getOtherDiscount($chattelBaseRate, $this->chattelSurcharge);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $calcShortTermRate = $mutualAidPremium->shortTermRate;

        if ($calcShortTermRate == 0) {
            $calcShortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;

        $applyRate = round(round(($otherDiscount) * $longTermRate, 2) * $calcShortTermRate, 2);

        //掛け金
        $itemInsurancePayout = $this->getValueByKey('item_insurance_payout', $estimate->property_detail);
        $otherItemInsurancePayout = $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor(($itemInsurancePayout + $otherItemInsurancePayout) * $applyRate * 10) / 10);

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
            '普通' => $this->getStringValueByKey('chattel_surcharge_category', $estimate->property_detail) == '-' ? 0 : 0.2, //動産割増（カテゴリ）が１級でも２級でも料率は0.2
            '工場' => $this->getFloatValueByKey('chattel_surcharge', $estimate->property_detail),
        };
    }

    /**
     * 割引の適用判別
     */
    public function getOtherDiscount($baseRate, $chattelPremium = 0, $comprehensivePremium = 0)
    {
        if ($this->discountRiskRate > 0) {
            $discountRiskRate = $this->discountRiskRate + $this->discountFireExtinguisher + $this->discountSecurity + $this->discountPublicProperty + $this->otherDiscount;
            if ($discountRiskRate == 0) {
                $discountRiskRate = 1;
            } else {
                $discountRiskRate = 1 - $discountRiskRate / 100;
            }

            return round((($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) + $chattelPremium + $comprehensivePremium) * $discountRiskRate, 2);
        }

        if ($this->discountFireExtinguisher > 0 || $this->discountSecurity > 0 || $this->discountPublicProperty > 0) {
            $discountFireExtinguisher = $this->discountFireExtinguisher == 0 ? 0 : $this->discountFireExtinguisher;
            $discountSecurity = $this->discountSecurity == 0 ? 0 : $this->discountSecurity;
            $otherDiscount = $this->otherDiscount == 0 ? 0 : $this->otherDiscount;
            $discount = $discountFireExtinguisher + $discountSecurity + $otherDiscount == 0 ? 0 : $discountFireExtinguisher + $discountSecurity + $otherDiscount;
            $discountPublicProperty = $this->discountPublicProperty;

            if ($discount == 0) {
                $discount = 1;
            } else {
                $discount = 1 - $discount / 100;
            }

            if ($discountPublicProperty == 0) {
                $discountPublicProperty = 1;
            } else {
                $discountPublicProperty = 1 - $discountPublicProperty / 100;
            }

            $result = round(($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $discount, 2);
            $result = round(($result + $chattelPremium + $comprehensivePremium) * $discountPublicProperty, 2);

            return $result;

        }

        $otherDiscount = $this->getValueByKey('other_discount', $this->estimate->property_detail, 0);
        if ($otherDiscount == 0) {
            $otherDiscount = 1;
        } else {
            $otherDiscount = 1 - $otherDiscount / 100;
        }

        $result = round(($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount, 2);

        return round($result + $chattelPremium + $comprehensivePremium, 2);
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

    private function getStringValueByKey(string $key, array $array, string $defaultValue = '-'): string
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return (string) $array[$key];
        }

        return $defaultValue;
    }
}
