<?php

declare(strict_types=1);

namespace App\Http\Controllers\Futsu2;

use App\Domains\MutualAidPremiums;
use App\Http\Controllers\Controller;
use App\Http\Requests\Estimate\DownloadRequest;
use App\Services\Futsu2\DownloadService;
use App\Services\Futsu2\Futsu2Service;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{
    public function __construct(
        private Futsu2Service $futsu2Service,
        private DownloadService $downloadService,
    ) {
    }

    public function index(DownloadRequest $request, int $id)
    {
        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $fileType = $request->getFileType();
        $keisyo = $request->getKeisyo();

        $estimateType = [
            '普通火災Ⅱ見積',
            '普通火災Ⅱ見積おすすめ契約',
            '普通火災Ⅱ見積地震おすすめ契約',
        ];

        $termRate = new MutualAidPremiums($estimate, $estimate->insurance_year);
        $shortTermRate = $termRate->shortTermRate;

        $estimateTypeList = $this->downloadService->getEnableDisableList($estimate, $estimateType);

        return view('futsu2.download.index', compact('estimate', 'fileType', 'keisyo', 'estimateTypeList', 'shortTermRate'));
    }

    public function download(DownloadRequest $request, int $id, $category): void
    {

        $fileType = $request->fileType;
        $keisyo = $request->keisyo;

        $estimate = $this->futsu2Service->find($id);

        if ($estimate->user_id != Auth::id()) {
            abort(404);
        }

        $this->downloadService->dropEstimate($category, $estimate, $fileType, $keisyo);
    }
}
