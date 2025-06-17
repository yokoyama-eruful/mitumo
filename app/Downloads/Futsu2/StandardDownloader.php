<?php

declare(strict_types=1);

namespace App\Downloads\Futsu2;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $standardCompensation,
        $fileType,
        $keisyo
    ) {
        $filePath = storage_path('app/estimateFutsu2/estimateTemplate/standard.xlsx');

        if ($fileType === 'excel') {
            $fileName = '普通火災共済Ⅱ見積書_' .$estimate->contractee. date('YmdHi') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');
        }

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $standardCompensation = [
            'J49' => $standardCompensation->coverageOptions['火災'],
            'J50' => $standardCompensation->coverageOptions['落雷'],
            'J51' => $standardCompensation->coverageOptions['破裂または爆発'],
            'J52' => $standardCompensation->coverageOptions['風災・雪災'],
            'J53' => $standardCompensation->coverageOptions['物体の落下・衝突'],
            'J54' => $standardCompensation->coverageOptions['騒じょう・労働争議'],
            'J55' => $standardCompensation->coverageOptions['水ぬれ'],
            'J56' => $standardCompensation->coverageOptions['盗難'],
            'J57' => $standardCompensation->coverageOptions['水害'],
        ];

        $this->setStandardInfo($spreadSheet, $estimate, $standardPremium, $standardCompensation, $keisyo);

        CalcInfo::setSheet($spreadSheet, $standardPremium);

        if ($fileType === 'pdf') {
            $objSheet = $spreadSheet->getSheetByName('計算');
            $spreadSheet->removeSheetByIndex($spreadSheet->getIndex($objSheet));

            $spreadSheet->setActiveSheetIndex(0);
            $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/standard'.$estimate->id.'.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/standard'.$estimate->id.'.pdf');
            $fileName = '普通火災共済Ⅱ見積書_' .$estimate->contractee. date('YmdHi'). '.pdf';

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

    private function setStandardInfo($spreadSheet, $estimate, $standardPremium, $standardCompensation, $keisyo)
    {
        $worksheet = $spreadSheet->getSheetByName('普通');
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

        $worksheet->setCellValue('O41', $standardPremium->getStandardPremium());

        $value = ($estimate->pay_method == '分割払' && $standardPremium->getStandardPremium() < 1000) ? '※掛金が1000円未満の場合、分割払いできません' : '';
        $worksheet->setCellValue('O46', $value);

        $worksheet->getStyle('O46')->getFont()->getColor()->setRGB('FF0000');

        foreach ($standardCompensation as $key => $standardCompensation) {
            $worksheet->setCellValue($key, $standardCompensation);
        }

    }
}
