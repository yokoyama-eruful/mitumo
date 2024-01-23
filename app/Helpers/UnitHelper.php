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

        return number_format($amount).'円';
    }

}

if (! function_exists('thousandYenUnit')) {
    function thousandYenUnit($amount)
    {
        if (! $amount) {

            return '';
        }

        //全角文字・数字が含まれているか判定
        if (preg_match('/^[^\x01-\x7E\xA1-\xDF]+$/', (string) $amount) > 0) {
            return $amount;
        }

        return number_format((float) $amount).'千円';
    }
}

if (! function_exists('yearUnit')) {
    function yearUnit($year)
    {
        if (! $year) {
            return '';
        }

        //全角文字・数字が含まれているか判定
        if (preg_match('/[^\x01-\x7E\xA1-\xDF]/u', (string) $year) > 0) {
            return $year;
        }

        return $year.'年';
    }
}

if (! function_exists('percentUnit')) {
    function percentUnit($percent)
    {
        if (! $percent) {
            return '';
        }

        //全角文字・数字が含まれているか判定
        if (preg_match('/[^\x01-\x7E\xA1-\xDF]/u', (string) $percent) > 0) {
            return $percent;
        }

        return $percent.'%';
    }
}

if (! function_exists('gainUnit')) {
    function gainUnit($percent)
    {
        if (! $percent) {
            return '';
        }

        //全角文字・数字が含まれているか判定
        if (preg_match('/[^\x01-\x7E\xA1-\xDF]/u', (string) $percent) > 0) {
            return $percent;
        }

        return $percent.'円お得';
    }
}
