<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class BuildingBaseRate
{
    public readonly float $baseRate;

    public readonly array $rateList;

    public function __construct($estimate)
    {
        $this->baseRate = $estimate->property_detail['base_rate'];
        $this->rateList = $this->makeRateList();
    }

    public function findRate($propertyType, $buildingAge, $rank)
    {
        if ($propertyType == '工場') {
            return $this->baseRate;
        }

        $buildingAge = $buildingAge > 25 ? 25 : $buildingAge;

        $result = array_filter($this->rateList, function ($item) use ($propertyType, $buildingAge, $rank) {
            return
             $item['物件'] === $propertyType
                && $item['築年数'] == $buildingAge
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
        $filePath = Storage::path('rateTable/buildingBaseRateTable.csv');

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
