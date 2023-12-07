<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Estimate\SaveRequest;
use App\Services\EstimateService;
use Illuminate\Support\Facades\Auth;

final class EstimateController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
    ) {
    }

    public function create(): \Illuminate\View\View
    {
        $estimate = $this->estimateService->create();

        return view('estimate.edit', ['estimate' => $estimate]);
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $estimate = $this->estimateService->find($id);

        return view('estimate.edit', ['estimate' => $estimate]);
    }

    public function save(int $id, SaveRequest $request): \Illuminate\Http\RedirectResponse
    {
        $params = $request->params();
        $params['user_id'] = Auth::id();

        if ($id == 'new') {
            $estimate = $this->estimateService->store($params);
        } else {
            $estimate = $this->estimateService->update($id, $params);
        }

        return redirect()->route('estimate.edit', ['id' => $estimate->id]);
    }
}
