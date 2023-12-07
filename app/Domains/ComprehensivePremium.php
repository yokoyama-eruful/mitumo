<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class ComprehensivePremium
{
    public readonly string $propertyType;

    public readonly int $buildingAge;

    public readonly string $rank;

    private array $rateList;

    public function __construct($estimate)
    {
        $this->propertyType = $estimate['property_type'];
        $this->buildingAge = $estimate['building_age'];
        $this->rank = $estimate['rank'];

        $this->rateList = $this->makeRateList();
    }

    //料率を取得する
    public function getRate(string $purpose): ?float
    {
        if (! in_array($purpose, array_keys($this->rateList))) {
            return null;
        }

        return floatval($this->rateList[$purpose]);
    }

    private function makeRateList()
    {
        $filePath = Storage::path('rateTable/comprehensivePremiumRateTable.csv');

        $matchedRows = [];

        $handle = fopen($filePath, 'r');
        if (! $handle) {
            return [];
        }

        while (($data = fgetcsv($handle, null, ',')) !== false) {
            if ($this->isMatched($data)) {
                $matchedRows[$data[1]] = $data[4];
            }
        }

        fclose($handle);

        return $matchedRows;
    }

    private function isMatched($data): bool
    {
        if (! isset($data[0]) && ! isset($data[2]) && ! isset($data[3])) {
            return false;
        }

        $building_age = $this->buildingAge > 25 ? 25 : $this->buildingAge;

        //物件が住宅の場合のみ築年数を考慮する。その他の物件は築年数は空文字
        return $data[0] === $this->propertyType &&
           (intval($data[2]) === $building_age || $data[2] === '') &&
            $data[3] === $this->rank;
    }
}
