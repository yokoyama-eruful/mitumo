<?php

declare(strict_types=1);

namespace App\Http\Controllers\New;

use App\Http\Requests\Account\SaveRequest;
use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

final class AccountController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {
    }

    public function index(): View
    {
        $userList = $this->userService->paginate();

        return view('new.account.index', compact('userList'));
    }

    public function create(): View
    {
        return view('new.account.edit');
    }

    public function edit(int $id): View
    {
        $user = $this->userService->find($id);

        return view('new.account.edit', compact('user'));
    }

    public function save($id, SaveRequest $request)
    {
        $params = $request->params();

        if ($id === 'new') {
            $this->userService->store($params);
        } else {
            $this->userService->update((int) $id, $params);
        }

        Session::flash('message', '保存しました。_'.now());

        return redirect()->route('new.account.index');
    }

    public function destroy(int $id)
    {
        $this->userService->destroy($id);

        return redirect()->route('new.account.index');
    }
}
