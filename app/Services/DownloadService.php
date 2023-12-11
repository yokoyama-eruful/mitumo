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
    public function dropStandard()
    {
        $standardEstimate = new StandardDownloader();
        $estimate = [];
        $calcData = [];

        $standardEstimate->dropFile($estimate, $calcData);
    }

    //総合火災見積書ダウンロード
    public function dropComprehensive()
    {
        $comprehensiveEstimate = new ComprehensiveDownloader();
        $standardDate = [];
        $calcData = [];

        $comprehensiveEstimate->dropFile($standardDate, $calcData);
    }

    //普通&総合火災見積書ダウンロード
    public function dropStandardAndComprehensive()
    {
        $standardAndComprehensiveEstimate = new StandardAndComprehensiveDownloader();
        $comprehensiveDate = [];
        $calcData = [];

        $standardAndComprehensiveEstimate->dropFile($comprehensiveDate, $calcData);
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    public function dropStandardOfRecommend()
    {
        $standardOfRecommendEstimate = new StandardOfRecommendDownloader();
        $standardOfRecommendDate = [];
        $calcData = [];

        $standardOfRecommendEstimate->dropFile($standardOfRecommendDate, $calcData);
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    public function dropComprehensiveOfRecommend()
    {
        $comprehensiveOfRecommendEstimate = new ComprehensiveOfRecommendDownloader();
        $comprehensiveOfRecommendDate = [];
        $calcData = [];

        $comprehensiveOfRecommendEstimate->dropFile($comprehensiveOfRecommendDate, $calcData);
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    public function dropStandardOfRecommendAndComprehensiveOfRecommend()
    {
        $standardOfRecommendAndComprehensiveOfRecommendEstimate = new StandardOfRecommendAndComprehensiveOfRecommendDownloader();
        $standardOfRecommendAndComprehensiveOfRecommendDate = [];
        $calcData = [];

        $standardOfRecommendAndComprehensiveOfRecommendEstimate->dropFile($standardOfRecommendAndComprehensiveOfRecommendDate, $calcData);
    }

    //普通火災見積書(地震特約あり)ダウンロード
    public function dropStandardOfEarthquake()
    {
        $standardOfEarthquakeEstimate = new StandardOfEarthquakeDownloader();
        $standardOfEarthquakeDate = [];
        $calcData = [];

        $standardOfEarthquakeEstimate->dropFile($standardOfEarthquakeDate, $calcData);
    }

    //総合火災見積書(地震特約あり)ダウンロード
    public function dropComprehensiveOfEarthquake()
    {
        $comprehensiveOfEarthquakeEstimate = new ComprehensiveOfEarthquakeDownloader();
        $comprehensiveOfEarthquakeDate = [];
        $calcData = [];

        $comprehensiveOfEarthquakeEstimate->dropFile($comprehensiveOfEarthquakeDate, $calcData);
    }

    public function dropAll()
    {
        $allEstimate = new AllDownloader();
        $standardDate = [];
        $comprehensiveDate = [];
        $comprehensiveOfEarthquakeDate = [];
        $comprehensiveOfRecommendDate = [];
        $standardAndComprehensiveDate = [];
        $standardOfEarthquakeDate = [];
        $standardOfRecommendAndComprehensiveOfRecommendDate = [];
        $standardOfRecommendDate = [];
        $calcData = [];

        $allEstimate->dropFile($standardDate,
            $comprehensiveDate,
            $comprehensiveOfEarthquakeDate,
            $comprehensiveOfRecommendDate,
            $standardAndComprehensiveDate,
            $standardOfEarthquakeDate,
            $standardOfRecommendAndComprehensiveOfRecommendDate,
            $standardOfRecommendDate, $calcData);
    }
}
