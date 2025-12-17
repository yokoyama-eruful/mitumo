<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessPiloti
{
    public readonly float $floorArea;

    public readonly float $pilotiFloorArea;

    public readonly float $exceptForPilotiFloorArea;

    public readonly int $buildingAge;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly string $remarks;

    private StandardAmount $standardAmount;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->floorArea = $estimate->floor_area ? $estimate->floor_area : 0;
        $this->pilotiFloorArea = $this->getFloatValueByKey('piloti_floor_area', $estimate->assess_detail);
        $this->exceptForPilotiFloorArea = $this->floorArea - $this->pilotiFloorArea;
        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->buildingYear = $estimate->building_year ? $estimate->building_year : 0;
        $this->assessUsage = $this->getStringValueByKey('assess_usage', $estimate->assess_detail);
        $this->assessStructure = $this->getStringValueByKey('assess_structure', $estimate->assess_detail);
        $this->remarks = $this->getStringValueByKey('remarks', $estimate->assess_detail);

        $this->standardAmount = new StandardAmount($this->assessUsage, $this->assessStructure);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    private function roundUpThousand(string $value): string
    {
        return bcmul(
            bcadd(
                bcdiv($value, '1000', 0),
                bccomp(bcmod($value, '1000'), '0') === 1 ? '1' : '0'
            ),
            '1000',
            0
        );
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewReProcure($assessUsage, $assessStructure, $floorArea, $pilotiFloorArea, $assessRank = '')
    {
        if (
            $assessRank == '' || $assessUsage == '' ||
            $assessStructure == '' || $floorArea == ''
        ) {
            return [
                '80' => '',
                '100' => '',
                '100まるめ' => '',
            ];
        }

        $standardAmountObj = new StandardAmount($assessUsage, $assessStructure);
        $standardAmount = (string)$standardAmountObj->findStandardAmount($assessRank);

        // ピロティー以外面積
        $otherArea = bcsub($floorArea, $pilotiFloorArea, 10);

        // 金額
        $pilotiOther = bcmul($standardAmount, $otherArea, 2);
        $pilotiOnly  = bcmul('60', $pilotiFloorArea, 2);

        $reProcure = bcadd($pilotiOther, $pilotiOnly, 2);

        $rate80 = bcmul($reProcure, '0.8', 2);

        return [
            '80'       => thousandYenUnit($rate80),
            '100'      => thousandYenUnit($reProcure),
            '100まるめ' => thousandYenUnit($this->roundUpThousand($reProcure)),
        ];
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewCurrentMarketPrice($assessUsage, $assessStructure, $floorArea, $pilotiFloorArea, $buildingAge, $assessRank = '')
    {
        if (
            $assessRank == '' || $assessUsage == '' ||
            $assessStructure == '' || $floorArea == '' ||
            $pilotiFloorArea == '' || $buildingAge == ''
        ) {
            return [
                '通常' => '',
                'まるめ' => '',
            ];
        }

        $standardAmountObj = new StandardAmount($assessUsage, $assessStructure);
        $agingDepObj = new AgingDepreciation($assessUsage, $assessStructure);

        $standardAmount = (string)$standardAmountObj->findStandardAmount($assessRank);
        $agingRate = (string)$agingDepObj->findAgingDepreciation($assessRank);

        // 再調達原価
        $otherArea = bcsub($floorArea, $pilotiFloorArea, 10);
        $reProcure = bcadd(
            bcmul($standardAmount, $otherArea, 0),
            bcmul('60', $pilotiFloorArea, 0),
            0
        );

        // 残価率
        $residual = bcmul($agingRate, $buildingAge, 17);

        if (bccomp($residual, '0.5', 17) >= 0) {
            $finalRate = '0.5';
        } else {
            $finalRate = bcsub('1', $residual, 17);
        }

        // 時価額
        $marketPrice = bcmul($reProcure, $finalRate, 2);

        return [
            '通常'   => thousandYenUnit($marketPrice),
            'まるめ' => thousandYenUnit($this->roundUpThousand($marketPrice)),
        ];
    }

    public function getExceptForPiloti($assessRank)
    {
        $standardAmount = (string)$this->standardAmount->findStandardAmount($assessRank);

        $reProcure = bcmul($standardAmount, (string)$this->exceptForPilotiFloorArea, 2);

        $rate80  = bcmul($reProcure, '0.8', 2);
        $rate120 = bcmul($reProcure, '1.2', 2);

        return [
            '標準単価' => $standardAmount,
            'ピロティー以外80%' => $rate80,
            'ピロティー以外80%まるめ' => $this->roundUpThousand($rate80),
            'ピロティー以外100%' => $reProcure,
            'ピロティー以外100%まるめ' => $this->roundUpThousand($reProcure),
            'ピロティー以外120%' => $rate120,
        ];
    }

    public function getPiloti()
    {
        $standardAmount = '60';

        $reProcure = bcmul($standardAmount, (string)$this->pilotiFloorArea, 2);

        $rate80 = bcmul($reProcure, '0.8', 2);

        return [
            '標準単価' => $standardAmount,
            'ピロティー80%' => $rate80,
            'ピロティー80%まるめ' => $this->roundUpThousand($rate80),
            'ピロティー100%' => $reProcure,
            'ピロティー100%まるめ' => $this->roundUpThousand($reProcure),
        ];
    }

    public function getAllFloorAreaReProcure($assessRank)
    {
        $except = $this->getExceptForPiloti($assessRank)['ピロティー以外100%'];
        $piloti = $this->getPiloti()['ピロティー100%'];

        $reProcure = bcadd($except, $piloti, 2);

        $rate80 = bcmul($reProcure, '0.8', 2);

        return [
            '80%' => $rate80,
            '80%まるめ' => $this->roundUpThousand($rate80),
            '100%' => $reProcure,
            '100%まるめ' => $this->roundUpThousand($reProcure),
        ];
    }

    public function getResidualValue($assessRank)
    {
        $agingDepreciation = $this->agingDepreciation->findAgingDepreciation($assessRank);
        $residualValue = $agingDepreciation * $this->buildingAge;

        if ($residualValue >= 0.5) {
            $finalResidualValue = 0.5;
        } else {
            $finalResidualValue = 1 - $residualValue;
        }

        return [
            '経年減価率' => $agingDepreciation,
            '残価率' => $residualValue,
            '残価率最終' => $finalResidualValue,
        ];
    }

    public function getCurrentMarketPrice($assessRank)
    {
        // 文字列で取得（bc用）
        $except = (string) $this->getExceptForPiloti($assessRank)['ピロティー以外100%'];
        $piloti = (string) $this->getPiloti()['ピロティー100%'];
        $residual = (string) $this->getResidualValue($assessRank)['残価率最終'];

        // 再調達原価合計（小数2桁）
        $reProcure = bcadd($except, $piloti, 2);

        // 時価額（小数2桁）
        $currentMarketPrice = bcmul($reProcure, $residual, 2);

        // 1000円単位切り上げ
        $roundThousand = fn(string $v): string =>
        bcmul(
            bcadd(
                bcdiv($v, '1000', 0),
                bccomp(bcmod($v, '1000'), '0') === 1 ? '1' : '0'
            ),
            '1000',
            0
        );

        return [
            '時価額' => bcadd($currentMarketPrice, '0', 0), // 表示用（整数）
            '時価額まるめ' => $roundThousand($currentMarketPrice),
        ];
    }


    private function getStringValueByKey(string $key, ?array $array, string $defaultValue = ''): string
    {
        if (is_null($array)) {
            return $defaultValue;
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return (string) $array[$key];
        }

        return $defaultValue;
    }

    private function getFloatValueByKey(string $key, ?array $array, float $defaultValue = 0): float
    {
        if (is_null($array)) {
            return $defaultValue;
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return (float) $array[$key];
        }

        return $defaultValue;
    }
}
