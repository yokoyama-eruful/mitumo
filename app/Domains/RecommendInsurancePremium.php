<?php

declare(strict_types=1);

namespace App\Domains;

final class RecommendInsurancePremium
{
    public readonly float $occupationalSurcharge;

    public readonly float $workSurcharge;

    public readonly float $baseRate;

    public readonly float $otherDiscount;

    public readonly array $recommendTermList;

    private array $longTermRateList;

    public function __construct($estimate)
    {
        $this->occupationalSurcharge = $estimate['occupational_surcharge'];
        $this->workSurcharge = $estimate['work_surcharge'];
        $this->baseRate = $estimate['base_rate'];
        $this->otherDiscount = $estimate['other_discount'];

        //共済期間
        //単年度の共済掛金
        $this->recommendTermList = [$estimate['recommend1'], $estimate['recommend2'], $estimate['recommend3']];
    }

    public function getTotalStandardPremium()
    {
        //建物共済掛金+家財共済掛金+設備共済掛金+商品共済掛金
    }

    public function calcStandardBuildingPremium()
    {
        //{ ( その他割引建物 * 実損払係数 ) * 長期率 * 短期率 } * 建物共済掛金　※1.短期率が適用されていない場合1をかける　※2.その他割引建物はcalcBuildingOtherDiscounts()
    }

    public function calcStandardChattelPremium()
    {
        //{ ( その他割引動産 * 実損払係数 ) * 長期率 * 短期率 } * 家財or設備共済掛金　※1.短期率が適用されていない場合1をかける　※2.その他割引動産はcalcChattelOtherDiscounts()
    }

    public function calcStandardItemPremium()
    {
        //{ ( その他割引動産 + 動産割増 ) * 長期率 * 短期率 } * 商品共済掛金　※1.短期率が適用されていない場合1をかける　※2.その他割引動産はcalcChattelOtherDiscounts()
    }

    private function calcBuildingOtherDiscounts()
    {
        //(基本率(建物)+職業割増+作業割増)*その他割引　※小数点第三位を四捨五入

    }

    private function calcChattelOtherDiscounts()
    {
        //(基本率(動産)+職業割増+作業割増)*その他割引　※小数点第三位を四捨五入

    }
}
