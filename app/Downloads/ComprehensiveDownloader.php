<?php

declare(strict_types=1);

namespace App\Downloads;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ComprehensiveDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $comprehensivePremium,
        $comprehensiveCompensation,
        $shortTerm,
        $fileType,
        $keisyo
    ) {
        $filePath = storage_path('app/estimateTemplate/comprehensive.xlsx');

        if ($fileType === 'excel') {
            $fileName = '総合火災共済見積書_' .$estimate->contractee . date('YmdHi') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');
        }

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $comprehensiveCompensation = [
            'G49' => $comprehensiveCompensation->coverageOptions['火災'],
            'G50' => $comprehensiveCompensation->coverageOptions['落雷'],
            'G51' => $comprehensiveCompensation->coverageOptions['破裂または爆発'],
            'G52' => $comprehensiveCompensation->coverageOptions['風災・雪災'],
            'G53' => $comprehensiveCompensation->coverageOptions['物体の落下・衝突'],
            'G54' => $comprehensiveCompensation->coverageOptions['騒じょう・労働争議'],
            'G55' => $comprehensiveCompensation->coverageOptions['水ぬれ'],
            'G56' => $comprehensiveCompensation->coverageOptions['盗難'],
            'G57' => $comprehensiveCompensation->coverageOptions['水害'],
            'P49' => $comprehensiveCompensation->paymentOptions['残存物取り片付け費用'],
            'P50' => $comprehensiveCompensation->paymentOptions['失火見舞費用'],
            'P51' => $comprehensiveCompensation->paymentOptions['地震火災費用'],
            'P52' => $comprehensiveCompensation->paymentOptions['修理付帯費用'],
            'P53' => $comprehensiveCompensation->paymentOptions['損害防止費用'],
        ];

        $this->setComprehensiveInfo($spreadSheet, $estimate, $comprehensivePremium, $comprehensiveCompensation, $shortTerm, $keisyo);

        CalcInfo::setSheet($spreadSheet, $standardPremium, $comprehensivePremium);

        if ($fileType === 'pdf') {
            $objSheet = $spreadSheet->getSheetByName('計算');
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($objSheet));

            $spreadSheet->setActiveSheetIndex(0);
            $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/comprehensive'.$estimate->id.'.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/comprehensive'.$estimate->id.'.pdf');
            $fileName = '総合火災共済見積書_' .$estimate->contractee . date('YmdHi'). '.pdf';

            header('Content-Type:' . $pdf);
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');

            readfile($pdf);

            Storage::deleteDirectory('/excel');
            Storage::deleteDirectory('/pdf');
        }

        if ($fileType === 'excel') {
            $spreadSheet->setActiveSheetIndex(0);
            $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');
            $writer->save('php://output');
        }

        exit;
    }

    private function setComprehensiveInfo($spreadSheet, $estimate, $comprehensivePremium, $comprehensiveCompensation, $shortTerm, $keisyo)
    {
        $worksheet = $spreadSheet->getSheetByName('総合');
        $worksheet->setCellValue('O1', date('Y年m月d日'));
        $worksheet->setCellValue('A3', $estimate->contractee ? $estimate->contractee.$keisyo : '');
        $worksheet->setCellValue('R5', $estimate->company);
        $worksheet->setCellValue('R6', $estimate->address);
        $worksheet->setCellValue('R7', $estimate->phone_number ? 'ＴＥＬ ： '.$estimate->phone_number : '');
        $worksheet->setCellValue('R8', $estimate->staff_name ? '担当 ： '.$estimate->staff_name : '');
        $worksheet->setCellValue('D20', $estimate->location);
        $worksheet->setCellValue('D23', $estimate->BuildingStructure);
        $worksheet->setCellValue('D27', $estimate->OtherBuildingStructure);
        $worksheet->setCellValue('O22', $estimate->property_type);
        $worksheet->setCellValue('D31', $estimate->rank);
        $worksheet->setCellValue('M31', $estimate->pay_method);
        $worksheet->setCellValue('D33', $estimate->InsurancePeriod);
        $worksheet->setCellValue('D35', $estimate->OtherItem);
        $worksheet->setCellValue('D41', thousandYenUnit(array_key_exists('building_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['building_insurance_payout'] : ''));
        $worksheet->setCellValue('K41', thousandYenUnit(array_key_exists('household_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['household_insurance_payout'] : ''));
        $worksheet->setCellValue('D43', thousandYenUnit(array_key_exists('facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['facility_insurance_payout'] : ''));
        $worksheet->setCellValue('K43', thousandYenUnit(array_key_exists('item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['item_insurance_payout'] : ''));
        $worksheet->setCellValue('D45', thousandYenUnit(array_key_exists('other_facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_facility_insurance_payout'] : ''));
        $worksheet->setCellValue('K45', thousandYenUnit(array_key_exists('other_item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_item_insurance_payout'] : ''));

        $worksheet->setCellValue('O41', $comprehensivePremium->getComprehensivePremium());
        foreach ($comprehensiveCompensation as $key => $comprehensiveCompensation) {
            $worksheet->setCellValue($key, $comprehensiveCompensation);
        }
    }
}
