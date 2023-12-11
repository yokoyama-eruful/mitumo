<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ComprehensiveDownloader
{
    public function dropFile(
        $comprehensiveDate,
        $calcData,
    ) {
        $filePath = storage_path('app/estimateTemplate/comprehensive.xlsx');

        $fileName = '総合火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setComprehensiveInfo($spreadSheet, $comprehensiveDate);

        $this->setCalcInfo($spreadSheet, $calcData);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setComprehensiveInfo($spreadSheet, $comprehensiveDate)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(総合)');
        // $worksheet->setCellValue('セル番号', $comprehensiveDate);
    }

    private function setCalcInfo($spreadSheet, $calcData)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
