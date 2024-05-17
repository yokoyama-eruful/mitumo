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
        return to_route('top.type', [
            'type' => 'futsu',
        ]);
    }

    public function type(TopRequest $request, $type)
    {
        return view('top.index', [
            'type' => $type,
        ]);
    }

    public function show()
    {
        $versionDomain = new Version();
        $versionList = $versionDomain->rateList;

        return view('top.show', compact('versionList'));
    }
}
