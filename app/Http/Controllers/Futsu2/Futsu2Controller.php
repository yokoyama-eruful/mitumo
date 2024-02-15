<?php

declare(strict_types=1);

namespace App\Http\Controllers\Futsu2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Futsu2\SaveRequest;
use App\Http\Requests\TopRequest;
use App\Services\Futsu2\Futsu2Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Futsu2Controller extends Controller
{
    public function __construct(
        private Futsu2Service $futsu2Service,
    ) {
    }

    public function create(): \Illuminate\View\View
    {
        $estimate = $this->futsu2Service->create();

        return view('futsu2.edit', ['estimate' => $estimate]);
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        return view('futsu2.edit', ['estimate' => $estimate]);
    }

    public function save($id, SaveRequest $request): \Illuminate\Http\RedirectResponse
    {
        $params = $request->params();
        $params['user_id'] = Auth::id();

        if ($id === 'new') {
            $estimate = $this->futsu2Service->store($params);
        } else {
            $estimate = $this->futsu2Service->update(intval($id), $params);
        }

        $redirectName = match ($request->submit_type) {
            'preview' => 'futsu2.preview.standard',
            'download' => 'futsu2.download.index',
            'assessSheet' => 'futsu2.assess.show',
            default => 'futsu2.edit',
        };

        Session::flash('message', '保存しました。_'.now());

        return redirect()->route($redirectName, ['id' => $estimate->id]);
    }

    public function duplication(TopRequest $request, int $id)
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $estimateType = $request->getEstimateType();
        $this->futsu2Service->duplication($id);

        return redirect()->route('top.index', compact('estimateType'));
    }

    public function destroy(TopRequest $request, int $id)
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $estimateType = $request->getEstimateType();
        $this->futsu2Service->destroy($id);

        return redirect()->route('top.index', compact('estimateType'));
    }
}
