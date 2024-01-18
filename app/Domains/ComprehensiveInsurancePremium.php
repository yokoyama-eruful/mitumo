<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Database\Eloquent\Model;

final class ComprehensiveInsurancePremium
{
    public readonly Model $estimate;

    public readonly float $chattelSurcharge;

    public readonly float $occupationalSurchargeRate;

    public readonly float $workSurchargeRate;

    public readonly float $otherDiscount;

    public readonly float $actualLossFactor;

    public readonly string $discountCategory;

    public readonly int $discountRiskRate;

    public readonly int $discountFireExtinguisher;

    public readonly int $discountSecurity;

    public readonly int $discountPublicProperty;

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
        $this->otherDiscount = $this->getFloatValueByKey('other_discount', $estimate->property_detail);
        $this->actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail);
        $this->discountCategory = isset($this->estimate->property_detail['discount_category']) ? $this->estimate->property_detail['discount_category'] : '';
        $this->discountRiskRate = $this->getValueByKey('discount_risk_rate', $this->estimate->property_detail, 0);
        $this->discountFireExtinguisher = $this->getValueByKey('discount_fire_extinguisher', $this->estimate->property_detail);
        $this->discountSecurity = $this->getValueByKey('discount_security', $this->estimate->property_detail);
        $this->discountPublicProperty = $this->discountCategory == '公有物件等割引' ? 25 : 0;

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

        return match ($this->discountCategory) {
            '公有物件等割引' => $this->payMethod == '長期年払' &&
            $estimate->insurance_year >= 5 &&
            is_null($estimate->property_detail['actual_loss_factor']) &&
            $this->discountFireExtinguisher + $this->discountSecurity < 10,

            'リスク評価割引' => false,

            default => $this->payMethod == '長期年払' &&
            $estimate->insurance_year >= 5 &&
            is_null($estimate->property_detail['actual_loss_factor']) &&
            $this->otherDiscount < 10,
        };

        // return
        //  $this->payMethod == '長期年払' &&
        // $estimate->insurance_year >= 5 &&
        // is_null($estimate->property_detail['actual_loss_factor']) &&
        // (is_null($estimate->property_detail['other_discount']) ||
        // $estimate->property_detail['other_discount'] < 10);

    }

    private function calcComprehensiveLongTerm()
    {
        if ($this->comprehensive2 > 2050 || $this->discountCategory == '公有物件等割引') {
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
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new BuildingBaseRate($estimate, 'estimateFutsu2/rateTable/buildingBaseRateTable.csv');
            } else {
                $baseRate = new BuildingBaseRate($estimate, 'rateTable/buildingBaseRateTable.csv');
            }
            $buildingBaseRate = $baseRate->findRate($propertyType, $buildingAge, $estimate->rank);
        } else {
            $buildingBaseRate = $estimate->building_base_rate;
        }
        //その他割引
        $comprehensivePremium = new ComprehensivePremium($estimate);
        $comprehensivePremium = $comprehensivePremium->getRate('建物');

        $otherDiscount = $this->getOtherDiscount($buildingBaseRate, 0, $comprehensivePremium);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(($otherDiscount) * $actualLossFactor, 2) * $longTermRate, 2);

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor($buildingInsurancePayout * $applyRate * 10) / 10);

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
        $comprehensivePremium = $comprehensivePremium->getRate('家財');

        $otherDiscount = $this->getOtherDiscount($chattelBaseRate, $this->chattelSurcharge, $comprehensivePremium);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = round(($otherDiscount) * $longTermRate, 2);

        //掛け金
        $householdInsurancePayout = $this->getValueByKey('household_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor($householdInsurancePayout * $applyRate * 10) / 10);

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
        $otherDiscount = $this->getOtherDiscount($chattelBaseRate, 0, $comprehensivePremium);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(($otherDiscount) * $actualLossFactor, 2) * $longTermRate, 2);

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor(($facilityInsurancePayout + $otherFacilityInsurancePayout) * $applyRate * 10) / 10);

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
        $otherDiscount = $otherDiscount = $this->getOtherDiscount($chattelBaseRate, $this->chattelSurcharge, $comprehensivePremium);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = round(($otherDiscount) * $longTermRate, 2);

        //掛け金
        $itemInsurancePayout = $this->getValueByKey('item_insurance_payout', $estimate->property_detail);
        $otherItemInsurancePayout = $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor(($itemInsurancePayout + $otherItemInsurancePayout) * $applyRate * 10) / 10);

        return [
            'その他割引' => $otherDiscount,
            '基本率' => $chattelBaseRate,
            '総合割増' => $comprehensivePremium,
            '長期率' => $longTermRate,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
    }

    public function getOtherDiscount($baseRate, $chattelPremium = 0, $comprehensivePremium = 0)
    {
        $discountCategoryRate = 0;

        switch ($this->discountCategory) {
            case 'リスク評価割引':
                $discountRiskRate = $this->discountRiskRate;
                if ($discountRiskRate == 0) {
                    $discountRiskRate = 1;
                } else {
                    $discountRiskRate = 1 - $discountRiskRate / 100;
                }
                $discountCategoryRate = round((($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) + $chattelPremium + $comprehensivePremium) * $discountRiskRate, 2);
                break;
            case '公有物件等割引':
                $discountFireExtinguisher = $this->discountFireExtinguisher == 0 ? 0 : $this->discountFireExtinguisher / 100;
                $discountSecurity = $this->discountSecurity == 0 ? 0 : $this->discountSecurity / 100;
                $discount = $discountFireExtinguisher + $discountSecurity == 0 ? 1 : $discountFireExtinguisher + $discountSecurity;
                $discountPublicProperty = $this->discountPublicProperty;
                if ($discountPublicProperty == 0) {
                    $discountPublicProperty = 1;
                } else {
                    $discountPublicProperty = 1 - $discountPublicProperty / 100;
                }
                $discountCategoryRate = (($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $discount + $chattelPremium + $comprehensivePremium) * $discountPublicProperty;
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
