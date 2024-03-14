<?php

declare(strict_types=1);

namespace App\Downloads\Futsu2;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardOfRecommendDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $recommendStandardPremium,
        $fileType,
        $keisyo
    ) {
        $filePath = storage_path('app/estimateFutsu2/estimateTemplate/standardOfRecommend.xlsx');

        if ($fileType === 'excel') {
            $fileName = 'おすすめ_普通_火災共済Ⅱ見積書_' .$estimate->contractee. date('YmdHi') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');
        }

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $recommendStandardPremium, $keisyo);

        $this->setCalcInfo($spreadSheet, $standardPremium);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        if ($fileType === 'pdf') {
            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/standardOfRecommend'.$estimate->id.'.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/standardOfRecommend'.$estimate->id.'.pdf');
            $fileName = 'おすすめ_普通_火災共済Ⅱ見積書_' .$estimate->contractee. date('YmdHi')  . '.pdf';

            header('Content-Type:' . $pdf);
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');

            readfile($pdf);

            Storage::deleteDirectory('/excel');
            Storage::deleteDirectory('/pdf');
        }

        if ($fileType === 'excel') {
            $writer->save('php://output');
        }

        exit;
    }

    private function setStandardOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $recommendStandardPremium, $keisyo)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普通)');
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
        $worksheet->setCellValue('M33', $estimate->InsurancePeriod);
        $worksheet->setCellValue('D35', $estimate->OtherItem);
        $worksheet->setCellValue('D41', thousandYenUnit(array_key_exists('building_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['building_insurance_payout'] : ''));
        $worksheet->setCellValue('K41', thousandYenUnit(array_key_exists('household_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['household_insurance_payout'] : ''));
        $worksheet->setCellValue('D43', thousandYenUnit(array_key_exists('facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['facility_insurance_payout'] : ''));
        $worksheet->setCellValue('K43', thousandYenUnit(array_key_exists('item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['item_insurance_payout'] : ''));
        $worksheet->setCellValue('D45', thousandYenUnit(array_key_exists('other_facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_facility_insurance_payout'] : ''));
        $worksheet->setCellValue('K45', thousandYenUnit(array_key_exists('other_item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_item_insurance_payout'] : ''));

        $worksheet->setCellValue('O41', $standardPremium->getStandardPremium());
        $worksheet->setCellValue('D49', yearUnit(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : ''));
        $worksheet->setCellValue('D51', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : '')['共済掛金']);
        $worksheet->setCellValue('D53', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : '')['１年更新の場合より']);
        $worksheet->setCellValue('I49', yearUnit(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : ''));
        $worksheet->setCellValue('I51', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : '')['共済掛金']);
        $worksheet->setCellValue('I53', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : '')['１年更新の場合より']);
        $worksheet->setCellValue('N49', yearUnit(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : ''));
        $worksheet->setCellValue('N51', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : '')['共済掛金']);
        $worksheet->setCellValue('N53', $recommendStandardPremium->getRecommendPremium(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : '')['１年更新の場合より']);
    }

    private function setCalcInfo($spreadSheet, $standardPremium)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        $worksheet->setCellValue('E3', $standardPremium->building['基本率']);
        $worksheet->setCellValue('G3', $standardPremium->building['長期率']);
        $worksheet->setCellValue('I3', $standardPremium->building['適用率']);
        $worksheet->setCellValue('K3', $standardPremium->building['掛金']);

        $worksheet->setCellValue('E7', $standardPremium->houseHold['基本率']);
        $worksheet->setCellValue('G7', $standardPremium->houseHold['長期率']);
        $worksheet->setCellValue('I7', $standardPremium->houseHold['適用率']);
        $worksheet->setCellValue('K7', $standardPremium->houseHold['掛金']);

        $worksheet->setCellValue('E11', $standardPremium->facility['基本率']);
        $worksheet->setCellValue('G11', $standardPremium->facility['長期率']);
        $worksheet->setCellValue('I11', $standardPremium->facility['適用率']);
        $worksheet->setCellValue('K11', $standardPremium->facility['掛金']);

        $worksheet->setCellValue('E16', $standardPremium->item['基本率']);
        $worksheet->setCellValue('G16', $standardPremium->item['長期率']);
        $worksheet->setCellValue('I16', $standardPremium->item['適用率']);
        $worksheet->setCellValue('K16', $standardPremium->item['掛金']);

        $worksheet->setCellValue('E21', $standardPremium->building['基本率']);
        $worksheet->setCellValue('E22', $standardPremium->houseHold['基本率']);
        $worksheet->setCellValue('E23', $standardPremium->occupationalSurchargeRate);
        $worksheet->setCellValue('E24', $standardPremium->workSurchargeRate);
        $worksheet->setCellValue('E25', $standardPremium->chattelSurcharge);
        $worksheet->setCellValue('E26', $standardPremium->building['短期率']);
        $worksheet->setCellValue('E27', $standardPremium->building['長期率']);
        $worksheet->setCellValue('E28', $standardPremium->actualLossFactor);

        $worksheet->setCellValue('L21', percentUnit($standardPremium->discountFireExtinguisher));
        $worksheet->setCellValue('L22', percentUnit($standardPremium->discountSecurity));
        $worksheet->setCellValue('L23', percentUnit($standardPremium->discountPublicProperty));
        $worksheet->setCellValue('L24', percentUnit($standardPremium->discountRiskRate));
        $worksheet->setCellValue('L25', $standardPremium->otherDiscount);
        $worksheet->setCellValue('L26', $standardPremium->getPayByLongTermFlag() == true ? '3%' : '');
    }
}
