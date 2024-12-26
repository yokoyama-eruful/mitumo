<?php

declare(strict_types=1);

namespace App\Downloads;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardOfRecommendAndComprehensiveOfRecommendDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $comprehensivePremium,
        $recommendStandardPremium,
        $recommendComprehensivePremium,
        $shortTerm,
        $fileType,
        $keisyo
    ) {
        $filePath = storage_path('app/estimateTemplate/standardOfRecommendAndComprehensiveOfRecommend.xlsx');

        if ($fileType === 'excel') {
            $fileName = 'おすすめ_普通_総合_火災共済見積書_' .$estimate->contractee. date('YmdHi') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');
        }

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium, $shortTerm, $keisyo);
        CalcInfo::setSheet($spreadSheet, $standardPremium, $comprehensivePremium);

        if ($fileType === 'pdf') {
            $objSheet = $spreadSheet->getSheetByName('計算');
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($objSheet));

            $spreadSheet->setActiveSheetIndex(0);
            $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/standardOfRecommendAndComprehensiveOfRecommend'.$estimate->id.'.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/standardOfRecommendAndComprehensiveOfRecommend'.$estimate->id.'.pdf');
            $fileName = 'おすすめ_普通_総合_火災共済見積書_' .$estimate->contractee. date('YmdHi') . '.pdf';

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

    private function setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium, $shortTerm, $keisyo)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普&総)');
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
        $worksheet->setCellValue('O44', $standardPremium->getStandardPremium());

        $worksheet->setCellValue('D49', yearUnit(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : ''));
        $worksheet->setCellValue('D51', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : '')['共済掛金']);
        $worksheet->setCellValue('D52', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : '')['１年更新の場合より']);
        $worksheet->setCellValue('I49', yearUnit(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : ''));
        $worksheet->setCellValue('I51', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : '')['共済掛金']);
        $worksheet->setCellValue('I52', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : '')['１年更新の場合より']);
        $worksheet->setCellValue('N49', yearUnit(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : ''));
        $worksheet->setCellValue('N51', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : '')['共済掛金']);
        $worksheet->setCellValue('N52', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : '')['１年更新の場合より']);

        $worksheet->setCellValue('D55', yearUnit(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : ''));
        $worksheet->setCellValue('D57', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : '')['共済掛金']);
        $worksheet->setCellValue('D58', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : '')['１年更新の場合より']);
        $worksheet->setCellValue('I55', yearUnit(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : ''));
        $worksheet->setCellValue('I57', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : '')['共済掛金']);
        $worksheet->setCellValue('I58', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : '')['１年更新の場合より']);
        $worksheet->setCellValue('N55', yearUnit(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : ''));
        $worksheet->setCellValue('N57', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : '')['共済掛金']);
        $worksheet->setCellValue('N58', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : '')['１年更新の場合より']);
    }
}
