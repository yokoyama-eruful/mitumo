<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardOfEarthquakeDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $earthquakePremium
    ) {
        $filePath = storage_path('app/estimateTemplate/standardOfEarthquake.xlsx');

        $fileName = '地震おすすめ_普通火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardOfEarthquakeInfo($spreadSheet, $estimate, $standardPremium, $earthquakePremium);

        $this->setCalcInfo($spreadSheet, $estimate);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardOfEarthquakeInfo($spreadSheet, $estimate, $standardPremium, $earthquakePremium)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(普通)');
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

        $worksheet->setCellValue('D49', $estimate->property_detail['earthquake1']);
        $worksheet->setCellValue('D51', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake1'])['共済金支払最大額']);
        $worksheet->setCellValue('D54', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake2'])['地震掛金']);
        $worksheet->setCellValue('D56', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake3'])['合計掛金']);
        $worksheet->setCellValue('I49', $estimate->property_detail['earthquake2']);
        $worksheet->setCellValue('I51', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake1'])['共済金支払最大額']);
        $worksheet->setCellValue('I54', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake2'])['地震掛金']);
        $worksheet->setCellValue('I56', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake3'])['合計掛金']);
        $worksheet->setCellValue('N49', $estimate->property_detail['earthquake3']);
        $worksheet->setCellValue('N51', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake1'])['共済金支払最大額']);
        $worksheet->setCellValue('N54', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake2'])['地震掛金']);
        $worksheet->setCellValue('N56', $earthquakePremium->getEarthquakePremium($estimate->property_detail['earthquake3'])['合計掛金']);
    }

    private function setCalcInfo($spreadSheet, $estimate)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
