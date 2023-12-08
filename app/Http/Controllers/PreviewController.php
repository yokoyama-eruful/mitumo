<?php

declare(strict_types=1);

namespace App\Http\Controllers;

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

        return view('preview.standard', compact('estimate'));
    }

    public function comprehensive(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('preview.comprehensive', compact('estimate'));
    }

    public function standardAndComprehensive(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('preview.standard-and-comprehensive', compact('estimate'));
    }

    public function standardOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('preview.standard-of-recommend', compact('estimate'));
    }

    public function comprehensiveOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('preview.comprehensive-of-recommend', compact('estimate'));
    }

    public function standardOfRecommendAndComprehensiveOfRecommend(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('preview.standard-of-recommend-and-comprehensive-or-recommend', compact('estimate'));
    }

    public function standardOfEarthquake(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('preview.standard-of-earthquake', compact('estimate'));
    }

    public function comprehensiveOfEarthquake(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('preview.comprehensive-of-earthquake', compact('estimate'));
    }
}
