<?php

declare(strict_types=1);

namespace App\Services;

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
    //普通火災見積書ダウンロード
    public function dropStandard($estimate, $standardPremium, $comprehensivePremium, $standardCompensation)
    {
        $standardEstimate = new StandardDownloader();
        $standardEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $standardCompensation);
    }

    //総合火災見積書ダウンロード
    public function dropComprehensive($estimate, $standardPremium, $comprehensivePremium, $comprehensiveCompensation)
    {
        $comprehensiveEstimate = new ComprehensiveDownloader();
        $comprehensiveEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $comprehensiveCompensation);
    }

    //普通&総合火災見積書ダウンロード
    public function dropStandardAndComprehensive($estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation)
    {
        $standardAndComprehensiveEstimate = new StandardAndComprehensiveDownloader();
        $standardAndComprehensiveEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation);
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    public function dropStandardOfRecommend($estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium)
    {
        $standardOfRecommendEstimate = new StandardOfRecommendDownloader();
        $standardOfRecommendEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium);
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    public function dropComprehensiveOfRecommend($estimate, $standardPremium, $comprehensivePremium, $recommendComprehensivePremium)
    {
        $comprehensiveOfRecommendEstimate = new ComprehensiveOfRecommendDownloader();
        $comprehensiveOfRecommendEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $recommendComprehensivePremium);
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    public function dropStandardOfRecommendAndComprehensiveOfRecommend($estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium)
    {
        $standardOfRecommendAndComprehensiveOfRecommendEstimate = new StandardOfRecommendAndComprehensiveOfRecommendDownloader();
        $standardOfRecommendAndComprehensiveOfRecommendEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium);
    }

    //普通火災見積書(地震特約あり)ダウンロード
    public function dropStandardOfEarthquake($estimate, $standardPremium, $comprehensivePremium, $earthquakePremium)
    {
        $standardOfEarthquakeEstimate = new StandardOfEarthquakeDownloader();
        $standardOfEarthquakeEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $earthquakePremium);
    }

    //総合火災見積書(地震特約あり)ダウンロード
    public function dropComprehensiveOfEarthquake($estimate, $standardPremium, $comprehensivePremium, $earthquakePremium)
    {
        $comprehensiveOfEarthquakeEstimate = new ComprehensiveOfEarthquakeDownloader();
        $comprehensiveOfEarthquakeEstimate->dropFile($estimate, $standardPremium, $comprehensivePremium, $earthquakePremium);
    }

    public function dropAll($estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation, $recommendStandardPremium, $recommendComprehensivePremium, $standardEarthquakePremium, $comprehensiveEarthquakePremium)
    {
        $allEstimate = new AllDownloader();
        $allEstimate->dropFile(
            $estimate,
            $standardPremium,
            $comprehensivePremium,
            $standardCompensation,
            $comprehensiveCompensation,
            $recommendStandardPremium,
            $recommendComprehensivePremium,
            $standardEarthquakePremium,
            $comprehensiveEarthquakePremium
        );
    }
}
