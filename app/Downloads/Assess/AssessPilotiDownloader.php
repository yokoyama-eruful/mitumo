<?php

declare(strict_types=1);

namespace App\Downloads\Assess;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AssessPilotiDownloader
{
    public function dropFile(
        $estimate,
        $assessData,
        $fileType
    ) {

        $filePath = storage_path('app/assessTemplate/piloti.xlsx');

        if ($fileType === 'excel') {
            $fileName = '評価表（ピロティー付）_'  .$estimate->contractee.'_'. date('YmdHi') . '.xlsx';
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

            $export_excel_path = storage_path('app/excel/piloti.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/piloti.pdf');
            $fileName = '評価表（ピロティー付）_'  .$estimate->contractee.'_'. date('YmdHi') . '.pdf';

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
        $worksheet = $spreadSheet->getSheetByName('評価表（ピロティー付）');
        $worksheet->setCellValue('L1', date('Y年m月d日'));
        $worksheet->setCellValue('B4', $estimate->contractee);
        $worksheet->setCellValue('F4', $estimate->location);
        $worksheet->setCellValue('B7', $assessData->assessUsage);
        $worksheet->setCellValue('E7', $assessData->assessStructure);
        $worksheet->setCellValue('H7', $assessData->exceptForPilotiFloorArea);
        $worksheet->setCellValue('K7', $assessData->buildingYear.'-01-01');
        $worksheet->setCellValue('H12', $assessData->pilotiFloorArea);
        $worksheet->setCellValue('K12', $assessData->floorArea);

        if ($assessData->assessStructure == 'プレハブ工法') {
            $worksheet->setCellValue('B17', '木質系');
            $worksheet->setCellValue('B18', '鉄骨系');
            $worksheet->setCellValue('B19', 'RC系');
        }

        $worksheet->setCellValue('C17', $assessData->getExceptForPiloti('A')['標準単価']);
        $worksheet->setCellValue('C18', $assessData->getExceptForPiloti('B')['標準単価']);
        $worksheet->setCellValue('C19', $assessData->getExceptForPiloti('C')['標準単価']);

        $worksheet->setCellValue('E17', $assessData->exceptForPilotiFloorArea);

        $worksheet->setCellValue('G17', $assessData->getExceptForPiloti('A')['ピロティー以外100%']);
        $worksheet->setCellValue('G18', $assessData->getExceptForPiloti('B')['ピロティー以外100%']);
        $worksheet->setCellValue('G19', $assessData->getExceptForPiloti('C')['ピロティー以外100%']);
        $worksheet->setCellValue('H17', $assessData->getExceptForPiloti('A')['ピロティー以外100%まるめ']);
        $worksheet->setCellValue('H18', $assessData->getExceptForPiloti('B')['ピロティー以外100%まるめ']);
        $worksheet->setCellValue('H19', $assessData->getExceptForPiloti('C')['ピロティー以外100%まるめ']);

        $worksheet->setCellValue('J17', $assessData->getExceptForPiloti('A')['ピロティー以外80%']);
        $worksheet->setCellValue('J18', $assessData->getExceptForPiloti('B')['ピロティー以外80%']);
        $worksheet->setCellValue('J19', $assessData->getExceptForPiloti('C')['ピロティー以外80%']);
        $worksheet->setCellValue('K17', $assessData->getExceptForPiloti('A')['ピロティー以外80%まるめ']);
        $worksheet->setCellValue('K18', $assessData->getExceptForPiloti('B')['ピロティー以外80%まるめ']);
        $worksheet->setCellValue('K19', $assessData->getExceptForPiloti('C')['ピロティー以外80%まるめ']);

        $worksheet->setCellValue('C24', $assessData->getPiloti()['標準単価']);

        $worksheet->setCellValue('E24', $assessData->pilotiFloorArea);

        $worksheet->setCellValue('G24', $assessData->getPiloti()['ピロティー100%']);
        $worksheet->setCellValue('H24', $assessData->getPiloti()['ピロティー100%まるめ']);

        $worksheet->setCellValue('J24', $assessData->getPiloti()['ピロティー80%']);
        $worksheet->setCellValue('K24', $assessData->getPiloti()['ピロティー80%まるめ']);

        $worksheet->setCellValue('E29', $assessData->floorArea);

        $worksheet->setCellValue('G29', $assessData->getAllFloorAreaReProcure('A')['100%']);
        $worksheet->setCellValue('G30', $assessData->getAllFloorAreaReProcure('B')['100%']);
        $worksheet->setCellValue('G31', $assessData->getAllFloorAreaReProcure('C')['100%']);
        $worksheet->setCellValue('H29', $assessData->getAllFloorAreaReProcure('A')['100%まるめ']);
        $worksheet->setCellValue('H30', $assessData->getAllFloorAreaReProcure('B')['100%まるめ']);
        $worksheet->setCellValue('H31', $assessData->getAllFloorAreaReProcure('C')['100%まるめ']);

        $worksheet->setCellValue('J29', $assessData->getAllFloorAreaReProcure('A')['80%']);
        $worksheet->setCellValue('J30', $assessData->getAllFloorAreaReProcure('B')['80%']);
        $worksheet->setCellValue('J31', $assessData->getAllFloorAreaReProcure('C')['80%']);
        $worksheet->setCellValue('K29', $assessData->getAllFloorAreaReProcure('A')['80%まるめ']);
        $worksheet->setCellValue('K30', $assessData->getAllFloorAreaReProcure('B')['80%まるめ']);
        $worksheet->setCellValue('K31', $assessData->getAllFloorAreaReProcure('C')['80%まるめ']);

        if ($assessData->assessStructure == 'プレハブ工法') {
            $worksheet->setCellValue('B29', '木質系');
            $worksheet->setCellValue('B30', '鉄骨系');
            $worksheet->setCellValue('B31', 'RC系');
        }

        if ($assessData->assessStructure == 'プレハブ工法') {
            $worksheet->setCellValue('B35', '木質系');
            $worksheet->setCellValue('B36', '鉄骨系');
            $worksheet->setCellValue('B37', 'RC系');
        }

        $worksheet->setCellValue('C35', $assessData->getResidualValue('A')['経年減価率']);
        $worksheet->setCellValue('C36', $assessData->getResidualValue('B')['経年減価率']);
        $worksheet->setCellValue('C37', $assessData->getResidualValue('C')['経年減価率']);

        $worksheet->setCellValue('E35', $assessData->buildingAge);

        $worksheet->setCellValue('G35', $assessData->getResidualValue('A')['残価率']);
        $worksheet->setCellValue('G36', $assessData->getResidualValue('B')['残価率']);
        $worksheet->setCellValue('G37', $assessData->getResidualValue('C')['残価率']);

        $worksheet->setCellValue('H35', $assessData->getResidualValue('A')['残価率最終']);
        $worksheet->setCellValue('H36', $assessData->getResidualValue('B')['残価率最終']);
        $worksheet->setCellValue('H37', $assessData->getResidualValue('C')['残価率最終']);

        $worksheet->setCellValue('J35', $assessData->getCurrentMarketPrice('A')['時価額']);
        $worksheet->setCellValue('J36', $assessData->getCurrentMarketPrice('B')['時価額']);
        $worksheet->setCellValue('J37', $assessData->getCurrentMarketPrice('C')['時価額']);

        $worksheet->setCellValue('K35', $assessData->getCurrentMarketPrice('A')['時価額まるめ']);
        $worksheet->setCellValue('K36', $assessData->getCurrentMarketPrice('B')['時価額まるめ']);
        $worksheet->setCellValue('K37', $assessData->getCurrentMarketPrice('C')['時価額まるめ']);
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
