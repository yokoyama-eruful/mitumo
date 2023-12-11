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
        $this->downloadService->dropStandard($id);

        return view('download.index', compact('estimate'));
    }

    //総合火災見積書ダウンロード
    public function comprehensive(int $id)
    {
        $this->downloadService->dropComprehensive($id);

        return view('download.index', compact('estimate'));
    }

    //普通&総合火災見積書ダウンロード
    public function standardAndComprehensive(int $id)
    {
        $this->downloadService->dropStandardAndComprehensive($id);

        return view('download.index', compact('estimate'));
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    public function standardOfRecommend(int $id)
    {
        $this->downloadService->dropStandardOfRecommend($id);

        return view('download.index', compact('estimate'));
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    public function comprehensiveOfRecommend(int $id)
    {
        $this->downloadService->dropComprehensiveOfRecommend($id);

        return view('download.index', compact('estimate'));
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    public function standardOfRecommendAndComprehensiveOfRecommend(int $id)
    {
        $this->downloadService->dropStandardOfRecommendAndComprehensiveOfRecommend($id);

        return view('download.index', compact('estimate'));
    }

    //普通火災見積書(地震特約あり)ダウンロード
    public function standardOfEarthquake(int $id)
    {
        $this->downloadService->dropStandardOfEarthquake($id);

        return view('download.index', compact('estimate'));
    }

    //総合火災見積書(地震特約あり)ダウンロード
    public function comprehensiveOfEarthquake(int $id)
    {
        $this->downloadService->dropComprehensiveOfEarthquake($id);

        return view('download.index', compact('estimate'));
    }

    //すべてダウンロード
    public function all(int $id)
    {
        $this->downloadService->dropAll($id);

        return view('download.index', compact('estimate'));
    }
}
