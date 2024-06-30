<?php

namespace App\Utils;

use Illuminate\Support\Str;

class UrlHelper
{
    public static function formatAndHashUrl(string $value): string
    {

        return hash('sha256', self::formatUrl($value));
    }

    public static function formatUrl(string $value): string
    {
        if (!Str::startsWith($value, ['http://', 'https://'])) {
            $value = 'https://' . $value;
        }
        if (!Str::endsWith($value, ['/'])) {
            $value = $value . "/";
        }

        return trim($value);
    }

    public static function formatUrlForCrawler(string $value): string
    {
        if (!Str::startsWith($value, ['http://', 'https://'])) {
            $value = 'https://' . $value;
        }

        return rtrim($value, '/');
    }
}
