<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Estimate;

final class EstimateService
{
    public function paginate()
    {

    }

    public function create(): Estimate
    {
        return Estimate::firstOrNew();
    }

    public function find(int $id): Estimate
    {
        return Estimate::first($id);
    }

    public function store(array $params): Estimate
    {
        return Estimate::create($params);
    }

    public function update(int $id, array $params): Estimate
    {
        Estimate::where($id, $id)->update($params);
        $estimate = Estimate::find($id);

        return $estimate;
    }
}
