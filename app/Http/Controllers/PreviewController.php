<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\ComprehensiveCompensation;
use App\Domains\ComprehensiveInsurancePremium;
use App\Domains\MutualAidPremiums;
use App\Domains\RecommendComprehensiveInsurancePremium;
use App\Domains\RecommendStandardInsurancePremium;
use App\Domains\StandardCompensation;
use App\Domains\StandardInsurancePremium;
use App\Services\EstimateService;

class PreviewController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
    ) {
    }

    public function standard(int $id): \Illuminate\View\View
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);

        $standardCompensation = new StandardCompensation($estimate->property_type);

        return view('preview.standard', compact('estimate', 'standardPremium', 'standardCompensation'));
    }

    public function comprehensive(int $id)
    {
        $estimate = $this->estimateService->find($id);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);

        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveCompensation = new ComprehensiveCompensation($estimate->property_type, $shortTerm->shortTermRate);

        return view('preview.comprehensive', compact('estimate', 'comprehensivePremium', 'comprehensiveCompensation'));
    }

    public function standardAndComprehensive(int $id)
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);

        $standardCompensation = new StandardCompensation($estimate->property_type);

        $shortTerm = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $comprehensiveCompensation = new ComprehensiveCompensation($estimate->property_type, $shortTerm->shortTermRate);

        return view('preview.standard-and-comprehensive', compact('estimate', 'standardPremium', 'comprehensivePremium', 'standardCompensation', 'comprehensiveCompensation'));
    }

    public function standardOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);

        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());

        return view('preview.standard-of-recommend', compact('estimate', 'standardPremium', 'recommendStandardPremium'));
    }

    public function comprehensiveOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);

        $recommendComprehensivePremium = new RecommendComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());

        return view('preview.comprehensive-of-recommend', compact('estimate', 'comprehensivePremium', 'recommendComprehensivePremium'));
    }

    public function standardOfRecommendAndComprehensiveOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);

        $recommendStandardPremium = new RecommendStandardInsurancePremium($estimate, $standardPremium->getStandardPremium());
        $recommendComprehensivePremium = new RecommendComprehensiveInsurancePremium($estimate, $comprehensivePremium->getComprehensivePremium());

        return view('preview.standard-of-recommend-and-comprehensive-or-recommend', compact('estimate', 'standardPremium', 'comprehensivePremium', 'recommendStandardPremium', 'recommendComprehensivePremium'));
    }

    public function standardOfEarthquake(int $id)
    {
        $estimate = $this->estimateService->find($id);
        $standardPremium = new StandardInsurancePremium($estimate);

        return view('preview.standard-of-earthquake', compact('estimate', 'standardPremium'));
    }

    public function comprehensiveOfEarthquake(int $id)
    {
        $estimate = $this->estimateService->find($id);
        $comprehensivePremium = new ComprehensiveInsurancePremium($estimate);

        return view('preview.comprehensive-of-earthquake', compact('estimate', 'comprehensivePremium'));
    }
}
