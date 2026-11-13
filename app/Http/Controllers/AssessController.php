<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\AssessDownloadService;
use App\Services\EstimateService;
use Illuminate\Support\Facades\Auth;

class AssessController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
        private AssessDownloadService $assessDownloadService,
    ) {

    }

    public function show(int $id)
    {
        $estimate = $this->estimateService->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        return view('estimate.assess.show', ['estimate' => $estimate]);
    }

    public function download(int $id, $fileType)
    {
        $estimate = $this->estimateService->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        ob_start();
        $this->notifyDownloadCompletion((string) request()->input('download_token', ''));
        $this->assessDownloadService->download($estimate, $fileType);

    }

    private function notifyDownloadCompletion(string $token): void
    {
        if (! preg_match('/^[a-f0-9]{32}$/', $token)) {
            return;
        }

        $secure = request()->isSecure();
        register_shutdown_function(static function () use ($token, $secure): void {
            setcookie('estimate_download_complete', $token, [
                'expires' => time() + 60,
                'path' => '/',
                'secure' => $secure,
                'httponly' => false,
                'samesite' => 'Lax',
            ]);
        });
    }
}
