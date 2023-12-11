<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardOfRecommendDownloader
{
    public function dropFile(
        $standardOfRecommendDate,
        $calcData,
    ) {
        $filePath = storage_path('app/estimateTemplate/standardOfRecommendAndComprehensiveOfRecommend.xlsx');

        $fileName = 'おすすめ_普通_総合_火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardOfRecommendInfo($spreadSheet, $standardOfRecommendDate);

        $this->setCalcInfo($spreadSheet, $calcData);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardOfRecommendInfo($spreadSheet, $standardOfRecommendDate)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普通)');
        // $worksheet->setCellValue('セル番号', $standardOfRecommendDate);
    }

    private function setCalcInfo($spreadSheet, $calcData)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
