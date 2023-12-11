<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardOfEarthquakeDownloader
{
    public function dropFile(
        $standardOfEarthquakeDate,
        $calcData,
    ) {
        $filePath = storage_path('app/estimateTemplate/standardOfEarthquake.xlsx');

        $fileName = '地震おすすめ_普通火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardOfEarthquakeInfo($spreadSheet, $standardOfEarthquakeDate);

        $this->setCalcInfo($spreadSheet, $calcData);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardOfEarthquakeInfo($spreadSheet, $standardOfEarthquakeDate)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ地震(普通)');
        // $worksheet->setCellValue('セル番号', $standardOfEarthquakeDate);
    }

    private function setCalcInfo($spreadSheet, $calcData)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
