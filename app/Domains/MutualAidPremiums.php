<?php

declare(strict_types=1);

namespace App\Domains;

use Exception;
use Illuminate\Support\Facades\Storage;

final class MutualAidPremiums
{
    public string $propertyType;

    public string $payMethod;

    public int $insuranceYear;

    public int $insuranceMonth;

    public int $insuranceDay;

    public float $shortTermRate;

    public float $longTerRate;

    public function __construct($estimate, $insuranceYear)
    {
        $this->propertyType = $estimate->property_type;
        $this->payMethod = $estimate->pay_method;

        $this->insuranceYear = (int) $insuranceYear;
        $this->insuranceMonth = $estimate->insurance_month ?? 0;
        $this->insuranceDay = $estimate->insurance_day ?? 0;

        $this->shortTermRate = $this->getShortTermRate();
        $this->longTerRate = $this->getLongTermRate();
    }

    private function getShortTermRate()
    {

        if ($this->insuranceYear > 0 && isset($this->insuranceYear)) {
            return 0;
        }

        //短期率
        //もし共済期間_年が空なら↓,そうでなければ''
        //共済期間_月 が空 かつ 共済期間_日 が入力 かつ　その値が15以下なら"15日以内",そうでなければ1
        if ((is_null($this->insuranceMonth) || $this->insuranceMonth == 0) &&
         (isset($this->insuranceDay) && $this->insuranceDay > 0) &&
          $this->insuranceDay <= 15) {

            return $this->findShortTermRate('', $this->insuranceDay);
        }

        if ((is_null($this->insuranceMonth) || $this->insuranceMonth == 0) &&
        (isset($this->insuranceDay) || $this->insuranceDay > 0) &&
         $this->insuranceDay >= 15) {

            return $shortTermRate = $this->findShortTermRate(1, '');
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
        $filePath = Storage::path('rateTable/shortTermRateTable.csv');

        $rateList = [];

        try {
            $handle = fopen($filePath, 'r');
        } catch (Exception) {
            throw new Exception('csvが見つかりません。');
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
            return $item['共済期間（月）'] == $month && $item['共済期間（日）'] == $day;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return;
        }

        return (float) $result['rate'];
    }

    private function getLongTermRate()
    {
        //長期率
        //払込方法が一時払いかつ共済期間_年が入力されていたらCSVから読み取り,そうでなければ1
        if ($this->payMethod == '一時払' && $this->insuranceYear) {
            return $this->findLongTermRate($this->insuranceYear);
        }

        return 1;
    }

    private function findLongTermRate($insuranceYear)
    {
        $filePath = Storage::path('rateTable/longTermRateTable.csv');

        $rateList = [];

        try {
            $handle = fopen($filePath, 'r');
        } catch (Exception) {
            throw new Exception('csvが見つかりません。');
        }

        $isHeaderFlag = true;
        while (($data = fgetcsv($handle, null, ',')) !== false) {
            if ($isHeaderFlag) {
                $isHeaderFlag = false;

                continue;
            }

            $rateList[] = [
                '共済期間（年）' => $data[0],
                'rate' => $data[1],
            ];
        }

        fclose($handle);

        $result = array_filter($rateList, function ($item) use ($insuranceYear) {
            return $item['共済期間（年）'] == $insuranceYear;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return;
        }

        return (float) $result['rate'];
    }
}
