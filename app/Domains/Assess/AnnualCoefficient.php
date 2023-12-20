<?php

declare(strict_types=1);

namespace App\Domains\Assess;

use Exception;
use Illuminate\Support\Facades\Storage;

final class AnnualCoefficient
{
    public float $annualCoefficient;

    public function __construct($rank, $startYear)
    {
        $this->annualCoefficient = $this->findAnnualCoefficientList($rank, $startYear);
    }

    public function findAnnualCoefficientList($rank, $startYear)
    {
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

            if ($data[0] == $startYear && $data[1] == $rank) {
                $annualCoefficientList = [
                    '年' => $data[0],
                    '級' => $data[1],
                    '年次係数' => $data[2],
                ];
            }
        }

        fclose($handle);

        return (float) $annualCoefficientList['年次係数'];
    }
}
