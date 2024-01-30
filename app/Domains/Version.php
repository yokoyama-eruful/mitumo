<?php

declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Facades\Storage;

final class Version
{
    public readonly array $rateList;

    public function __construct()
    {
        $this->rateList = $this->makeRateList();
    }

    private function makeRateList()
    {
        $filePath = Storage::path('version/version.csv');

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
                'バージョン' => $data[0],
                '更新日時' => $data[1],
                '更新内容' => $data[2],
            ];
        }

        fclose($handle);

        return $rateList;
    }
}
