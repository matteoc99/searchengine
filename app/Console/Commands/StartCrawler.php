<?php

namespace App\Console\Commands;

use App\Crawlers\CrawlerOptionsBuilder;
use App\Jobs\CrawlerJob;
use App\Models\Site;
use App\Models\SiteStage;
use App\Models\SiteStatus;
use App\Services\SiteService;
use Illuminate\Console\Command;

class StartCrawler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start-crawler {--force} {--site_id=} {--status=} {--limit=1} {--depth=1} {--stage=2}';

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

        $id     = $this->option("site_id");
        $limit  = $this->option("limit");
        $depth  = $this->option("depth");
        $stage  = $this->option("stage");
        $force  = $this->option("force");
        $status = $this->option("status");

        $filters = [];
        if (!empty($id)) {
            $filters['id'] = $id;
        }
        if (!empty($status) && SiteStatus::tryFrom($status)) {
            $filters['status'] = SiteStatus::from($status);
        }

        $sites = SiteService::instance()->allWhere($filters);


        /**@var Site $site**/
        foreach ($sites as $site) {
            $options = CrawlerOptionsBuilder::create($site->url)
                ->withStage(SiteStage::tryFrom($stage) ?? SiteStage::PLAIN_HTTP)
                ->withLimit($limit)
                ->withDepth($depth)
                ->setForce($force)
                ->build();
            CrawlerJob::dispatch($options);
        }

        return 0;
    }
}
