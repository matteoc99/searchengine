<?php

namespace App\Services;

use App\Models\Site;

class SiteService extends BaseService
{

    protected function model(): string
    {
        return Site::class;
    }
}
