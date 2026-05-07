<?php

declare(strict_types=1);

namespace App\Http\Controllers\Old;

use App\Http\Requests\Account\SaveRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Session;

class AccountController extends Controller
{
    public function __construct(
        private UserService $userService,
    ) {

    }

    public function index()
    {
        $userList = $this->userService->paginate();

        return view('old.account.index', compact('userList'));
    }

    public function create(): \Illuminate\View\View
    {
        return view('old.account.edit');
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $user = $this->userService->find($id);

        return view('old.account.edit', compact('user'));
    }

    public function save($id, SaveRequest $request): \Illuminate\Http\RedirectResponse
    {
        $params = $request->params();
        if ($id === 'new') {
            $user = $this->userService->store($params);
        } else {
            $user = $this->userService->update(intval($id), $params);
        }

        session::flash('message', '保存しました。_'.now());
        $userList = $this->userService->paginate();

        return redirect()->route('account.index', compact('userList'));
    }

    public function destroy(int $id)
    {
        $this->userService->destroy($id);
        $userList = $this->userService->paginate();

        return redirect()->route('account.index', compact('userList'));
    }
}
