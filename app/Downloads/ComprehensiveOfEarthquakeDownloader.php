<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ComprehensiveOfEarthquakeDownloader
{
    public function dropFile(
        $comprehensiveOfEarthquakeDate,
        $calcData,
    ) {
        $filePath = storage_path('app/estimateTemplate/comprehensiveOfEarthquake.xlsx');

        $fileName = '地震おすすめ_総合火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setComprehensiveOfEarthquakeInfo($spreadSheet, $comprehensiveOfEarthquakeDate);

        $this->setCalcInfo($spreadSheet, $calcData);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setComprehensiveOfEarthquakeInfo($spreadSheet, $comprehensiveOfEarthquakeDate)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(総合)');
        // $worksheet->setCellValue('セル番号', $comprehensiveOfEarthquakeDate);
    }

    private function setCalcInfo($spreadSheet, $calcData)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
