<?php

declare(strict_types=1);

namespace App\Domains\Assess;

use Exception;
use Illuminate\Support\Facades\Storage;

final class AgingDepreciation
{
    public readonly array $agingDepreciationList;

    public function __construct($assessUsage, $assessStructure)
    {
        $this->agingDepreciationList = $this->setAgingDepreciation($assessUsage, $assessStructure);
    }

    private function setAgingDepreciation($assessUsage, $assessStructure)
    {
        if (is_null($assessUsage) || is_null($assessStructure)) {
            return [];
        }

        $filePath = Storage::path('rateTable/AgingDepreciationList.csv');

        $agingDepreciationList = [];

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
                $agingDepreciationList[$data[1]] = [
                    '用途' => $data[0],
                    '評価' => $data[1],
                    '構造' => $data[2],
                    '経年減価率' => $data[3],
                ];
            }
        }

        fclose($handle);

        return $agingDepreciationList;
    }

    public function findAgingDepreciation(string $assessRank)
    {
        if (empty($this->agingDepreciationList) || ! in_array($assessRank, ['A', 'B', 'C'])) {
            return 0;
        }

        return (float) $this->agingDepreciationList[$assessRank]['経年減価率'];
    }
}
