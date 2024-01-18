<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Database\Eloquent\Model;

final class EarthquakeStandardInsurancePremium
{
    public readonly Model $estimate;

    public readonly string $propertyType;

    public readonly string $payMethod;

    public readonly int $insuranceYear;

    public readonly float $standardPremium;

    public readonly float $chattelSurcharge;

    public readonly float $occupationalSurchargeRate;

    public readonly float $workSurchargeRate;

    public readonly float $totalStandard;

    public readonly float $totalEarthquakeStandard;

    public function __construct($estimate, $standardPremium)
    {
        $this->estimate = $estimate;
        $this->propertyType = $estimate->property_type;
        $this->payMethod = $estimate->pay_method;
        $this->insuranceYear = $estimate->insurance_year ?? 0;
        $this->standardPremium = $standardPremium;
        $this->chattelSurcharge = $this->getChattelSurcharge($estimate);
        $this->occupationalSurchargeRate = $this->getFloatValueByKey('occupational_surcharge_rate', $estimate->property_detail);
        $this->workSurchargeRate = $this->getFloatValueByKey('work_surcharge_rate', $estimate->property_detail);

        $this->totalStandard = $this->calcTotalStandard();
        $this->totalEarthquakeStandard = $this->calcTotalEarthquakeStandard();
    }

    public function getEarthquakePremium($earthquakeRatio)
    {
        if (! $earthquakeRatio || $earthquakeRatio == 0 || $earthquakeRatio == '選択しない') {
            return [
                '共済金支払最大額' => '',
                '地震掛金' => '',
                '合計掛金' => '',
            ];
        }

        return [
            '共済金支払最大額' => $this->setMaxInsurancePremium($earthquakeRatio),
            '地震掛金' => $this->setEarthquakePremium($earthquakeRatio),
            '合計掛金' => $this->setTotalPremium($earthquakeRatio),
        ];
    }

    private function setMaxInsurancePremium($earthquakeRatio)
    {
        return (int) $this->estimate->property_detail['building_insurance_payout'] * $earthquakeRatio / 100;
    }

    private function setEarthquakePremium($earthquakeRatio)
    {
        if (! $this->getEarthquakeFlag() || $this->estimate->insurance_year >= 2) {
            return '地震特約加入不可';
        }

        return (int) $this->calcEarthquakeDiv($earthquakeRatio);
    }

    private function setTotalPremium($earthquakeRatio)
    {
        if (! $this->getEarthquakeFlag() || $this->estimate->insurance_year >= 2) {
            return '地震特約加入不可';
        }

        if ($this->setMaxInsurancePremium($earthquakeRatio) == '') {
            return '';
        }

        return (int) $this->standardPremium + $this->setEarthquakePremium($earthquakeRatio);
    }

    private function getEarthquakeFlag()
    {
        $shortTermRate = new MutualAidPremiums($this->estimate, $this->insuranceYear);
        $shortTermRate = $shortTermRate->shortTermRate;

        return $shortTermRate == 0 &&
        in_array($this->propertyType, ['普通', '工場'], true);
    }

    private function calcEarthquakeDiv($earthquakeRatio)
    {
        $earthquakePremium = $this->calcEarthquakePremium($earthquakeRatio);

        if (in_array($this->payMethod, ['一時払', '長期年払'], true)) {
            return $earthquakePremium;
        }

        $earthquakeMonth = $earthquakePremium / 12;

        return round(ceil($earthquakeMonth), -1);
    }

    private function calcEarthquakePremium($earthquakeRatio)
    {
        return round(ceil($this->setMaxInsurancePremium($earthquakeRatio) * 2.42), -1);
    }

    private function calcTotalStandard()
    {

        $buildingPremium = $this->calcBuilding();
        $houseHoldPremium = $this->calcHouseHold();
        $facilityPremium = $this->calcFacility();
        $itemPremium = $this->calcItem();

        return round($buildingPremium + $houseHoldPremium + $facilityPremium + $itemPremium, -1);
    }

    private function calcTotalEarthquakeStandard()
    {
        if (in_array($this->payMethod, ['一時払', '長期年払'], true)) {
            return $this->totalStandard;
        }

        if ($this->totalStandard >= 100000) {
            $coefficient = 1.0;
        } else {
            $coefficient = 1.1;
        }

        $totalEarthquakeStandard = $this->totalStandard * $coefficient / 12;

        return round(ceil($totalEarthquakeStandard), -1);
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
            if ($estimate->getTable() == 'estimate_futsu2') {
                $baseRate = new BuildingBaseRate($estimate, 'estimateFutsu2/rateTable/buildingBaseRateTable.csv');
            } else {
                $baseRate = new BuildingBaseRate($estimate, 'rateTable/buildingBaseRateTable.csv');
            }
            $buildingBaseRate = $baseRate->findRate($estimate->property_type, $buildingAge, $estimate->rank);
        } else {
            $buildingBaseRate = $estimate->building_base_rate;
        }

        $otherDiscount = $this->getOtherDiscount($buildingBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;

        $shortTermRate = $mutualAidPremium->shortTermRate;
        if ($shortTermRate == 0) {
            $shortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(round($otherDiscount * $actualLossFactor, 2) * $longTermRate, 2) * $shortTermRate, 2);

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor($buildingInsurancePayout * $applyRate * 10) / 10);

        return $premium;
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
        $otherDiscount = $this->getOtherDiscount($chattelBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;

        $shortTermRate = $mutualAidPremium->shortTermRate;
        if ($shortTermRate == 0) {
            $shortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = round(round($otherDiscount * $longTermRate, 2) * $shortTermRate, 2);

        //掛け金
        $householdInsurancePayout = $this->getValueByKey('household_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor($householdInsurancePayout * $applyRate * 10) / 10);

        return $premium;
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

        $otherDiscount = $this->getOtherDiscount($chattelBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;

        $shortTermRate = $mutualAidPremium->shortTermRate;
        if ($shortTermRate == 0) {
            $shortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = round(round(round($otherDiscount * $actualLossFactor, 2) * $longTermRate, 2) * $shortTermRate, 2);

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor(($facilityInsurancePayout + $otherFacilityInsurancePayout) * $applyRate * 10) / 10);

        return $premium;
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

        $otherDiscount = $this->getOtherDiscount($chattelBaseRate, $this->chattelSurcharge);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;

        $shortTermRate = $mutualAidPremium->shortTermRate;
        if ($shortTermRate == 0) {
            $shortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;

        $applyRate = round(round(($otherDiscount) * $longTermRate, 2) * $shortTermRate, 2);

        //掛け金
        $itemInsurancePayout = $this->getValueByKey('item_insurance_payout', $estimate->property_detail);
        $otherItemInsurancePayout = $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail);
        $premium = ceil(floor(($itemInsurancePayout + $otherItemInsurancePayout) * $applyRate * 10) / 10);

        return $premium;
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
     * 割引の適用判別
     */
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
