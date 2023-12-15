<?php

declare(strict_types=1);

namespace App\Downloads;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardAndComprehensiveDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $comprehensivePremium,
        $standardCompensation,
        $comprehensiveCompensation
    ) {
        $filePath = storage_path('app/estimateTemplate/standardAndComprehensive.xlsx');

        $fileName = '普通_総合_火災共済見積書_' . date('YmdHi') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
        header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
        header('Cache-Control: max-age=0');

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $standardCompensation = [
            'F49' => $standardCompensation->coverageOptions['火災'],
            'F50' => $standardCompensation->coverageOptions['落雷'],
            'F51' => $standardCompensation->coverageOptions['破裂または爆発'],
            'F52' => $standardCompensation->coverageOptions['風災・雪災'],
            'F53' => $standardCompensation->coverageOptions['物体の落下・衝突'],
            'F54' => $standardCompensation->coverageOptions['騒じょう・労働争議'],
            'F55' => $standardCompensation->coverageOptions['水ぬれ'],
            'F56' => $standardCompensation->coverageOptions['盗難'],
            'F57' => $standardCompensation->coverageOptions['水害'],
            'O49' => $standardCompensation->paymentOptions['残存物取り片付け費用'],
            'O50' => $standardCompensation->paymentOptions['失火見舞費用'],
            'O51' => $standardCompensation->paymentOptions['地震火災費用'],
            'O52' => $standardCompensation->paymentOptions['修理付帯費用'],
            'O53' => $standardCompensation->paymentOptions['損害防止費用'],
        ];

        $comprehensiveCompensation = [
            'H49' => $comprehensiveCompensation->coverageOptions['火災'],
            'H50' => $comprehensiveCompensation->coverageOptions['落雷'],
            'H51' => $comprehensiveCompensation->coverageOptions['破裂または爆発'],
            'H52' => $comprehensiveCompensation->coverageOptions['風災・雪災'],
            'H53' => $comprehensiveCompensation->coverageOptions['物体の落下・衝突'],
            'H54' => $comprehensiveCompensation->coverageOptions['騒じょう・労働争議'],
            'H55' => $comprehensiveCompensation->coverageOptions['水ぬれ'],
            'H56' => $comprehensiveCompensation->coverageOptions['盗難'],
            'H57' => $comprehensiveCompensation->coverageOptions['水害'],
            'Q49' => $comprehensiveCompensation->paymentOptions['残存物取り片付け費用'],
            'Q50' => $comprehensiveCompensation->paymentOptions['失火見舞費用'],
            'Q51' => $comprehensiveCompensation->paymentOptions['地震火災費用'],
            'Q52' => $comprehensiveCompensation->paymentOptions['修理付帯費用'],
            'Q53' => $comprehensiveCompensation->paymentOptions['損害防止費用'],
        ];

        $this->setStandardAndComprehensiveInfo($spreadSheet, $estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation);

        $this->setCalcInfo($spreadSheet, $estimate);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        $writer->save('php://output');

        exit;
    }

    private function setStandardAndComprehensiveInfo($spreadSheet, $estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation)
    {
        $worksheet = $spreadSheet->getSheetByName('普通&総合');
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
        $worksheet->setCellValue('O44', $comprehensivePremium->getComprehensivePremium());

        foreach ($standardCompensation as $key => $standardCompensation) {
            $worksheet->setCellValue($key, $standardCompensation);
        }

        foreach ($comprehensiveCompensation as $key => $comprehensiveCompensation) {
            $worksheet->setCellValue($key, $comprehensiveCompensation);
        }
    }

    private function setCalcInfo($spreadSheet, $estimate)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        // $worksheet->setCellValue('セル番号', $calcData);
    }
}
