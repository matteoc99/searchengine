<?php

namespace Database\Seeders;

use App\Services\SiteService;
use Illuminate\Database\Seeder;

class SitesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteService::instance()->updateOrCreate(['url' => 'matteocosi.com']);
    }
}
