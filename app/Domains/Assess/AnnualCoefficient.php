<?php

declare(strict_types=1);

namespace App\Domains\Assess;

use Exception;
use Illuminate\Support\Facades\Storage;

final class AnnualCoefficient
{
    public float $annualCoefficient;

    public function __construct($rank, $buildingYear)
    {
        $this->annualCoefficient = $this->findAnnualCoefficientList($rank, $buildingYear);
    }

    public function findAnnualCoefficientList($rank, $buildingYear)
    {
        if (! $rank || ! $buildingYear) {
            return 0;
        }

        if ($buildingYear > 2024) {
            return 0;
        }

        if ($buildingYear < 1976) {
            return 0;
        }

        $filePath = Storage::path('rateTable/AnnualCoefficient.csv');

        $annualCoefficientList = [];

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

            if ($data[0] == $buildingYear) {
                $annualRate = match ($rank) {
                    'M' => $data[1],
                    'T' => $data[2],
                    'H' => $data[3],
                    '1級' => $data[4],
                    '2級' => $data[5],
                    '3級' => $data[6],
                };

                $annualCoefficientList = [
                    '年' => $data[0],
                    '級' => $rank,
                    '年次係数' => $annualRate,
                ];
            }

            // if ($data[0] == $buildingYear && $data[1] == $rank) {
            //     $annualCoefficientList = [
            //         '年' => $data[0],
            //         '級' => $data[1],
            //         '年次係数' => $data[2],
            //     ];
            // }
        }

        fclose($handle);

        return (float) $annualCoefficientList['年次係数'];
    }
}
