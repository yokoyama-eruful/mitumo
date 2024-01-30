<?php

declare(strict_types=1);

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

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
            'staff_id' => ['required', 'string'],
            'name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['present', 'confirmed'],
            'password_confirmation' => ['present'],
        ];
    }

    public function params(): array
    {
        if (is_null($this->password)) {
            return [
                'staff_id' => $this->staff_id,
                'name' => $this->name,
                'email' => $this->email,
            ];
        }

        return [
            'staff_id' => $this->staff_id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ];
    }
}
