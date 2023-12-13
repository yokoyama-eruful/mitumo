<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\EstimateService;

final class TopController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
    ) {

    }

    public function index()
    {
        $estimateList = $this->estimateService->paginate(10);

        return view('top.index', ['estimateList' => $estimateList]);
    }
}
