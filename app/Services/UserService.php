<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final class UserService
{
    public function paginate(int $pageCount = 10): LengthAwarePaginator
    {
        return User::paginate($pageCount);
    }

    public function create(): User
    {
        return User::where('id', 'new')->firstOrNew();
    }

    public function find(int $id): User
    {
        return User::find($id);
    }

    public function store(array $params): User
    {
        return User::create($params);
    }

    public function update(int $id, array $params): User
    {
        User::where('id', $id)->update($params);

        return User::find($id);
    }

    public function destroy(int $id): void
    {
        User::destroy($id);
    }
}
