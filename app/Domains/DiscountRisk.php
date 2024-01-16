<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class DiscountRisk
{
    public readonly array $rateList;

    public function __construct()
    {
        $this->rateList = $this->makeRateList();
    }

    public function findCode($code)
    {
        $result = array_filter($this->rateList, function ($item) use ($code) {
            return $item['項目番号'] == $code;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return;
        }

        return floatval($result['割引率']);
    }

    public function getDiscountRiskItem($code)
    {
        $result = array_filter($this->rateList, function ($item) use ($code) {
            return $item['項目番号'] === $code;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return;
        }

        return $result['大分類'].' '.$result['小分類'];
    }

    private function makeRateList()
    {
        $filePath = Storage::path('rateTable/riskRateTable.csv');

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
                '項目番号' => $data[0],
                '大分類' => $data[1],
                '小分類' => $data[2],
                '割引率' => $data[3],
            ];
        }

        fclose($handle);

        return $rateList;
    }
}
