<?php

declare(strict_types=1);

namespace App\Downloads\Assess;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AssessRenovationDownloader
{
    public function dropFile(
        $estimate,
        $assessData,
        $fileType
    ) {

        $filePath = storage_path('app/assessTemplate/renovation.xlsx');

        if ($fileType === 'excel') {
            $fileName = '改装物件の評価計算式_'  .$estimate->contractee.'_'. date('YmdHi') . '.xlsx';
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

            $export_excel_path = storage_path('app/excel/renovation.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/renovation.pdf');
            $fileName = '改装物件の評価計算式_'  .$estimate->contractee.'_'. date('YmdHi') . '.pdf';

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
        $worksheet = $spreadSheet->getSheetByName('改装物件の評価計算式');
        $worksheet->setCellValue('P1', date('Y年m月d日'));
        $worksheet->setCellValue('R4', $assessData->replacementPrice);
        $worksheet->setCellValue('R5', $assessData->renovationCost);
        $worksheet->setCellValue('R6', $assessData->conventionalAge);
        $worksheet->setCellValue('R8', $assessData->depreciationRate);
        $worksheet->setCellValue('R9', $assessData->renovationAge);
        $worksheet->setCellValue('R10', $assessData->renovationDepreciationRate);
        $worksheet->setCellValue('R12', $estimate->contractee);
        $worksheet->setCellValue('R13', $estimate->location);

        $worksheet->setCellValue('F6', $assessData->replacementPrice);
        $worksheet->setCellValue('H6', $assessData->renovationCost);
        $worksheet->setCellValue('J6', $assessData->replacementPrice);
        $worksheet->setCellValue('L6', $assessData->conventionalDepreciationRate['価額構成割合']);
        $worksheet->setCellValue('N6', $assessData->conventionalDepreciationRate['価額構成割合まるめ']);

        $worksheet->setCellValue('D9', $assessData->conventionalDepreciationRate['価額構成割合まるめ']);
        $worksheet->setCellValue('F9', $assessData->conventionalAge);
        $worksheet->setCellValue('H9', $assessData->depreciationRate);
        $worksheet->setCellValue('L9', $assessData->conventionalDepreciationRate['減価率']);
        $worksheet->setCellValue('N9', $assessData->conventionalDepreciationRate['減価率まるめ']);

        $worksheet->setCellValue('F13', $assessData->renovationCost);
        $worksheet->setCellValue('H13', $assessData->replacementPrice);
        $worksheet->setCellValue('J13', $assessData->renovationCostRate['価額構成割合']);
        $worksheet->setCellValue('L13', $assessData->renovationCostRate['価額構成割合']);
        $worksheet->setCellValue('N13', $assessData->renovationCostRate['価額構成割合まるめ']);

        $worksheet->setCellValue('D16', $assessData->renovationCostRate['価額構成割合まるめ']);
        $worksheet->setCellValue('F16', $assessData->renovationAge);
        $worksheet->setCellValue('H16', $assessData->renovationDepreciationRate);
        $worksheet->setCellValue('L16', $assessData->renovationCostRate['減価率']);
        $worksheet->setCellValue('N16', $assessData->renovationCostRate['減価率まるめ']);

        $worksheet->setCellValue('F20', $assessData->conventionalDepreciationRate['減価率まるめ']);
        $worksheet->setCellValue('H20', $assessData->renovationCostRate['減価率まるめ']);
        $worksheet->setCellValue('J20', $assessData->totalDepreciationRate);

        $worksheet->setCellValue('D24', $assessData->replacementPrice);
        $worksheet->setCellValue('J24', $assessData->totalDepreciationRate);
        $worksheet->setCellValue('L24', $assessData->residualValue);
        $worksheet->setCellValue('N24', $assessData->currentMarketPrice['時価額']);
        $worksheet->setCellValue('N25', $assessData->currentMarketPrice['時価額まるめ']);
    }
}
