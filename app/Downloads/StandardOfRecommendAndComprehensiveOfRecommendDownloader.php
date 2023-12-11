<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardOfRecommendAndComprehensiveOfRecommendDownloader
{
    public function dropFile(
        $standardOfRecommendAndComprehensiveOfRecommendDate,
        $calcData,
    ) {
        $filePath = storage_path('app/estimateTemplate/standardOfRecommendAndComprehensiveOfRecommend.xlsx');

        $fileName = 'おすすめ_普通_総合_火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $standardOfRecommendAndComprehensiveOfRecommendDate);

        $this->setCalcInfo($spreadSheet, $calcData);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $standardOfRecommendAndComprehensiveOfRecommendDate)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普&総)');
        // $worksheet->setCellValue('セル番号', $standardOfRecommendAndComprehensiveOfRecommendDate);
    }

    private function setCalcInfo($spreadSheet, $calcData)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
