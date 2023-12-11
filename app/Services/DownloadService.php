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
use App\Models\Estimate;

final class DownloadService
{
    //普通火災見積書ダウンロード
    public function dropStandard($id)
    {
        $estimate = Estimate::find($id);
        $standardDate = [];
        $calcData = [];

        $standardEstimate = new StandardDownloader();
        $standardEstimate->dropFile($standardDate, $calcData);
    }

    //総合火災見積書ダウンロード
    public function dropComprehensive($id)
    {
        //見積もり情報の取得
        $estimate = Estimate::find($id);
        $comprehensiveDate = [];
        $calcData = [];

        $comprehensiveEstimate = new ComprehensiveDownloader();
        $comprehensiveEstimate->dropFile($comprehensiveDate, $calcData);
    }

    //普通&総合火災見積書ダウンロード
    public function dropStandardAndComprehensive($id)
    {
        $estimate = Estimate::find($id);
        $comprehensiveDate = [];
        $calcData = [];

        $standardAndComprehensiveEstimate = new StandardAndComprehensiveDownloader();
        $standardAndComprehensiveEstimate->dropFile($comprehensiveDate, $calcData);
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    public function dropStandardOfRecommend($id)
    {
        $estimate = Estimate::find($id);
        $standardOfRecommendDate = [];
        $calcData = [];

        $standardOfRecommendEstimate = new StandardOfRecommendDownloader();
        $standardOfRecommendEstimate->dropFile($standardOfRecommendDate, $calcData);
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    public function dropComprehensiveOfRecommend($id)
    {
        $estimate = Estimate::find($id);
        $comprehensiveOfRecommendDate = [];
        $calcData = [];

        $comprehensiveOfRecommendEstimate = new ComprehensiveOfRecommendDownloader();
        $comprehensiveOfRecommendEstimate->dropFile($comprehensiveOfRecommendDate, $calcData);
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    public function dropStandardOfRecommendAndComprehensiveOfRecommend($id)
    {
        $estimate = Estimate::find($id);
        $standardOfRecommendAndComprehensiveOfRecommendDate = [];
        $calcData = [];

        $standardOfRecommendAndComprehensiveOfRecommendEstimate = new StandardOfRecommendAndComprehensiveOfRecommendDownloader();
        $standardOfRecommendAndComprehensiveOfRecommendEstimate->dropFile($standardOfRecommendAndComprehensiveOfRecommendDate, $calcData);
    }

    //普通火災見積書(地震特約あり)ダウンロード
    public function dropStandardOfEarthquake($id)
    {
        $estimate = Estimate::find($id);
        $standardOfEarthquakeDate = [];
        $calcData = [];

        $standardOfEarthquakeEstimate = new StandardOfEarthquakeDownloader();
        $standardOfEarthquakeEstimate->dropFile($standardOfEarthquakeDate, $calcData);
    }

    //総合火災見積書(地震特約あり)ダウンロード
    public function dropComprehensiveOfEarthquake($id)
    {
        $estimate = Estimate::find($id);
        $comprehensiveOfEarthquakeDate = [];
        $calcData = [];

        $comprehensiveOfEarthquakeEstimate = new ComprehensiveOfEarthquakeDownloader();
        $comprehensiveOfEarthquakeEstimate->dropFile($comprehensiveOfEarthquakeDate, $calcData);
    }

    public function dropAll($id)
    {
        $estimate = Estimate::find($id);
        $standardDate = [];
        $comprehensiveDate = [];
        $comprehensiveOfEarthquakeDate = [];
        $comprehensiveOfRecommendDate = [];
        $standardAndComprehensiveDate = [];
        $standardOfEarthquakeDate = [];
        $standardOfRecommendAndComprehensiveOfRecommendDate = [];
        $standardOfRecommendDate = [];
        $calcData = [];

        $allEstimate = new AllDownloader();
        $allEstimate->dropFile(
            $standardDate,
            $comprehensiveDate,
            $comprehensiveOfEarthquakeDate,
            $comprehensiveOfRecommendDate,
            $standardAndComprehensiveDate,
            $standardOfEarthquakeDate,
            $standardOfRecommendAndComprehensiveOfRecommendDate,
            $standardOfRecommendDate,
            $calcData
        );
    }
}
