<?php

declare(strict_types=1);

namespace App\Domains;

final class OtherDiscounts
{
    public readonly string $propertyType;

    public readonly float $occupationalSurcharge;

    public readonly float $workSurcharge;

    public readonly float $baseRate;

    public function __construct($estimate)
    {
        $this->propertyType = $estimate['property_type'];
        $this->occupationalSurcharge = $estimate['occupational_surcharge'];
        $this->workSurcharge = $estimate['work_surcharge'];
        $this->baseRate = $estimate['base_rate'];
    }

    public function calcOtherDiscounts()
    {
        //(基本率+職業割増+作業割増)*その他割引　※小数点第三位を四捨五入

    }
}
