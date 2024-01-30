<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopRequest extends FormRequest
{
    public function getEstimateType()
    {
        return $this->query('estimateType') ?? 'futsu';
    }
}
