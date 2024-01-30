<?php

declare(strict_types=1);

namespace App\Services\Futsu2;

use App\Models\Futsu2;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

final class Futsu2Service
{
    public function paginate(int $pageCount = 10): LengthAwarePaginator
    {
        return Futsu2::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($pageCount);
    }

    public function create(): Futsu2
    {
        return Futsu2::where('id', 'new')->firstOrNew();
    }

    public function find(int $id): Futsu2
    {
        return Futsu2::find($id);
    }

    public function store(array $params): Futsu2
    {
        return Futsu2::create($params);
    }

    public function update(int $id, array $params): Futsu2
    {
        Futsu2::where('id', $id)->update($params);

        return Futsu2::find($id);
    }

    public function duplication($id)
    {
        $originalRecord = Futsu2::find($id);

        if ($originalRecord) {
            $newRecord = $originalRecord->replicate();

            $newRecord->save();
        }
    }

    public function destroy(int $id): void
    {
        Futsu2::destroy($id);
    }
}
