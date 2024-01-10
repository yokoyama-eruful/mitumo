<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class FactoryPropertyBasePremium
{
    public readonly array $rateList;

    public function __construct()
    {
        $this->rateList = $this->makeRateList();
    }

    public function findCode($code, $rank)
    {
        $result = array_filter($this->rateList, function ($item) use ($code, $rank) {
            return $item['作業工程コード'] === $code && $item['級別'] === $rank;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return [
                '基本率' => '',
                '動産割増' => '',
            ];
        }

        return [
            '基本率' => floatval($result['基本率']),
            '動産割増' => floatval($result['動産割増']),
        ];
    }

    public function getFactoryPropertyBasePremiumItem($code, $rank)
    {
        $result = array_filter($this->rateList, function ($item) use ($code, $rank) {
            return $item['作業工程コード'] === $code && $item['級別'] === $rank;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return;
        }

        return $result['中項目'].' '.$result['小項目'];
    }

    private function makeRateList()
    {
        $filePath = Storage::path('factoryTable/FactoryTable.csv');

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
                '項目' => $data[3],
                '級別' => $data[4],
                '基本率' => $data[5],
                '動産割増' => $data[6],
                '作業工程コード' => $data[7],
            ];
        }

        fclose($handle);

        return $rateList;
    }
}
