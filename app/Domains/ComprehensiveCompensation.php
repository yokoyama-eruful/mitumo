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
            '火災' => $this->isContractDecision() ? '×' : '○',
            '落雷' => $this->isContractDecision() ? '×' : '○',
            '破裂または爆発' => $this->isContractDecision() ? '×' : '○',
            '風災・雪災' => $this->isContractDecision() ? '×' : '○',
            '物体の落下・衝突' => $this->isContractDecision() ? '×' : '○',
            '騒じょう・労働争議' => $this->isContractDecision() ? '×' : '○',
            '水ぬれ' => $this->isContractDecision() ? '×' : '○',
            '盗難' => $this->isContractDecision() ? '×' : '○',
            '水害' => $this->isContractDecision() ? '×' : '○',
        ];
    }

    private function setPaymentOptions(): array
    {
        return [
            '残存物取り片付け費用' => $this->isContractDecision() ? '×' : '○',
            '失火見舞費用' => $this->isContractDecision() ? '×' : '○',
            '地震火災費用' => $this->isContractDecision() ? '×' : '○',
            '修理付帯費用' => in_array($this->propertyType, ['普通', '工場']) ? '○' : '×',
            '損害防止費用' => $this->isContractDecision() ? '×' : '○',
        ];
    }

    //総合火災契約の可不可条件（修理付帯費用以外）
    private function isContractDecision()
    {
        return ! ($this->propertyType == '工場' || $this->shortTermRate != 0);
    }
}
