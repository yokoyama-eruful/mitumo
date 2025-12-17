<?php

declare(strict_types=1);

namespace App\Domains\Assess;

final class AssessSolarPanel
{
    public readonly string $floorArea;

    public readonly int $buildingAge;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public readonly string $installationDate;

    public readonly string $installationCost;

    public readonly string $remarks;

    private StandardAmount $standardAmount;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->floorArea = (string) ($estimate->floor_area ?? '0');
        $this->buildingAge = $estimate->building_age ? $estimate->building_age : 0;
        $this->buildingYear = $estimate->building_year ? $estimate->building_year : 0;
        $this->assessUsage = $this->getStringValueByKey('assess_usage', $estimate->assess_detail);
        $this->assessStructure = $this->getStringValueByKey('assess_structure', $estimate->assess_detail);
        $this->installationDate = $this->getStringValueByKey('installation_date', $estimate->assess_detail);
        $this->installationCost = (string) ($this->getIntValueByKey('installation_cost', $estimate->assess_detail) ?? '0');
        $this->remarks = $this->getStringValueByKey('remarks', $estimate->assess_detail);

        $this->standardAmount = new StandardAmount($this->assessUsage, $this->assessStructure);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    /*
        ** LiveFireで使用する評価額
        */
    public function viewReProcure($assessUsage, $assessStructure, $floorArea, $assessRank = '')
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
        $amount = (string) $standardAmount->findStandardAmount($assessRank);

        // 再調達原価
        $reProcure = bcmul($amount, (string) $floorArea, 2);

        // 80%
        $rate80 = bcmul($reProcure, '0.8', 2);

        return [
            '80' => thousandYenUnit($rate80),
            '100' => thousandYenUnit($reProcure),
            '100まるめ' => thousandYenUnit(
                bcmul(
                    bcadd(
                        bcdiv($reProcure, '1000', 0),
                        '1',
                        0
                    ),
                    '1000',
                    0
                )
            ),
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

        $standardAmount = new StandardAmount($assessUsage, $assessStructure);
        $agingDepreciation = new AgingDepreciation($assessUsage, $assessStructure);

        // 標準単価
        $amount = (string) $standardAmount->findStandardAmount($assessRank);

        // 再調達原価
        $reProcure = bcmul($amount, (string) $floorArea, 2);

        // 経年減価率（年あたり）
        $agingRate = (string) $agingDepreciation->findAgingDepreciation($assessRank);

        // 累計減価率 = 年数 × 率
        $residualLoss = bcmul($agingRate, (string) $buildingAge, 6);

        // 上限 0.5
        if (bccomp($residualLoss, '0.5', 6) === 1) {
            $finalResidualValue = '0.5';
        } else {
            $finalResidualValue = bcsub('1', $residualLoss, 6);
        }

        // 時価額
        $marketPrice = bcmul($reProcure, $finalResidualValue, 2);

        return [
            '通常' => thousandYenUnit($marketPrice),
            'まるめ' => thousandYenUnit(
                bcmul(
                    bcadd(
                        bcdiv($marketPrice, '1000', 0),
                        '1',
                        0
                    ),
                    '1000',
                    0
                )
            ),
        ];
    }

    public function getSolarPanelInfo()
    {
        $agingDepreciation = '0.1';

        // 経過年数
        $progressYear = (string) now()->diffInYears($this->installationDate);

        // 残価率（年数 × 年率）
        $residualValue = bcmul($progressYear, $agingDepreciation, 6);

        // 上限 0.5
        if (bccomp($residualValue, '0.5', 6) === 1) {
            $finalResidualValue = '0.5';
        } else {
            $finalResidualValue = bcsub('1', $residualValue, 6);
        }

        // 時価額
        $marketPrice = bcmul(
            (string) $this->installationCost,
            $finalResidualValue,
            0
        );

        return [
            '経過年数' => (int) $progressYear,
            '経年減価率' => (float) $agingDepreciation,
            '残価率' => (float) $residualValue,
            '残価率最終' => (float) $finalResidualValue,
            '時価額' => $marketPrice,
        ];
    }

    public function getReProcure($assessRank = '')
    {
        $standardAmount = (string) $this->standardAmount->findStandardAmount($assessRank);
        $floorArea = (string) $this->floorArea;

        // 再調達原価（小数2桁）
        $reProcure = bcmul($standardAmount, $floorArea, 2);

        // 80%
        $rate80 = bcmul($reProcure, '0.8', 2);

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

        $base80 = $roundThousand($rate80);
        $base100 = $roundThousand($reProcure);
        $install = (string) $this->installationCost;

        return [
            '標準単価' => $standardAmount,

            '80%' => $rate80,
            '80%まるめ' => $base80,
            '太陽光付き80%まるめ' => bcadd($base80, $install, 0),

            '100%' => $reProcure,
            '100%まるめ' => $base100,
            '太陽光付き100%まるめ' => bcadd($base100, $install, 0),

            '120%' => bcmul($reProcure, '1.2', 2),
        ];
    }

    public function getResidualValue($assessRank = '')
    {
        $aging = (string) $this->agingDepreciation->findAgingDepreciation($assessRank);
        $age = (string) $this->buildingAge;

        // 経年減価
        $residualValue = bcmul($aging, $age, 2);

        // 上限0.5
        $finalResidualValue = bccomp($residualValue, '0.5', 2) === 1
            ? '0.5'
            : bcsub('1', $residualValue, 2);

        return [
            '経年減価率' => $aging,
            '残価率' => $residualValue,
            '残価率最終' => $finalResidualValue,
        ];
    }


    public function getCurrentMarketPrice($assessRank = '')
    {
        $reProcure = (string) $this->getReProcure($assessRank)['100%'];
        $residual = (string) $this->getResidualValue($assessRank)['残価率最終'];

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

        $rounded = $roundThousand($currentMarketPrice);
        $solar = (string) $this->getSolarPanelInfo()['時価額'];

        return [
            '時価額' => bcadd($currentMarketPrice, '0', 0),
            '時価額まるめ' => $rounded,
            '太陽光付まるめ' => bcadd($rounded, $solar, 0),
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

    private function getIntValueByKey(string $key, ?array $array, int $defaultValue = 0): int
    {
        if (is_null($array)) {
            return $defaultValue;
        }

        if (array_key_exists($key, $array) && $array[$key]) {
            return (int) $array[$key];
        }

        return $defaultValue;
    }
}
