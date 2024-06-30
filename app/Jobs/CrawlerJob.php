<?php

namespace App\Jobs;

use App\Crawlers\CrawlerOptions;
use App\Crawlers\SiteCrawlerObserver;
use App\Crawlers\SiteCrawlerProfile;
use App\Models\SiteStage;
use App\Utils\UrlHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;

class CrawlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(
        protected CrawlerOptions $options
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $crawler = Crawler::create()
            ->addCrawlObserver(new SiteCrawlerObserver($this->options->stage))
            ->setTotalCrawlLimit($this->options->limit)
            ->setUserAgent("MinimalisticEngine/1.0 (https://searchengine.matteocosi.com)")
            ->setConcurrency(2)
            ->setMaximumDepth($this->options->depth)
            ->setDelayBetweenRequests(rand(10, 100))
            ->setCrawlProfile(new SiteCrawlerProfile($this->options->force_crawl))
            ->setConcurrency(4);

        if ($this->options == SiteStage::PUPPETEER) {
            $browser = new Browsershot();
            $browser->timeout(300);
            $browser->newHeadless();
            if (App::isLocal()) {
                $browser = $browser->noSandbox();
            }

            $crawler->executeJavaScript()
                ->setBrowsershot($browser);
        }
        $crawler->startCrawling(UrlHelper::formatUrlForCrawler($this->options->url));
    }
}
