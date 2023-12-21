<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

final class AuthController extends Controller
{
public function test()
{
    return view('test');
}

    public function index()
    {
        return view('auth.index');
    }

    public function login(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        return redirect()->route('top.index');
    }

    public function logout()
    {
        Auth::logout();

        return Redirect()->route('auth.index');
    }
}
