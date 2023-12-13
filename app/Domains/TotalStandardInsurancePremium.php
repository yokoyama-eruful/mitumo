<?php

declare(strict_types=1);

namespace App\Domains;

use App\Models\Estimate;

final class StandardInsurancePremium
{
    public readonly Estimate $estimate;

    public readonly string $target;

    public readonly string $payMethod;

    public readonly array $building;

    public readonly array $houseHold;

    public readonly array $facility;

    public readonly array $item;

    public readonly int $standard1;

    public readonly int $standard2;

    public readonly int $standardDiv;

    public readonly bool $payByLongTermFlag;

    public readonly int $standardLongTerm;

    public function __construct($estimate, $target)
    {
        // $target =  建物　家財　設備　商品
        $this->estimate = $estimate;
        $this->target = $target;
        $this->payMethod = $estimate->pay_method;

        $building = $this->calcBuilding();
        $houseHold = $this->calcHouseHold();
        $facility = $this->calcFacility();
        $item = $this->calcItem();

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
        return $this->building['掛金'] + $this->houseHold['掛金'] + $this->facility['掛金'] + $this->item['掛金'];
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
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                $baseRate = new BuildingBaseRate($estimate);
                $buildingBaseRate = $baseRate->baseRate;
            } else {
                $buildingBaseRate = $estimate->base_rate;
            }

            $occupationalSurchargeRate = $estimate->property_detail['occupational_surcharge_rate'];
            $workSurchargeRate = $estimate->property_detail['work_surcharge_rate'];
            $otherDiscount = $estimate->property_detail['other_discount'];

            return ($buildingBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount;
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate);

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = ($otherDiscount * $estimate->property_detail['actual_loss_factor']) * $longTermRate * $shortTermRate;

        //掛け金
        $premium = $estimate->property_detail['building_insurance_payout'] * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
    }

    private function calcHouseHold()
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                $baseRate = new ChattelBaseRate($estimate);
                $chattelBaseRate = $baseRate->baseRate;
            } else {
                $chattelBaseRate = $estimate->base_rate;
            }

            $occupationalSurchargeRate = $estimate->property_detail['occupational_surcharge_rate'];
            $workSurchargeRate = $estimate->property_detail['work_surcharge_rate'];
            $otherDiscount = $estimate->property_detail['other_discount'];

            return ($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount;
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate);

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = $otherDiscount * $longTermRate * $shortTermRate;

        //掛け金
        $premium = $estimate->property_detail['household_insurance_payout'] * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
    }

    private function calcFacility()
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                $baseRate = new ChattelBaseRate($estimate);
                $chattelBaseRate = $baseRate->baseRate;
            } else {
                $chattelBaseRate = $estimate->base_rate;
            }

            $occupationalSurchargeRate = $estimate->property_detail['occupational_surcharge_rate'];
            $workSurchargeRate = $estimate->property_detail['work_surcharge_rate'];
            $otherDiscount = $estimate->property_detail['other_discount'];

            return ($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount;
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate);

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = ($otherDiscount * $estimate->property_detail['actual_loss_factor']) * $longTermRate * $shortTermRate;

        //掛け金
        $premium = ($estimate->property_detail['facility_insurance_payout'] + $estimate->property_detail['other_facility_insurance_payout']) * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
    }

    private function calcItem()
    {
        $estimate = $this->estimate;
        //その他割引

        $calcOtherDiscount = function ($estimate) {
            $propertyType = $estimate->property_type;

            if (in_array($propertyType, ['住宅', '普通'], true)) {
                $baseRate = new ChattelBaseRate($estimate);
                $chattelBaseRate = $baseRate->baseRate;
            } else {
                $chattelBaseRate = $estimate->base_rate;
            }

            $occupationalSurchargeRate = $estimate->property_detail['occupational_surcharge_rate'];
            $workSurchargeRate = $estimate->property_detail['work_surcharge_rate'];
            $otherDiscount = $estimate->property_detail['other_discount'];

            return ($chattelBaseRate + $occupationalSurchargeRate + $workSurchargeRate) * $otherDiscount;
        };

        $otherDiscount = $calcOtherDiscount($estimate);

        ///適用率
        $mutualAidPremium = new MutualAidPremiums($estimate);

        $shortTermRate = $mutualAidPremium->shortTermRate;
        $longTermRate = $mutualAidPremium->longTerRate;
        $applyRate = ($otherDiscount + 動産割増) * $longTermRate * $shortTermRate;

        //掛け金
        $premium = ($estimate->property_detail['item_insurance_payout'] + $estimate->property_detail['other_item_insurance_payout']) * $applyRate;

        return [
            'その他割引' => $otherDiscount,
            '適用率' => $applyRate,
            '掛金' => $premium,
        ];
    }

    /**
     * 配列から安全に中身を取得するUtils関数
     */
    private function getValueByKey(string $key, array $array): int
    {
        if (array_key_exists($key, $array) && $array[$key]) {
            return intval($array[$key]);
        }

        return 0;
    }
}
