<?php

declare(strict_types=1);

if (! function_exists('array_keys_exists')) {
    function array_keys_exists(array $keys, $array)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $array)) {
                return true;
            }
        }

        return false;
    }
}
