<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardAndComprehensiveDownloader
{
    public function dropFile(
        $standardAndComprehensiveDate,
        $calcData,
    ) {
        $filePath = storage_path('app/estimateTemplate/standardAndComprehensive.xlsx');

        $fileName = '普通_総合_火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardAndComprehensiveInfo($spreadSheet, $standardAndComprehensiveDate);

        $this->setCalcInfo($spreadSheet, $calcData);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardAndComprehensiveInfo($spreadSheet, $standardAndComprehensiveDate)
    {
        $worksheet = $spreadSheet->getSheetByName('普通&総合');
        $worksheet->setCellValue('セル番号', $standardAndComprehensiveDate);
    }

    private function setCalcInfo($spreadSheet, $calcData)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        $worksheet->setCellValue('セル番号', $calcData);
    }
}
