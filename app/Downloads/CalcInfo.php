<?php

declare(strict_types=1);

namespace App\Downloads;

class CalcInfo
{
    public static function setSheet($spreadSheet, $standardPremium, $comprehensivePremium)
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
        $worksheet->setCellValue('E25', $standardPremium->estimate->workerCountClass());
        $worksheet->setCellValue('E26', $standardPremium->chattelSurcharge);
        $worksheet->setCellValue('E27', $standardPremium->building['短期率']);
        $worksheet->setCellValue('E28', $standardPremium->building['長期率']);
        $worksheet->setCellValue('E29', $standardPremium->actualLossFactor);

        $worksheet->setCellValue('L21', percentUnit($standardPremium->discountFireExtinguisher));
        $worksheet->setCellValue('L22', percentUnit($standardPremium->discountSecurity));
        $worksheet->setCellValue('L23', percentUnit($standardPremium->discountPublicProperty));
        $worksheet->setCellValue('L24', percentUnit($standardPremium->discountRiskRate));
        $worksheet->setCellValue('L25', $standardPremium->otherDiscount);
        $worksheet->setCellValue('L26', $standardPremium->getPayByLongTermFlag() == true ? '3%' : '');
    }
}
