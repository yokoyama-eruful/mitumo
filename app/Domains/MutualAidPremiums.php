<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class MutualAidPremiums
{
    private string $propertyType;

    private ?int $insuranceYear;

    private ?int $insuranceMonth;

    private ?int $insuranceDay;

    private float $shortTermRate;

    private float $longTerRate = 0;

    public function __construct($estimate)
    {
        $this->propertyType = $estimate->property_type;

        $this->insuranceYear = $estimate->insurance_year;
        $this->insuranceMonth = $estimate->insurance_month;
        $this->insuranceDay = $estimate->insurance_day;

        $this->shortTermRate = $this->getShortTermRate();
        $this->longTerRate = $this->getLongTermRate();
    }

    private function getShortTermRate()
    {

        if (isset($this->insuranceYear)) {
            return 1;
        }

        //短期率
        //もし共済期間_年が空なら↓,そうでなければ''
        //共済期間_月 が空 かつ 共済期間_日 が入力 かつ　その値が15以下なら"15日以内",そうでなければ1
        if (is_null($this->insuranceMonth) && isset($this->insuranceDay) && $this->insuranceDay > 15) {
            return 1;
        }

        if (isset($this->insuranceMonth)) {
            //上記以外なら( 共済期間_月 * 31 + 共済期間_日 ) / 31 小数以下切り上げ
            $month = ceil(($this->insuranceMonth * 31 + $this->insuranceDay) / 31);
            $shortTermRate = $this->findShortTermRate($month, '');
        } else {
            $shortTermRate = $this->findShortTermRate('', $this->insuranceDay);
        }

        return $shortTermRate;
    }

    private function findShortTermRate($month, $day)
    {
        $filePath = Storage::path('rateTable/baseRateTable.csv');

        $rateList = [];

        $handle = fopen($filePath, 'r');
        if (! $handle) {
            return [];
        }

        $isHeaderFlag = true;
        while (($data = fgetcsv($handle, null, ',')) !== false) {
            if ($isHeaderFlag) {
                $isHeaderFlag = false;

                continue;
            }

            $rateList[] = [
                '共済期間（月）' => $data[0],
                '共済期間（日）' => $data[1],
                'rate' => $data[2],
            ];
        }

        fclose($handle);

        $result = array_filter($rateList, function ($item) use ($month, $day) {
            return $item['共済期間（月）'] === $month && $item['共済期間（日）'] === $day;
        });

        return $result['rate'];
    }

    private function getLongTermRate()
    {
        //長期率
        //払込方法が一時払いかつ共済期間_年が入力されていたらCSVから読み取り,そうでなければ1
        return 0;
    }

    private function makeLongTermRateList()
    {
        $filePath = Storage::path('rateTable/baseRateTable.csv');

        $rateList = [];

        $handle = fopen($filePath, 'r');
        if (! $handle) {
            return [];
        }

        $isHeaderFlag = true;
        while (($data = fgetcsv($handle, null, ',')) !== false) {
            if ($isHeaderFlag) {
                $isHeaderFlag = false;

                continue;
            }

            $rateList[] = [
                '物件' => $data[0],
                '築年数' => $data[1],
                '級別' => $data[2],
                '基本率' => $data[3],
            ];
        }

        fclose($handle);

        return $rateList;
    }
}
