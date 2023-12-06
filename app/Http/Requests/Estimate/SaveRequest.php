<?php

declare(strict_types=1);

namespace App\Http\Requests\Estimate;

use Illuminate\Foundation\Http\FormRequest;

final class SaveRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }

    public function params(): array
    {
        return [

        ];
    }
}
