<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Recommend implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value) {
            return;
        }

        if ($value < 2 || $value >= 36) {
            $fail('おすすめ契約は空白か整数値で2~35の間でいけません。');
        }
    }
}
