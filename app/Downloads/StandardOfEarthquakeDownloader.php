<?php

declare(strict_types=1);

namespace App\Downloads;

use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class StandardOfEarthquakeDownloader
{
    public function dropFile(
        $estimate,
        $standardPremium,
        $comprehensivePremium,
        $earthquakePremium,
        $fileType,
        $keisyo
    ) {
        $filePath = storage_path('app/estimateTemplate/standardOfEarthquake.xlsx');

        if ($fileType === 'excel') {
            $fileName = '地震おすすめ_普通火災共済見積書_' .$estimate->contractee. date('YmdHi') . '.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;');
            header("Content-Disposition: attachment; filename*=UTF-8''" . rawurlencode($fileName));
            header('Cache-Control: max-age=0');
        }

        $reader = new Xlsx();
        $spreadSheet = $reader->load($filePath);

        $this->setStandardOfEarthquakeInfo($spreadSheet, $estimate, $standardPremium, $earthquakePremium, $keisyo);

        $this->setCalcInfo($spreadSheet, $standardPremium, $comprehensivePremium);

        $spreadSheet->setActiveSheetIndex(0);

        $writer = IOFactory::createWriter($spreadSheet, 'Xlsx');

        if ($fileType === 'pdf') {
            Storage::makeDirectory('/excel');
            Storage::makeDirectory('/pdf');

            $export_excel_path = storage_path('app/excel/standardOfEarthquake.xlsx');

            $writer->save($export_excel_path);

            $export_pdf_path = storage_path('app/pdf');
            if (file_exists($export_excel_path)) {
                $cmd = 'export HOME=/tmp && /usr/bin/soffice --language=ja --headless --convert-to pdf --outdir ' . $export_pdf_path . ' ' . $export_excel_path;
                exec($cmd);
            }

            $pdf = storage_path('app/pdf/standardOfEarthquake.pdf');
            $fileName = '地震おすすめ_普通火災共済見積書_' .$estimate->contractee. date('YmdHi')  . '.pdf';

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

    private function setStandardOfEarthquakeInfo($spreadSheet, $estimate, $standardPremium, $earthquakePremium, $keisyo)
    {
        $worksheet = $spreadSheet->getSheetByName('地震おすすめ(普通)');
        $worksheet->setCellValue('O1', date('Y年m月d日'));
        $worksheet->setCellValue('A3', $estimate->contractee.$keisyo);
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
        $worksheet->setCellValue('D41', thousandYenUnit(array_key_exists('building_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['building_insurance_payout'] : ''));
        $worksheet->setCellValue('K41', thousandYenUnit(array_key_exists('household_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['household_insurance_payout'] : ''));
        $worksheet->setCellValue('D43', thousandYenUnit(array_key_exists('facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['facility_insurance_payout'] : ''));
        $worksheet->setCellValue('K43', thousandYenUnit(array_key_exists('item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['item_insurance_payout'] : ''));
        $worksheet->setCellValue('D45', thousandYenUnit(array_key_exists('other_facility_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_facility_insurance_payout'] : ''));
        $worksheet->setCellValue('K45', thousandYenUnit(array_key_exists('other_item_insurance_payout', $estimate->property_detail) ? $estimate->property_detail['other_item_insurance_payout'] : ''));

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
