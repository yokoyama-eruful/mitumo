<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Estimate\SaveRequest;
use App\Services\EstimateService;
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

        return view('estimate.edit', ['estimate' => $estimate]);
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $estimate = $this->estimateService->find($id);

        return view('estimate.edit', ['estimate' => $estimate]);
    }

    public function save($id, SaveRequest $request): \Illuminate\Http\RedirectResponse
    {
        $params = $request->params();
        $params['user_id'] = Auth::id();

        if ($id === 'new') {
            $estimate = $this->estimateService->store($params);
        } else {
            $estimate = $this->estimateService->update(intval($id), $params);
        }

        $redirectName = match ($request->submit_type) {
            'preview' => 'estimate.preview.standard',
            'download' => 'estimate.download.index',
            'assessSheet' => 'estimate.assess.show',
            default => 'estimate.edit',
        };

        Session::flash('message', '保存しました。_'.now());

        return redirect()->route($redirectName, ['id' => $estimate->id]);
    }

    public function duplication(int $id)
    {
        $this->estimateService->duplication($id);

        return redirect()->route('top.index');
    }

    public function destroy(int $id)
    {
        $this->estimateService->destroy($id);

        return redirect()->back();
    }
}
