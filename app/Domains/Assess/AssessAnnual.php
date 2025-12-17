<?php

declare(strict_types=1);

namespace App\Domains\Assess;

use App\Utils\JapaneseEraConverter;

final class AssessAnnual
{
    public string $buildingYearEra;

    public readonly string $rank;

    public readonly float $floorArea;

    public readonly int $buildingAge;

    public readonly int $originalBuildYear;

    public readonly int $buildingYear;

    public readonly string $assessUsage;

    public readonly string $assessStructure;

    public $annualPrice;

    public $remarks;

    private AnnualCoefficient $annualCoefficient;

    private AgingDepreciation $agingDepreciation;

    public function __construct($estimate)
    {
        $this->buildingYearEra = $estimate->building_year_era;
        $this->rank = $estimate->rank ?? '';
        $this->floorArea = (float)$estimate->floor_area ? (float)$estimate->floor_area : 0;
        $this->buildingAge = (int)$estimate->building_age ? (int)$estimate->building_age : 0;
        $this->originalBuildYear = (int)$estimate->building_year ? (int)$estimate->building_year : 0;
        $this->buildingYear = $this->changeWarekiToSeireki();
        $this->assessUsage = $this->getStringValueByKey('assess_usage', $estimate->assess_detail);
        $this->assessStructure = $this->getStringValueByKey('assess_structure', $estimate->assess_detail);
        $this->remarks = $this->getStringValueByKey('remarks', $estimate->assess_detail);
        $this->annualPrice = $this->getIntValueByKey('annual_price', $estimate->assess_detail);
        $this->annualCoefficient = new AnnualCoefficient($this->rank, $this->buildingYear);
        $this->agingDepreciation = new AgingDepreciation($this->assessUsage, $this->assessStructure);
    }

    private function changeWarekiToSeireki()
    {
        $westernYear = JapaneseEraConverter::getJapaneseEraStartYear($this->buildingYearEra);
        $this->buildingYearEra = $this->buildingYearEra == '' ? '西暦' : $this->buildingYearEra;

        if ($this->buildingYearEra !== '西暦') {
            $buildingYear = $westernYear + $this->originalBuildYear - 1;
        } else {
            $buildingYear = $this->originalBuildYear;
        }

        return $buildingYear;
    }

    /*
      ** LiveFireで使用する評価額
      */
    public function viewReProcure($rank, $annualPrice, $buildingAge)
    {
        if ($rank == '' || $annualPrice == '' || $buildingAge == '') {
            return [
                '80' => '',
                '100' => '',
                '100まるめ' => '',
            ];
        }

        $annualCoefficient = new AnnualCoefficient($rank, $this->buildingYear);

        $annualCoefficient = (string) $annualCoefficient->annualCoefficient;

        $reProcure = bcmul($annualPrice, $annualCoefficient, 2);

        $reProcure80 = bcmul($reProcure, '0.8', 2);

        $rounded = bcmul(
            bcadd(
                bcdiv($reProcure, '1000', 0),
                bccomp(bcmod($reProcure, '1000'), '0') === 1 ? '1' : '0'
            ),
            '1000',
            0
        );

        return [
            '80' => thousandYenUnit((int)$reProcure80),
            '100' => thousandYenUnit((int)$reProcure),
            '100まるめ' => thousandYenUnit((int)$rounded),
        ];
    }

    /*
    ** LiveFireで使用する評価額
    */
    public function viewCurrentMarketPrice($assessUsage, $assessStructure, $rank, $annualPrice, $buildingAge, $assessRank = '')
    {
        $annualCoefficient = new AnnualCoefficient($rank, $this->buildingYear);
        $agingDepreciation = new AgingDepreciation($assessUsage, $assessStructure);

        if ($assessRank == '') {
            return [
                '通常' => '',
                'まるめ' => '',
            ];
        }
        $annualCoefficient = (string) $annualCoefficient->annualCoefficient;
        $reProcure = bcmul($annualPrice, $annualCoefficient, 2);

        $agingDepreciation = (string) $agingDepreciation->findAgingDepreciation($assessRank);

        $residualValue = bcmul($agingDepreciation, (string) $this->buildingAge, 17);

        if (bccomp($residualValue, '0.5', 17) >= 0) {
            $finalResidualValue = '0.5';
        } else {
            $finalResidualValue = bcsub('1', $residualValue, 17);
        }

        $marketRaw = bcmul($reProcure, $finalResidualValue, 2);

        $marketRounded = bcmul(
            bcadd(
                bcdiv($marketRaw, '1000', 0),
                bccomp(bcmod($marketRaw, '1000'), '0') === 1 ? '1' : '0'
            ),
            '1000',
            0
        );

        return [
            '通常' => thousandYenUnit((int)$marketRaw),
            'まるめ' => thousandYenUnit((int)$marketRounded),
        ];
    }

    public function getReProcure()
    {
        $annualCoefficient = (string)$this->annualCoefficient->annualCoefficient;
        $reProcure = bcmul((string)$this->annualPrice, $annualCoefficient, 2);

        $reProcure80  = bcmul($reProcure, '0.8', 2);
        $reProcure120 = bcmul($reProcure, '1.2', 2);

        $round = function (string $value): string {
            return bcmul(
                bcadd(
                    bcdiv($value, '1000', 0),
                    bccomp(bcmod($value, '1000'), '0') === 1 ? '1' : '0'
                ),
                '1000',
                0
            );
        };

        return [
            '年次係数' => $annualCoefficient,
            '80%' => $reProcure80,
            '80%まるめ' => $round($reProcure80),
            '100%' => $reProcure,
            '100%まるめ' => $round($reProcure),
            '120%' => $reProcure120,
        ];
    }

    public function getResidualValue($assessRank)
    {
        $agingDepreciation = (string)$this->agingDepreciation->findAgingDepreciation($assessRank);
        $residualValue = bcmul($agingDepreciation, (string)$this->buildingAge, 17);

        if (bccomp($residualValue, '0.5', 17) >= 0) {
            $finalResidualValue = '0.5';
        } else {
            $finalResidualValue = bcsub('1', $residualValue, 17);
        }

        return [
            '経年減価率' => $agingDepreciation,
            '残価率' => $residualValue,
            '残価率最終' => $finalResidualValue,
        ];
    }

    public function getCurrentMarketPrice($assessRank)
    {
        $reProcure = $this->getReProcure()['100%'];
        $residual  = $this->getResidualValue($assessRank)['残価率最終'];

        $marketRaw = bcmul($reProcure, $residual, 2);

        $marketRounded = bcmul(
            bcadd(
                bcdiv($marketRaw, '1000', 0),
                bccomp(bcmod($marketRaw, '1000'), '0') === 1 ? '1' : '0'
            ),
            '1000',
            0
        );

        return [
            '時価額' => $marketRaw,
            '時価額まるめ' => $marketRounded,
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
