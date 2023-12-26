<?php

declare(strict_types=1);

namespace App\Downloads;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class AllDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $comprehensivePremium,
        $standardCompensation,
        $comprehensiveCompensation,
        $recommendStandardPremium,
        $recommendComprehensivePremium,
        $standardEarthquakePremium,
        $comprehensiveEarthquakePremium,
        $fileType,
        $keisyo
    ) {
        $filePath = storage_path('app/estimateTemplate/all.xlsx');

        if ($fileType === 'excel') {
            $fileName = '火災共済見積書_' .$estimate->contractee . date('YmdHi') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');
        }

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $mutualItem = [
            'O1' => date('Y年m月d日'),
            'A3' => $estimate->contractee.$keisyo,
            'R5' => $estimate->company,
            'R6' => $estimate->address,
            'R7' => 'ＴＥＬ ： '.$estimate->phone_number,
            'R8' => '担当 ： '.$estimate->staff_name,
            'D20' => $estimate->location,
            'D23' => $estimate->BuildingStructure,
            'D27' => $estimate->BuildingStructure,
            'O22' => $estimate->property_type,
            'D31' => $estimate->rank,
            'M31' => $estimate->pay_method,
            'M33' => $estimate->InsurancePeriod,
            'D35' => $estimate->OtherItem,
            'D41' => thousandYenUnit(array_key_exists('building_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['building_insurance_payout'] : ''),
            'K41' => thousandYenUnit(array_key_exists('household_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['household_insurance_payout'] : ''),
            'D43' => thousandYenUnit(array_key_exists('facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['facility_insurance_payout'] : ''),
            'K43' => thousandYenUnit(array_key_exists('item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['item_insurance_payout'] : ''),
            'D45' => thousandYenUnit(array_key_exists('other_facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_facility_insurance_payout'] : ''),
            'K45' => thousandYenUnit(array_key_exists('other_item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_item_insurance_payout'] : ''),
        ];

        $this->setStandardInfo($spreadSheet, $standardPremium, $standardCompensation, $mutualItem);

        $this->setComprehensiveInfo($spreadSheet, $comprehensivePremium, $comprehensiveCompensation, $mutualItem);

        $this->setStandardAndComprehensiveInfo($spreadSheet, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation, $mutualItem);

        $this->setStandardOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $recommendStandardPremium, $mutualItem);

        $this->setComprehensiveOfRecommendInfo($spreadSheet, $estimate, $comprehensivePremium, $recommendComprehensivePremium, $mutualItem);

        $this->setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium, $mutualItem);

        $this->setStandardOfEarthquakeInfo($spreadSheet, $estimate, $standardPremium, $standardEarthquakePremium, $mutualItem);

        $this->setComprehensiveOfEarthquakeInfo($spreadSheet, $estimate, $comprehensivePremium, $comprehensiveEarthquakePremium, $mutualItem);

        $this->setCalcInfo($spreadSheet, $standardPremium, $comprehensivePremium);

        $spreadSheet->setActiveSheetIndex(0);
        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        if ($fileType === 'pdf') {
            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/all.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/all.pdf');
            $fileName = '火災共済見積書_' .$estimate->contractee . date('YmdHi') . '.pdf';

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

    private function setStandardInfo($spreadSheet, $standardInsurancePremium, $standardCompensation, $mutualItem)
    {
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

        $worksheet = $spreadSheet->getSheetByName('普通');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $standardInsurancePremium->getStandardPremium());

        foreach ($standardCompensation as $key => $standardCompensation) {
            $worksheet->setCellValue($key, $standardCompensation);
        }
    }

    private function setComprehensiveInfo($spreadSheet, $comprehensivePremium, $comprehensiveCompensation, $mutualItem)
    {
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

        $worksheet = $spreadSheet->getSheetByName('総合');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $comprehensivePremium->getComprehensivePremium());

        foreach ($comprehensiveCompensation as $key => $comprehensiveCompensation) {
            $worksheet->setCellValue($key, $comprehensiveCompensation);
        }
    }

    private function setStandardAndComprehensiveInfo($spreadSheet, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation, $mutualItem)
    {
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

        $worksheet = $spreadSheet->getSheetByName('普通&総合');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $standardPremium->getStandardPremium());
        $worksheet->setCellValue('O44', $comprehensivePremium->getComprehensivePremium());

        foreach ($standardCompensation as $key => $standardCompensation) {
            $worksheet->setCellValue($key, $standardCompensation);
        }

        foreach ($comprehensiveCompensation as $key => $comprehensiveCompensation) {
            $worksheet->setCellValue($key, $comprehensiveCompensation);
        }
    }

    private function setStandardOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $recommendStandardPremium, $mutualItem)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普通)');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

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

    private function setComprehensiveOfRecommendInfo($spreadSheet, $estimate, $comprehensivePremium, $recommendComprehensivePremium, $mutualItem)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(総合)');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $comprehensivePremium->getComprehensivePremium());

        $worksheet->setCellValue('D49', yearUnit(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : ''));
        $worksheet->setCellValue('D51', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : '')['共済掛金']);
        $worksheet->setCellValue('D53', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend1', $estimate->property_detail) ? $estimate->property_detail['recommend1'] : '')['１年更新の場合より']);
        $worksheet->setCellValue('I49', yearUnit(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : ''));
        $worksheet->setCellValue('I51', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : '')['共済掛金']);
        $worksheet->setCellValue('I53', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend2', $estimate->property_detail) ? $estimate->property_detail['recommend2'] : '')['１年更新の場合より']);
        $worksheet->setCellValue('N49', yearUnit(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : ''));
        $worksheet->setCellValue('N51', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : '')['共済掛金']);
        $worksheet->setCellValue('N53', $recommendComprehensivePremium->getRecommendPremium(array_key_exists('recommend3', $estimate->property_detail) ? $estimate->property_detail['recommend3'] : '')['１年更新の場合より']);
    }

    private function setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium, $mutualItem)
    {
        $worksheet = $spreadSheet->getSheetByName('おすすめ(普&総)');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $standardPremium->getStandardPremium());
        $worksheet->setCellValue('O44', $comprehensivePremium->getComprehensivePremium());

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

    private function setStandardOfEarthquakeInfo($spreadSheet, $estimate, $standardPremium, $earthquakePremium, $mutualItem)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(普通)');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $standardPremium->getStandardPremium());

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

    private function setComprehensiveOfEarthquakeInfo($spreadSheet, $estimate, $comprehensivePremium, $earthquakePremium, $mutualItem)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(総合)');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $comprehensivePremium->getComprehensivePremium());

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

    private function setCalcInfo($spreadSheet, $standardPremium, $comprehensivePremium)
    {
        $worksheet = $spreadSheet->getSheetByName('計算');
        $worksheet->setCellValue('E3', $standardPremium->building['基本率']);
        $worksheet->setCellValue('I3', $standardPremium->building['長期率']);
        $worksheet->setCellValue('K3', $standardPremium->building['適用率']);
        $worksheet->setCellValue('M3', $standardPremium->building['掛金']);
        $worksheet->setCellValue('E4', $comprehensivePremium->building['基本率']);
        $worksheet->setCellValue('G4', $comprehensivePremium->building['総合割増']);
        $worksheet->setCellValue('I4', $comprehensivePremium->building['長期率']);
        $worksheet->setCellValue('K4', $comprehensivePremium->building['適用率']);
        $worksheet->setCellValue('M4', $comprehensivePremium->building['掛金']);

        $worksheet->setCellValue('E7', $standardPremium->houseHold['基本率']);
        $worksheet->setCellValue('I7', $standardPremium->houseHold['長期率']);
        $worksheet->setCellValue('K7', $standardPremium->houseHold['適用率']);
        $worksheet->setCellValue('M7', $standardPremium->houseHold['掛金']);
        $worksheet->setCellValue('E8', $comprehensivePremium->houseHold['基本率']);
        $worksheet->setCellValue('G8', $comprehensivePremium->houseHold['総合割増']);
        $worksheet->setCellValue('I8', $comprehensivePremium->houseHold['長期率']);
        $worksheet->setCellValue('K8', $comprehensivePremium->houseHold['適用率']);
        $worksheet->setCellValue('M8', $comprehensivePremium->houseHold['掛金']);

        $worksheet->setCellValue('E11', $standardPremium->facility['基本率']);
        $worksheet->setCellValue('I11', $standardPremium->facility['長期率']);
        $worksheet->setCellValue('K11', $standardPremium->facility['適用率']);
        $worksheet->setCellValue('M11', $standardPremium->facility['掛金']);
        $worksheet->setCellValue('E12', $comprehensivePremium->facility['基本率']);
        $worksheet->setCellValue('G12', $comprehensivePremium->facility['総合割増']);
        $worksheet->setCellValue('I12', $comprehensivePremium->facility['長期率']);
        $worksheet->setCellValue('K12', $comprehensivePremium->facility['適用率']);
        $worksheet->setCellValue('M12', $comprehensivePremium->facility['掛金']);

        $worksheet->setCellValue('E16', $standardPremium->item['基本率']);
        $worksheet->setCellValue('I16', $standardPremium->item['長期率']);
        $worksheet->setCellValue('K16', $standardPremium->item['適用率']);
        $worksheet->setCellValue('M16', $standardPremium->item['掛金']);
        $worksheet->setCellValue('E17', $comprehensivePremium->item['基本率']);
        $worksheet->setCellValue('G17', $comprehensivePremium->item['総合割増']);
        $worksheet->setCellValue('I17', $comprehensivePremium->item['長期率']);
        $worksheet->setCellValue('K17', $comprehensivePremium->item['適用率']);
        $worksheet->setCellValue('M17', $comprehensivePremium->item['掛金']);

        $worksheet->setCellValue('E21', $standardPremium->building['基本率']);
        $worksheet->setCellValue('E22', $standardPremium->houseHold['基本率']);
        $worksheet->setCellValue('E23', $standardPremium->occupationalSurchargeRate);
        $worksheet->setCellValue('E24', $standardPremium->workSurchargeRate);
        $worksheet->setCellValue('E25', $standardPremium->chattelSurcharge);
        $worksheet->setCellValue('E26', $standardPremium->otherDiscount);
        $worksheet->setCellValue('E27', $standardPremium->building['短期率']);
        $worksheet->setCellValue('E28', $standardPremium->building['長期率']);
        $worksheet->setCellValue('E29', $standardPremium->actualLossFactor);
    }
}
