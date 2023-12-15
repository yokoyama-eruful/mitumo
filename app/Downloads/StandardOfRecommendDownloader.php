<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardOfRecommendDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $recommendStandardPremium
    ) {
        $filePath = storage_path('app/estimateTemplate/standardOfRecommend.xlsx');

        $fileName = 'おすすめ_普通_火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $recommendStandardPremium);

        $this->setCalcInfo($spreadSheet, $estimate);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $recommendStandardPremium)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普通)');
        $worksheet->setCellValue('O1', date('Y年m月d日'));
        $worksheet->setCellValue('A3', $estimate->contractee);
        $worksheet->setCellValue('R5', $estimate->company);
        $worksheet->setCellValue('R6', $estimate->address);
        $worksheet->setCellValue('R7', 'ＴＥＬ ： '.$estimate->phone_number);
        $worksheet->setCellValue('R8', '担当 ： '.$estimate->staff_name);
        $worksheet->setCellValue('D20', $estimate->location);
        $worksheet->setCellValue('D23', $estimate->BuildingStructure);
        $worksheet->setCellValue('D27', $estimate->BuildingStructure);
        $worksheet->setCellValue('O22', $estimate->property_type);
        $worksheet->setCellValue('D31', $estimate->rank);
        $worksheet->setCellValue('M31', $estimate->pay_method);
        $worksheet->setCellValue('M33', $estimate->InsurancePeriod);
        $worksheet->setCellValue('D35', $estimate->OtherItem);
        $worksheet->setCellValue('D41', thousandYenUnit($estimate->property_detail['building_insurance_payout']));
        $worksheet->setCellValue('K41', thousandYenUnit($estimate->property_detail['household_insurance_payout']));
        $worksheet->setCellValue('D43', thousandYenUnit($estimate->property_detail['facility_insurance_payout']));
        $worksheet->setCellValue('K43', thousandYenUnit($estimate->property_detail['item_insurance_payout']));
        $worksheet->setCellValue('D45', thousandYenUnit($estimate->property_detail['other_facility_insurance_payout']));
        $worksheet->setCellValue('K45', thousandYenUnit($estimate->property_detail['other_item_insurance_payout']));

        $worksheet->setCellValue('O41', $standardPremium->getStandardPremium());
        $worksheet->setCellValue('D49', $estimate->property_detail['recommend1']);
        $worksheet->setCellValue('D51', $recommendStandardPremium->getRecommendPremium($estimate->property_detail['recommend1'])['共済掛金']);
        $worksheet->setCellValue('D53', $recommendStandardPremium->getRecommendPremium($estimate->property_detail['recommend1'])['１年更新の場合より']);
        $worksheet->setCellValue('I49', $estimate->property_detail['recommend2']);
        $worksheet->setCellValue('I51', $recommendStandardPremium->getRecommendPremium($estimate->property_detail['recommend2'])['共済掛金']);
        $worksheet->setCellValue('I53', $recommendStandardPremium->getRecommendPremium($estimate->property_detail['recommend2'])['１年更新の場合より']);
        $worksheet->setCellValue('N49', $estimate->property_detail['recommend3']);
        $worksheet->setCellValue('N51', $recommendStandardPremium->getRecommendPremium($estimate->property_detail['recommend3'])['共済掛金']);
        $worksheet->setCellValue('N53', $recommendStandardPremium->getRecommendPremium($estimate->property_detail['recommend3'])['１年更新の場合より']);
    }

    private function setCalcInfo($spreadSheet, $estimate)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
