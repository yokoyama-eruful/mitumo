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

    public readonly float $otherDiscount;

    public readonly float $actualLossFactor;

    public readonly int $discountRiskRate;

    public readonly int $discountFireExtinguisher;

    public readonly int $discountSecurity;

    public readonly int $discountPublicProperty;

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
        $this->otherDiscount = $this->getFloatValueByKey('other_discount', $estimate->property_detail);
        $this->actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail);
        $this->discountRiskRate = $this->getValueByKey('discount_risk_rate', $this->estimate->property_detail, 0);
        $this->discountFireExtinguisher = $this->getValueByKey('discount_fire_extinguisher', $this->estimate->property_detail);
        $this->discountSecurity = $this->getValueByKey('discount_security', $this->estimate->property_detail);
        $this->discountPublicProperty = $this->getValueByKey('discount_public', $this->estimate->property_detail);

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
        $earthquakePremium = (string)$this->calcEarthquakePremium($earthquakeRatio);

        if (in_array($this->payMethod, ['一時払', '長期年払'], true)) {
            return (int)$earthquakePremium;
        }

        $div = bcdiv($earthquakePremium, '12', 10);

        if (bccomp($div, (string)(int)$div, 10) === 0) {
            $ceil = (string)(int)$div;
        } else {
            $ceil = bcadd((string)(int)$div, '1', 0);
        }

        return (int)round((int)$ceil, -1);
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

        $coefficient = ($this->totalStandard >= 100000) ? '1.0' : '1.1';
        $base = (string)$this->totalStandard;

        $mul = bcmul($base, $coefficient, 10);
        $div = bcdiv($mul, '12', 10);

        if (bccomp($div, (string)(int)$div, 10) === 0) {
            $ceil = (string)(int)$div;
        } else {
            $ceil = bcadd((string)(int)$div, '1', 0);
        }

        return (int)round((int)$ceil, -1);
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
            $buildingBaseRate = $baseRate->findRate($estimate->property_type, $buildingAge, $estimate->rank, $startDate);
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
        $actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail, 1);
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
        $actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail, 1);
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

            // $result = round(($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $discount, 2);
            // $result = round(($result + $chattelPremium + $comprehensivePremium) * $discountPublicProperty, 2);

            $result = round(((($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $discount) + $chattelPremium + $comprehensivePremium) * $discountPublicProperty, 2);

            return $result;
        }

        $otherDiscount = $this->getValueByKey('other_discount', $this->estimate->property_detail, 0);
        if ($otherDiscount == 0) {
            $otherDiscount = 1;
        } else {
            $otherDiscount = 1 - $otherDiscount / 100;
        }

        // $result = round(($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount, 2);

        return round((($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount) + $chattelPremium + $comprehensivePremium, 2);
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
