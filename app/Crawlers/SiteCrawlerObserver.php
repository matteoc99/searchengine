<?php

namespace App\Crawlers;

use App\Models\SiteStage;
use App\Models\SiteStatus;
use App\Services\SiteService;
use App\Utils\UrlHelper;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class SiteCrawlerObserver extends CrawlObserver
{
    public function __construct(protected SiteStage $stage = SiteStage::PLAIN_HTTP) { }

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null, ?string $linkText = null): void
    {
        Log::info('Crawled URL', ['url' => (string)$url, 'status_code' => $response->getStatusCode()]);
        $this->handleResponse($response, $url);
    }

    protected function handleResponse(ResponseInterface $response, UriInterface $url): void
    {
        $statusCode   = $response->getStatusCode();
        $formattedUrl = UrlHelper::formatUrl($url);
        $response->getBody()->rewind();

        switch (true) {
            case $statusCode >= 200 && $statusCode < 300:
                $this->updateSite($formattedUrl, SiteStatus::SUCCESS, $this->stage, $statusCode, $response->getBody()->getContents());
                break;
            case $statusCode >= 500 && $statusCode < 600:
                $this->updateSite($formattedUrl, SiteStatus::REDIRECTED, $this->stage, $statusCode);
            case $statusCode >= 300 && $statusCode < 400:
                if ($response->hasHeader('Location')) {
                    $location = $response->getHeaderLine('Location');
                    if (filter_var($location, FILTER_VALIDATE_URL)) {
                        // If the redirect location is a full URL, use it directly
                        $actualUrl = $location;
                    } else {
                        // If the redirect location is a relative path, resolve it against the base URL
                        $actualUrl = rtrim($formattedUrl, '/') . '/' . ltrim($location, '/');
                    }
                    $this->updateSite($actualUrl, SiteStatus::UNKNOWN, $this->stage, $statusCode);
                    $this->updateSite($formattedUrl, SiteStatus::REDIRECTED, $this->stage, $statusCode, [
                        "links" => [$actualUrl]
                    ]);

                } else {
                    $this->updateSite($formattedUrl, SiteStatus::FAILED, $this->stage, $statusCode);
                }
                break;
            case $statusCode >= 400 && $statusCode < 500:
                $this->updateSite($formattedUrl, SiteStatus::FAILED, $this->stage, $statusCode);
                break;
            default:
                $this->updateSite($formattedUrl, SiteStatus::BROKEN, $this->stage, $statusCode);
        }
    }

    protected function updateSite(string $url, SiteStatus $status, SiteStage $stage, int $httpCode, array|string $content = null): void
    {
        $data = [
            'http_code' => $httpCode,
            'status'    => $status,
            'stage'     => $stage,
            'content'   => $content,
            'url'       => $url
        ];

        $data = array_filter($data, fn($value) => !is_null($value));

        SiteService::instance()->updateOrCreate(['url_hash' => UrlHelper::formatAndHashUrl($url)], $data);
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null, ?string $linkText = null): void
    {
        $response = $requestException->getResponse();
        Log::error('Crawl failed', ['url' => (string)$url, 'exception' => $requestException->getMessage()]);

        if ($response instanceof ResponseInterface) {
            $this->handleResponse($response, $url);
        } else {
            report($requestException);
            $this->updateSite($url, SiteStatus::BROKEN, $this->stage, 999);
        }
    }
}
