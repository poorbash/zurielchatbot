<?php

use Illuminate\Support\Arr;

function appConfig(string $path): bool|int|string|array
{
    return Arr::get($GLOBALS['config'], $path);
}

function appStr(string $path): string|array
{
    return Arr::get($GLOBALS['strings'], $path);
}