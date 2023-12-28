<?php

declare(strict_types=1);

namespace App\Domains\Assess;

use Exception;
use Illuminate\Support\Facades\Storage;

final class StandardAmount
{
    private array $standardAmountList;

    public function __construct($assessUsage, $assessStructure)
    {
        $this->standardAmountList = $this->setStandardAmountList($assessUsage, $assessStructure);
    }

    private function setStandardAmountList($assessUsage, $assessStructure)
    {
        if (is_null($assessUsage) || is_null($assessStructure)) {
            return [];
        }

        $filePath = Storage::path('rateTable/standardAmountList.csv');

        $standardAmountList = [];

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

            if ($data[0] == $assessUsage && $data[2] == $assessStructure) {
                $standardAmountList[$data[1]] = [
                    '用途' => $data[0],
                    '評価' => $data[1],
                    '構造' => $data[2],
                    '標準単価' => $data[3],
                ];
            }
        }

        fclose($handle);

        return $standardAmountList;
    }

    public function findStandardAmount(string $assessRank)
    {
        if (empty($this->standardAmountList) || ! in_array($assessRank, ['A', 'B', 'C'])) {
            return 0;
        }

        return (int) $this->standardAmountList[$assessRank]['標準単価'];
    }
}
