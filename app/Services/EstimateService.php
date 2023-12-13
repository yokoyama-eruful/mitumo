<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Estimate;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

final class EstimateService
{
    public function paginate(int $pageCount = 10): LengthAwarePaginator
    {
        return Estimate::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate($pageCount);
    }

    public function create(): Estimate
    {
        return Estimate::where('id', 'new')->firstOrNew();
    }

    public function find(int $id): Estimate
    {
        return Estimate::find($id);
    }

    public function store(array $params): Estimate
    {
        return Estimate::create($params);
    }

    public function update(int $id, array $params): Estimate
    {
        Estimate::where('id', $id)->update($params);

        return Estimate::find($id);
    }

    public function destroy(int $id): void
    {
        Estimate::destroy($id);
    }
}
