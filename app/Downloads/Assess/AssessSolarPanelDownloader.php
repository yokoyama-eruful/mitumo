<?php

declare(strict_types=1);

namespace App\Downloads\Assess;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AssessSolarPanelDownloader
{
    public function dropFile(
        $estimate,
        $assessData,
        $fileType
    ) {

        $filePath = storage_path('app/assessTemplate/sun.xlsx');

        if ($fileType === 'excel') {
            $fileName = '評価表（太陽光付）_' .$estimate->contractee.'_'. date('YmdHi') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');
        }

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setNormalInfo($spreadSheet, $estimate, $assessData);

        $spreadSheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        if ($fileType === 'pdf') {
            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/sun.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/sun.pdf');
            $fileName = '評価表（太陽光付）_'  .$estimate->contractee.'_'. date('YmdHi') . '.pdf';

            header('Content-Type:' . $pdf);
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');

            readfile($pdf);
        }

        if ($fileType === 'excel') {
            $writer->save('php://output');
        }

        exit;
    }

    private function setNormalInfo($spreadSheet, $estimate, $assessData)
    {
        $worksheet = $spreadSheet->getSheetByName('評価表（太陽光付）');
        $worksheet->setCellValue('L1', date('Y年m月d日'));
        $worksheet->setCellValue('B4', $estimate->contractee);
        $worksheet->setCellValue('F4', $estimate->location);
        $worksheet->setCellValue('B7', $assessData->assessUsage);
        $worksheet->setCellValue('E7', $assessData->assessStructure);
        $worksheet->setCellValue('H7', $assessData->floorArea);
        $worksheet->setCellValue('K7', $assessData->buildingYear.'-01-01');
        $worksheet->setCellValue('B15', $assessData->installationDate);
        $worksheet->setCellValue('E15', $assessData->installationCost);
        $worksheet->setCellValue('H15', $assessData->getSolarPanelInfo()['経過年数']);
        $worksheet->setCellValue('I15', $assessData->getSolarPanelInfo()['経年減価率']);
        $worksheet->setCellValue('J15', $assessData->getSolarPanelInfo()['残価率']);
        $worksheet->setCellValue('K15', $assessData->getSolarPanelInfo()['残価率最終']);
        $worksheet->setCellValue('M15', $assessData->getSolarPanelInfo()['時価額']);

        if ($assessData->assessStructure == 'プレハブ造') {
            $worksheet->setCellValue('B19', '木質系');
            $worksheet->setCellValue('B20', '鉄骨系');
            $worksheet->setCellValue('B21', 'RC系');
        }

        $worksheet->setCellValue('C19', $assessData->getReProcure('A')['標準単価']);
        $worksheet->setCellValue('C20', $assessData->getReProcure('B')['標準単価']);
        $worksheet->setCellValue('C21', $assessData->getReProcure('C')['標準単価']);

        $worksheet->setCellValue('E19', $assessData->floorArea);

        $worksheet->setCellValue('G19', $assessData->getReProcure('A')['100%']);
        $worksheet->setCellValue('G20', $assessData->getReProcure('B')['100%']);
        $worksheet->setCellValue('G21', $assessData->getReProcure('C')['100%']);
        $worksheet->setCellValue('H19', $assessData->getReProcure('A')['100%まるめ']);
        $worksheet->setCellValue('H20', $assessData->getReProcure('B')['100%まるめ']);
        $worksheet->setCellValue('H21', $assessData->getReProcure('C')['100%まるめ']);
        $worksheet->setCellValue('I19', $assessData->getReProcure('A')['太陽光付き100%まるめ']);
        $worksheet->setCellValue('I20', $assessData->getReProcure('B')['太陽光付き100%まるめ']);
        $worksheet->setCellValue('I21', $assessData->getReProcure('C')['太陽光付き100%まるめ']);

        $worksheet->setCellValue('K19', $assessData->getReProcure('A')['80%']);
        $worksheet->setCellValue('K20', $assessData->getReProcure('B')['80%']);
        $worksheet->setCellValue('K21', $assessData->getReProcure('C')['80%']);
        $worksheet->setCellValue('L19', $assessData->getReProcure('A')['80%まるめ']);
        $worksheet->setCellValue('L20', $assessData->getReProcure('B')['80%まるめ']);
        $worksheet->setCellValue('L21', $assessData->getReProcure('C')['80%まるめ']);
        $worksheet->setCellValue('M19', $assessData->getReProcure('A')['太陽光付き80%まるめ']);
        $worksheet->setCellValue('M20', $assessData->getReProcure('B')['太陽光付き80%まるめ']);
        $worksheet->setCellValue('M21', $assessData->getReProcure('C')['太陽光付き80%まるめ']);

        if ($assessData->assessStructure == 'プレハブ造') {
            $worksheet->setCellValue('B25', '木質系');
            $worksheet->setCellValue('B26', '鉄骨系');
            $worksheet->setCellValue('B27', 'RC系');
        }

        $worksheet->setCellValue('C25', $assessData->getResidualValue('A')['経年減価率']);
        $worksheet->setCellValue('C26', $assessData->getResidualValue('B')['経年減価率']);
        $worksheet->setCellValue('C27', $assessData->getResidualValue('C')['経年減価率']);

        $worksheet->setCellValue('E25', $assessData->buildingAge);

        $worksheet->setCellValue('G25', $assessData->getResidualValue('A')['残価率']);
        $worksheet->setCellValue('G26', $assessData->getResidualValue('B')['残価率']);
        $worksheet->setCellValue('G27', $assessData->getResidualValue('C')['残価率']);
        $worksheet->setCellValue('H25', $assessData->getResidualValue('A')['残価率最終']);
        $worksheet->setCellValue('H26', $assessData->getResidualValue('B')['残価率最終']);
        $worksheet->setCellValue('H27', $assessData->getResidualValue('C')['残価率最終']);

        $worksheet->setCellValue('K25', $assessData->getCurrentMarketPrice('A')['時価額']);
        $worksheet->setCellValue('K26', $assessData->getCurrentMarketPrice('B')['時価額']);
        $worksheet->setCellValue('K27', $assessData->getCurrentMarketPrice('C')['時価額']);
        $worksheet->setCellValue('L25', $assessData->getCurrentMarketPrice('A')['時価額まるめ']);
        $worksheet->setCellValue('L26', $assessData->getCurrentMarketPrice('B')['時価額まるめ']);
        $worksheet->setCellValue('L27', $assessData->getCurrentMarketPrice('C')['時価額まるめ']);
        $worksheet->setCellValue('M25', $assessData->getCurrentMarketPrice('A')['太陽光付まるめ']);
        $worksheet->setCellValue('M26', $assessData->getCurrentMarketPrice('B')['太陽光付まるめ']);
        $worksheet->setCellValue('M27', $assessData->getCurrentMarketPrice('C')['太陽光付まるめ']);

        $worksheet->setCellValue('B30', $assessData->remarks);
    }

    public function ConvertYear($fiscal_year)
    {
        $eras = [
            ['year' => 2019, 'name' => '令和', 'new_Japanese_calendar' => '平成31年'],
            ['year' => 1989, 'name' => '平成', 'new_Japanese_calendar' => '平成元年'],
            ['year' => 1926, 'name' => '昭和', 'new_Japanese_calendar' => '大正15年'],
        ];

        foreach ($eras as $era) {
            if ($fiscal_year === $era['year']) {
                return $era['new_Japanese_calendar'];
            }

            if ($fiscal_year >= $era['year']) {
                $year = $fiscal_year - $era['year'] + 1;

                return $era['name'] . $year . '年';
            }
        }
    }
}
