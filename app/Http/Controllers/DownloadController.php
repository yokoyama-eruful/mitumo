<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Domains\MutualAidPremiums;
use App\Http\Requests\Estimate\DownloadRequest;
use App\Services\DownloadService;
use App\Services\EstimateService;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{
    public function __construct(
        private EstimateService $estimateService,
        private DownloadService $downloadService,
    ) {
    }

    public function index(DownloadRequest $request, int $id)
    {
        $estimate = $this->estimateService->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $fileType = $request->getFileType();
        $keisyo = $request->getKeisyo();

        $estimateType = [
            '普通火災見積', '総合火災見積', '普通火災見積&総合火災見積',
            '普通火災見積おすすめ契約', '総合火災見積おすすめ契約',
            '普通火災見積&総合火災見積おすすめ契約',
            '普通火災見積地震おすすめ契約',
            '総合火災見積地震おすすめ契約', 'すべて',
        ];

        $termRate = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $shortTermRate = $termRate->shortTermRate;

        $estimateTypeList = $this->downloadService->getEnableDisableList($estimate, $estimateType);

        return view('download.index', compact('estimate', 'fileType', 'keisyo', 'estimateTypeList', 'shortTermRate'));
    }

    public function download(DownloadRequest $request, int $id, $category): void
    {

        $fileType = $request->fileType;
        $keisyo = $request->keisyo;

        $estimate = $this->estimateService->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        ob_start();
        $this->notifyDownloadCompletion((string) $request->input('download_token', ''));
        $this->downloadService->dropEstimate($category, $estimate, $fileType, $keisyo);
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
