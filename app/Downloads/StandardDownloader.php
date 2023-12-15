<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardDownloader
{
    public function dropFile(
        $estimate,
        $standardInsurancePremium,
        $standardCompensation
    ) {

        $filePath = storage_path('app/estimateTemplate/standard.xlsx');

        $fileName = '普通火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $standardCompensation = [
            'G49' => $standardCompensation->coverageOptions['火災'],
            'G50' => $standardCompensation->coverageOptions['落雷'],
            'G51' => $standardCompensation->coverageOptions['破裂または爆発'],
            'G52' => $standardCompensation->coverageOptions['風災・雪災'],
            'G53' => $standardCompensation->coverageOptions['物体の落下・衝突'],
            'G54' => $standardCompensation->coverageOptions['騒じょう・労働争議'],
            'G55' => $standardCompensation->coverageOptions['水ぬれ'],
            'G56' => $standardCompensation->coverageOptions['盗難'],
            'G57' => $standardCompensation->coverageOptions['水害'],
            'P49' => $standardCompensation->paymentOptions['残存物取り片付け費用'],
            'P50' => $standardCompensation->paymentOptions['失火見舞費用'],
            'P51' => $standardCompensation->paymentOptions['地震火災費用'],
            'P52' => $standardCompensation->paymentOptions['修理付帯費用'],
            'P53' => $standardCompensation->paymentOptions['損害防止費用'],
        ];

        $this->setStandardInfo($spreadSheet, $estimate, $standardInsurancePremium, $standardCompensation);

        $this->setCalcInfo($spreadSheet, $estimate);

        $spreadSheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardInfo($spreadSheet, $estimate, $standardInsurancePremium, $standardCompensation)
    {
        $worksheet = $spreadSheet->getSheetByName('普通');
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

        $worksheet->setCellValue('O41', $standardInsurancePremium->getStandardPremium());
        foreach ($standardCompensation as $key => $standardCompensation) {
            $worksheet->setCellValue($key, $standardCompensation);
        }

    }

    private function setCalcInfo($spreadSheet, $estimate)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
