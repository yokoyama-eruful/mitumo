<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessNormal
{
    public readonly float $floorArea;

    public readonly int $buildingAge;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly string $remarks;

    private StandardAmount $standardAmount;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->floorArea = $this->getBuildArea($estimate);

        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->buildingYear = $estimate->building_year ? $estimate->building_year : 0;
        $this->assessUsage = $this->getValueByKey('assess_usage', $estimate->assess_detail);
        $this->assessStructure = $this->getValueByKey('assess_structure', $estimate->assess_detail);
        $this->remarks = $this->getValueByKey('remarks', $estimate->assess_detail);

        $this->standardAmount = new StandardAmount($this->assessUsage, $this->assessStructure);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    private function getBuildArea($estimate)
    {
        if ($estimate->assess_item == '通常(専有面積)') {
            return $estimate->exclusive_area ?? 0;
        }

        return $estimate->floor_area ?? 0;
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewReProcure(string $assessUsage, string $assessStructure, string $floorArea, string $assessRank = '')
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

        $standardAmount = new StandardAmount($assessUsage, $assessStructure);

        $amount = (string)$standardAmount->findStandardAmount($assessRank);
        $floorArea = (string)$floorArea;

        // 再調達原価（円）
        $reProcure = bcmul($amount, $floorArea, 2);

        $rate80 = bcmul($reProcure, '0.8', 2);

        $roundThousand = fn(string $v) =>
        bcmul(
            bcadd(
                bcdiv($v, '1000', 0),
                bccomp(bcmod($v, '1000'), '0') === 1 ? '1' : '0'
            ),
            '1000',
            0
        );

        return [
            '80' => thousandYenUnit($rate80),
            '100' => thousandYenUnit($reProcure),
            '100まるめ' => thousandYenUnit($roundThousand($reProcure)),
        ];
    }

    /*
    ** Livewireで使用する評価額
    */
    public function viewCurrentMarketPrice($assessUsage, $assessStructure, $floorArea, $buildingAge, $assessRank = '')
    {
        if (
            $assessRank == '' || $assessUsage == '' ||
            $assessStructure == '' || $floorArea == '' ||
            $buildingAge == ''
        ) {
            return [
                '通常' => '',
                'まるめ' => '',
            ];
        }

        $agingDepreciationObj = new AgingDepreciation($assessUsage, $assessStructure);
        $standardAmount = new StandardAmount($assessUsage, $assessStructure);

        // 全部 string
        $amount = (string)$standardAmount->findStandardAmount($assessRank);
        $floorArea = (string)$floorArea;
        $buildingAge = (string)$buildingAge;

        // 再調達原価（円）
        $reProcure = bcmul($amount, $floorArea, 2);

        // 経年減価率
        $agingDepreciation = (string)$agingDepreciationObj->findAgingDepreciation($assessRank);

        // 残価率合計
        $residualValue = bcmul($agingDepreciation, $buildingAge, 17);

        // 最終残価率
        if (bccomp($residualValue, '0.5', 17) >= 0) {
            $finalResidualValue = '0.5';
        } else {
            $finalResidualValue = bcsub('1', $residualValue, 17);
        }

        // 時価額（円）
        $marketPrice = bcmul($reProcure, $finalResidualValue, 2);

        // 千円切り上げ
        $rounded = bcmul(
            bcadd(
                bcdiv($marketPrice, '1000', 0),
                bccomp(bcmod($marketPrice, '1000'), '0') === 1 ? '1' : '0'
            ),
            '1000',
            0
        );

        return [
            '通常' => thousandYenUnit($marketPrice),
            'まるめ' => thousandYenUnit($rounded),
        ];
    }

    public function getReProcure(string $assessRank = ''): array
    {
        $standardAmount = (string)$this->standardAmount->findStandardAmount($assessRank);
        $floorArea = (string)$this->floorArea;

        // 再調達原価（円）
        $reProcure = bcmul($standardAmount, $floorArea, 2);

        // 割合計算
        $rate80  = bcmul($reProcure, '0.8', 2);
        $rate120 = bcmul($reProcure, '1.2', 2);
        // 千円単位切り上げ
        $roundThousand = function (string $v): string {
            return bcmul(
                bcadd(
                    bcdiv($v, '1000', 0),
                    bccomp(bcmod($v, '1000'), '0') === 1 ? '1' : '0'
                ),
                '1000',
                0
            );
        };

        return [
            '標準単価'   => $standardAmount,
            '80%'       => $rate80,
            '80%まるめ' => $roundThousand($rate80),
            '100%'      => $reProcure,
            '100%まるめ' => $roundThousand($reProcure),
            '120%'      => $rate120,
        ];
    }


    public function getResidualValue(string $assessRank = ''): array
    {
        $agingDepreciation = (string)$this->agingDepreciation->findAgingDepreciation($assessRank);
        $buildingAge = (string)$this->buildingAge;

        // 残価率（高精度）
        $residualValue = bcmul($agingDepreciation, $buildingAge, 17);

        if (bccomp($residualValue, '0.5', 17) >= 0) {
            $finalResidualValue = '0.5';
        } else {
            $finalResidualValue = bcsub('1', $residualValue, 17);
        }

        return [
            '経年減価率' => $agingDepreciation,
            '残価率'     => $residualValue,
            '残価率最終' => $finalResidualValue,
        ];
    }


    public function getCurrentMarketPrice(string $assessRank = ''): array
    {
        $reProcure = $this->getReProcure($assessRank)['100%'];
        $residualRate = $this->getResidualValue($assessRank)['残価率最終'];

        // 時価額（円）
        $currentMarketPrice = bcmul($reProcure, $residualRate, 0);

        // 千円切り上げ
        $rounded = bcmul(
            bcadd(
                bcdiv($currentMarketPrice, '1000', 0),
                bccomp(bcmod($currentMarketPrice, '1000'), '0') === 1 ? '1' : '0'
            ),
            '1000',
            0
        );

        return [
            '時価額'       => $currentMarketPrice,
            '時価額まるめ' => $rounded,
        ];
    }


    private function getValueByKey(string $key, ?array $array, string $defaultValue = ''): string
    {
        if (is_null($array)) {
            return $defaultValue;
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return (string) $array[$key];
        }

        return $defaultValue;
    }
}
