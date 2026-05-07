<?php

declare(strict_types=1);

namespace App\Http\Controllers\New\Futsu2;

use App\Http\Requests\Futsu2\SaveRequest;
use App\Http\Requests\TopRequest;
use App\Services\Futsu2\Futsu2Service;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

final class Futsu2Controller extends Controller
{
    public function __construct(
        private Futsu2Service $futsu2Service,
    ) {
    }

    public function create(): \Illuminate\View\View
    {
        $estimate = $this->futsu2Service->create();

        return view('new.futsu2.edit', ['estimate' => $estimate]);
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        return view('new.futsu2.edit', ['estimate' => $estimate]);
    }

    public function save($id, SaveRequest $request): \Illuminate\Http\RedirectResponse
    {
        $params = $request->params();
        $params['user_id'] = Auth::id();

        if ($id === 'new') {
            $estimate = $this->futsu2Service->store($params);
        } else {
            $estimate = $this->futsu2Service->update((int) $id, $params);
        }

        $redirectName = match ($request->submit_type) {
            'preview' => 'new.futsu2.preview.standard',
            'download' => 'new.futsu2.download.index',
            'assessSheet' => 'futsu2.assess.show',
            default => 'new.futsu2.edit',
        };

        Session::flash('message', '保存しました。_'.now());

        return redirect()->route($redirectName, ['id' => $estimate->id]);
    }

    public function duplication(TopRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $this->futsu2Service->duplication($id);

        return redirect()->route('new.top.type', ['type' => 'futsu2']);
    }

    public function destroy(TopRequest $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $this->futsu2Service->destroy($id);

        return redirect()->route('new.top.type', ['type' => 'futsu2']);
    }
}
