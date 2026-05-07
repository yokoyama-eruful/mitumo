<?php

declare(strict_types=1);

namespace App\Http\Controllers\New;

use App\Domains\Version;
use App\Http\Requests\TopRequest;
use Illuminate\Routing\Controller;

final class TopController extends Controller
{
    public function index(TopRequest $request)
    {
        return to_route('new.top.type', [
            'type' => 'futsu',
        ]);
    }

    public function type(TopRequest $request, string $type)
    {
        if (! in_array($type, ['futsu', 'futsu2'], true)) {
            abort(404);
        }

        return view('new.top.index', [
            'type' => $type,
        ]);
    }

    public function show()
    {
        $versionDomain = new Version();
        $versionList = $versionDomain->rateList;

        return view('new.top.show', compact('versionList'));
    }
}
