<?php

namespace App\Crawlers;

use App\Models\SiteStatus;
use App\Services\SiteService;
use App\Utils\UrlHelper;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlProfiles\CrawlProfile;

class SiteCrawlerProfile extends CrawlProfile
{


    public function __construct(protected $force = false) { }

    public function shouldCrawl(UriInterface $url): bool
    {

        return $this->force || !SiteService::instance()->where([
                'url_hash' => UrlHelper::formatAndHashUrl($url)
            ])->whereIn('status', SiteStatus::group('done'))
                ->exists();
    }
}
