<?php

declare(strict_types=1);

namespace App\Services;

use App\Domains\ComprehensiveCompensation;
use App\Domains\ComprehensiveInsurancePremium;
use App\Domains\EarthquakeComprehensiveInsurancePremium;
use App\Domains\EarthquakeStandardInsurancePremium;
use App\Domains\MutualAidPremiums;
use App\Domains\RecommendComprehensiveInsurancePremium;
use App\Domains\RecommendStandardInsurancePremium;
use App\Domains\StandardCompensation;
use App\Domains\StandardInsurancePremium;
use App\Downloads\AllDownloader;
use App\Downloads\ComprehensiveDownloader;
use App\Downloads\ComprehensiveOfEarthquakeDownloader;
use App\Downloads\ComprehensiveOfRecommendDownloader;
use App\Downloads\StandardAndComprehensiveDownloader;
use App\Downloads\StandardDownloader;
use App\Downloads\StandardOfEarthquakeDownloader;
use App\Downloads\StandardOfRecommendAndComprehensiveOfRecommendDownloader;
use App\Downloads\StandardOfRecommendDownloader;

final class DownloadService
{
    public function dropEstimate($category, $estimate, $fileType, $keisyo)
    {
        switch ($category) {
            case '普通火災見積':
                $this->dropStandard($estimate, $fileType, $keisyo);
                break;
            case '総合火災見積':
                $this->dropComprehensive($estimate, $fileType, $keisyo);
                break;
            case '普通火災見積&総合火災見積':
                $this->dropStandardAndComprehensive($estimate, $fileType, $keisyo);
                break;
            case '普通火災見積おすすめ契約':
                $this->dropStandardOfRecommend($estimate, $fileType, $keisyo);
                break;
            case '総合火災見積おすすめ契約':
                $this->dropComprehensiveOfRecommend($estimate, $fileType, $keisyo);
                break;
            case '普通火災見積&総合火災見積おすすめ契約':
                $this->dropStandardOfRecommendAndComprehensiveOfRecommend($estimate, $fileType, $keisyo);
                break;
            case '普通火災見積地震おすすめ契約':
                $this->dropStandardOfEarthquake($estimate, $fileType, $keisyo);
                break;
            case '総合火災見積地震おすすめ契約':
                $this->dropComprehensiveOfEarthquake($estimate, $fileType, $keisyo);
                break;
            default:
                $this->dropAll($estimate, $fileType, $keisyo);
                break;
        }
    }

    //普通火災見積書ダウンロード
    private function dropStandard($estimate, $fileType, $keisyo)
    {
        $standardEstimate = new StandardDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $standardCompensation = new StandardCompensation($estimate->property_type);
        $standardEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $fileType, $keisyo);
    }

    //総合火災見積書ダウンロード
    private function dropComprehensive($estimate, $fileType, $keisyo)
    {
        $comprehensiveEstimate = new ComprehensiveDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveCompensation = new ComprehensiveCompensation($estimate->property_type, $shortTerm->shortTermRate);
        $comprehensiveEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $comprehensiveCompensation, $shortTerm, $fileType, $keisyo);
    }

    //普通&総合火災見積書ダウンロード
    private function dropStandardAndComprehensive($estimate, $fileType, $keisyo)
    {
        $standardAndComprehensiveEstimate = new StandardAndComprehensiveDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $standardCompensation = new StandardCompensation($estimate->property_type);
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveCompensation = new ComprehensiveCompensation($estimate->property_type, $shortTerm->shortTermRate);
        $standardAndComprehensiveEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation, $shortTerm, $fileType, $keisyo);
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    private function dropStandardOfRecommend($estimate, $fileType, $keisyo)
    {
        $standardOfRecommendEstimate = new StandardOfRecommendDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $standardOfRecommendEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $fileType, $keisyo);
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    private function dropComprehensiveOfRecommend($estimate, $fileType, $keisyo)
    {
        $comprehensiveOfRecommendEstimate = new ComprehensiveOfRecommendDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $recommendComprehensivePremium = new RecommendComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveOfRecommendEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $recommendComprehensivePremium, $shortTerm, $fileType, $keisyo);
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    private function dropStandardOfRecommendAndComprehensiveOfRecommend($estimate, $fileType, $keisyo)
    {
        $standardOfRecommendAndComprehensiveOfRecommendEstimate = new StandardOfRecommendAndComprehensiveOfRecommendDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $recommendComprehensivePremium = new RecommendComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $standardOfRecommendAndComprehensiveOfRecommendEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium, $shortTerm, $fileType, $keisyo);
    }

    //普通火災見積書(地震特約あり)ダウンロード
    private function dropStandardOfEarthquake($estimate, $fileType, $keisyo)
    {
        $standardOfEarthquakeEstimate = new StandardOfEarthquakeDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $earthquakePremium = new EarthquakeStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $standardOfEarthquakeEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $earthquakePremium, $fileType, $keisyo);
    }

    //総合火災見積書(地震特約あり)ダウンロード
    private function dropComprehensiveOfEarthquake($estimate, $fileType, $keisyo)
    {
        $comprehensiveOfEarthquakeEstimate = new ComprehensiveOfEarthquakeDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $earthquakePremium = new EarthquakeComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveOfEarthquakeEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $shortTerm, $earthquakePremium, $fileType, $keisyo);
    }

    private function dropAll($estimate, $fileType, $keisyo)
    {
        $allEstimate = new AllDownloader();
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $standardCompensation = new StandardCompensation($estimate->property_type);
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveCompensation = new ComprehensiveCompensation($estimate->property_type, $shortTerm->shortTermRate);
        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $recommendComprehensivePremium = new RecommendComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());
        $standardEarthquakePremium = new EarthquakeStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $comprehensiveEarthquakePremium = new EarthquakeComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());
        $allEstimate->dropFile(
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
        );
    }

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
}
