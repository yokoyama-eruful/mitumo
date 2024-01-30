<?php

declare(strict_types=1);

namespace App\Domains;

final class Futsu2Compensation
{
    public readonly string $propertyType;

    public readonly array $coverageOptions;

    public readonly array $paymentOptions;

    public function __construct($propertyType)
    {
        $this->propertyType = $propertyType;
        $this->coverageOptions = $this->setCoverageOptions();
    }

    private function setCoverageOptions(): array
    {

        return [
            '火災' => '○',
            '落雷' => '○',
            '破裂または爆発' => '○',
            '風災・雪災' => '-',
            '物体の落下・衝突' => '-',
            '騒じょう・労働争議' => '-',
            '水ぬれ' => '-',
            '盗難' => '-',
            '水害' => '-',
        ];
    }
}
