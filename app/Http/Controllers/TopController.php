<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\Version;
use App\Http\Requests\TopRequest;
use App\Services\EstimateService;
use App\Services\Futsu2\Futsu2Service;

final class TopController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
        private Futsu2Service $futsu2Service,
    ) {

    }

    public function index(TopRequest $request)
    {
        $estimateType = $request->getEstimateType();
        if ($estimateType == 'futsu') {
            $estimateList = $this->estimateService->paginate(10);
        } else {
            $estimateList = $this->futsu2Service->paginate(10);
        }

        $versionDomain = new Version();

        return view('top.index', compact('estimateList', 'estimateType', 'versionDomain'));
    }

    public function show()
    {
        $versionDomain = new Version();
        $versionList = $versionDomain->rateList;

        return view('top.show', compact('versionList'));
    }
}
