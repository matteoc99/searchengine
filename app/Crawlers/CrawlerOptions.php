<?php

namespace App\Crawlers;

use App\Models\SiteStage;

class CrawlerOptions
{

    public function __construct(
        public string    $url,
        public SiteStage $stage = SiteStage::PLAIN_HTTP,
        public int       $limit = 100,
        public int       $depth = 10,
        public bool      $force_crawl = false,

    )
    {
    }

}
