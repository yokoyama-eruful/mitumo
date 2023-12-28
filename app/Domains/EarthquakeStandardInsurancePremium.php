<?php

declare(strict_types=1);

namespace App\Domains;

use App\Models\Estimate;

final class EarthquakeStandardInsurancePremium
{
    public readonly Estimate $estimate;

    public readonly string $propertyType;

    public readonly string $payMethod;

    public readonly int $insuranceYear;

    public readonly float $standardPremium;

    public readonly float $totalStandard;

    public readonly float $totalEarthquakeStandard;

    public function __construct($estimate, $standardPremium)
    {
        $this->estimate = $estimate;
        $this->propertyType = $estimate->property_type;
        $this->payMethod = $estimate->pay_method;
        $this->insuranceYear = $estimate->insurance_year ?? 0;
        $this->standardPremium = $standardPremium;

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
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $occupationalSurchargeRate = $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail);
            $workSurchargeRate = $this->getValueByKey('work_surcharge_rate', $estimate->property_detail);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 1);

            if ($otherDiscount != 1) {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($estimate->building_base_rate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;

        $shortTermRate = $mutualAidPremium->shortTermRate;
        if ($shortTermRate == 0) {
            $shortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = $otherDiscount * $actualLossFactor * $longTermRate * $shortTermRate;

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = $buildingInsurancePayout * $applyRate;

        return $premium;
    }

    private function calcHouseHold()
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                $baseRate = new ChattelBaseRate($estimate);
                $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            } else {
                $chattelBaseRate = $estimate->building_base_rate;
            }

            $occupationalSurchargeRate = $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail);
            $workSurchargeRate = $this->getValueByKey('work_surcharge_rate', $estimate->property_detail);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 100);
            if ($otherDiscount != 1) {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;

        $shortTermRate = $mutualAidPremium->shortTermRate;
        if ($shortTermRate == 0) {
            $shortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = $otherDiscount * $longTermRate * $shortTermRate;

        //掛け金
        $householdInsurancePayout = $this->getValueByKey('household_insurance_payout', $estimate->property_detail);
        $premium = $householdInsurancePayout * $applyRate;

        return $premium;
    }

    private function calcFacility()
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                $baseRate = new ChattelBaseRate($estimate);
                $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            } else {
                $chattelBaseRate = $estimate->building_base_rate;
            }

            $occupationalSurchargeRate = $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail);
            $workSurchargeRate = $this->getValueByKey('work_surcharge_rate', $estimate->property_detail);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 100);
            if ($otherDiscount != 1) {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;

        $shortTermRate = $mutualAidPremium->shortTermRate;
        if ($shortTermRate == 0) {
            $shortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = $otherDiscount * $actualLossFactor * $longTermRate * $shortTermRate;

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = ($facilityInsurancePayout + $otherFacilityInsurancePayout) * $applyRate;

        return $premium;
    }

    private function calcItem()
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                $baseRate = new ChattelBaseRate($estimate);
                $chattelBaseRate = $baseRate->findRate($propertyType, $estimate->rank);
            } else {
                $chattelBaseRate = $estimate->building_base_rate;
            }

            $occupationalSurchargeRate = $this->getValueByKey('occupational_surcharge_rate', $estimate->property_detail, 1);
            $workSurchargeRate = $this->getValueByKey('work_surcharge_rate', $estimate->property_detail);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 100);
            $otherDiscount = $this->getValueByKey('other_discount', $estimate->property_detail, 100);
            if ($otherDiscount != 1) {
                $otherDiscount = 1 - $otherDiscount / 100;
            }

            return round(($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount, 2);
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $estimate->insurance_year);

        $shortTermRate = $mutualAidPremium->shortTermRate;

        $shortTermRate = $mutualAidPremium->shortTermRate;
        if ($shortTermRate == 0) {
            $shortTermRate = 1;
        }

        $longTermRate = $mutualAidPremium->longTerRate;

        $chattelSurchargeRate = match ($estimate->property_type) {
            '住宅' => 0,
            '普通' => 0.2, //動産割増（カテゴリ）が１級でも２級でも料率は0.2
            '工場' => $estimate->property_detail['chattel_surcharge'],
        };

        $applyRate = ($otherDiscount + $chattelSurchargeRate) * $longTermRate * $shortTermRate;

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
