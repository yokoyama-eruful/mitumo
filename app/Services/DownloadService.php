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

        $standardEstimate->dropFile($estimate);
    }

    //総合火災見積書ダウンロード
    public function dropComprehensive()
    {
        $comprehensiveEstimate = new ComprehensiveDownloader();
        $estimate = [];

        $comprehensiveEstimate->dropFile($estimate);
    }

    //普通&総合火災見積書ダウンロード
    public function dropStandardAndComprehensive()
    {
        $standardAndComprehensiveEstimate = new StandardAndComprehensiveDownloader();
        $estimate = [];

        $standardAndComprehensiveEstimate->dropFile($estimate);
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    public function dropStandardOfRecommend()
    {
        $standardOfRecommendEstimate = new StandardOfRecommendDownloader();
        $estimate = [];

        $standardOfRecommendEstimate->dropFile($estimate);
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    public function dropComprehensiveOfRecommend()
    {
        $comprehensiveOfRecommendEstimate = new ComprehensiveOfRecommendDownloader();
        $estimate = [];

        $comprehensiveOfRecommendEstimate->dropFile($estimate);
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    public function dropStandardOfRecommendAndComprehensiveOfRecommend()
    {
        $standardOfRecommendAndComprehensiveOfRecommendEstimate = new StandardOfRecommendAndComprehensiveOfRecommendDownloader();
        $estimate = [];

        $standardOfRecommendAndComprehensiveOfRecommendEstimate->dropFile($estimate);
    }

    //普通火災見積書(地震特約あり)ダウンロード
    public function dropStandardOfEarthquake()
    {
        $standardOfEarthquakeEstimate = new StandardOfEarthquakeDownloader();
        $estimate = [];

        $standardOfEarthquakeEstimate->dropFile($estimate);
    }

    //総合火災見積書(地震特約あり)ダウンロード
    public function dropComprehensiveOfEarthquake()
    {
        $comprehensiveOfEarthquakeEstimate = new ComprehensiveOfEarthquakeDownloader();
        $estimate = [];

        $comprehensiveOfEarthquakeEstimate->dropFile($estimate);
    }

    public function dropAll()
    {
        $allEstimate = new AllDownloader();
        $estimate = [];

        $allEstimate->dropFile($estimate);
    }
}
