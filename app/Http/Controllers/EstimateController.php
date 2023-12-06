<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Estimate\SaveRequest;

final class EstimateController extends Controller
{
    public function __construct()
    {

    }

    public function create()
    {
        return view('estimate.edit');
    }

    public function edit($id)
    {
        return view('estimate.edit');
    }

    public function save($id, SaveRequest $request)
    {
    }
}
