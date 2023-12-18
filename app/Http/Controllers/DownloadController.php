<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\ComprehensiveCompensation;
use App\Domains\ComprehensiveInsurancePremium;
use App\Domains\EarthquakeComprehensiveInsurancePremium;
use App\Domains\EarthquakeStandardInsurancePremium;
use App\Domains\MutualAidPremiums;
use App\Domains\RecommendComprehensiveInsurancePremium;
use App\Domains\RecommendStandardInsurancePremium;
use App\Domains\StandardCompensation;
use App\Domains\StandardInsurancePremium;
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
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $standardCompensation = new StandardCompensation($estimate->property_type);

        $this->downloadService->dropStandard($estimate, $standardPremium, $comprehensivePremium, $standardCompensation);
    }

    //総合火災見積書ダウンロード
    public function comprehensive(int $id): void
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveCompensation = new ComprehensiveCompensation($estimate->property_type, $shortTerm->shortTermRate);

        $this->downloadService->dropComprehensive($estimate, $standardPremium, $comprehensivePremium, $comprehensiveCompensation);
    }

    //普通&総合火災見積書ダウンロード
    public function standardAndComprehensive(int $id): void
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $standardCompensation = new StandardCompensation($estimate->property_type);
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveCompensation = new ComprehensiveCompensation($estimate->property_type, $shortTerm->shortTermRate);

        $this->downloadService->dropStandardAndComprehensive($estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation);
    }

    //普通火災見積書(おすすめ契約あり)ダウンロード
    public function standardOfRecommend(int $id): void
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());

        $this->downloadService->dropStandardOfRecommend($estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium);
    }

    //総合火災見積書(おすすめ契約あり)ダウンロード
    public function comprehensiveOfRecommend(int $id): void
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $recommendComprehensivePremium = new RecommendComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());

        $this->downloadService->dropComprehensiveOfRecommend($estimate, $standardPremium, $comprehensivePremium, $recommendComprehensivePremium);
    }

    //普通&総合火災見積書(おすすめ契約あり)ダウンロード
    public function standardOfRecommendAndComprehensiveOfRecommend(int $id): void
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $recommendComprehensivePremium = new RecommendComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());

        $this->downloadService->dropStandardOfRecommendAndComprehensiveOfRecommend($estimate, $standardPremium, $comprehensivePremium, $recommendStandardPremium, $recommendComprehensivePremium);
    }

    //普通火災見積書(地震特約あり)ダウンロード
    public function standardOfEarthquake(int $id): void
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $earthquakePremium = new EarthquakeStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());

        $this->downloadService->dropStandardOfEarthquake($estimate, $standardPremium, $comprehensivePremium, $earthquakePremium);
    }

    //総合火災見積書(地震特約あり)ダウンロード
    public function comprehensiveOfEarthquake(int $id): void
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $earthquakePremium = new EarthquakeComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());

        $this->downloadService->dropComprehensiveOfEarthquake($estimate, $standardPremium, $comprehensivePremium, $earthquakePremium);
    }

    //すべてダウンロード
    public function all(int $id): void
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);
        $standardCompensation = new StandardCompensation($estimate->property_type);
        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveCompensation = new ComprehensiveCompensation($estimate->property_type, $shortTerm->shortTermRate);
        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $recommendComprehensivePremium = new RecommendComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());
        $earthquakePremium = new EarthquakeStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $earthquakePremium = new EarthquakeComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());
        $this->downloadService->dropAll($estimate, $standardPremium, $comprehensivePremium, $standardCompensation, $comprehensiveCompensation, $recommendStandardPremium, $recommendComprehensivePremium, $earthquakePremium, $earthquakePremium);
    }
}
