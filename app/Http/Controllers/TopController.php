<?php

declare(strict_types=1);

namespace App\Http\Controllers;

final class TopController extends Controller
{
    public function index()
    {
        return view('top.index');
    }

    public function edit()
    {
        return view('top.edit');
    }
}
