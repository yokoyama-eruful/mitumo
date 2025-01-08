<?php

declare(strict_types=1);

namespace App\Domains;

final class ComprehensiveCompensation
{
    public readonly string $propertyType;

    public readonly float $shortTermRate;

    public readonly array $coverageOptions;

    public readonly array $paymentOptions;

    public function __construct($propertyType, $shortTermRate)
    {
        $this->propertyType = $propertyType;
        $this->shortTermRate = $shortTermRate;

        $this->coverageOptions = $this->setCoverageOptions();

        $this->paymentOptions = $this->setPaymentOptions();
    }

    private function setCoverageOptions(): array
    {
        return [
            '火災' => '○',
            '落雷' => '○',
            '破裂または爆発' => '○',
            '風災・雪災' => '○',
            '物体の落下・衝突' => '○',
            '騒じょう・労働争議' => '○',
            '水ぬれ' => '○',
            '盗難' => '○',
            '水害' => '○',
        ];
    }

    private function setPaymentOptions(): array
    {
        return [
            '残存物取り片付け費用' => '○',
            '失火見舞費用' => '○',
            '地震火災費用' => '○',
            '修理付帯費用' => in_array($this->propertyType, ['普通', '工場']) ? '○' : '×',
            '損害防止費用' => '○',
        ];
    }
}
