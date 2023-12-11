<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AllDownloader
{
    public function dropFile(
        $standardDate,
        $comprehensiveDate,
        $comprehensiveOfEarthquakeDate,
        $comprehensiveOfRecommendDate,
        $standardAndComprehensiveDate,
        $standardOfEarthquakeDate,
        $standardOfRecommendAndComprehensiveOfRecommendDate,
        $standardOfRecommendDate,
        $calcData,
    ) {
        $filePath = storage_path('app/estimateTemplate/all.xlsx');

        $fileName = '火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardInfo($spreadSheet, $standardDate);

        $this->setComprehensiveInfo($spreadSheet, $comprehensiveDate);

        $this->setStandardAndComprehensiveInfo($spreadSheet, $standardAndComprehensiveDate);

        $this->setStandardOfRecommendInfo($spreadSheet, $standardOfRecommendDate);

        $this->setComprehensiveOfRecommendInfo($spreadSheet, $comprehensiveOfRecommendDate);

        $this->setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $standardOfRecommendAndComprehensiveOfRecommendDate);

        $this->setStandardOfEarthquakeInfo($spreadSheet, $standardOfEarthquakeDate);

        $this->setComprehensiveOfEarthquakeInfo($spreadSheet, $comprehensiveOfEarthquakeDate);

        $this->setCalcInfo($spreadSheet, $calcData);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardInfo($spreadSheet, $standardOfEarthquakeDate)
    {
        $worksheet = $spreadSheet->getSheetByName('普通');
        $worksheet->setCellValue('セル番号', $standardOfEarthquakeDate);
    }

    private function setComprehensiveInfo($spreadSheet, $comprehensiveDate)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(総合)');
        $worksheet->setCellValue('セル番号', $comprehensiveDate);
    }

    private function setStandardAndComprehensiveInfo($spreadSheet, $standardAndComprehensiveDate)
    {
        $worksheet = $spreadSheet->getSheetByName('普通&総合');
        $worksheet->setCellValue('セル番号', $standardAndComprehensiveDate);
    }

    private function setStandardOfRecommendInfo($spreadSheet, $standardOfRecommendDate)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普通)');
        $worksheet->setCellValue('セル番号', $standardOfRecommendDate);
    }

    private function setComprehensiveOfRecommendInfo($spreadSheet, $comprehensiveOfRecommendDate)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(総合)');
        $worksheet->setCellValue('セル番号', $comprehensiveOfRecommendDate);
    }

    private function setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $standardOfRecommendAndComprehensiveOfRecommendDate)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普&総)');
        $worksheet->setCellValue('セル番号', $standardOfRecommendAndComprehensiveOfRecommendDate);
    }

    private function setStandardOfEarthquakeInfo($spreadSheet, $standardOfEarthquakeDate)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ地震(普通)');
        $worksheet->setCellValue('セル番号', $standardOfEarthquakeDate);
    }

    private function setComprehensiveOfEarthquakeInfo($spreadSheet, $comprehensiveOfEarthquakeDate)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(総合)');
        $worksheet->setCellValue('セル番号', $comprehensiveOfEarthquakeDate);
    }

    private function setCalcInfo($spreadSheet, $calcData)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        $worksheet->setCellValue('セル番号', $calcData);
    }
}
