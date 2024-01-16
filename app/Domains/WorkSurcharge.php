<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class WorkSurcharge
{
    public readonly array $rateList;

    public function __construct($path)
    {
        $this->rateList = $this->makeRateList($path);
    }

    public function findCode($code, $rank)
    {
        $result = array_filter($this->rateList, function ($item) use ($code, $rank) {
            return $item['作業工程コード'] === $code && $item['級別'] === $rank;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return;
        }

        return floatval($result['割増率']);
    }

    public function getWorkSurchargeItem($code, $rank)
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

    private function makeRateList($path)
    {
        $filePath = Storage::path($path);

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
                '作業工程コード' => $data[4],
                '級別' => $data[5],
                '割増率' => $data[6],
            ];
        }

        fclose($handle);

        return $rateList;
    }
}
