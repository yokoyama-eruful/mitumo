<?php

declare(strict_types=1);

namespace App\Domains;


use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

final class ComprehensivePremium
{
    public readonly string $propertyType;

    public readonly int $buildingAge;

    public readonly string $rank;

    private $startDate;

    private array $rateList;

    public function __construct($estimate)
    {
        $this->propertyType = $estimate['property_type'];
                
        if($estimate['building_age'] == ''){
            $this->buildingAge= 25;
        }else{
            $this->buildingAge = $estimate['building_age'];
        }

        $this->startDate = $estimate['start_date'] ?? '';

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
            if ($this->isMatched($data)&&$this->startDateFilter($data)) {
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

        $buildingAge = $this->buildingAge > 25 ? 25 : $this->buildingAge;

        return $data[0] === $this->propertyType &&
           (intval($data[2]) === $buildingAge || $data[2] === '') &&
            $data[3] === $this->rank;
    }

    private function startDateFilter($data): bool
    {
        if (empty($this->startDate)) {
            return true;
        }

        $target = Carbon::parse($this->startDate);

        return Carbon::parse($data[5])->lte($target);
    }
}
