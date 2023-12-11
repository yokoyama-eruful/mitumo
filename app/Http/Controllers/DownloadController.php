<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\DownloadService;
use App\Services\EstimateService;

class DownloadController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
        private DownloadService $downloadService,
    ) {
    }

    public function index(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('download.index', compact('estimate'));
    }

    //普通火災見積書ダウンロード
    public function standard(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropStandard($estimate);

        return view('download.index', compact('estimate'));
    }

    //総合火災見積書ダウンロード
    public function comprehensive(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropComprehensive($estimate);

        return view('download.index', compact('estimate'));
    }

    //普通&総合火災見積書ダウンロード
    public function standardAndComprehensive(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropStandardAndComprehensive($estimate);

        return view('download.index', compact('estimate'));
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    public function standardOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropStandardOfRecommend($estimate);

        return view('download.index', compact('estimate'));
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    public function comprehensiveOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropComprehensiveOfRecommend($estimate);

        return view('download.index', compact('estimate'));
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    public function standardOfRecommendAndComprehensiveOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropStandardOfRecommendAndComprehensiveOfRecommend($estimate);

        return view('download.index', compact('estimate'));
    }

    //普通火災見積書(地震特約あり)ダウンロード
    public function standardOfEarthquake(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropStandardOfEarthquake($estimate);

        return view('download.index', compact('estimate'));
    }

    //総合火災見積書(地震特約あり)ダウンロード
    public function comprehensiveOfEarthquake(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropComprehensiveOfEarthquake($estimate);

        return view('download.index', compact('estimate'));
    }

    //すべてダウンロード
    public function all(int $id)
    {
        $estimate = $this->estimateService->find($id);

        $this->downloadService->dropAll($estimate);

        return view('download.index', compact('estimate'));
    }
}
