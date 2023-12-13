<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class ChattelBaseRate
{
    public readonly float $baseRate;

    public readonly array $rateList;

    public function __construct($estimate)
    {
        $this->baseRate = array_key_exists('base_rate', $estimate->property_detail) ? $estimate->property_detail['base_rate'] : 0;
        $this->rateList = $this->makeRateList();
    }

    public function findRate($propertyType, $rank)
    {
        if ($propertyType == '工場') {
            return $this->baseRate;
        }

        $result = array_filter($this->rateList, function ($item) use ($propertyType, $rank) {
            return
             $item['物件'] === $propertyType
                && $item['級別'] === $rank;
        });

        $result = ! empty($result) ? reset($result) : null;

        if (is_null($result)) {
            return;
        }

        return floatval($result['基本率']);
    }

    private function makeRateList()
    {
        $filePath = Storage::path('rateTable/chattelBaseTable.csv');

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
                '級別' => $data[1],
                '基本率' => $data[2],
            ];
        }

        fclose($handle);

        return $rateList;
    }
}
