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
    public function standard(int $id): void
    {
        $this->downloadService->dropStandard($id);
    }

    //総合火災見積書ダウンロード
    public function comprehensive(int $id): void
    {
        $this->downloadService->dropComprehensive($id);
    }

    //普通&総合火災見積書ダウンロード
    public function standardAndComprehensive(int $id): void
    {
        $this->downloadService->dropStandardAndComprehensive($id);
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    public function standardOfRecommend(int $id): void
    {
        $this->downloadService->dropStandardOfRecommend($id);
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    public function comprehensiveOfRecommend(int $id): void
    {
        $this->downloadService->dropComprehensiveOfRecommend($id);
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    public function standardOfRecommendAndComprehensiveOfRecommend(int $id): void
    {
        $this->downloadService->dropStandardOfRecommendAndComprehensiveOfRecommend($id);
    }

    //普通火災見積書(地震特約あり)ダウンロード
    public function standardOfEarthquake(int $id): void
    {
        $this->downloadService->dropStandardOfEarthquake($id);
    }

    //総合火災見積書(地震特約あり)ダウンロード
    public function comprehensiveOfEarthquake(int $id): void
    {
        $this->downloadService->dropComprehensiveOfEarthquake($id);
    }

    //すべてダウンロード
    public function all(int $id): void
    {
        $this->downloadService->dropAll($id);
    }
}
