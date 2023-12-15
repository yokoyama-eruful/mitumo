<?php

declare(strict_types=1);

if (! function_exists('yenUnit')) {
    function yenUnit($amount)
    {
        if (! $amount) {
            return '';
        }

        //全角文字・数字が含まれているか判定
        if (preg_match('/[^\x01-\x7E\xA1-\xDF]/u', (string) $amount) > 0) {
            return $amount;
        }

        return $amount.'円';
    }
}
