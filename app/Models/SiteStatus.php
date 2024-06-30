<?php

namespace App\Models;

enum SiteStatus: int
{
    case UNKNOWN = 1;
    case SUCCESS = 2;
    case REDIRECTED = 3;
    case FAILED = 4;
    case BROKEN = 9;


    public static function group(string $group): array
    {
        return match ($group) {
            'done' => [self::SUCCESS, self::REDIRECTED, self::BROKEN],
            default => [],
        };
    }

}
