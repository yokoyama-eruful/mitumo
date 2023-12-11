<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class OccupationalSurcharge
{
    public readonly array $rateList;

    public function __construct()
    {
        $this->rateList = $this->makeRateList();
    }

    public function findCode($code)
    {
        $result = array_filter($this->rateList, function ($item) use ($code) {
            return $item['標準コード'] === $code;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return;
        }

        return floatval($result['割増率']);
    }

    private function makeRateList()
    {
        $filePath = Storage::path('surchargeTable/occupationalSurchargeTable.csv');

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
                '大項目' => $data[0],
                '中項目' => $data[1],
                '小項目' => $data[2],
                '備考' => $data[3],
                '級別' => $data[4],
                '割増率' => $data[5],
                '標準コード' => $data[6],
            ];
        }

        fclose($handle);

        return $rateList;
    }
}
