<?php

namespace App\Jobs;

use App\Crawlers\SiteHandler;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Spatie\Browsershot\Browsershot;
use Spatie\Crawler\Crawler;

class CrawlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */
    public function __construct(protected string $url)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $browser = new Browsershot();
        if (App::isLocal()) {
            $browser = $browser->noSandbox();
        }

        Crawler::create()
            ->addCrawlObserver(new SiteHandler())
            ->setTotalCrawlLimit(100)
            ->setConcurrency(4)
            ->executeJavaScript()
            ->setBrowsershot($browser)
            ->startCrawling($this->url);
    }
}
