<?php

namespace App\Crawlers;

use App\Services\SiteService;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Spatie\Crawler\CrawlObservers\CrawlObserver;

class SiteHandler extends CrawlObserver
{

    public function crawled(UriInterface $url, ResponseInterface $response, ?UriInterface $foundOnUrl = null, ?string $linkText = null,): void
    {
        $this->handleResponse($response, $url);
    }

    public function crawlFailed(UriInterface $url, RequestException $requestException, ?UriInterface $foundOnUrl = null, ?string $linkText = null,): void
    {
        $this->handleResponse($requestException->getResponse(), $url);
    }

    private function handleResponse(ResponseInterface $response, UriInterface $url)
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode >= 200 && $statusCode < 300) {
            SiteService::instance()->updateOrCreate(['url' => (string)$url], [
                'content'  => $response->getBody()->getContents(),
                'metadata' => $response->getHeaders()
            ]);
        } elseif ($statusCode >= 300 && $statusCode < 400) {
            Log::info("URL moved (3xx): {$url} with status code: {$statusCode}");
            SiteService::instance()->query()->where('url', (string)$url)->delete();
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            if ($statusCode == 404) {
                Log::info("Not found (404): {$url}");
                SiteService::instance()->query()->where('url', (string)$url)->delete();
            } elseif ($statusCode == 403) {
                Log::info("Forbidden (403): {$url}");
                SiteService::instance()->query()->where('url', (string)$url)->delete();
            } else {
                Log::info("Client error (4xx): {$url} with status code: {$statusCode}");
            }
        } elseif ($statusCode >= 500) {
            Log::info("Server error (500): {$url}");
        } else {
            Log::info("Unhandled status code {$statusCode} for URL: {$url}");

        }
    }

}
