<?php

declare(strict_types=1);

namespace App\Downloads\Assess;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AssessAnnualDownloader
{
    public function dropFile(
        $estimate,
        $assessData,
        $isPdfType
    ) {

        $filePath = storage_path('app/assessTemplate/annual.xlsx');

        $fileName = '評価表（年次取得）_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setNormalInfo($spreadSheet, $estimate, $assessData);

        $spreadSheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setNormalInfo($spreadSheet, $estimate, $assessData)
    {
        $worksheet = $spreadSheet->getSheetByName('評価表（年次取得）');
        $worksheet->setCellValue('L1', date('Y年m月d日'));
        $worksheet->setCellValue('B4', $estimate->contractee);
        $worksheet->setCellValue('F4', $estimate->address);
        $worksheet->setCellValue('B7', $assessData->assessUsage);
        $worksheet->setCellValue('E7', $assessData->assessStructure);
        $worksheet->setCellValue('G7', $assessData->rank);
        $worksheet->setCellValue('H7', $assessData->floorArea);
        $worksheet->setCellValue('K7', $assessData->buildingAge);

        $worksheet->setCellValue('B15', $assessData->annualPrice);

        $worksheet->setCellValue('E15', $assessData->getReProcure()['年次係数']);

        $worksheet->setCellValue('G15', $assessData->getReProcure()['100%']);
        $worksheet->setCellValue('H15', $assessData->getReProcure()['100%まるめ']);

        $worksheet->setCellValue('J15', $assessData->getReProcure()['80%']);
        $worksheet->setCellValue('K15', $assessData->getReProcure()['80%まるめ']);

        $worksheet->setCellValue('C20', $assessData->getResidualValue('A')['経年減価率']);
        $worksheet->setCellValue('C21', $assessData->getResidualValue('B')['経年減価率']);
        $worksheet->setCellValue('C22', $assessData->getResidualValue('C')['経年減価率']);

        $worksheet->setCellValue('E20', $assessData->buildingAge);

        $worksheet->setCellValue('G20', $assessData->getResidualValue('A')['残価率']);
        $worksheet->setCellValue('G21', $assessData->getResidualValue('B')['残価率']);
        $worksheet->setCellValue('G22', $assessData->getResidualValue('C')['残価率']);

        $worksheet->setCellValue('H20', $assessData->getResidualValue('A')['残価率最終']);
        $worksheet->setCellValue('H21', $assessData->getResidualValue('B')['残価率最終']);
        $worksheet->setCellValue('H22', $assessData->getResidualValue('C')['残価率最終']);

        $worksheet->setCellValue('J20', $assessData->getCurrentMarketPrice('A')['時価額']);
        $worksheet->setCellValue('J21', $assessData->getCurrentMarketPrice('B')['時価額']);
        $worksheet->setCellValue('J22', $assessData->getCurrentMarketPrice('C')['時価額']);

        $worksheet->setCellValue('K20', $assessData->getCurrentMarketPrice('A')['時価額まるめ']);
        $worksheet->setCellValue('K21', $assessData->getCurrentMarketPrice('B')['時価額まるめ']);
        $worksheet->setCellValue('K22', $assessData->getCurrentMarketPrice('C')['時価額まるめ']);
    }
}
