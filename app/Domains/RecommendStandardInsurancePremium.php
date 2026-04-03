<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Database\Eloquent\Model;

final class RecommendStandardInsurancePremium
{
    public readonly Model $estimate;

    public readonly float $standardPremium;

    public readonly float $chattelSurcharge;

    public readonly float $occupationalSurchargeRate;

    public readonly float $workSurchargeRate;

    public readonly float $otherDiscount;

    public readonly float $actualLossFactor;

    public readonly int $discountRiskRate;

    public readonly int $discountFireExtinguisher;

    public readonly int $discountSecurity;

    public readonly int $discountPublicProperty;

    public function __construct($estimate, $standardPremium)
    {
        $this->estimate = $estimate;
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
        $mutualAidPremium = new MutualAidPremiums($estimate, (int) $recommendYear); //おすすめ契約年数で長期率を取得

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = $this->calcApplyRate(
            (string)$otherDiscount,
            (string)$actualLossFactor,
            (string)$longTermRate
        );

        //掛け金
        $buildingInsurancePayout = $this->getValueByKey('building_insurance_payout', $estimate->property_detail);
        $premium = $this->calcPremium($buildingInsurancePayout, $applyRate);

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

        //その他割引
        $otherDiscount = $this->getOtherDiscount($chattelBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear); //おすすめ契約年数で長期率を取得

        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = $this->calcApplyRate(
            (string)$otherDiscount,
            (string)$longTermRate
        );

        //掛け金
        $householdInsurancePayout = $this->getValueByKey('household_insurance_payout', $estimate->property_detail);
        $premium = $this->calcPremium($householdInsurancePayout, $applyRate);

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

        $otherDiscount = $this->getOtherDiscount($chattelBaseRate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear); //おすすめ契約年数で長期率を取得

        $longTermRate = $mutualAidPremium->longTerRate;
        $actualLossFactor = $this->getFloatValueByKey('actual_loss_factor', $estimate->property_detail, 1);
        $applyRate = $this->calcApplyRate(
            (string)$otherDiscount,
            (string)$actualLossFactor,
            (string)$longTermRate
        );

        //掛け金
        $facilityInsurancePayout = $this->getValueByKey('facility_insurance_payout', $estimate->property_detail);
        $otherFacilityInsurancePayout = $this->getValueByKey('other_facility_insurance_payout', $estimate->property_detail);
        $premium = $this->calcPremium($facilityInsurancePayout + $otherFacilityInsurancePayout, $applyRate);

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

        $otherDiscount = $this->getOtherDiscount($chattelBaseRate, $this->chattelSurcharge);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate, $recommendYear); //おすすめ契約年数で長期率を取得

        $longTermRate = $mutualAidPremium->longTerRate;

        $applyRate = $this->calcApplyRate(
            (string)$otherDiscount,
            (string)$longTermRate
        );

        //掛け金
        $itemInsurancePayout = $this->getValueByKey('item_insurance_payout', $estimate->property_detail);
        $otherItemInsurancePayout = $this->getValueByKey('other_item_insurance_payout', $estimate->property_detail);
        $premium = $this->calcPremium($itemInsurancePayout + $otherItemInsurancePayout, $applyRate);

        return $premium;
    }

    public function getOtherDiscount($baseRate, $chattelPremium = 0, $comprehensivePremium = 0)
    {
        if ($this->discountRiskRate > 0) {
            $discountRiskRate = $this->discountRiskRate + $this->discountFireExtinguisher + $this->discountSecurity + $this->discountPublicProperty + $this->otherDiscount;
            if ($discountRiskRate == 0) {
                $discountRiskRate = 1;
            } else {
                $discountRiskRate = (float)bcsub('1', bcdiv((string)$discountRiskRate, '100', 10), 10);
            }

            $base = $this->calcBaseRate((string)$baseRate, (string)$chattelPremium, (string)$comprehensivePremium);
            return (float)$this->bcRound2(bcmul($base, (string)$discountRiskRate, 10));
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
                $discount = (float)bcsub('1', bcdiv((string)$discount, '100', 10), 10);
            }

            if ($discountPublicProperty == 0) {
                $discountPublicProperty = 1;
            } else {
                $discountPublicProperty = (float)bcsub('1', bcdiv((string)$discountPublicProperty, '100', 10), 10);
            }

            // $result = round(($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $discount, 2);
            // $result = round(($result + $chattelPremium + $comprehensivePremium) * $discountPublicProperty, 2);

            $base = bcadd(
                bcmul(
                    bcadd(
                        bcadd((string)$baseRate, (string)$this->occupationalSurchargeRate, 10),
                        (string)$this->workSurchargeRate,
                        10
                    ),
                    (string)$discount,
                    10
                ),
                bcadd((string)$chattelPremium, (string)$comprehensivePremium, 10),
                10
            );
            $result = $this->bcRound2(bcmul($base, (string)$discountPublicProperty, 10));

            return (float)$result;
        }

        $otherDiscount = $this->getValueByKey('other_discount', $this->estimate->property_detail, 0);
        if ($otherDiscount == 0) {
            $otherDiscount = 1;
        } else {
            $otherDiscount = (float)bcsub('1', bcdiv((string)$otherDiscount, '100', 10), 10);
        }

        // $result = round(($baseRate + $this->occupationalSurchargeRate + $this->workSurchargeRate) * $otherDiscount, 2);

        $base = bcadd(
            bcmul(
                bcadd(
                    bcadd((string)$baseRate, (string)$this->occupationalSurchargeRate, 10),
                    (string)$this->workSurchargeRate,
                    10
                ),
                (string)$otherDiscount,
                10
            ),
            bcadd((string)$chattelPremium, (string)$comprehensivePremium, 10),
            10
        );
        return (float)$this->bcRound2($base);
    }

    private function calcApplyRate(string ...$factors): string
    {
        $rate = array_shift($factors);
        if ($rate === null) {
            return '0.00';
        }

        foreach ($factors as $factor) {
            $rate = $this->bcRound2(bcmul($rate, $factor, 10));
        }

        return $rate;
    }

    private function calcPremium(int $insurancePayout, string $applyRate): int
    {
        $raw = bcmul((string)$insurancePayout, $applyRate, 10);
        $multiplied = bcmul($raw, '10', 0);
        $floorTenth = bcdiv($multiplied, '10', 1);
        return (int)$this->bcCeil($floorTenth);
    }

    private function calcBaseRate(string $baseRate, string $chattelPremium, string $comprehensivePremium): string
    {
        return bcadd(
            bcadd(
                bcadd($baseRate, (string)$this->occupationalSurchargeRate, 10),
                (string)$this->workSurchargeRate,
                10
            ),
            bcadd($chattelPremium, $comprehensivePremium, 10),
            10
        );
    }

    private function bcRound2(string $value): string
    {
        return bcadd($value, '0.005', 2);
    }

    private function bcCeil(string $value): string
    {
        $integer = (string)(int)$value;
        if (bccomp($value, $integer, 10) === 0) {
            return $integer;
        }

        return bcadd($integer, '1', 0);
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
