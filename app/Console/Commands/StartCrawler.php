<?php

namespace App\Console\Commands;

use App\Jobs\CrawlerJob;
use App\Models\Site;
use App\Services\SiteService;
use Illuminate\Console\Command;

class StartCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-crawler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sites = SiteService::instance()->all();

        foreach ($sites as $site) {
            CrawlerJob::dispatch($site->url);
        }

        return 0;
    }
}
