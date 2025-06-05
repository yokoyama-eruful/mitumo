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
            'A3' => $estimate->contractee ? $estimate->contractee.$keisyo : '',
            'R5' => $estimate->company,
            'R6' => $estimate->address,
            'R7' => $estimate->phone_number ? 'ＴＥＬ ： '.$estimate->phone_number : '',
            'R8' => $estimate->staff_name ? '担当 ： '.$estimate->staff_name : '',
            'D20' => $estimate->location,
            'D23' => $estimate->BuildingStructure,
            'D27' => $estimate->OtherBuildingStructure,
            'O22' => $estimate->property_type,
            'D31' => $estimate->rank,
            'M31' => $estimate->pay_method,
            'D33' => $estimate->InsurancePeriod,
            'D35' => $estimate->OtherItem,
            'D41' => thousandYenUnit(array_key_exists('building_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['building_insurance_payout'] : ''),
            'K41' => thousandYenUnit(array_key_exists('household_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['household_insurance_payout'] : ''),
            'D43' => thousandYenUnit(array_key_exists('facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['facility_insurance_payout'] : ''),
            'K43' => thousandYenUnit(array_key_exists('item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['item_insurance_payout'] : ''),
            'D45' => thousandYenUnit(array_key_exists('other_facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_facility_insurance_payout'] : ''),
            'K45' => thousandYenUnit(array_key_exists('other_item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_item_insurance_payout'] : ''),
        ];

        $estimateType = [
            '普通火災見積', '総合火災見積', '普通火災見積&総合火災見積',
            '普通火災見積おすすめ契約', '総合火災見積おすすめ契約',
            '普通火災見積&総合火災見積おすすめ契約',
            '普通火災見積地震おすすめ契約',
            '総合火災見積地震おすすめ契約', 'すべて',
        ];

        $estimateTypeList = $this->getEnableDisableList($estimate, $estimateType);

        if ($estimateTypeList['普通火災見積'] === 'enable') {
            $this->setStandardInfo($spreadSheet, $standardPremium, $standardCompensation, $mutualItem);
        } else {
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($spreadSheet->getSheetByName('普通')));
        }

        if ($estimateTypeList['総合火災見積'] === 'enable') {
            $this->setComprehensiveInfo($spreadSheet, $comprehensivePremium, $comprehensiveCompensation, $mutualItem);
        } else {
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($spreadSheet->getSheetByName('総合')));
        }

        if ($estimateTypeList['普通火災見積&総合火災見積'] === 'enable') {
            $this->setStandardAndComprehensiveInfo($spreadSheet, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation, $mutualItem);
        } else {
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($spreadSheet->getSheetByName('普通&総合')));
        }

        if ($estimateTypeList['普通火災見積おすすめ契約'] === 'enable') {
            $this->setStandardOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $recommendStandardPremium, $mutualItem);
        } else {
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($spreadSheet->getSheetByName('おすすめ(普通)')));
        }

        if ($estimateTypeList['総合火災見積おすすめ契約'] === 'enable') {
            $this->setComprehensiveOfRecommendInfo($spreadSheet, $estimate, $comprehensivePremium, $recommendComprehensivePremium, $mutualItem);
        } else {
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($spreadSheet->getSheetByName('おすすめ(総合)')));
        }

        if ($estimateTypeList['普通火災見積&総合火災見積おすすめ契約'] === 'enable') {
            $this->setStandardOfRecommendAndComprehensiveOfRecommendInfo($spreadSheet, $estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium, $mutualItem);
        } else {
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($spreadSheet->getSheetByName('おすすめ(普&総)')));
        }

        if ($estimateTypeList['普通火災見積地震おすすめ契約'] === 'enable') {
            $this->setStandardOfEarthquakeInfo($spreadSheet, $estimate, $standardPremium, $standardEarthquakePremium, $mutualItem);
        } else {
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($spreadSheet->getSheetByName('地震おすすめ(普通)')));
        }

        if ($estimateTypeList['総合火災見積地震おすすめ契約'] === 'enable') {
            $this->setComprehensiveOfEarthquakeInfo($spreadSheet, $estimate, $comprehensivePremium, $comprehensiveEarthquakePremium, $mutualItem);
        } else {
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($spreadSheet->getSheetByName('地震おすすめ(総合)')));
        }

        CalcInfo::setSheet($spreadSheet, $standardPremium, $comprehensivePremium);

        if ($fileType === 'pdf') {
            $objSheet = $spreadSheet->getSheetByName('計算');
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($objSheet));

            $spreadSheet->setActiveSheetIndex(0);
            $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/all'.$estimate->id.'.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/all'.$estimate->id.'.pdf');
            $fileName = '火災共済見積書_' .$estimate->contractee . date('YmdHi') . '.pdf';

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

    //突貫で入れたもの（共通化するべき）
    public function getEnableDisableList($estimate, $estimateType)
    {
        $enableDisableList = [];

        foreach ($estimateType as $estimateType) {

            if (str_contains($estimateType, '総合') &&
            ($estimate->property_type == '工場')) {
                $enableDisableList = array_merge($enableDisableList, [$estimateType => 'disable']);

                continue;
            }

            if (in_array(
                $estimateType,
                ['普通火災見積おすすめ契約', '総合火災見積おすすめ契約', '普通火災見積&総合火災見積おすすめ契約'],
                true) && ! array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail)) {

                $enableDisableList = array_merge($enableDisableList, [$estimateType => 'disable']);

                continue;
            }

            if (in_array(
                $estimateType,
                ['普通火災見積地震おすすめ契約', '総合火災見積地震おすすめ契約', '普通火災見積&総合火災見積地震おすすめ契約'],
                true) && ! array_keys_exists(['earthquake1', 'earthquake2', 'earthquake3'], $estimate->property_detail)) {
                $enableDisableList = array_merge($enableDisableList, [$estimateType => 'disable']);

                continue;
            }

            $enableDisableList = array_merge($enableDisableList, [$estimateType => 'enable']);
        }

        return $enableDisableList;
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    private function setStandardInfo($spreadSheet, $standardInsurancePremium, $standardCompensation, $mutualItems)
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
        foreach ($mutualItems as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $standardInsurancePremium->getStandardPremium());

        $value = ($mutualItems['M31'] == '分割払' && $standardInsurancePremium->getStandardPremium() < 1000) ? '※掛金が1000円未満の場合、分割払いできません' : '';
        $worksheet->setCellValue('O46', $value);

        $worksheet->getStyle('O46')->getFont()->getColor()->setRGB('FF0000');

        foreach ($standardCompensation as $key => $standardCompensation) {
            $worksheet->setCellValue($key, $standardCompensation);
        }
    }

    private function setComprehensiveInfo($spreadSheet, $comprehensivePremium, $comprehensiveCompensation, $mutualItems)
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
        foreach ($mutualItems as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $comprehensivePremium->getComprehensivePremium());

        $value = ($mutualItems['M31'] == '分割払' && $comprehensivePremium->getComprehensivePremium() < 1000) ? '※掛金が1000円未満の場合、分割払いできません' : '';
        $worksheet->setCellValue('O46', $value);

        $worksheet->getStyle('O46')->getFont()->getColor()->setRGB('FF0000');

        foreach ($comprehensiveCompensation as $key => $comprehensiveCompensation) {
            $worksheet->setCellValue($key, $comprehensiveCompensation);
        }
    }

    private function setStandardAndComprehensiveInfo($spreadSheet, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation, $mutualItems)
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
        foreach ($mutualItems as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $comprehensivePremium->getComprehensivePremium());
        $worksheet->setCellValue('O44', $standardPremium->getStandardPremium());

        $value = ($mutualItems['M31'] == '分割払' && ($comprehensivePremium->getComprehensivePremium() < 1000 || $standardPremium->getStandardPremium() < 1000)) ? '※掛金が1000円未満の場合、分割払いできません' : '';
        $worksheet->setCellValue('O46', $value);

        $worksheet->getStyle('O46')->getFont()->getColor()->setRGB('FF0000');

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

    private function setStandardOfEarthquakeInfo($spreadSheet, $estimate, $standardPremium, $earthquakePremium, $mutualItem)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(普通)');
        foreach ($mutualItem as $key => $mutualItem) {
            $worksheet->setCellValue($key, $mutualItem);
        }

        $worksheet->setCellValue('O41', $standardPremium->getStandardPremium());

        $value = ($estimate->pay_method == '分割払' && $standardPremium->getStandardPremium() < 1000) ? '※掛金が1000円未満の場合、分割払いできません' : '';
        $worksheet->setCellValue('O46', $value);

        $worksheet->getStyle('O46')->getFont()->getColor()->setRGB('FF0000');

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

        $value = ($estimate->pay_method == '分割払' && $comprehensivePremium->getComprehensivePremium() < 1000) ? '※掛金が1000円未満の場合、分割払いできません' : '';
        $worksheet->setCellValue('O46', $value);

        $worksheet->getStyle('O46')->getFont()->getColor()->setRGB('FF0000');

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
