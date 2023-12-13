<?php

declare(strict_types=1);

namespace App\Utils;

class FullWidthDigitConverter
{
    public static function convert($input)
    {
        $converted = mb_convert_kana($input, 'n', 'UTF-8');

        if (! is_numeric($converted)) {
            return;
        }

        return $converted;
    }
}
