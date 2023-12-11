<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\EstimateService;

class DownloadController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
    ) {
    }

    public function index(int $id)
    {
        $estimate = $this->estimateService->find($id);

        return view('download.index', compact('estimate'));
    }
}
