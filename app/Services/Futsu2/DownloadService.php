<?php

declare(strict_types=1);

namespace App\Services\Futsu2;

use App\Domains\EarthquakeStandardInsurancePremium;
use App\Domains\Futsu2Compensation;
use App\Domains\RecommendStandardInsurancePremium;
use App\Domains\StandardInsurancePremium;
use App\Downloads\Futsu2\StandardDownloader;
use App\Downloads\Futsu2\StandardOfEarthquakeDownloader;
use App\Downloads\Futsu2\StandardOfRecommendDownloader;

final class DownloadService
{
    public function dropEstimate($category, $estimate, $fileType, $keisyo)
    {
        switch ($category) {
            case '普通火災Ⅱ見積':
                $this->dropStandard($estimate, $fileType, $keisyo);
                break;
            case '普通火災Ⅱ見積おすすめ契約':
                $this->dropStandardOfRecommend($estimate, $fileType, $keisyo);
                break;
            case '普通火災Ⅱ見積地震おすすめ契約':
                $this->dropStandardOfEarthquake($estimate, $fileType, $keisyo);
                break;
        }
    }

    //普通火災見積書ダウンロード
    private function dropStandard($estimate, $fileType, $keisyo)
    {
        $standardEstimate = new StandardDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $standardCompensation = new Futsu2Compensation($estimate->property_type);
        $standardEstimate->dropFile($estimate, $standardPremium, $standardCompensation, $fileType, $keisyo);
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    private function dropStandardOfRecommend($estimate, $fileType, $keisyo)
    {
        $standardOfRecommendEstimate = new StandardOfRecommendDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $standardOfRecommendEstimate->dropFile($estimate, $standardPremium, $recommendStandardPremium, $fileType, $keisyo);
    }

    //普通火災見積書(地震特約あり)ダウンロード
    private function dropStandardOfEarthquake($estimate, $fileType, $keisyo)
    {
        $earthquakeFlag = true;
        $standardOfEarthquakeEstimate = new StandardOfEarthquakeDownloader();
        $standardPremium = new StandardInsurancePremium($estimate,$earthquakeFlag);
        $earthquakePremium = new EarthquakeStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $standardOfEarthquakeEstimate->dropFile($estimate, $standardPremium, $earthquakePremium, $fileType, $keisyo);
    }

    public function getEnableDisableList($estimate, $estimateType)
    {
        $enableDisableList = [];

        foreach ($estimateType as $estimateType) {

            if (in_array(
                $estimateType,
                ['普通火災Ⅱ見積おすすめ契約'],
                true) && ! array_keys_exists(['recommend1', 'recommend2', 'recommend3'], $estimate->property_detail)) {
                $enableDisableList = array_merge($enableDisableList, [$estimateType => 'disable']);

                continue;
            }

            if (in_array(
                $estimateType,
                ['普通火災Ⅱ見積地震おすすめ契約'],
                true) && ! array_keys_exists(['earthquake1', 'earthquake2', 'earthquake3'], $estimate->property_detail)) {
                $enableDisableList = array_merge($enableDisableList, [$estimateType => 'disable']);

                continue;
            }

            $enableDisableList = array_merge($enableDisableList, [$estimateType => 'enable']);
        }

        return $enableDisableList;
    }
}
