<?php

declare(strict_types=1);

namespace App\Downloads;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class ComprehensiveOfEarthquakeDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $comprehensivePremium,
        $shortTerm,
        $earthquakePremium,
        $fileType,
        $keisyo
    ) {
        $filePath = storage_path('app/estimateTemplate/comprehensiveOfEarthquake.xlsx');

        if ($fileType === 'excel') {
            $fileName = '地震おすすめ_総合火災共済見積書_'.$estimate->contractee . date('YmdHi') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');
        }

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setComprehensiveOfEarthquakeInfo($spreadSheet, $estimate, $comprehensivePremium, $shortTerm, $earthquakePremium, $keisyo);

        CalcInfo::setSheet($spreadSheet, $standardPremium, $comprehensivePremium);

        if ($fileType === 'pdf') {
            $objSheet = $spreadSheet->getSheetByName('計算');
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($objSheet));

            $spreadSheet->setActiveSheetIndex(0);
            $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/comprehensiveOfEarthquake'.$estimate->id.'.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/comprehensiveOfEarthquake'.$estimate->id.'.pdf');
            $fileName = '地震おすすめ_総合火災共済見積書_'.$estimate->contractee . date('YmdHi')  . '.pdf';

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

    private function setComprehensiveOfEarthquakeInfo($spreadSheet, $estimate, $comprehensivePremium, $shortTerm, $earthquakePremium, $keisyo)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(総合)');
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

        $worksheet->setCellValue('O41', $shortTerm->shortTermRate != 0 ? '-' : $comprehensivePremium->getComprehensivePremium());

        $worksheet->setCellValue('D49', array_key_exists('earthquake1', $estimate->property_detail) ? $estimate->property_detail['earthquake1'] : '');
        $worksheet->setCellValue('D51', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake1', $estimate->property_detail) ? $estimate->property_detail['earthquake1'] : '')['共済金支払最大額']);
        $worksheet->setCellValue('D54', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake1', $estimate->property_detail) ? $estimate->property_detail['earthquake1'] : '')['地震掛金']);
        $worksheet->setCellValue('D56', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake1', $estimate->property_detail) ? $estimate->property_detail['earthquake1'] : '')['合計掛金']);
        $worksheet->setCellValue('I49', array_key_exists('earthquake2', $estimate->property_detail) ? $estimate->property_detail['earthquake2'] : '');
        $worksheet->setCellValue('I51', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake2', $estimate->property_detail) ? $estimate->property_detail['earthquake2'] : '')['共済金支払最大額']);
        $worksheet->setCellValue('I54', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake2', $estimate->property_detail) ? $estimate->property_detail['earthquake2'] : '')['地震掛金']);
        $worksheet->setCellValue('I56', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake2', $estimate->property_detail) ? $estimate->property_detail['earthquake2'] : '')['合計掛金']);
        $worksheet->setCellValue('N49', array_key_exists('earthquake3', $estimate->property_detail) ? $estimate->property_detail['earthquake3'] : '');
        $worksheet->setCellValue('N51', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake3', $estimate->property_detail) ? $estimate->property_detail['earthquake3'] : '')['共済金支払最大額']);
        $worksheet->setCellValue('N54', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake3', $estimate->property_detail) ? $estimate->property_detail['earthquake3'] : '')['地震掛金']);
        $worksheet->setCellValue('N56', $earthquakePremium->getEarthquakePremium(array_key_exists('earthquake3', $estimate->property_detail) ? $estimate->property_detail['earthquake3'] : '')['合計掛金']);
    }
}
