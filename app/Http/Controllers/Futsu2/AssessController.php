<?php

declare(strict_types=1);

namespace App\Http\Controllers\Futsu2;

use App\Http\Controllers\Controller;
use App\Services\AssessDownloadService;
use App\Services\Futsu2\Futsu2Service;
use Illuminate\Support\Facades\Auth;

class AssessController extends Controller
{
    public function __construct(
        private Futsu2Service $futsu2Service,
        private AssessDownloadService $assessDownloadService,
    ) {

    }

    public function show(int $id)
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        return view('futsu2.assess.show', ['estimate' => $estimate]);
    }

    public function download(int $id, $fileType)
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $this->assessDownloadService->download($estimate, $fileType);

    }
}
