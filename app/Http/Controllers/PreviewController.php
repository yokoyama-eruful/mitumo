<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class PreviewController extends Controller
{
    public function standard(): \Illuminate\View\View
    {
        return view('preview.standard');
    }

    public function comprehensive()
    {
        return view('preview.comprehensive');
    }

    public function standardAndComprehensive()
    {
        return view('preview.standardAndComprehensive');
    }

    public function standardOfRecommend()
    {
        return view('preview.standardOfRecommend');
    }

    public function comprehensiveOfRecommend()
    {
        return view('preview.comprehensiveOfRecommend');
    }

    public function standardOfRecommendAndComprehensiveOfRecommend()
    {
        return view('preview.standardOfRecommendAndComprehensiveOfRecommend');
    }

    public function standardOfEarthquake()
    {
        return view('preview.standardOfEarthquake');
    }

    public function comprehensiveOfEarthquake()
    {
        return view('preview.comprehensiveOfEarthquake');
    }
}
