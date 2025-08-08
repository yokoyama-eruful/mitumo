<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

final class BuildingBaseRate
{
    public readonly ?float $baseRate;

    public readonly array $rateList;

    public function __construct($estimate, $path)
    {
        $this->baseRate = $estimate->building_base_rate;
        $this->rateList = $this->makeRateList($path);
    }

    public function findRate($propertyType, $buildingAge, $rank,$startDate)
    {
        if ($propertyType == '工場') {
            return $this->baseRate;
        }

        if ($buildingAge == '') {
            $buildingAge = 25;
        }

        $buildingAge = $buildingAge > 25 ? 25 : $buildingAge;

        $result = array_filter($this->rateList, function ($item) use ($propertyType, $buildingAge, $rank) {
            return
             $item['物件'] === $propertyType
                && $item['築年数'] == $buildingAge
                && $item['級別'] === $rank;
        });

        if (empty($result)) {
            return null;
        }

        // 適用日が指定されている場合、最新の適用日を持つレコードを選択
        if (!empty($startDate)) {
            $result = array_filter($result, function ($item) use ($startDate) {
                return Carbon::parse($item['適用日'])->lte(Carbon::parse($startDate));
            });
        }

        // 適用日がない場合、最新の適用日を持つレコードを選択
        $target = Carbon::parse($startDate);

        // 1. 条件に合うデータだけを抽出
        $filtered = array_filter($result, function ($item) use ($target) {
            return Carbon::parse($item['適用日'])->lte($target);
        });

        // 2. 条件に合うデータがあるなら、最新日付のものを選ぶ
        $applicable = null;
        if (!empty($filtered)) {
            usort($filtered, function ($a, $b) {
                return strtotime($b['適用日']) <=> strtotime($a['適用日']);
            });
            $applicable = reset($filtered);
        }

        if (is_null($applicable)) {
            return;
        }

        return floatval($applicable['基本率']);
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
                '物件' => $data[0],
                '築年数' => $data[1],
                '級別' => $data[2],
                '基本率' => $data[3],
                '適用日' => $data[4],
            ];
        }

        fclose($handle);

        return $rateList;
    }
}
