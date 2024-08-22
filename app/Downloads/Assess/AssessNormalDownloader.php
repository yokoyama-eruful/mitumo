<?php

declare(strict_types=1);

namespace App\Downloads\Assess;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AssessNormalDownloader
{
    public function dropFile(
        $estimate,
        $assessData,
        $fileType
    ) {
        $filePath = storage_path('app/assessTemplate/normal.xlsx');

        if ($fileType === 'excel') {
            $fileName = '評価表_'.$estimate->assess_item.'_'  .$estimate->contractee.'_'. date('YmdHi') . '.xlsx';
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

            $export_excel_path = storage_path('app/excel/normal.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/normal.pdf');
            $fileName = '評価表_'.$estimate->assess_item.'_'  .$estimate->contractee.'_'. date('YmdHi') . '.pdf';

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
        $worksheet = $spreadSheet->getSheetByName('評価表（通常）');
        $worksheet->setCellValue('L1', date('Y年m月d日'));
        $worksheet->setCellValue('B4', $estimate->contractee);
        $worksheet->setCellValue('F4', $estimate->location);
        $worksheet->setCellValue('B7', $assessData->assessUsage);
        $worksheet->setCellValue('E7', $assessData->assessStructure);
        $worksheet->setCellValue('H7', $assessData->floorArea);
        $worksheet->setCellValue('K7', $assessData->buildingYear.'-01-01');

        $worksheet->setCellValue('C15', $assessData->getReProcure('A')['標準単価']);
        $worksheet->setCellValue('C16', $assessData->getReProcure('B')['標準単価']);
        $worksheet->setCellValue('C17', $assessData->getReProcure('C')['標準単価']);

        $worksheet->setCellValue('E15', $assessData->floorArea);

        $worksheet->setCellValue('G15', $assessData->getReProcure('A')['100%']);
        $worksheet->setCellValue('G16', $assessData->getReProcure('B')['100%']);
        $worksheet->setCellValue('G17', $assessData->getReProcure('C')['100%']);

        $worksheet->setCellValue('H15', $assessData->getReProcure('A')['100%まるめ']);
        $worksheet->setCellValue('H16', $assessData->getReProcure('B')['100%まるめ']);
        $worksheet->setCellValue('H17', $assessData->getReProcure('C')['100%まるめ']);

        $worksheet->setCellValue('J15', $assessData->getReProcure('A')['80%']);
        $worksheet->setCellValue('J16', $assessData->getReProcure('B')['80%']);
        $worksheet->setCellValue('J17', $assessData->getReProcure('C')['80%']);

        $worksheet->setCellValue('K15', $assessData->getReProcure('A')['80%まるめ']);
        $worksheet->setCellValue('K16', $assessData->getReProcure('B')['80%まるめ']);
        $worksheet->setCellValue('K17', $assessData->getReProcure('C')['80%まるめ']);

        $worksheet->setCellValue('C22', $assessData->getResidualValue('A')['経年減価率']);
        $worksheet->setCellValue('C23', $assessData->getResidualValue('B')['経年減価率']);
        $worksheet->setCellValue('C24', $assessData->getResidualValue('C')['経年減価率']);

        $worksheet->setCellValue('E22', $assessData->buildingAge);

        $worksheet->setCellValue('G22', $assessData->getResidualValue('A')['残価率']);
        $worksheet->setCellValue('G23', $assessData->getResidualValue('B')['残価率']);
        $worksheet->setCellValue('G24', $assessData->getResidualValue('C')['残価率']);

        $worksheet->setCellValue('H22', $assessData->getResidualValue('A')['残価率最終']);
        $worksheet->setCellValue('H23', $assessData->getResidualValue('B')['残価率最終']);
        $worksheet->setCellValue('H24', $assessData->getResidualValue('C')['残価率最終']);

        $worksheet->setCellValue('J22', $assessData->getCurrentMarketPrice('A')['時価額']);
        $worksheet->setCellValue('J23', $assessData->getCurrentMarketPrice('B')['時価額']);
        $worksheet->setCellValue('J24', $assessData->getCurrentMarketPrice('C')['時価額']);

        $worksheet->setCellValue('K22', $assessData->getCurrentMarketPrice('A')['時価額まるめ']);
        $worksheet->setCellValue('K23', $assessData->getCurrentMarketPrice('B')['時価額まるめ']);
        $worksheet->setCellValue('K24', $assessData->getCurrentMarketPrice('C')['時価額まるめ']);

        $worksheet->setCellValue('B27', $assessData->remarks);

        $worksheet->setTitle('評価表_'.$estimate->assess_item);
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
