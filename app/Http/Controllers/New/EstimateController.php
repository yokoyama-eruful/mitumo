<?php

declare(strict_types=1);

namespace App\Http\Controllers\New;

use App\Http\Requests\Estimate\SaveRequest;
use App\Http\Requests\TopRequest;
use App\Services\EstimateService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final class EstimateController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
    ) {
    }

    public function create(): \Illuminate\View\View
    {
        $estimate = $this->estimateService->create();

        return view('new.estimate.edit', ['estimate' => $estimate]);
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $estimate = $this->estimateService->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        return view('new.estimate.edit', ['estimate' => $estimate]);
    }

    public function save($id, SaveRequest $request): \Illuminate\Http\RedirectResponse
    {
        $params = $request->params();
        $params['user_id'] = Auth::id();

        if ($id === 'new') {
            $estimate = $this->estimateService->store($params);
        } else {
            $estimate = $this->estimateService->update((int) $id, $params);
        }

        $redirectName = match ($request->submit_type) {
            'preview' => 'new.estimate.preview.standard',
            'download' => 'new.estimate.download.index',
            'assessSheet' => 'estimate.assess.show',
            default => 'new.estimate.edit',
        };

        Session::flash('message', '保存しました。_'.now());

        return redirect()->route($redirectName, ['id' => $estimate->id]);
    }

    public function duplication(TopRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $estimate = $this->estimateService->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $this->estimateService->duplication($id);

        return redirect()->route('new.top.type', ['type' => 'futsu']);
    }

    public function destroy(TopRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $estimate = $this->estimateService->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $this->estimateService->destroy($id);

        return redirect()->route('new.top.type', ['type' => 'futsu']);
    }
}
